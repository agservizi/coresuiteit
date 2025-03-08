/**
 * CoreSuite IT - Windows 11 Lock Screen
 * Implementa la schermata di blocco/login in stile Windows 11
 */

document.addEventListener('DOMContentLoaded', function() {
    initLockScreen();
});

/**
 * Inizializza la schermata di blocco
 */
function initLockScreen() {
    // Crea il pulsante di blocco nella taskbar o nel menu utente se non esiste
    setupLockButton();
    
    // Crea la schermata di blocco se non esiste
    if (!document.getElementById('win11LockScreen')) {
        createLockScreen();
    }
    
    // Registra scorciatoie da tastiera (Win+L)
    registerLockShortcuts();
}

/**
 * Aggiunge il pulsante di blocco al menu utente
 */
function setupLockButton() {
    // Aggiungi al menu utente nel pannello di notifica
    const userPanel = document.querySelector('.user-panel');
    if (userPanel) {
        // Verifica se il pulsante già esiste
        if (!userPanel.querySelector('[data-action="lock"]')) {
            const lockItem = document.createElement('div');
            lockItem.className = 'panel-item';
            lockItem.dataset.action = 'lock';
            lockItem.innerHTML = '<i class="fas fa-lock"></i> Blocca';
            lockItem.addEventListener('click', lockScreen);
            
            // Inserisci prima del logout se esiste, altrimenti alla fine
            const logoutItem = userPanel.querySelector('[data-action="logout"]');
            if (logoutItem) {
                userPanel.insertBefore(lockItem, logoutItem);
            } else {
                userPanel.appendChild(lockItem);
            }
        }
    }
    
    // Aggiungi anche al menu Start
    const startFooter = document.querySelector('.start-panel-footer');
    if (startFooter) {
        const powerButton = startFooter.querySelector('.power-button');
        if (powerButton && !powerButton.querySelector('.lock-option')) {
            // Trasforma in dropdown se non lo è già
            powerButton.style.position = 'relative';
            powerButton.addEventListener('click', function(e) {
                e.stopPropagation();
                
                // Verifica se il menu esiste già
                let powerMenu = document.getElementById('powerMenu');
                if (powerMenu) {
                    powerMenu.classList.toggle('show');
                } else {
                    // Crea il menu
                    powerMenu = document.createElement('div');
                    powerMenu.id = 'powerMenu';
                    powerMenu.className = 'power-menu';
                    powerMenu.innerHTML = `
                        <div class="power-menu-item lock-option" data-action="lock">
                            <i class="fas fa-lock"></i> Blocca
                        </div>
                        <div class="power-menu-item" data-action="restart">
                            <i class="fas fa-sync"></i> Riavvia
                        </div>
                        <div class="power-menu-item" data-action="shutdown">
                            <i class="fas fa-power-off"></i> Spegni
                        </div>
                    `;
                    powerButton.appendChild(powerMenu);
                    
                    // Aggiungi eventi ai pulsanti
                    powerMenu.querySelector('[data-action="lock"]').addEventListener('click', lockScreen);
                    powerMenu.classList.add('show');
                    
                    // Chiudi cliccando altrove
                    document.addEventListener('click', function closeMenu(e) {
                        if (!powerButton.contains(e.target)) {
                            powerMenu.classList.remove('show');
                            document.removeEventListener('click', closeMenu);
                        }
                    });
                }
            });
        }
    }
}

/**
 * Crea la struttura HTML della schermata di blocco
 */
