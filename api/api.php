<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

// Impostazioni header per CORS e JSON
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Gestione delle richieste OPTIONS per il preflight CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Ottieni il metodo HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Ottieni l'endpoint richiesto dalla query string
$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

// Autenticazione API (semplice token basato su Basic Auth)
function authenticateApi() {
    if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
        return false;
    }
    
    $apiKey = $_SERVER['PHP_AUTH_USER'];
    $apiSecret = $_SERVER['PHP_AUTH_PW'];
    
    // Qui implementare la logica per verificare le credenziali API
    // Per ora usiamo valori hardcoded per esempio
    return $apiKey === 'api_key' && $apiSecret === 'api_secret';
}

// Gestione errori
function sendError($message, $code = 400) {
    http_response_code($code);
    echo json_encode(['error' => $message]);
    exit;
}

// Risposta di successo
function sendResponse($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit;
}

// Controllo autenticazione per endpoints protetti
$publicEndpoints = ['login', 'health'];
if (!in_array($endpoint, $publicEndpoints) && !authenticateApi()) {
    sendError('Unauthorized', 401);
}

// Routing delle richieste API
switch ($endpoint) {
    case 'health':
        // Endpoint per verifica disponibilità API
        sendResponse(['status' => 'ok', 'version' => '1.0.0']);
        break;
        
    case 'login':
        // Endpoint per ottenere token di accesso
        if ($method !== 'POST') {
            sendError('Method not allowed', 405);
        }
        
        // Ottieni i dati dalla richiesta
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['email']) || !isset($data['password'])) {
            sendError('Missing required fields: email, password');
        }
        
        // Verifica credenziali
        try {
            $stmt = $conn->prepare("SELECT * FROM utenti WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $data['email']);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (password_verify($data['password'], $user['password'])) {
                    // Genera token JWT (implementazione semplificata)
                    $token = bin2hex(random_bytes(32));
                    
                    sendResponse([
                        'token' => $token,
                        'user' => [
                            'id' => $user['id'],
                            'nome' => $user['nome'],
                            'cognome' => $user['cognome'],
                            'email' => $user['email'],
                            'ruolo' => $user['ruolo']
                        ]
                    ]);
                }
            }
            
            sendError('Invalid credentials', 401);
        } catch (PDOException $e) {
            sendError('Database error: ' . $e->getMessage(), 500);
        }
        break;
        
    case 'clienti':
        // Endpoint per la gestione dei clienti
        switch ($method) {
            case 'GET':
                // Ottieni lista clienti o singolo cliente
                $id = isset($_GET['id']) ? intval($_GET['id']) : null;
                
                try {
                    if ($id) {
                        // Ottieni singolo cliente
                        $stmt = $conn->prepare("SELECT * FROM clienti WHERE id = :id LIMIT 1");
                        $stmt->bindParam(':id', $id);
                        $stmt->execute();
                        
                        if ($stmt->rowCount() === 0) {
                            sendError('Client not found', 404);
                        }
                        
                        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
                        sendResponse($cliente);
                    } else {
                        // Paginazione
                        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;
                        $offset = ($page - 1) * $limit;
                        
                        // Ottieni lista clienti
                        $stmt = $conn->prepare("SELECT * FROM clienti ORDER BY cognome, nome LIMIT :limit OFFSET :offset");
                        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                        $stmt->execute();
                        
                        $clienti = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        // Ottieni il conteggio totale
                        $countStmt = $conn->query("SELECT COUNT(*) FROM clienti");
                        $totalCount = $countStmt->fetchColumn();
                        
                        sendResponse([
                            'data' => $clienti,
                            'meta' => [
                                'total' => intval($totalCount),
                                'page' => $page,
                                'limit' => $limit,
                                'totalPages' => ceil($totalCount / $limit)
                            ]
                        ]);
                    }
                } catch (PDOException $e) {
                    sendError('Database error: ' . $e->getMessage(), 500);
                }
                break;
                
            case 'POST':
                // Crea un nuovo cliente
                $data = json_decode(file_get_contents('php://input'), true);
                
                // Validazione dati
                $requiredFields = ['nome', 'cognome', 'telefono'];
                foreach ($requiredFields as $field) {
                    if (!isset($data[$field]) || empty($data[$field])) {
                        sendError("Missing required field: $field");
                    }
                }
                
                try {
                    $stmt = $conn->prepare("INSERT INTO clienti (nome, cognome, codice_fiscale, email, telefono, indirizzo, citta, cap, note, data_registrazione) 
                                           VALUES (:nome, :cognome, :codice_fiscale, :email, :telefono, :indirizzo, :citta, :cap, :note, NOW())");
                    
                    $stmt->bindParam(':nome', $data['nome']);
                    $stmt->bindParam(':cognome', $data['cognome']);
                    $stmt->bindParam(':codice_fiscale', $data['codice_fiscale'] ?? null);
                    $stmt->bindParam(':email', $data['email'] ?? null);
                    $stmt->bindParam(':telefono', $data['telefono']);
                    $stmt->bindParam(':indirizzo', $data['indirizzo'] ?? null);
                    $stmt->bindParam(':citta', $data['citta'] ?? null);
                    $stmt->bindParam(':cap', $data['cap'] ?? null);
                    $stmt->bindParam(':note', $data['note'] ?? null);
                    
                    if ($stmt->execute()) {
                        $newClientId = $conn->lastInsertId();
                        
                        // Ottieni il cliente appena creato
                        $stmt = $conn->prepare("SELECT * FROM clienti WHERE id = :id");
                        $stmt->bindParam(':id', $newClientId);
                        $stmt->execute();
                        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        sendResponse($cliente, 201);
                    } else {
                        sendError('Failed to create client', 500);
                    }
                } catch (PDOException $e) {
                    sendError('Database error: ' . $e->getMessage(), 500);
                }
                break;
                
            case 'PUT':
                // Aggiorna un cliente esistente
                $id = isset($_GET['id']) ? intval($_GET['id']) : null;
                
                if (!$id) {
                    sendError('Missing client ID');
                }
                
                $data = json_decode(file_get_contents('php://input'), true);
                
                try {
                    // Verifica esistenza cliente
                    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM clienti WHERE id = :id");
                    $checkStmt->bindParam(':id', $id);
                    $checkStmt->execute();
                    
                    if ($checkStmt->fetchColumn() === 0) {
                        sendError('Client not found', 404);
                    }
                    
                    // Costruisci la query dinamicamente in base ai campi presenti
                    $updateFields = [];
                    $params = [':id' => $id];
                    
                    $allowedFields = ['nome', 'cognome', 'codice_fiscale', 'email', 'telefono', 'indirizzo', 'citta', 'cap', 'note'];
                    
                    foreach ($allowedFields as $field) {
                        if (isset($data[$field])) {
                            $updateFields[] = "$field = :$field";
                            $params[":$field"] = $data[$field];
                        }
                    }
                    
                    if (empty($updateFields)) {
                        sendError('No fields to update');
                    }
                    
                    $updateQuery = "UPDATE clienti SET " . implode(', ', $updateFields) . " WHERE id = :id";
                    
                    $stmt = $conn->prepare($updateQuery);
                    
                    if ($stmt->execute($params)) {
                        // Ottieni il cliente aggiornato
                        $stmt = $conn->prepare("SELECT * FROM clienti WHERE id = :id");
                        $stmt->bindParam(':id', $id);
                        $stmt->execute();
                        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        sendResponse($cliente);
                    } else {
                        sendError('Failed to update client', 500);
                    }
                } catch (PDOException $e) {
                    sendError('Database error: ' . $e->getMessage(), 500);
                }
                break;
                
            case 'DELETE':
                // Elimina un cliente
                $id = isset($_GET['id']) ? intval($_GET['id']) : null;
                
                if (!$id) {
                    sendError('Missing client ID');
                }
                
                try {
                    $stmt = $conn->prepare("DELETE FROM clienti WHERE id = :id");
                    $stmt->bindParam(':id', $id);
                    
                    if ($stmt->execute()) {
                        if ($stmt->rowCount() === 0) {
                            sendError('Client not found', 404);
                        }
                        
                        sendResponse(['message' => 'Client deleted successfully']);
                    } else {
                        sendError('Failed to delete client', 500);
                    }
                } catch (PDOException $e) {
                    sendError('Database error: ' . $e->getMessage(), 500);
                }
                break;
                
            default:
                sendError('Method not allowed', 405);
                break;
        }
        break;
        
    case 'pagamenti':
        // Endpoint per la gestione dei pagamenti
        // Implementazione simile a quella dei clienti
        break;
        
    case 'energia':
        // Endpoint per la gestione dei contratti energia
        // Implementazione simile a quella dei clienti
        break;
        
    case 'telefonia':
        // Endpoint per la gestione dei contratti telefonia
        // Implementazione simile a quella dei clienti
        break;
        
    default:
        sendError('Endpoint not found', 404);
        break;
}
