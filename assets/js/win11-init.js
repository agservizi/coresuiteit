/**
 * CoreSuite IT - Windows 11 Initializer
 * 
 * File di inizializzazione globale per l'interfaccia Windows 11
 * Coordina il caricamento di tutti i componenti necessari per una esperienza coerente
 */

document.addEventListener('DOMContentLoaded', function() {
    initWindowsEnvironment();
});

/**
 * Inizializza l'intero ambiente Windows 11
 */
function initWindowsEnvironment() {
    console.log('Inizializzazione ambiente Windows 11 per CoreSuite IT');
    
    // Configurazione corpo documento
    setupWindowsBody();
    
    // Carica i componenti core
    loadCoreComponents();
    
    // Carica componenti avanzati
    loadAdvancedComponents();
    
    // Abilita funzionalità Windows 11
    enableWindowsFunctionality();
    
    console.log('Ambiente Windows 11 inizializzato con successo');
}

/**
 * Configura il body per Windows 11
 */
function setupWindowsBody() {
    document.body.classList.add('windows11-environment');
    
    // Imposta tema in base alle preferenze o impostazioni
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
    }
    
    // Aggiungi sfondo Mica
    if (!document.querySelector('.win11-mica-bg')) {
        const micaBackground = document.createElement('div');
        micaBackground.className = 'win11-mica-bg';
        document.body.prepend(micaBackground);
    }
    
    // Aggiungi contenitore per effetti fluidi
    if (!document.getElementById('win11FluentContainer')) {
        const fluentContainer = document.createElement('div');
        fluentContainer.id = 'win11FluentContainer';
        document.body.appendChild(fluentContainer);
    }
}

/**
 * Carica i componenti fondamentali di Windows 11
 */
function loadCoreComponents() {
    // Crea la taskbar se non esiste
    if (!document.querySelector('.win11-taskbar')) {
        createTaskbar();
    }
    
    // Crea container per notifiche
    if (!document.getElementById('win11NotificationContainer')) {
        const notificationContainer = document.createElement('div');
        notificationContainer.id = 'win11NotificationContainer';
        notificationContainer.className = 'win11-toast-container';
        document.body.appendChild(notificationContainer);
    }
}

/**
 * Crea la taskbar di Windows 11
 */
function createTaskbar() {
    console.log('Creazione taskbar Windows 11');
    
    const taskbar = document.createElement('div');
    taskbar.className = 'win11-taskbar';
    
    // Contenuto taskbar
    taskbar.innerHTML = `
        <div class="taskbar-start">
            <button class="taskbar-icon" id="startMenuBtn" title="Start">
                <i class="fab fa-windows"></i>
            </button>
            <button class="taskbar-icon" id="widgetBtn" title="Widget">
                <i class="fas fa-th-large"></i>
            </button>
            <button class="taskbar-icon" id="taskViewBtn" title="Task View">
                <i class="far fa-clone"></i>
            </button>
        </div>
        <div class="taskbar-app-icons">
            <button class="taskbar-icon" title="Esplora File">
                <i class="far fa-folder"></i>
            </button>
            <button class="taskbar-icon" title="Dashboard">
                <i class="fas fa-chart-line"></i>
            </button>
            <button class="taskbar-icon" title="Clienti">
                <i class="fas fa-users"></i>
            </button>
            <button class="taskbar-icon" title="Pagamenti">
                <i class="fas fa-credit-card"></i>
            </button>
        </div>
        <div class="win11-task-right">
            <button class="taskbar-icon" id="notificationBtn" title="Notifiche">
                <i class="far fa-bell"></i>
            </button>
            <div class="taskbar-item">
                <span id="taskbarTime" class="taskbar-time">00:00</span>
                <span id="taskbarDate" class="taskbar-date">01/01/2023</span>
            </div>
        </div>
    `;
    
    document.body.appendChild(taskbar);
    
    // Inizializza orologio
    updateTaskbarClock();
    setInterval(updateTaskbarClock, 60000);
    
    // Associa eventi ai pulsanti
    taskbar.querySelector('#startMenuBtn').addEventListener('click', toggleStartMenu);
    taskbar.querySelector('#widgetBtn').addEventListener('click', toggleWidgetPanel);
    taskbar.querySelector('#taskViewBtn').addEventListener('click', toggleTaskView);
    taskbar.querySelector('#notificationBtn').addEventListener('click', toggleNotificationPanel);
}

/**
 * Aggiorna l'orologio della taskbar
 */