function createLockScreen() {
    const lockScreen = document.createElement('div');
    lockScreen.id = 'win11LockScreen';
    lockScreen.className = 'win11-lock-screen';
    
    // Ottieni le informazioni dell'utente
    const username = getUserInfo('name') || 'Utente';
    const userAvatar = getUserInfo('avatar') || 'assets/img/avatar.jpg';
    
    // Crea il contenuto HTML
    lockScreen.innerHTML = `
        <div class="lock-screen-background"></div>
        <div class="lock-screen-overlay"></div>
        
        <!-- Informazioni orario e data -->
        <div class="lock-screen-time-container">
            <div class="lock-screen-time" id="lockScreenTime">00:00</div>
            <div class="lock-screen-date" id="lockScreenDate">1 gennaio 2023</div>
        </div>
        
        <!-- Area di notifica (solo indicatori) -->
        <div class="lock-screen-notification-area">
            <div class="lock-indicator">
                <i class="fas fa-wifi"></i>
            </div>
            <div class="lock-indicator">
                <i class="fas fa-battery-three-quarters"></i>
            </div>
        </div>
        
        <!-- Schermata di Login -->
        <div class="login-screen">
            <div class="login-user-avatar">
                <img src="${userAvatar}" alt="${username}">
            </div>
            <div class="login-user-name">${username}</div>
            <div class="login-password-field">
                <input type="password" placeholder="Password" id="loginPassword">
                <button class="login-submit-btn" id="loginButton">
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
            <div class="login-error-message" id="loginError"></div>
            <div class="login-options">
                <div class="login-option">
                    <i class="fas fa-key"></i> Opzioni di accesso
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(lockScreen);
    
    // Inizializza l'orologio nella schermata di blocco
    updateLockScreenClock();
    setInterval(updateLockScreenClock, 1000);
    
    // Gestisci gli eventi di login
    setupLoginEvents();
}

/**
 * Aggiorna l'ora e la data nella schermata di blocco
 */
function updateLockScreenClock() {
    const timeElement = document.getElementById('lockScreenTime');
    const dateElement = document.getElementById('lockScreenDate');
    
    if (!timeElement || !dateElement) return;
    
    const now = new Date();
    
    // Aggiorna ora
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    timeElement.textContent = `${hours}:${minutes}`;
    
    // Aggiorna data
    const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
    let formattedDate = now.toLocaleDateString('it-IT', options);
    // Capitalizza la prima lettera
    formattedDate = formattedDate.charAt(0).toUpperCase() + formattedDate.slice(1);
    dateElement.textContent = formattedDate;
}

/**
 * Configura gli eventi della schermata di login
 */
function setupLoginEvents() {
    const passwordInput = document.getElementById('loginPassword');
    const loginButton = document.getElementById('loginButton');
    const errorMessage = document.getElementById('loginError');
    
    if (!passwordInput || !loginButton || !errorMessage) return;
    
    // Invia password quando si preme invio
    passwordInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            attemptLogin();
        }
    });
    
    // Invia password quando si clicca il pulsante
    loginButton.addEventListener('click', attemptLogin);
    
    // Funzione di login
    function attemptLogin() {
        const password = passwordInput.value;
        
        // Simulazione autenticazione
        // In un'app reale, questa funzione eseguirebbe una richiesta al server
        if (password === '1234' || password === '') { // Password demo
            unlockScreen();
        } else {
            // Mostra errore
            errorMessage.textContent = 'La password non è corretta. Riprova.';
            passwordInput.value = '';
            
            // Animazione di errore
            passwordInput.classList.add('shake');
            setTimeout(() => {
                passwordInput.classList.remove('shake');
            }, 500);
        }
    }
    
    // Attiva la schermata di blocco anche cliccando al di fuori della finestra di login
    const lockScreen = document.getElementById('win11LockScreen');
    if (lockScreen) {
        lockScreen.addEventListener('click', function(e) {
            // Se il clic è fuori dalla zona di login, mostra la login
            if (!e.target.closest('.login-screen') || e.target === lockScreen) {
                showLoginScreen();
            }
        });
    }
}

/**
 * Mostra la schermata di login (dopo il click sulla schermata di blocco)
 */
function showLoginScreen() {
    const loginScreen = document.querySelector('.login-screen');
    const timeDateContainer = document.querySelector('.lock-screen-time-container');
    
    if (loginScreen && timeDateContainer) {
        // Anima l'uscita dell'ora e l'entrata della schermata di login
        timeDateContainer.classList.add('fade-up');
        loginScreen.classList.add('show');
        
        // Focus sulla password
        setTimeout(() => {
            document.getElementById('loginPassword')?.focus();
        }, 300);
    }
}

/**
 * Blocca lo schermo
 */
function lockScreen() {
    const lockScreen = document.getElementById('win11LockScreen');
    if (!lockScreen) return;
    
    // Reimposta la schermata di blocco allo stato iniziale
    const loginScreen = lockScreen.querySelector('.login-screen');
    const timeDateContainer = lockScreen.querySelector('.lock-screen-time-container');
    
    if (loginScreen && timeDateContainer) {
        loginScreen.classList.remove('show');
        timeDateContainer.classList.remove('fade-up');
    }
    
    // Reimposta il campo password
    const passwordInput = document.getElementById('loginPassword');
    const errorMessage = document.getElementById('loginError');
    if (passwordInput) passwordInput.value = '';
    if (errorMessage) errorMessage.textContent = '';
    
    // Mostra la schermata di blocco
    lockScreen.classList.add('show');
    document.body.classList.add('locked');
    
    // Aggiorna orologio
    updateLockScreenClock();
}

/**
 * Sblocca lo schermo
 */
function unlockScreen() {
    const lockScreen = document.getElementById('win11LockScreen');
    if (!lockScreen) return;
    
    // Animazione di sblocco
    lockScreen.classList.add('unlocking');
    
    setTimeout(() => {
        lockScreen.classList.remove('show');
        lockScreen.classList.remove('unlocking');
        document.body.classList.remove('locked');
        
        // Reimposta altri stati
        lockScreen.querySelector('.login-screen')?.classList.remove('show');
        lockScreen.querySelector('.lock-screen-time-container')?.classList.remove('fade-up');
    }, 500);
}

/**
 * Registra scorciatoie da tastiera per bloccare lo schermo
 */
function registerLockShortcuts() {
    document.addEventListener('keydown', function(e) {
        // Ctrl+L come surrogato di Win+L
        if (e.ctrlKey && e.key === 'l') {
            e.preventDefault();
            lockScreen();
        }
        
        // Escape per tornare alla schermata di blocco dalla login
        if (e.key === 'Escape') {
            const loginScreen = document.querySelector('.login-screen.show');
            const lockScreen = document.getElementById('win11LockScreen');
            
            if (loginScreen && lockScreen && lockScreen.classList.contains('show')) {
                loginScreen.classList.remove('show');
                lockScreen.querySelector('.lock-screen-time-container')?.classList.remove('fade-up');
            }
        }
    });
}

/**
 * Ottiene le informazioni dell'utente
 * @param {string} info - Tipo di informazione: 'name', 'avatar', 'email', ecc.
 * @returns {string} - Valore dell'informazione richiesta
 */
function getUserInfo(info) {
    // In un'app reale, queste informazioni verrebbero recuperate da una sessione o API
    const userInfo = {
        name: 'CoreSuite Utente',
        avatar: 'assets/img/avatar.jpg',
        email: 'utente@coresuite.it'
    };
    
    return userInfo[info] || null;
}
