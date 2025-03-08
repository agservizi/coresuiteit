/**
 * CoreSuite IT - Windows 11 Integration
 * File principale che coordina tutti i componenti Windows 11 e li integra in un'unica esperienza coerente
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inizializza componenti core
    initWindowsEnvironment();
    
    // Configura tema e personalizzazione
    initThemeSystem();
    
    // Sistemi di notifica e comunicazione
    initNotificationSystem();
    
    // Sistema di desktop virtuali
    initVirtualDesktops();
    
    // Integrazione con altri sistemi
    initCoresuiteIntegration();
    
    // Registra gli hook per eventi globali
    registerGlobalEventHandlers();
});

/**
 * Inizializza l'ambiente Windows 11 base
 */
function initWindowsEnvironment() {
    console.log("Inizializzazione ambiente Windows 11...");
    
    // Aggiungi la classe Windows 11 al body per lo styling globale
    document.body.classList.add('windows11-environment');
    
    // Crea o recupera il container principale
    let win11Container = document.getElementById('win11Container');
    if (!win11Container) {
        win11Container = document.createElement('div');
        win11Container.id = 'win11Container';
        win11Container.className = 'win11-container';
        document.body.appendChild(win11Container);
    }
    
    // Aggiungi il contesto di esecuzione all'oggetto window
    window.win11Context = {
        version: '1.0.0',
        buildNumber: '22621.2715',
        openWindows: [],
        taskbarItems: [],
        notificationQueue: [],
        activeDesktop: 0,
        desktops: [{ id: 0, name: 'Desktop 1', windows: [] }],
        settings: loadSettings()
    };
    
    // Inizializza tutti i componenti Windows 11
    loadComponentsInOrder([
        { name: 'win11-effects', hasInitialized: false },
        { name: 'win11-components', hasInitialized: false },
        { name: 'win11-notifications', hasInitialized: false },
        { name: 'win11-widgets', hasInitialized: false },
        { name: 'win11-fluent', hasInitialized: false },
        { name: 'win11-settings', hasInitialized: false }
    ]);
    
    console.log("Ambiente Windows 11 inizializzato correttamente.");
}

/**
 * Carica i componenti in un ordine specifico per gestire le dipendenze
 * @param {Array} components - Array di oggetti componente
 */
function loadComponentsInOrder(components) {
    // Prima verifica quali componenti sono già inizializzati
    components.forEach(component => {
        // Cerca una funzione globale che indica che il componente è stato inizializzato
        const initFunctionName = 'init' + component.name.split('-')[1].charAt(0).toUpperCase() + 
                                component.name.split('-')[1].slice(1);
        
        if (typeof window[initFunctionName] === 'function') {
            component.hasInitialized = true;
            console.log(`Componente ${component.name} già inizializzato.`);
        }
    });
    
    // Poi inizializza quelli non ancora caricati in ordine
    components.forEach(component => {
        if (!component.hasInitialized) {
            console.log(`Inizializzazione componente ${component.name}...`);
            // Qui potresti implementare un caricamento dinamico dei script se necessario
            // Per ora assumiamo che gli script siano già caricati nella pagina
        }
    });
}

/**
 * Carica le impostazioni salvate o usa i valori predefiniti
 * @returns {Object} Oggetto impostazioni
 */
function loadSettings() {
    const defaultSettings = {
        theme: 'light',
        accentColor: '#0078d4',
        transparency: true,
        notifications: true,
        animations: true,
        backgroundImage: 'assets/img/win11-wallpaper.jpg',
        language: 'it-IT',
        sound: true,
        autoHideTaskbar: false,
        startMenuLayout: 'default',
        accessibility: {
            highContrast: false,
            largeText: false,
            reducedMotion: false
        }
    };
    
    // Carica impostazioni da localStorage se esistono
    const savedSettings = localStorage.getItem('win11Settings');
    if (savedSettings) {
        try {
            return Object.assign({}, defaultSettings, JSON.parse(savedSettings));
        } catch (e) {
            console.error("Errore parsing impostazioni:", e);
            return defaultSettings;
        }
    }
    
    return defaultSettings;
}

/**
 * Inizializza il sistema di temi di Windows 11
 */
function initThemeSystem() {
    // Carica il tema corrente dalle impostazioni
    const settings = window.win11Context.settings;
    const preferredTheme = settings.theme;
    
    // Imposta il tema al caricamento della pagina
    document.documentElement.setAttribute('data-theme', preferredTheme);
    if (preferredTheme === 'dark') {
        document.body.classList.add('dark-theme');
    }
    
    // Sincronizza tutti i toggle di tema
    const themeToggles = document.querySelectorAll('.theme-toggle, #themeToggle, #darkModeToggleSettings');
    themeToggles.forEach(toggle => {
        if (toggle.type === 'checkbox') {
            toggle.checked = (preferredTheme === 'dark');
        }
    });
    
    // Applica il colore di accento
    document.documentElement.style.setProperty('--win11-accent', settings.accentColor);
    document.documentElement.style.setProperty('--win11-accent-dark', adjustColor(settings.accentColor, -20));
    document.documentElement.style.setProperty('--win11-accent-light', adjustColor(settings.accentColor, 20));
    
    // Gestisci effetti di trasparenza
    if (!settings.transparency) {
        document.documentElement.classList.add('no-transparency');
    }
    
    // Imposta sfondo desktop
    if (settings.backgroundImage) {
        document.body.style.backgroundImage = `url('${settings.backgroundImage}')`;
    }
}

