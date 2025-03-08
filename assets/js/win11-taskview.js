/**
 * CoreSuite IT - Windows 11 Task View
 * 
 * Implementa la funzionalità "Task View" di Windows 11, che permette di visualizzare
 * tutte le finestre aperte e gestire i desktop virtuali
 */

document.addEventListener('DOMContentLoaded', function() {
    initTaskView();
});

/**
 * Inizializza la funzionalità Task View
 */
function initTaskView() {
    // Crea il pulsante Task View nella taskbar se non esiste
    if (!document.querySelector('.taskbar-icon[title="Task View"]')) {
        createTaskViewButton();
    }

    // Crea il pannello Task View se non esiste
    if (!document.getElementById('taskViewPanel')) {
        createTaskViewPanel();
    }
    
    // Inizializza desktop virtuali
    initVirtualDesktops();
    
    // Registra scorciatoie da tastiera
    registerTaskViewShortcuts();
}

/**
 * Crea il pulsante Task View nella taskbar
 */
function createTaskViewButton() {
    const taskViewBtn = document.createElement('button');
    taskViewBtn.className = 'taskbar-icon';
    taskViewBtn.title = 'Task View';
    taskViewBtn.innerHTML = '<i class="far fa-clone"></i>';
    
    // Inserisci dopo il pulsante Widget
    const widgetBtn = document.querySelector('.taskbar-icon[title="Widget"]');
    if (widgetBtn && widgetBtn.parentNode) {
        widgetBtn.parentNode.insertBefore(taskViewBtn, widgetBtn.nextSibling);
    } else {
        // Fallback: inserisci all'inizio della taskbar
        const taskbarIcons = document.querySelector('.taskbar-app-icons');
        if (taskbarIcons) {
            taskbarIcons.prepend(taskViewBtn);
        }
    }
    
    // Aggiungi event listener
    taskViewBtn.addEventListener('click', toggleTaskView);
}

/**
 * Crea il pannello Task View
 */
function createTaskViewPanel() {
    const taskViewPanel = document.createElement('div');
    taskViewPanel.id = 'taskViewPanel';
    taskViewPanel.className = 'task-view-panel';
    
    taskViewPanel.innerHTML = `
        <div class="task-view-backdrop"></div>
        <div class="task-view-header">
            <span class="close-task-view">
                <i class="fas fa-times"></i>
            </span>
            <div class="virtual-desktops">
                <div class="desktop-thumbnails">
                    <div class="desktop-thumbnail active" data-desktop-id="1">
                        <div class="desktop-name">Desktop 1</div>
                    </div>
                    <div class="add-desktop">
                        <i class="fas fa-plus"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="task-view-content">
            <div class="window-thumbnails">
                <!-- I thumbnail delle finestre verranno aggiunti qui dinamicamente -->
                <div class="no-windows-message">
                    <i class="far fa-window-maximize"></i>
                    <p>Non ci sono finestre aperte</p>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(taskViewPanel);
    
    // Aggiungi eventi
    taskViewPanel.querySelector('.close-task-view').addEventListener('click', hideTaskView);
    taskViewPanel.querySelector('.add-desktop').addEventListener('click', addVirtualDesktop);
    
    // Chiusura quando si clicca sullo sfondo
    taskViewPanel.querySelector('.task-view-backdrop').addEventListener('click', hideTaskView);
    
    // Animazione di apertura/chiusura
    taskViewPanel.addEventListener('transitionend', function(e) {
        if (e.propertyName === 'opacity' && !taskViewPanel.classList.contains('show')) {
            // Resetta lo stato quando il pannello è completamente nascosto
            resetTaskViewState();
        }
    });
}

/**
 * Mostra la Task View
 */
function showTaskView() {
    const taskViewPanel = document.getElementById('taskViewPanel');
    if (!taskViewPanel) return;
    
    // Nascondi altri pannelli aperti
    document.querySelectorAll('.win11-start-panel.show, .notification-panel.show, .widgets-panel.show').forEach(panel => {
        panel.classList.remove('show');
    });
    
    // Mostra la Task View
    taskViewPanel.classList.add('show');
    
    // Aggiorna i thumbnail delle finestre
    updateWindowThumbnails();
    
    // Evidenzia il pulsante nella taskbar
    document.querySelector('.taskbar-icon[title="Task View"]')?.classList.add('active');
    
    // Aggiungi classe al body per effetti globali (blur, ecc.)
    document.body.classList.add('task-view-active');
}

/**
 * Nasconde la Task View
 */
function hideTaskView() {
    const taskViewPanel = document.getElementById('taskViewPanel');
    if (!taskViewPanel) return;
    
    // Nascondi il pannello
    taskViewPanel.classList.remove('show');
    
    // Rimuovi highlight dal pulsante della taskbar
    document.querySelector('.taskbar-icon[title="Task View"]')?.classList.remove('active');
    
    // Rimuovi classe dal body
    document.body.classList.remove('task-view-active');
}

/**
 * Alterna visibilità della Task View
 */
function toggleTaskView() {
    const taskViewPanel = document.getElementById('taskViewPanel');
    if (!taskViewPanel) return;
    
    if (taskViewPanel.classList.contains('show')) {
        hideTaskView();
    } else {
        showTaskView();
    }
}

/**
 * Resetta lo stato della Task View
 */
function resetTaskViewState() {
    // Esegui pulizia se necessario
}

/**
 * Aggiorna i thumbnail delle finestre nella Task View
 */
function updateWindowThumbnails() {
    const thumbnailsContainer = document.querySelector('.window-thumbnails');
    if (!thumbnailsContainer) return;
    
    // Rimuovi i thumbnail esistenti (tranne il messaggio "nessuna finestra")
    thumbnailsContainer.querySelectorAll('.window-thumbnail').forEach(thumbnail => {
        thumbnail.remove();
    });
    
    // Ottieni finestre aperte
    const appWindows = document.querySelectorAll('.app-window');
    const noWindowsMessage = thumbnailsContainer.querySelector('.no-windows-message');
    
    if (appWindows.length === 0) {
        // Mostra il messaggio "nessuna finestra"
        if (noWindowsMessage) {
            noWindowsMessage.style.display = 'flex';
        }
        return;
    }
    
    // Nascondi il messaggio "nessuna finestra"
    if (noWindowsMessage) {
        noWindowsMessage.style.display = 'none';
    }
    
    // Crea thumbnail per ogni finestra
    appWindows.forEach((window, index) => {
        const windowTitle = window.querySelector('.app-title')?.textContent || `Finestra ${index + 1}`;
        const windowIcon = window.querySelector('.app-icon')?.innerHTML || '<i class="far fa-window-maximize"></i>';
        
        const thumbnail = document.createElement('div');
        thumbnail.className = 'window-thumbnail';
        thumbnail.dataset.windowId = window.id || index;
        
        // Crea una rappresentazione visuale della finestra
        thumbnail.innerHTML = `
            <div class="window-preview">
                <div class="preview-title-bar">
                    ${windowIcon}
                    <span class="preview-title">${windowTitle}</span>
                </div>
                <div class="preview-content"></div>
            </div>
            <div class="thumbnail-title">${windowTitle}</div>
            <button class="close-window" title="Chiudi">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        // Aggiungi gli eventi al thumbnail
        thumbnail.addEventListener('click', () => {
            // Attiva la finestra corrispondente
            activateWindow(window);
            hideTaskView();
        });
        
        // Pulsante di chiusura
        thumbnail.querySelector('.close-window').addEventListener('click', (e) => {
            e.stopPropagation();
            closeWindow(window);
            updateWindowThumbnails();
        });
        
        // Aggiungi il thumbnail al contenitore
        thumbnailsContainer.insertBefore(thumbnail, noWindowsMessage);
        
        // Cattura istantanea della finestra (versione semplificata)
        captureWindowSnapshot(window, thumbnail.querySelector('.preview-content'));
    });
}

