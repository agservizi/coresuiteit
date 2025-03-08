/**
 * CoreSuite IT - Windows 11 
 * File principale che gestisce tutte le funzionalità dell'interfaccia Windows 11
 */

// Namespace principale
const Win11 = {
    // Impostazioni predefinite
    settings: {
        theme: localStorage.getItem('theme') || 'light',
        accentColor: '#0078d4',
        transparency: true,
        animations: true,
        notifications: true
    },
    
    // Inizializza tutti i componenti
    init: function() {
        console.log('Inizializzazione Windows 11...');
        
        // Applica tema e stili di base
        this.applyTheme(this.settings.theme);
        
        // Inizializza componenti principali
        this.taskbar.init();
        this.startMenu.init();
        this.notifications.init();
        this.widgets.init();
        this.contextMenu.init();
        this.windowManager.init();
        
        // Effetti Fluent
        this.fluent.init();
        
        console.log('Windows 11 inizializzato con successo!');
    },
    
    // Gestione tema
    applyTheme: function(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        if (theme === 'dark') {
            document.body.classList.add('dark-theme');
        } else {
            document.body.classList.remove('dark-theme');
        }
        localStorage.setItem('theme', theme);
    },
    
    // Taskbar
    taskbar: {
        init: function() {
            this.createClock();
            this.attachTaskbarEvents();
        },
        
        createClock: function() {
            const clockElement = document.getElementById('taskbarTime');
            if (!clockElement) return;
            
            const updateTime = () => {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                clockElement.textContent = `${hours}:${minutes}`;
            };
            
            updateTime();
            setInterval(updateTime, 60000);
        },
        
        attachTaskbarEvents: function() {
            // Gestione pulsanti della taskbar
            const taskbarButtons = document.querySelectorAll('.taskbar-icon');
            taskbarButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Effetto click
                    this.classList.add('active');
                    setTimeout(() => this.classList.remove('active'), 200);
                    
                    // Azioni specifiche in base al pulsante
                    if (this.id === 'startMenuBtn') {
                        Win11.startMenu.toggle();
                    } else if (this.getAttribute('title') === 'Widget') {
                        Win11.widgets.toggle();
                    } else if (this.getAttribute('title') === 'Notifiche') {
                        Win11.notifications.toggle();
                    }
                });
            });
        }
    },
    
    // Menu Start
    startMenu: {
        element: null,
        
        init: function() {
            this.element = document.getElementById('startMenuPanel');
            if (!this.element) this.createStartMenu();
            this.attachEvents();
        },
        
        createStartMenu: function() {
            // Crea il menu Start se non esiste
            this.element = document.createElement('div');
            this.element.id = 'startMenuPanel';
            this.element.className = 'win11-start-panel';
            this.element.innerHTML = `
                <div class="start-panel-header">
                    <div class="search-container">
                        <i class="fas fa-search"></i>
                        <input type="text" class="search-input" placeholder="Cerca app, impostazioni e documenti">
                    </div>
                </div>
                
                <div class="start-panel-apps">
                    <!-- App fissate -->
                </div>
                
                <div class="start-panel-recommended">
                    <div class="start-panel-section-title">
                        <span>Consigliati</span>
                    </div>
                    <div class="start-panel-recent">
                        <!-- Elementi recenti -->
                    </div>
                </div>
                
                <div class="start-panel-footer">
                    <div class="user-profile">
                        <div class="user-avatar">
                            <img src="assets/img/avatar.jpg" alt="User Avatar">
                        </div>
                        <div class="user-name">Utente</div>
                    </div>
                    <div class="power-button">
                        <i class="fas fa-power-off"></i>
                    </div>
                </div>
            `;
            document.body.appendChild(this.element);
        },
        
        attachEvents: function() {
            // Gestione click fuori per chiudere
            document.addEventListener('click', (e) => {
                if (this.element && 
                    this.element.classList.contains('show') && 
                    !this.element.contains(e.target) && 
                    e.target.id !== 'startMenuBtn') {
                    this.hide();
                }
            });
        },
        
        show: function() {
            if (this.element) {
                this.element.classList.add('show');
                document.getElementById('startMenuBtn')?.classList.add('active');
            }
        },
        
        hide: function() {
            if (this.element) {
                this.element.classList.remove('show');
                document.getElementById('startMenuBtn')?.classList.remove('active');
            }
        },
        
        toggle: function() {
            if (this.element) {
                if (this.element.classList.contains('show')) {
                    this.hide();
                } else {
                    // Nascondi altri pannelli aperti
                    Win11.notifications.hide();
                    Win11.widgets.hide();
                    this.show();
                }
            }
        }
    },
    
    // Notifiche
    notifications: {
        container: null,
        panel: null,
        
        init: function() {
            this.createContainer();
            this.createPanel();
        },
        
        createContainer: function() {
            if (!document.getElementById('win11NotificationContainer')) {
                this.container = document.createElement('div');
                this.container.id = 'win11NotificationContainer';
                this.container.className = 'win11-toast-container';
                document.body.appendChild(this.container);
            } else {
                this.container = document.getElementById('win11NotificationContainer');
            }
        },
        
        createPanel: function() {
            if (!document.getElementById('notificationPanel')) {
                this.panel = document.createElement('div');
                this.panel.id = 'notificationPanel';
                this.panel.className = 'notification-panel';
                this.panel.innerHTML = `
                    <div class="notification-header">
                        <h6>Notifiche</h6>
                        <span class="mark-all">Cancella tutto</span>
                    </div>
                    <div class="notification-body">
                        <!-- Le notifiche verranno aggiunte qui -->
                        <div class="no-notifications">
                            <i class="fas fa-bell-slash"></i>
                            <p>Nessuna notifica</p>
                        </div>
                    </div>
                `;
                document.body.appendChild(this.panel);
            } else {
                this.panel = document.getElementById('notificationPanel');
            }
        },
        
        show: function() {
            if (this.panel) {
                this.panel.classList.add('show');
            }
        },
        
        hide: function() {
            if (this.panel) {
                this.panel.classList.remove('show');
            }
        },
        
        toggle: function() {
            if (this.panel) {
                if (this.panel.classList.contains('show')) {
                    this.hide();
                } else {
                    // Nascondi altri pannelli aperti
                    Win11.startMenu.hide();
                    Win11.widgets.hide();
                    this.show();
                }
            }
        },
        
        showToast: function(options = {}) {
            const defaultOptions = {
                title: 'Notifica',
                message: '',
                type: 'info',
                duration: 5000
            };
            
            const settings = Object.assign({}, defaultOptions, options);
            
            // Crea il toast
            const toast = document.createElement('div');
            toast.className = `win11-toast win11-toast-${settings.type}`;
            
            // Imposta l'icona in base al tipo
            let iconClass = 'fa-info-circle';
            if (settings.type === 'success') iconClass = 'fa-check-circle';
            else if (settings.type === 'warning') iconClass = 'fa-exclamation-triangle';
            else if (settings.type === 'error') iconClass = 'fa-times-circle';
            
            // Imposta il contenuto
            toast.innerHTML = `
                <div class="win11-toast-icon">
                    <i class="fas ${iconClass}"></i>
                </div>
                <div class="win11-toast-content">
                    <div class="win11-toast-title">${settings.title}</div>
                    <div class="win11-toast-message">${settings.message}</div>
                </div>
                <button class="win11-toast-close">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            // Aggiungi al container
            this.container.appendChild(toast);
            
            // Funzione per rimuovere il toast
            const closeToast = () => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            };
            
            // Aggiungi l'evento di chiusura al pulsante
            toast.querySelector('.win11-toast-close').addEventListener('click', closeToast);
            
            // Mostra il toast
            setTimeout(() => toast.classList.add('show'), 10);
            
            // Chiudi automaticamente il toast
            if (settings.duration > 0) {
                setTimeout(closeToast, settings.duration);
            }
            
            return toast;
        }
    },
    
    // Widget
    widgets: {
        panel: null,
        
        init: function() {
            this.createWidgetPanel();
        },
        
        createWidgetPanel: function() {
            if (!document.getElementById('widgetsPanel')) {
                this.panel = document.createElement('div');
                this.panel.id = 'widgetsPanel';
                this.panel.className = 'widgets-panel';
                this.panel.innerHTML = `
                    <div class="widgets-header">
                        <div class="widget-user">
                            <img src="assets/img/avatar.jpg" alt="User" class="widget-avatar">
                            <span>Buongiorno, Utente</span>
                        </div>
                        <button class="win11-btn win11-btn-icon" id="closeWidgets">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="widgets-grid">
                        <!-- I widget verranno aggiunti qui -->
                    </div>
                `;
                document.body.appendChild(this.panel);
                
                // Aggiungi evento di chiusura
                document.getElementById('closeWidgets').addEventListener('click', () => this.hide());
            } else {
                this.panel = document.getElementById('widgetsPanel');
            }
        },
        
        show: function() {
            if (this.panel) {
                this.panel.classList.add('show');
            }
        },
        
        hide: function() {
            if (this.panel) {
                this.panel.classList.remove('show');
            }
        },
        
        toggle: function() {
            if (this.panel) {
                if (this.panel.classList.contains('show')) {
                    this.hide();
                } else {
                    // Nascondi altri pannelli aperti
                    Win11.startMenu.hide();
                    Win11.notifications.hide();
                    this.show();
                }
            }
        }
    },
    
    // Menu contestuale
    contextMenu: {
        menu: null,
        
        init: function() {
            this.attachContextMenuEvents();
        },
        
        attachContextMenuEvents: function() {
            document.addEventListener('contextmenu', (e) => {
                // Mostra il menu contextuale solo su determinati elementi
                if (e.target === document.body || e.target.classList.contains('desktop-item')) {
                    e.preventDefault();
                    this.show(e.clientX, e.clientY);
                }
            });
            
            // Chiudi cliccando altrove
            document.addEventListener('click', () => {
                this.hide();
            });
        },
        
        show: function(x, y) {
            // Rimuovi il menu esistente
            this.hide();
            
            // Crea nuovo menu
            this.menu = document.createElement('div');
            this.menu.className = 'win11-context-menu';
            this.menu.innerHTML = `
                <div class="context-menu-item"><i class="fas fa-sync"></i>Aggiorna</div>
                <div class="context-menu-separator"></div>
                <div class="context-menu-item"><i class="fas fa-th-large"></i>Visualizza</div>
                <div class="context-menu-item"><i class="fas fa-sort"></i>Ordina per</div>
                <div class="context-menu-separator"></div>
                <div class="context-menu-item"><i class="fas fa-folder-plus"></i>Nuova cartella</div>
                <div class="context-menu-item"><i class="fas fa-file"></i>Nuovo file</div>
                <div class="context-menu-separator"></div>
                <div class="context-menu-item"><i class="fas fa-cog"></i>Personalizza</div>
            `;
            
            // Posiziona il menu
            this.menu.style.left = x + 'px';
            this.menu.style.top = y + 'px';
            document.body.appendChild(this.menu);
            
            // Verifica che il menu non esca dallo schermo
            const rect = this.menu.getBoundingClientRect();
            if (rect.right > window.innerWidth) {
                this.menu.style.left = (window.innerWidth - rect.width - 5) + 'px';
            }
            if (rect.bottom > window.innerHeight) {
                this.menu.style.top = (window.innerHeight - rect.height - 5) + 'px';
            }
            
            // Mostra il menu con animazione
            setTimeout(() => this.menu.classList.add('show'), 10);
            
            // Aggiungi i listener agli elementi del menu
            const menuItems = this.menu.querySelectorAll('.context-menu-item');
            menuItems.forEach(item => {
                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.hide();
                    // Qui puoi aggiungere azioni specifiche per ogni elemento
                });
            });
        },
        
        hide: function() {
            if (this.menu && document.body.contains(this.menu)) {
                document.body.removeChild(this.menu);
                this.menu = null;
            }
        }
    },
    
    // Gestione finestre
    windowManager: {
        init: function() {
            this.setupWindowControls();
        },
        
        setupWindowControls: function() {
            const minimizeBtn = document.querySelector('.win-control-btn.minimize');
            const maximizeBtn = document.querySelector('.win-control-btn.maximize');
            const closeBtn = document.querySelector('.win-control-btn.close');
            
            if (minimizeBtn) {
                minimizeBtn.addEventListener('click', () => this.minimizeWindow());
            }
            
            if (maximizeBtn) {
                maximizeBtn.addEventListener('click', () => this.toggleMaximize());
            }
            
            if (closeBtn) {
                closeBtn.addEventListener('click', () => this.closeWindow());
            }
            
            // Rendi trascinabile la barra del titolo
            const titleBar = document.querySelector('.app-title-bar');
            const appWindow = document.querySelector('.app-window');
            
            if (titleBar && appWindow) {
                this.makeWindowDraggable(titleBar, appWindow);
            }
        },
        
        minimizeWindow: function() {
            const appWindow = document.querySelector('.app-window');
            if (appWindow) {
                appWindow.classList.add('minimizing');
                setTimeout(() => {
                    appWindow.classList.remove('minimizing');
                }, 300);
            }
        },
        
        toggleMaximize: function() {
            const appWindow = document.querySelector('.app-window');
            const maximizeBtn = document.querySelector('.win-control-btn.maximize i');
            
            if (appWindow && maximizeBtn) {
                appWindow.classList.toggle('maximized');
                
                if (appWindow.classList.contains('maximized')) {
                    maximizeBtn.className = 'fas fa-clone';
                } else {
                    maximizeBtn.className = 'fas fa-square';
                }
            }
        },
        
        closeWindow: function() {
            const appWindow = document.querySelector('.app-window');
            if (appWindow) {
                appWindow.classList.add('closing');
                setTimeout(() => {
                    appWindow.classList.remove('closing');
                }, 300);
            }
        },
        
        makeWindowDraggable: function(handle, window) {
            let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
            
            handle.addEventListener('mousedown', dragMouseDown);
            
            function dragMouseDown(e) {
                // Evita il drag sui controlli della finestra
                if (e.target.closest('.win-controls')) return;
                
                // Evita il drag se la finestra è massimizzata
                if (window.classList.contains('maximized')) return;
                
                e.preventDefault();
                
                // Ottieni la posizione iniziale del cursore
                pos3 = e.clientX;
                pos4 = e.clientY;
                
                // Aggiungi classe per stile durante il trascinamento
                window.classList.add('dragging');
                
                // Imposta gli eventi per il trascinamento e il rilascio
                document.addEventListener('mousemove', elementDrag);
                document.addEventListener('mouseup', closeDragElement);
            }
            
            function elementDrag(e) {
                e.preventDefault();
                
                // Calcola la nuova posizione del cursore
                pos1 = pos3 - e.clientX;
                pos2 = pos4 - e.clientY;
                pos3 = e.clientX;
                pos4 = e.clientY;
                
                // Imposta la nuova posizione della finestra
                const top = window.offsetTop - pos2;
                const left = window.offsetLeft - pos1;
                
                window.style.top = top + "px";
                window.style.left = left + "px";
            }
            
            function closeDragElement() {
                // Rimuovi classe di trascinamento
                window.classList.remove('dragging');
                
                // Rimuovi gli eventi
                document.removeEventListener('mousemove', elementDrag);
                document.removeEventListener('mouseup', closeDragElement);
            }
        }
    },
    
    // Effetti Fluent
    fluent: {
        init: function() {
            this.initMicaEffect();
            this.initClickEffects();
        },
        
        initMicaEffect: function() {
            // Aggiungi effetto Mica al background
            if (!document.querySelector('.win11-mica-bg')) {
                const micaBackground = document.createElement('div');
                micaBackground.className = 'win11-mica-bg';
                document.body.prepend(micaBackground);
            }
            
            // Elementi con effetto Acrylic
            document.querySelectorAll('.win11-start-panel, .notification-panel, .widgets-panel').forEach(element => {
                element.addEventListener('mouseenter', function() {
                    this.style.backdropFilter = 'blur(30px) saturate(180%)';
                });
                
                element.addEventListener('mouseleave', function() {
                    this.style.backdropFilter = 'blur(20px) saturate(150%)';
                });
            });
        },
        
        initClickEffects: function() {
            // Effetto click
            document.addEventListener('click', (e) => {
                const ripple = document.createElement('div');
                ripple.className = 'win11-click-effect';
                ripple.style.left = e.clientX + 'px';
                ripple.style.top = e.clientY + 'px';
                
                document.body.appendChild(ripple);
                setTimeout(() => ripple.remove(), 700);
            });
        }
    }
};

// Inizializza Windows 11 quando il DOM è pronto
document.addEventListener('DOMContentLoaded', function() {
    Win11.init();
});
