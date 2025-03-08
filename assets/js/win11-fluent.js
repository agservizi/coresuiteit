/**
 * CoreSuite IT - Windows 11 Fluent Effects
 * 
 * Effetti avanzati di trasparenza e interattività per ricreare 
 * l'esperienza Fluent Design di Windows 11 Pro
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inizializza tutti gli effetti fluent
    initMicaBackground();
    initAcrylicEffect();
    initLightEffects();
    initWindowsMovement();
    initDialogEffects();
    initFluentPointers();
    initNotificationCenter();
});

/**
 * Effetto Mica - Trasparenza avanzata del desktop su elementi di sfondo
 * https://docs.microsoft.com/en-us/windows/apps/design/style/mica
 */
function initMicaBackground() {
    // Elementi che dovrebbero avere l'effetto Mica (finestra principale, sidebar)
    const micaElements = document.querySelectorAll('.app-window, .app-sidebar');
    
    if (micaElements.length === 0) return;
    
    // Crea l'elemento di background per l'effetto Mica
    const micaBackground = document.createElement('div');
    micaBackground.className = 'win11-mica-bg';
    document.body.prepend(micaBackground);
    
    // Aggiorna l'effetto in base alla posizione di scorrimento
    function updateMicaEffect() {
        // Determina il livello di trasparenza in base alla modalità scura/chiara
        const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';
        const opacityLevel = isDarkMode ? 0.8 : 0.92;
        
        micaElements.forEach(element => {
            // Applica l'effetto specifico per Mica con il colore del tema
            element.style.backgroundColor = isDarkMode 
                ? `rgba(32, 32, 32, ${opacityLevel})`
                : `rgba(243, 243, 243, ${opacityLevel})`;
                
            // Aggiungi l'effetto "paint through" per far vedere lo sfondo
            element.style.backdropFilter = 'blur(20px) saturate(125%)';
            
            // Effetto di luce ambientale se il mouse è sopra un elemento
            if (element.matches(':hover')) {
                element.style.backdropFilter = 'blur(30px) saturate(140%)';
            }
        });
    }
    
    // Ascolta il cambio di tema
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('change', updateMicaEffect);
    }
    
    // Inizializza l'effetto e imposta gli ascoltatori
    updateMicaEffect();
    window.addEventListener('scroll', updateMicaEffect);
    window.addEventListener('resize', updateMicaEffect);
}

/**
 * Effetto Acrylic - Blur e trasparenza avanzata per flyout e componenti
 * https://docs.microsoft.com/en-us/windows/apps/design/style/acrylic
 */
function initAcrylicEffect() {
    // Elementi che dovrebbero avere l'effetto Acrylic (menu, panel)
    const acrylicElements = document.querySelectorAll(
        '.win11-start-panel, .notification-panel, .user-panel, ' +
        '.action-center, .calendar-panel, .win11-context-menu'
    );
    
    acrylicElements.forEach(element => {
        // Aggiungi una texture sottile di rumore
        const noiseTexture = document.createElement('div');
        noiseTexture.className = 'acrylic-noise';
        element.appendChild(noiseTexture);
        
        // Effetto di intensificazione al passaggio del mouse
        element.addEventListener('mouseenter', function() {
            this.style.backdropFilter = 'blur(50px) saturate(150%)';
            noiseTexture.style.opacity = '0.04';
        });
        
        element.addEventListener('mouseleave', function() {
            this.style.backdropFilter = 'blur(30px) saturate(125%)';
            noiseTexture.style.opacity = '0.02';
        });
    });
}

/**
 * Effetti di luce ambientale per le interazioni
 */
function initLightEffects() {
    // Aggiungi effetti di luce ai bottoni e ai componenti interattivi
    const interactiveElements = document.querySelectorAll(
        '.win11-btn, .win11-card, .nav-link, ' +
        '.app-item, .taskbar-icon, .start-panel-apps a'
    );
    
    interactiveElements.forEach(element => {
        element.addEventListener('mouseenter', function(e) {
            // Crea un effetto di luce che segue il mouse
            const light = document.createElement('div');
            light.className = 'win11-light-effect';
            
            // Posiziona l'effetto di luce
            this.style.overflow = 'hidden';
            this.style.position = 'relative';
            this.appendChild(light);
            
            // Movimento dell'effetto di luce
            this.addEventListener('mousemove', moveLight);
            
            function moveLight(e) {
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                light.style.top = y + 'px';
                light.style.left = x + 'px';
            }
            
            // Rimuovi l'effetto quando il mouse esce
            this.addEventListener('mouseleave', function() {
                if (light.parentNode === this) {
                    this.removeChild(light);
                }
                this.removeEventListener('mousemove', moveLight);
            });
        });
    });
}

/**
 * Abilita il movimento delle finestre Windows 11 tramite la title bar
 */