/**
 * Cattura un'istantanea visuale di una finestra
 * @param {HTMLElement} window - Elemento della finestra da catturare
 * @param {HTMLElement} container - Contenitore dove inserire l'istantanea
 */
function captureWindowSnapshot(window, container) {
    // Metodo semplice: clona il contenuto della finestra
    const windowContent = window.querySelector('.app-window-content');
    if (windowContent && container) {
        // Versione semplificata che copia lo stile
        container.style.backgroundColor = getComputedStyle(windowContent).backgroundColor;
        
        // Se possibile, aggiungi un'anteprima rappresentativa
        // Nota: una vera implementazione userebbe html2canvas o simili per catturare un'immagine reale
        const firstImage = windowContent.querySelector('img');
        if (firstImage) {
            const imgPreview = document.createElement('img');
            imgPreview.src = firstImage.src;
            imgPreview.style.width = "100%";
            imgPreview.style.height = "auto";
            imgPreview.style.objectFit = "cover";
            container.appendChild(imgPreview);
        } else {
            // Fallback: mostra un'icona generica
            const icon = document.createElement('div');
            icon.className = 'generic-preview-icon';
            icon.innerHTML = '<i class="far fa-file"></i>';
            container.appendChild(icon);
        }
    }
}

/**
 * Attiva una finestra specifica
 * @param {HTMLElement} window - La finestra da attivare
 */
function activateWindow(window) {
    if (!window) return;
    
    // Porta la finestra in primo piano
    const highestZIndex = Array.from(document.querySelectorAll('.app-window'))
        .map(w => parseInt(getComputedStyle(w).zIndex) || 0)
        .reduce((max, z) => Math.max(max, z), 0);
    
    window.style.zIndex = highestZIndex + 1;
    
    // Assicurati che la finestra sia visibile
    window.classList.remove('minimized');
    
    // Aggiorna la taskbar
    updateTaskbarActiveWindow(window);
}

/**
 * Chiude una finestra
 * @param {HTMLElement} window - La finestra da chiudere
 */
function closeWindow(window) {
    if (!window) return;
    
    // Animazione di chiusura
    window.classList.add('closing');
    
    // Rimuovi la finestra dopo l'animazione
    setTimeout(() => {
        if (window.parentNode) {
            window.parentNode.removeChild(window);
        }
        updateTaskbarActiveWindow(null);
    }, 300);
}