/**
 * Aggiusta il colore per varianti più chiare o scure
 * @param {string} color - Colore esadecimale
 * @param {number} amount - Quantità di aggiustamento (-100 a 100)
 * @returns {string} Colore aggiustato
 */
function adjustColor(color, amount) {
    return color; // Implementazione semplificata, in un'app reale userebbe algoritmi di adattamento del colore
}

/**
 * Inizializza il sistema di notifica di Windows 11
 */
function initNotificationSystem() {
    // Crea il container per le notifiche se non esiste
    let notificationContainer = document.getElementById('win11NotificationContainer');
    if (!notificationContainer) {
        notificationContainer = document.createElement('div');
        notificationContainer.id = 'win11NotificationContainer';
        notificationContainer.className = 'win11-toast-container';
        document.body.appendChild(notificationContainer);
    }
    
    // Crea un sistema di pub/sub per le notifiche
    window.win11Notifications = {
        subscribers: [],
        subscribe: function(callback) {
            this.subscribers.push(callback);
            return () => {
                this.subscribers = this.subscribers.filter(cb => cb !== callback);
            };
        },
        publish: function(notification) {
            // Aggiungi alla coda
            window.win11Context.notificationQueue.push(notification);
            
            // Notifica tutti i sottoscrittori
            this.subscribers.forEach(callback => callback(notification));
            
            // Mostra la notifica
            showWin11Notification(notification);
        }
    };
    
    // Esponi l'API pubblica per le notifiche
    window.showWin11Notification = function(options) {
        if (!window.win11Context.settings.notifications) return;
        
        const notification = Object.assign({
            title: 'Notifica',
            message: '',
            type: 'info',
            duration: 5000,
            actions: []
        }, options);
        
        createToastNotification(notification);
        
        return notification;
    };
}

/**
 * Crea una notifica toast in stile Windows 11
 * @param {Object} notification - Dati della notifica
 */
function createToastNotification(notification) {
    const container = document.getElementById('win11NotificationContainer');
    if (!container) return;
    
    // Crea element toast
    const toast = document.createElement('div');
    toast.className = `win11-toast win11-toast-${notification.type}`;
    
    // Imposta icona in base al tipo
    let iconClass = 'fa-info-circle';
    switch (notification.type) {
        case 'success': iconClass = 'fa-check-circle'; break;
        case 'warning': iconClass = 'fa-exclamation-triangle'; break;
        case 'error': iconClass = 'fa-times-circle'; break;
    }
    
    // Costruisci il contenuto
    toast.innerHTML = `
        <div class="win11-toast-icon">
            <i class="fas ${iconClass}"></i>
        </div>
        <div class="win11-toast-content">
            <div class="win11-toast-title">${notification.title}</div>
            <div class="win11-toast-message">${notification.message}</div>
            ${notification.actions.length > 0 ? '<div class="win11-toast-actions"></div>' : ''}
        </div>
        <button class="win11-toast-close">
            <i class="fas fa-times"></i>
        </button>
        ${notification.progress ? '<div class="win11-toast-progress-bar win11-toast-progress-animate"></div>' : ''}
    `;
    
    // Aggiungi actions se presenti
    if (notification.actions.length > 0) {
        const actionsContainer = toast.querySelector('.win11-toast-actions');
        notification.actions.forEach(action => {
            const btn = document.createElement('button');
            btn.className = `win11-toast-btn ${action.primary ? 'win11-toast-btn-primary' : ''}`;
            btn.textContent = action.text;
            btn.addEventListener('click', () => {
                if (typeof action.callback === 'function') {
                    action.callback();
                }
                closeToast(toast);
            });
            actionsContainer.appendChild(btn);
        });
    }
    
    // Gestisci chiusura
    toast.querySelector('.win11-toast-close').addEventListener('click', () => closeToast(toast));
    
    // Aggiungi al container
    container.appendChild(toast);
    
    // Animazione di ingresso
    setTimeout(() => {
        toast.classList.add('show');
        
        // Auto chiusura se duration > 0
        if (notification.duration > 0) {
            setTimeout(() => closeToast(toast), notification.duration);
        }
    }, 10);
    
    function closeToast(toast) {
        toast.classList.remove('show');
        setTimeout(() => {
            if (toast.parentNode === container) {
                container.removeChild(toast);
            }
        }, 300);
    }
}

/**
 * Inizializza il sistema di desktop virtuali
 */
function initVirtualDesktops() {
    // Questa è una funzione avanzata che richiede maggiore implementazione
    console.log("Sistema desktop virtuali inizializzato.");
}