function updateTaskbarClock() {
    const now = new Date();
    
    // Aggiorna ora
    const timeElement = document.getElementById('taskbarTime');
    if (timeElement) {
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        timeElement.textContent = `${hours}:${minutes}`;
    }
    
    // Aggiorna data se esiste l'elemento
    const dateElement = document.getElementById('taskbarDate');
    if (dateElement) {
        const day = String(now.getDate()).padStart(2, '0');
        const month = String(now.getMonth() + 1).padStart(2, '0');
        dateElement.textContent = `${day}/${month}/${now.getFullYear()}`;
    }
}

/**
 * Carica componenti avanzati di Windows 11
 */
function loadAdvancedComponents() {
    // Inizializza manager per finestre
    if (typeof initWindowManager === 'function') {
        initWindowManager();
    }
    
    // Inizializza effetti fluent
    if (typeof initMicaEffect === 'function') {
        initMicaEffect();
    }
    
    // Inizializza Snap Layout
    if (typeof initSnapLayouts === 'function') {
        initSnapLayouts();
    }
    
    // Inizializza Task View
    if (typeof initTaskView === 'function') {
        initTaskView();
    }
    
    // Inizializza Widget Dashboard
    if (typeof initWidgetDashboard === 'function') {
        initWidgetDashboard();
    }
}

/**
 * Abilita funzionalità avanzate di Windows 11
 */
function enableWindowsFunctionality() {
    // Registra scorciatoie da tastiera globali
    setupGlobalKeyboardShortcuts();
    
    // Abilita effetti click
    setupClickEffects();
    
    // Abilita menu contestuale
    setupContextMenu();
}

/**
 * Configura scorciatoie da tastiera globali
 */
function setupGlobalKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        // Win+I (Ctrl+I) - Impostazioni
        if (e.ctrlKey && e.key === 'i') {
            e.preventDefault();
            if (typeof openSettingsPanel === 'function') {
                openSettingsPanel();
            }
        }
        
        // Win+E (Ctrl+E) - Esplora file
        if (e.ctrlKey && e.key === 'e') {
            e.preventDefault();
            console.log('Apertura Esplora File');
        }
        
        // Win+L (Ctrl+L) - Blocca schermo
        if (e.ctrlKey && e.key === 'l') {
            e.preventDefault();
            if (typeof lockScreen === 'function') {
                lockScreen();
            }
        }
        
        // Alt+Tab - Task View
        if (e.altKey && e.key === 'Tab') {
            e.preventDefault();
            if (typeof toggleTaskView === 'function') {
                toggleTaskView();
            }
        }
        
        // Escape - Chiudi pannelli aperti
        if (e.key === 'Escape') {
            closeAllPanels();
        }
    });
}

/**
 * Configura effetti click
 */
function setupClickEffects() {
    document.addEventListener('click', function(e) {
        // Non creare effetto per click su elementi interattivi
        if (e.target.closest('button, a, input, select, textarea')) return;
        
        const ripple = document.createElement('div');
        ripple.className = 'win11-click-effect';
        ripple.style.left = e.clientX + 'px';
        ripple.style.top = e.clientY + 'px';
        
        document.body.appendChild(ripple);
        
        // Rimuovi l'effetto dopo l'animazione
        setTimeout(() => {
            ripple.remove();
        }, 700);
    });
}

/**
 * Configura menu contestuale
 */
function setupContextMenu() {
    document.addEventListener('contextmenu', function(e) {
        if (e.target === document.body ||
            e.target.classList.contains('win11-desktop') ||
            e.target.classList.contains('desktop-item')) {
            
            e.preventDefault();
            showContextMenu(e.clientX, e.clientY);
        }
    });
    
    document.addEventListener('click', function() {
        hideContextMenu();
    });
}

/**
 * Mostra menu contestuale
 */