/**
 * Aggiorna l'indicatore di finestra attiva nella taskbar
 * @param {HTMLElement|null} activeWindow - La finestra attiva
 */
function updateTaskbarActiveWindow(activeWindow) {
    // Implementazione semplificata
    // In un'implementazione reale questo collegherebbe la finestra attiva
    // con il suo pulsante nella taskbar
}

/**
 * Inizializza la gestione dei desktop virtuali
 */
function initVirtualDesktops() {
    // Ottieni/inizializza l'elenco dei desktop virtuali
    window.virtualDesktops = window.virtualDesktops || [
        { id: 1, name: "Desktop 1", windows: [] }
    ];
    
    // Gestione degli eventi sui thumbnail dei desktop
    document.querySelectorAll('.desktop-thumbnail').forEach(thumbnail => {
        thumbnail.addEventListener('click', () => {
            switchToDesktop(thumbnail.dataset.desktopId);
        });
        
        // Aggiungi pulsante di chiusura ai desktop (eccetto il primo)
        if (thumbnail.dataset.desktopId !== "1") {
            const closeButton = document.createElement('button');
            closeButton.className = 'close-desktop';
            closeButton.innerHTML = '<i class="fas fa-times"></i>';
            closeButton.addEventListener('click', (e) => {
                e.stopPropagation();
                removeVirtualDesktop(thumbnail.dataset.desktopId);
            });
            thumbnail.appendChild(closeButton);
        }
    });
}

/**
 * Aggiunge un nuovo desktop virtuale
 */
function addVirtualDesktop() {
    const desktopThumbnails = document.querySelector('.desktop-thumbnails');
    const addDesktopButton = document.querySelector('.add-desktop');
    
    if (!desktopThumbnails || !addDesktopButton) return;
    
    // Crea un nuovo ID desktop
    const newDesktopId = window.virtualDesktops.length + 1;
    
    // Aggiungi il nuovo desktop all'array
    window.virtualDesktops.push({
        id: newDesktopId,
        name: `Desktop ${newDesktopId}`,
        windows: []
    });
    
    // Crea il thumbnail per il nuovo desktop
    const desktopThumbnail = document.createElement('div');
    desktopThumbnail.className = 'desktop-thumbnail';
    desktopThumbnail.dataset.desktopId = newDesktopId;
    desktopThumbnail.innerHTML = `
        <div class="desktop-name">Desktop ${newDesktopId}</div>
        <button class="close-desktop">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // Inserisci prima del pulsante "+"
    desktopThumbnails.insertBefore(desktopThumbnail, addDesktopButton);
    
    // Aggiungi eventi al nuovo desktop
    desktopThumbnail.addEventListener('click', () => {
        switchToDesktop(newDesktopId);
    });
    
    desktopThumbnail.querySelector('.close-desktop').addEventListener('click', (e) => {
        e.stopPropagation();
        removeVirtualDesktop(newDesktopId);
    });
}

/**
 * Passa a un desktop virtuale specifico
 * @param {number|string} desktopId - ID del desktop
 */
function switchToDesktop(desktopId) {
    // Converti in numero
    desktopId = parseInt(desktopId);
    
    // Aggiorna i thumbnail
    document.querySelectorAll('.desktop-thumbnail').forEach(thumbnail => {
        if (parseInt(thumbnail.dataset.desktopId) === desktopId) {
            thumbnail.classList.add('active');
        } else {
            thumbnail.classList.remove('active');
        }
    });
    
    // Simula il passaggio a un altro desktop
    // In un'implementazione reale, mostreresti/nasconderesti finestre di conseguenza
    console.log(`Passaggio al desktop ${desktopId}`);
}

/**
 * Rimuove un desktop virtuale
 * @param {number|string} desktopId - ID del desktop da rimuovere
 */
function removeVirtualDesktop(desktopId) {
    // Converti in numero
    desktopId = parseInt(desktopId);
    
    // Non permettere di rimuovere il desktop 1
    if (desktopId === 1) return;
    
    // Rimuovi il desktop dall'array
    window.virtualDesktops = window.virtualDesktops.filter(d => d.id !== desktopId);
    
    // Rimuovi il thumbnail
    const thumbnail = document.querySelector(`.desktop-thumbnail[data-desktop-id="${desktopId}"]`);
    if (thumbnail) {
        // Se il desktop rimosso è attivo, passa al desktop 1
        if (thumbnail.classList.contains('active')) {
            switchToDesktop(1);
        }
        thumbnail.remove();
    }
}

/**
 * Registra scorciatoie da tastiera per Task View
 */
function registerTaskViewShortcuts() {
    document.addEventListener('keydown', function(e) {
        // Windows + Tab (Alt+Tab per questa implementazione)
        if (e.altKey && e.key === 'Tab') {
            e.preventDefault();
            toggleTaskView();
        }
        
        // Escape per chiudere Task View
        if (e.key === 'Escape') {
            const taskViewPanel = document.getElementById('taskViewPanel');
            if (taskViewPanel && taskViewPanel.classList.contains('show')) {
                hideTaskView();
            }
        }
    });
}