/**
 * Inizializza l'integrazione con Coresuite IT
 */
function initCoresuiteIntegration() {
    // Integrazione con le funzionalità specifiche di CoreSuite
    
    // Aggiungi badge notifica alla taskbar quando ci sono notifiche importanti
    const unreadNotificationsCount = getUnreadNotificationsCount();
    updateNotificationBadge(unreadNotificationsCount);
    
    // Integrazione con il sistema di autenticazione
    syncUserProfileWithSystem();
    
    // Collega le impostazioni di Windows 11 alle impostazioni dell'applicazione
    syncAppSettingsWithSystem();
}

/**
 * Ottiene il conteggio delle notifiche non lette
 * @returns {number} Numero di notifiche non lette
 */
function getUnreadNotificationsCount() {
    // Implementazione semplificata, in una app reale prenderebbe i dati dal backend
    return window.win11Context.notificationQueue.filter(n => !n.read).length || 0;
}

/**
 * Aggiorna il badge delle notifiche nella taskbar
 * @param {number} count - Conteggio notifiche
 */
function updateNotificationBadge(count) {
    const notificationButton = document.querySelector('.win11-task-right [title="Notifiche"]');
    if (!notificationButton) return;
    
    let badge = notificationButton.querySelector('.notification-badge');
    
    if (count > 0) {
        if (!badge) {
            badge = document.createElement('span');
            badge.className = 'notification-badge';
            notificationButton.appendChild(badge);
        }
        badge.textContent = count > 9 ? '9+' : count;
    } else if (badge) {
        badge.remove();
    }
}

/**
 * Sincronizza il profilo utente con il sistema
 */
function syncUserProfileWithSystem() {
    // Recupera il profilo utente attuale (in un'app reale verrebbe dal backend)
    const userProfile = {
        name: 'Utente',
        avatar: 'assets/img/avatar.jpg',
        email: 'utente@example.com'
    };
    
    // Aggiorna tutti gli elementi UI che mostrano il profilo utente
    const userNameElements = document.querySelectorAll('.user-name');
    const userAvatarElements = document.querySelectorAll('.user-avatar');
    
    userNameElements.forEach(el => {
        el.textContent = userProfile.name;
    });
    
    userAvatarElements.forEach(el => {
        if (el.tagName.toLowerCase() === 'img') {
            el.src = userProfile.avatar;
            el.alt = userProfile.name;
        }
    });
}

/**
 * Sincronizza le impostazioni dell'app con il sistema
 */
function syncAppSettingsWithSystem() {
    const settings = window.win11Context.settings;
    
    // Salva le impostazioni nel localStorage
    localStorage.setItem('win11Settings', JSON.stringify(settings));
}

/**
 * Registra handler globali per eventi di sistema
 */
function registerGlobalEventHandlers() {
    // Gestisce tasti di scelta rapida
    document.addEventListener('keydown', function(e) {
        // Windows + E (Esplora file)
        if (e.ctrlKey && e.key === 'e') {
            e.preventDefault();
            openFileExplorer();
        }
        
        // Alt + Tab (Cambio app)
        if (e.altKey && e.key === 'Tab') {
            e.preventDefault();
            toggleTaskView();
        }
        
        // Windows + A (Centro notifiche)
        if (e.ctrlKey && e.key === 'a') {
            e.preventDefault();
            toggleActionCenter();
        }
        
        // Windows + I (Impostazioni)
        if (e.ctrlKey && e.key === 'i') {
            e.preventDefault();
            openSettings();
        }
    });
    
    // Gestione cambiamenti di visibilità della pagina
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'visible') {
            // L'app torna in primo piano
            console.log("CoreSuite IT tornato in primo piano");
        } else {
            // L'app va in background
            console.log("CoreSuite IT in background");
        }
    });
}

/**
 * Apre l'esplora file
 */
function openFileExplorer() {
    console.log("Apertura Esplora File");
    // Implementazione di apertura esplora file
}

/**
 * Toggle della vista attività
 */
function toggleTaskView() {
    const taskViewPanel = document.getElementById('taskViewPanel');
    if (taskViewPanel) {
        taskViewPanel.classList.toggle('show');
    } else {
        console.log("Task View non disponibile");
    }
}

/**
 * Toggle dell'action center
 */
function toggleActionCenter() {
    const actionCenter = document.getElementById('actionCenter');
    if (actionCenter) {
        actionCenter.classList.toggle('show');
    } else {
        console.log("Action Center non disponibile");
    }
}

/**
 * Apre le impostazioni
 */
function openSettings() {
    // Implementazione che richiama la funzione già definita altrove
    if (typeof openSettingsPanel === 'function') {
        openSettingsPanel();
    } else {
        console.log("Pannello impostazioni non disponibile");
        
        // Fallback: crea una semplice notifica
        window.showWin11Notification({
            title: 'Impostazioni',
            message: 'Il pannello impostazioni verrà implementato presto',
            type: 'info'
        });
    }
}