function showContextMenu(x, y) {
    hideContextMenu();
    
    const menu = document.createElement('div');
    menu.id = 'win11ContextMenu';
    menu.className = 'win11-context-menu';
    
    menu.innerHTML = `
        <div class="context-menu-item"><i class="fas fa-sync"></i> Aggiorna</div>
        <div class="context-menu-separator"></div>
        <div class="context-menu-item"><i class="fas fa-th-large"></i> Visualizza</div>
        <div class="context-menu-item"><i class="fas fa-sort"></i> Ordina per</div>
        <div class="context-menu-separator"></div>
        <div class="context-menu-item"><i class="fas fa-plus"></i> Nuovo</div>
        <div class="context-menu-separator"></div>
        <div class="context-menu-item"><i class="fas fa-cog"></i> Personalizza</div>
        <div class="context-menu-item"><i class="fas fa-desktop"></i> Impostazioni schermo</div>
    `;
    
    // Posiziona il menu
    menu.style.left = x + 'px';
    menu.style.top = y + 'px';
    
    // Aggiungi al body
    document.body.appendChild(menu);
    
    // Verifica che il menu non esca dalla finestra
    const menuRect = menu.getBoundingClientRect();
    const maxRight = window.innerWidth - menuRect.width - 10;
    const maxBottom = window.innerHeight - menuRect.height - 10;
    
    if (x > maxRight) menu.style.left = maxRight + 'px';
    if (y > maxBottom) menu.style.top = maxBottom + 'px';
    
    // Mostra con animazione
    requestAnimationFrame(() => {
        menu.classList.add('show');
    });
    
    // Aggiungi event listeners agli elementi del menu
    menu.querySelectorAll('.context-menu-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.stopPropagation();
            hideContextMenu();
            // Qui puoi aggiungere azioni specifiche
        });
    });
}

/**
 * Nascondi menu contestuale
 */
function hideContextMenu() {
    const menu = document.getElementById('win11ContextMenu');
    if (menu) {
        menu.classList.remove('show');
        setTimeout(() => {
            menu.remove();
        }, 300);
    }
}

/**
 * Chiudi tutti i pannelli aperti
 */
function closeAllPanels() {
    // Chiudi tutti i pannelli e menu aperti
    document.querySelectorAll('.win11-start-panel.show, .widgets-panel.show, .notification-panel.show, .task-view-panel.show, .win11-settings-panel.show').forEach(panel => {
        panel.classList.remove('show');
    });
    
    // Rimuovi evidenziazione dai pulsanti della taskbar
    document.querySelectorAll('.taskbar-icon.active').forEach(btn => {
        btn.classList.remove('active');
    });
}

/**
 * Toggle del menu Start
 */
function toggleStartMenu() {
    const startMenuPanel = document.getElementById('startMenuPanel');
    
    if (startMenuPanel) {
        // Se il pannello esiste, alterna visibilità
        const isVisible = startMenuPanel.classList.contains('show');
        
        // Chiudi tutti i pannelli
        closeAllPanels();
        
        if (!isVisible) {
            // Mostra il menu start
            startMenuPanel.classList.add('show');
            document.getElementById('startMenuBtn')?.classList.add('active');
        }
    } else {
        // Se il pannello non esiste, crealo
        if (typeof Win11 !== 'undefined' && typeof Win11.startMenu !== 'undefined') {
            Win11.startMenu.init();
            Win11.startMenu.show();
        } else {
            console.error('Componente Menu Start non trovato');
        }
    }
}

/**
 * Toggle del pannello widget
 */
function toggleWidgetPanel() {
    const widgetsPanel = document.getElementById('widgetsPanel');
    
    if (widgetsPanel) {
        const isVisible = widgetsPanel.classList.contains('show');
        
        // Chiudi tutti i pannelli
        closeAllPanels();
        
        if (!isVisible) {
            widgetsPanel.classList.add('show');
            document.getElementById('widgetBtn')?.classList.add('active');
        }
    } else {
        console.error('Pannello Widget non trovato');
    }
}

/**
 * Toggle della Task View
 */
function toggleTaskView() {
    const taskViewPanel = document.getElementById('taskViewPanel');
    
    if (taskViewPanel) {
        const isVisible = taskViewPanel.classList.contains('show');
        
        // Chiudi tutti i pannelli
        closeAllPanels();
        
        if (!isVisible) {
            taskViewPanel.classList.add('show');
            document.getElementById('taskViewBtn')?.classList.add('active');
        }
    } else {
        console.error('Pannello Task View non trovato');
    }
}

/**
 * Toggle del pannello notifiche
 */
function toggleNotificationPanel() {
    const notificationPanel = document.getElementById('notificationPanel');
    
    if (notificationPanel) {
        const isVisible = notificationPanel.classList.contains('show');
        
        // Chiudi tutti i pannelli
        closeAllPanels();
        
        if (!isVisible) {
            notificationPanel.classList.add('show');
            document.getElementById('notificationBtn')?.classList.add('active');
        }
    } else {
        console.error('Pannello Notifiche non trovato');
    }
}