function initWindowsMovement() {
    const titleBar = document.querySelector('.app-title-bar');
    const appWindow = document.querySelector('.app-window');
    
    if (!titleBar || !appWindow) return;
    
    let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
    
    // Rendi la titlebar draggabile, escludendo i controlli
    titleBar.addEventListener('mousedown', function(e) {
        // Evita il drag se si clicca sui controlli della finestra
        if (e.target.closest('.win-controls')) return;
        
        e.preventDefault();
        
        // Inizia il movimento solo se la finestra non è massimizzata
        if (!appWindow.classList.contains('maximized')) {
            // Ottieni la posizione del mouse
            pos3 = e.clientX;
            pos4 = e.clientY;
            
            // Aggiungi classe per lo stile durante il drag
            appWindow.classList.add('window-dragging');
            
            // Aggiungi gli event listener per il movimento
            document.addEventListener('mousemove', elementDrag);
            document.addEventListener('mouseup', closeDragElement);
        }
    });
    
    function elementDrag(e) {
        e.preventDefault();
        
        // Calcola la nuova posizione
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
        
        // Imposta la nuova posizione
        const newTop = appWindow.offsetTop - pos2;
        const newLeft = appWindow.offsetLeft - pos1;
        
        // Limita il movimento all'interno della finestra
        const maxTop = window.innerHeight - 50;
        const maxLeft = window.innerWidth - 50;
        
        appWindow.style.top = Math.max(0, Math.min(newTop, maxTop)) + "px";
        appWindow.style.left = Math.max(0, Math.min(newLeft, maxLeft)) + "px";
    }
    
    function closeDragElement() {
        // Rimuovi la classe di stile e gli event listener
        appWindow.classList.remove('window-dragging');
        document.removeEventListener('mouseup', closeDragElement);
        document.removeEventListener('mousemove', elementDrag);
    }
    
    // Doppio click sulla barra del titolo per massimizzare/ripristinare
    titleBar.addEventListener('dblclick', function(e) {
        // Evita il doppio click sui controlli della finestra
        if (e.target.closest('.win-controls')) return;
        
        const maximizeBtn = document.querySelector('.win-control-btn.maximize');
        if (maximizeBtn) {
            maximizeBtn.click();
        }
    });
}

/**
 * Effetti Fluent per le finestre di dialogo
 */
function initDialogEffects() {
    // Overlay per i dialoghi modali
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('show.bs.modal', function () {
            // Aggiungi un overlay con blur per il dialogo
            const overlay = document.createElement('div');
            overlay.className = 'win11-modal-backdrop';
            document.body.appendChild(overlay);
            
            // Animazione ingresso
            setTimeout(() => {
                overlay.classList.add('show');
                modal.classList.add('win11-modal-animate');
            }, 10);
        });
        
        modal.addEventListener('hide.bs.modal', function () {
            // Rimuovi l'overlay con animazione
            const overlay = document.querySelector('.win11-modal-backdrop');
            if (overlay) {
                overlay.classList.remove('show');
                setTimeout(() => {
                    overlay.remove();
                }, 300);
            }
        });
    });
}

/**
 * Puntatori Fluent con effetti al click
 */
function initFluentPointers() {
    // Aggiungi l'effetto Fluent Pointer (click effect)
    document.addEventListener('click', function(e) {
        // Crea l'elemento per l'effetto
        const ripple = document.createElement('div');
        ripple.className = 'win11-click-effect';
        ripple.style.left = e.clientX + 'px';
        ripple.style.top = e.clientY + 'px';
        
        // Aggiungi al DOM e rimuovi dopo l'animazione
        document.body.appendChild(ripple);
        setTimeout(() => {
            ripple.remove();
        }, 700);
    });
}

/**
 * Centro notifiche Windows 11
 */
