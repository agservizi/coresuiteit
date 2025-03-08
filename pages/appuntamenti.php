<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
require_once '../includes/appointments.php';

// Controllo permessi
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Ottieni la data corrente o la data selezionata
$selected_date = isset($_GET['date']) ? sanitizeInput($_GET['date']) : date('Y-m-d');
?>

<div class="container mx-auto">
    <!-- Modal Nuovo Appuntamento -->
    <div id="modalNuovoAppuntamento" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <!-- ...existing code... -->
        </form>
    </div>

    <!-- Modal Modifica Appuntamento -->
    <div id="modalModificaAppuntamento" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg w-full max-w-md mx-4 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Modifica Appuntamento</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="toggleModal('modalModificaAppuntamento')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="formModificaAppuntamento" method="post" action="" class="p-6">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" id="edit_id" name="id">
                
                <div class="space-y-4">
                    <div>
                        <label for="edit_cliente_id" class="block text-sm font-medium text-gray-700 mb-1">Cliente <span class="text-red-500">*</span></label>
                        <select id="edit_cliente_id" name="cliente_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                            <option value="">-- Seleziona Cliente --</option>
                            <?php foreach ($clienti as $cliente): ?>
                                <option value="<?php echo $cliente['id']; ?>">
                                    <?php echo $cliente['cognome'] . ' ' . $cliente['nome']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="edit_titolo" class="block text-sm font-medium text-gray-700 mb-1">Titolo <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_titolo" name="titolo" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="edit_data" class="block text-sm font-medium text-gray-700 mb-1">Data <span class="text-red-500">*</span></label>
                            <input type="date" id="edit_data" name="data" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        </div>
                        
                        <div>
                            <label for="edit_ora" class="block text-sm font-medium text-gray-700 mb-1">Ora <span class="text-red-500">*</span></label>
                            <input type="time" id="edit_ora" name="ora" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="edit_durata" class="block text-sm font-medium text-gray-700 mb-1">Durata (min) <span class="text-red-500">*</span></label>
                            <select id="edit_durata" name="durata" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                                <option value="15">15 minuti</option>
                                <option value="30">30 minuti</option>
                                <option value="45">45 minuti</option>
                                <option value="60">1 ora</option>
                                <option value="90">1 ora e 30 minuti</option>
                                <option value="120">2 ore</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="edit_stato" class="block text-sm font-medium text-gray-700 mb-1">Stato <span class="text-red-500">*</span></label>
                            <select id="edit_stato" name="stato" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                                <option value="in attesa">In attesa</option>
                                <option value="confermato">Confermato</option>
                                <option value="cancellato">Cancellato</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label for="edit_descrizione" class="block text-sm font-medium text-gray-700 mb-1">Descrizione</label>
                        <textarea id="edit_descrizione" name="descrizione" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded mr-2" onclick="toggleModal('modalModificaAppuntamento')">
                            Annulla
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                            Salva Modifiche
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Form per eliminare appuntamento -->
    <form id="formEliminaAppuntamento" method="post" action="">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" id="delete_id">
    </form>
</div>

<script>
function toggleModal(id) {
    const modal = document.getElementById(id);
    modal.classList.toggle('hidden');
}

function editAppointment(appuntamento) {
    toggleModal('modalModificaAppuntamento');
    
    document.getElementById('edit_id').value = appuntamento.id;
    document.getElementById('edit_cliente_id').value = appuntamento.cliente_id;
    document.getElementById('edit_titolo').value = appuntamento.titolo;
    document.getElementById('edit_data').value = appuntamento.data;
    document.getElementById('edit_ora').value = appuntamento.ora;
    document.getElementById('edit_durata').value = appuntamento.durata;
    document.getElementById('edit_descrizione').value = appuntamento.descrizione;
    document.getElementById('edit_stato').value = appuntamento.stato;
}

function deleteAppointment(id) {
    if (confirm('Sei sicuro di voler eliminare questo appuntamento?')) {
        document.getElementById('delete_id').value = id;
        document.getElementById('formEliminaAppuntamento').submit();
    }
}
</script>