function initNotificationCenter() {
    const notificationBtn = document.querySelector('.win11-task-right [title="Notifiche"]');
    
    if (!notificationBtn) return;
    
    // Crea il pannello se non esiste
    let notificationCenter = document.getElementById('win11NotificationCenter');
    
    if (!notificationCenter) {
        notificationCenter = document.createElement('div');
        notificationCenter.id = 'win11NotificationCenter';
        notificationCenter.className = 'win11-notification-center';
        notificationCenter.innerHTML = `
            <div class="notification-center-header">
                <div class="notification-center-date">${getCurrentFormattedDate()}</div>
                <button class="win11-btn win11-btn-icon" id="clearAllNotifications">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="notification-center-tabs">
                <button class="notification-tab active" data-tab="notifications">Notifiche</button>
                <button class="notification-tab" data-tab="quick-settings">Impostazioni rapide</button>
            </div>
            <div class="notification-center-content">
                <!-- Notifiche Tab -->
                <div class="notification-tab-content active" id="notifications-content">
                    <div class="notification-group">
                        <div class="notification-group-header">
                            <span>Nuove</span>
                        </div>
                        <div class="notification-card">
                            <div class="notification-card-header">
                                <img src="assets/img/favicon.ico" alt="App Icon">
                                <div class="notification-app-name">CoreSuite</div>
                                <div class="notification-time">14:30</div>
                            </div>
                            <div class="notification-card-body">
                                <div class="notification-title">Nuovo cliente registrato</div>
                                <div class="notification-message">
                                    Un nuovo cliente è stato registrato nel sistema.
                                </div>
                            </div>
                            <div class="notification-card-actions">
                                <button class="notification-action">Visualizza</button>
                                <button class="notification-action notification-dismiss">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="notification-card">
                            <div class="notification-card-header">
                                <img src="assets/img/favicon.ico" alt="App Icon">
                                <div class="notification-app-name">CoreSuite</div>
                                <div class="notification-time">12:15</div>
                            </div>
                            <div class="notification-card-body">
                                <div class="notification-title">Pagamento completato</div>
                                <div class="notification-message">
                                    Pagamento #54238 elaborato con successo.
                                </div>
                            </div>
                            <div class="notification-card-actions">
                                <button class="notification-action">Dettagli</button>
                                <button class="notification-action notification-dismiss">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="notification-group">
                        <div class="notification-group-header">
                            <span>Precedenti</span>
                        </div>
                        <div class="notification-card read">
                            <div class="notification-card-header">
                                <img src="assets/img/favicon.ico" alt="App Icon">
                                <div class="notification-app-name">CoreSuite</div>
                                <div class="notification-time">Ieri</div>
                            </div>
                            <div class="notification-card-body">
                                <div class="notification-title">Sistema aggiornato</div>
                                <div class="notification-message">
                                    Il sistema è stato aggiornato alla versione 1.0.2
                                </div>
                            </div>
                            <div class="notification-card-actions">
                                <button class="notification-action">Info</button>
                                <button class="notification-action notification-dismiss">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Settings Tab -->
                <div class="notification-tab-content" id="quick-settings-content">
                    <div class="quick-settings-grid">
                        <div class="quick-setting-tile toggle">
                            <div class="quick-setting-icon">
                                <i class="fas fa-wifi"></i>
                            </div>
                            <div class="quick-setting-name">Wi-Fi</div>
                        </div>
                        <div class="quick-setting-tile toggle">
                            <div class="quick-setting-icon">
                                <i class="fab fa-bluetooth-b"></i>
                            </div>
                            <div class="quick-setting-name">Bluetooth</div>
                        </div>
                        <div class="quick-setting-tile toggle">
                            <div class="quick-setting-icon">
                                <i class="fas fa-plane"></i>
                            </div>
                            <div class="quick-setting-name">Modalità aereo</div>
                        </div>
                        <div class="quick-setting-tile toggle active">
                            <div class="quick-setting-icon">
                                <i class="fas fa-moon"></i>
                            </div>
                            <div class="quick-setting-name">Non disturbare</div>
                        </div>
                        <div class="quick-setting-tile toggle">
                            <div class="quick-setting-icon">
                                <i class="fas fa-battery-three-quarters"></i>
                            </div>
                            <div class="quick-setting-name">Risparmio batteria</div>
                        </div>
                        <div class="quick-setting-tile" data-action="settings">
                            <div class="quick-setting-icon">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="quick-setting-name">Impostazioni</div>
                        </div>
                    </div>
                    <div class="quick-settings-sliders">
                        <div class="quick-setting-slider">
                            <div class="slider-icon">
                                <i class="fas fa-volume-up"></i>
                            </div>
                            <div class="slider-control">
                                <input type="range" class="win11-slider" min="0" max="100" value="75">
                            </div>
                        </div>
                        <div class="quick-setting-slider">
                            <div class="slider-icon">
                                <i class="fas fa-sun"></i>
                            </div>
                            <div class="slider-control">
                                <input type="range" class="win11-slider" min="0" max="100" value="60">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(notificationCenter);
        
        // Gestione delle tab nel centro notifiche
        const tabs = notificationCenter.querySelectorAll('.notification-tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Rimuovi classe active da tutte le tab
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Mostra il contenuto della tab selezionata
                const tabName = this.getAttribute('data-tab');
                const tabContents = notificationCenter.querySelectorAll('.notification-tab-content');
                tabContents.forEach(content => {
                    content.classList.remove('active');
                });
                document.getElementById(tabName + '-content').classList.add('active');
            });
        });
        
        // Gestione pulsante di dismissione delle notifiche
        const dismissButtons = notificationCenter.querySelectorAll('.notification-dismiss');
        dismissButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const card = this.closest('.notification-card');
                card.style.height = card.offsetHeight + 'px';
                
                // Aggiungi classe per l'animazione di uscita
                card.classList.add('dismissing');
                
                // Rimuovi la notifica dopo l'animazione
                setTimeout(() => {
                    card.style.height = '0';
                    card.style.margin = '0';
                    card.style.padding = '0';
                    setTimeout(() => {
                        card.remove();
                        
                        // Se non ci sono più notifiche in un gruppo, rimuovi il gruppo
                        const groups = notificationCenter.querySelectorAll('.notification-group');
                        groups.forEach(group => {
                            if (group.querySelectorAll('.notification-card').length === 0) {
                                group.remove();
                            }
                        });
                    }, 300);
                }, 100);
            });
        });
        
        // Gestione del toggle per le quick settings
        const toggleTiles = notificationCenter.querySelectorAll('.quick-setting-tile.toggle');
        toggleTiles.forEach(tile => {
            tile.addEventListener('click', function() {
                this.classList.toggle('active');
            });
        });
        
        // Azioni specifiche
        const actionTiles = notificationCenter.querySelectorAll('[data-action]');
        actionTiles.forEach(tile => {
            tile.addEventListener('click', function() {
                const action = this.getAttribute('data-action');
                
                if (action === 'settings') {
                    // Apri le impostazioni
                    notificationCenter.classList.remove('show');
                    // Qui puoi aggiungere codice per aprire le impostazioni
                }
            });
        });
        
        // Gestione slider
        const sliders = notificationCenter.querySelectorAll('.win11-slider');
        sliders.forEach(slider => {
            slider.addEventListener('input', function() {
                const icon = this.parentNode.previousElementSibling.querySelector('i');
                
                // Per il volume
                if (icon.classList.contains('fa-volume-up') || 
                    icon.classList.contains('fa-volume-down') ||
                    icon.classList.contains('fa-volume-mute')) {
                    
                    if (this.value == 0) {
                        icon.className = 'fas fa-volume-mute';
                    } else if (this.value < 50) {
                        icon.className = 'fas fa-volume-down';
                    } else {
                        icon.className = 'fas fa-volume-up';
                    }
                }
                
                // Per la luminosità
                if (icon.classList.contains('fa-sun')) {
                    // Regola l'opacità dell'icona in base al valore
                    icon.style.opacity = 0.4 + (this.value / 100) * 0.6;
                }
            });
        });
        
        // Gestione pulsante di pulizia
        const clearAllBtn = document.getElementById('clearAllNotifications');
        if (clearAllBtn) {
            clearAllBtn.addEventListener('click', function() {
                const notifications = notificationCenter.querySelectorAll('.notification-card');
                
                // Anima e rimuovi tutte le notifiche
                notifications.forEach((card, index) => {
                    setTimeout(() => {
                        card.classList.add('dismissing');
                        setTimeout(() => {
                            card.style.height = '0';
                            card.style.margin = '0';
                            card.style.padding = '0';
                            setTimeout(() => {
                                card.remove();
                            }, 300);
                        }, 100);
                    }, index * 50);
                });
                
                // Dopo aver rimosso le notifiche, aggiungi un messaggio "nessuna notifica"
                setTimeout(() => {
                    const groups = notificationCenter.querySelectorAll('.notification-group');
                    groups.forEach(group => group.remove());
                    
                    const notificationsContent = document.getElementById('notifications-content');
                    if (notificationsContent && notificationsContent.querySelectorAll('.notification-card').length === 0) {
                        notificationsContent.innerHTML = `
                            <div class="no-notifications">
                                <i class="fas fa-bell-slash"></i>
                                <p>Nessuna notifica</p>
                            </div>
                        `;
                    }
                }, 500);
            });
        }
    }
    
    // Toggle del centro notifiche
    notificationBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        
        // Chiudi altri pannelli aperti
        document.querySelectorAll('.win11-start-panel.show, .calendar-panel.show, .action-center.show, .user-panel.show').forEach(panel => {
            panel.classList.remove('show');
        });
        
        // Toggle del centro notifiche
        notificationCenter.classList.toggle('show');
        
        // Aggiorna la data corrente
        const dateHeader = notificationCenter.querySelector('.notification-center-date');
        if (dateHeader) {
            dateHeader.textContent = getCurrentFormattedDate();
        }
    });
    
    // Chiudi quando si clicca fuori
    document.addEventListener('click', function(e) {
        if (notificationCenter.classList.contains('show') && 
            !notificationCenter.contains(e.target) && 
            e.target !== notificationBtn) {
            notificationCenter.classList.remove('show');
        }
    });
}

/**
 * Ottiene la data corrente formattata
 * @returns {string} - Data formattata
 */
function getCurrentFormattedDate() {
    const now = new Date();
    const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
    // Capitalizza la prima lettera
    let formatted = now.toLocaleDateString('it-IT', options);
    return formatted.charAt(0).toUpperCase() + formatted.slice(1);
}
