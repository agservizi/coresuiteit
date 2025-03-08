/**
 * CoreSuite IT - Windows 11 Snap Layout
 * 
 * Implementa la funzionalità di Snap Layout che permette di organizzare
 * le finestre in vari layout predefiniti
 */

document.addEventListener('DOMContentLoaded', function() {
    initSnapLayouts();
});

/**
 * Inizializza la funzionalità Snap Layout
 */
function initSnapLayouts() {
    // Crea l'overlay per i layout di snap
    createSnapLayoutOverlay();
    
    // Aggiungi event listeners ai pulsanti di massimizzazione
    setupMaximizeButtonHover();
    
    // Aggiungi zone di snap ai bordi dello schermo
    setupScreenEdgeSnap();
    
    // Aggiungi scorciatoie da tastiera
    setupSnapKeyboardShortcuts();
}

/**
 * Crea l'overlay per i layout di snap
 */
function createSnapLayoutOverlay() {
    if (document.getElementById('snapLayoutOverlay')) return;
    
    const overlay = document.createElement('div');
    overlay.id = 'snapLayoutOverlay';
    overlay.className = 'snap-layout-overlay';
    
    // Layout disponibili: 2x2, 1+2 (3 zone), 1+1 (2 zone)
    overlay.innerHTML = `
        <div class="snap-layout-container">
            <div class="snap-layout-group" data-layout="2x2">
                <div class="snap-zone" data-position="top-left"></div>
                <div class="snap-zone" data-position="top-right"></div>
                <div class="snap-zone" data-position="bottom-left"></div>
                <div class="snap-zone" data-position="bottom-right"></div>
            </div>
            <div class="snap-layout-group" data-layout="1+2">
                <div class="snap-zone" data-position="left"></div>
                <div class="snap-zone" data-position="top-right"></div>
                <div class="snap-zone" data-position="bottom-right"></div>
            </div>
            <div class="snap-layout-group" data-layout="2-vertical">
                <div class="snap-zone" data-position="left"></div>
                <div class="snap-zone" data-position="right"></div>
            </div>
        </div>
    `;
    
    document.body.appendChild(overlay);
    
    // Aggiungi eventi per le zone di snap
    overlay.querySelectorAll('.snap-zone').forEach(zone => {
        zone.addEventListener('click', function() {
            const position = this.dataset.position;
            const layout = this.parentNode.dataset.layout;
            snapActiveWindow(position, layout);
            hideSnapLayoutOverlay();
        });
        
        // Effetto hover
        zone.addEventListener('mouseenter', function() {
            this.classList.add('hover');
        });
        
        zone.addEventListener('mouseleave', function() {
            this.classList.remove('hover');
        });
    });
}

/**
 * Configura l'hover sul pulsante massimizza per mostrare i layout
 */
function setupMaximizeButtonHover() {
    // Delegazione eventi per gestire anche finestre create dinamicamente
    document.addEventListener('mouseenter', function(e) {
        const target = e.target;
        
        // Controlla se è un pulsante massimizza
        if (target.classList.contains('maximize') && 
            target.classList.contains('win-control-btn')) {
            
            const window = target.closest('.app-window');
            if (window && !window.classList.contains('maximized')) {
                showSnapLayoutOverlay(target);
            }
        }
    }, true);
    
    document.addEventListener('mouseleave', function(e) {
        const target = e.target;
        
        if (target.classList.contains('maximize') && 
            target.classList.contains('win-control-btn')) {
            
            const overlay = document.getElementById('snapLayoutOverlay');
            // Nascondi overlay solo se il mouse non è passato all'overlay stesso
            if (overlay && !overlay.contains(e.relatedTarget)) {
                hideSnapLayoutOverlay();
            }
        }
    }, true);
    
    // Gestisci uscita dall'overlay
    const overlay = document.getElementById('snapLayoutOverlay');
    if (overlay) {
        overlay.addEventListener('mouseleave', function(e) {
            // Nascondi overlay solo se il mouse non è tornato al pulsante massimizza
            if (!e.relatedTarget || !e.relatedTarget.classList.contains('maximize')) {
                hideSnapLayoutOverlay();
            }
        });
    }
}

/**
 * Mostra l'overlay dei layout di snap
 * @param {HTMLElement} button - Pulsante massimizza che ha attivato l'overlay
 */
function showSnapLayoutOverlay(button) {
    const overlay = document.getElementById('snapLayoutOverlay');
    if (!overlay) return;
    
    // Posiziona l'overlay vicino al pulsante
    const buttonRect = button.getBoundingClientRect();
    overlay.style.top = (buttonRect.bottom + 10) + 'px';
    overlay.style.left = (buttonRect.left - 150) + 'px'; // Centrato orizzontalmente
    
    // Memorizza la finestra attiva
    overlay.setAttribute('data-target-window', button.closest('.app-window').id || '');
    
    // Mostra l'overlay
    overlay.classList.add('show');
}

/**
 * Nascondi l'overlay dei layout di snap
 */
function hideSnapLayoutOverlay() {
    const overlay = document.getElementById('snapLayoutOverlay');
    if (overlay) {
        overlay.classList.remove('show');
    }
}

/**
 * Configura il rilevamento dei bordi dello schermo per lo snap
 */
function setupScreenEdgeSnap() {
    let draggingWindow = null;
    let dragStartX = 0;
    let dragStartY = 0;
    
    // Monitora l'inizio del trascinamento di una finestra
    document.addEventListener('mousedown', function(e) {
        const titleBar = e.target.closest('.app-title-bar');
        if (titleBar && !e.target.closest('.win-controls')) {
            draggingWindow = titleBar.closest('.app-window');
            if (draggingWindow && !draggingWindow.classList.contains('maximized')) {
                dragStartX = e.clientX;
                dragStartY = e.clientY;
            } else {
                draggingWindow = null;
            }
        }
    });
    
    // Monitora il movimento durante il trascinamento
    document.addEventListener('mousemove', function(e) {
        if (draggingWindow) {
            const screenWidth = window.innerWidth;
            const screenHeight = window.innerHeight;
            
            // Snap verticale (sinistra/destra)
            if (e.clientX <= 20) {
                showSnapPreview('left-half');
            } else if (e.clientX >= screenWidth - 20) {
                showSnapPreview('right-half');
            } 
            // Snap in alto (massimizza)
            else if (e.clientY <= 10) {
                showSnapPreview('maximize');
            } 
            // Snap agli angoli
            else if (e.clientX <= 20 && e.clientY <= 20) {
                showSnapPreview('top-left');
            } else if (e.clientX >= screenWidth - 20 && e.clientY <= 20) {
                showSnapPreview('top-right');
            } else if (e.clientX <= 20 && e.clientY >= screenHeight - 20) {
                showSnapPreview('bottom-left');
            } else if (e.clientX >= screenWidth - 20 && e.clientY >= screenHeight - 20) {
                showSnapPreview('bottom-right');
            } else {
                hideSnapPreview();
            }
        }
    });
    
    // Esegui lo snap al rilascio del mouse
    document.addEventListener('mouseup', function(e) {
        if (draggingWindow) {
            const screenWidth = window.innerWidth;
            const screenHeight = window.innerHeight;
            
            // Applica lo snap in base alla posizione
            if (e.clientX <= 20) {
                snapActiveWindow('left', '2-vertical');
            } else if (e.clientX >= screenWidth - 20) {
                snapActiveWindow('right', '2-vertical');
            } else if (e.clientY <= 10) {
                maximizeWindow(draggingWindow);
            } else if (e.clientX <= 20 && e.clientY <= 20) {
                snapActiveWindow('top-left', '2x2');
            } else if (e.clientX >= screenWidth - 20 && e.clientY <= 20) {
                snapActiveWindow('top-right', '2x2');
            } else if (e.clientX <= 20 && e.clientY >= screenHeight - 20) {
                snapActiveWindow('bottom-left', '2x2');
            } else if (e.clientX >= screenWidth - 20 && e.clientY >= screenHeight - 20) {
                snapActiveWindow('bottom-right', '2x2');
            }
            
            // Reset
            draggingWindow = null;
            hideSnapPreview();
        }
    });
}

/**
 * Mostra un'anteprima dello snap
 * @param {string} position - Posizione dello snap
 */
function showSnapPreview(position) {
    // Rimuovi qualsiasi preview esistente
    hideSnapPreview();
    
    // Crea un elemento per l'anteprima
    const preview = document.createElement('div');
    preview.id = 'snapPreview';
    preview.className = 'snap-preview snap-preview-' + position;
    document.body.appendChild(preview);
    
    // Animazione di entrata
    requestAnimationFrame(() => {
        preview.classList.add('show');
    });
}

/**
 * Nascondi l'anteprima dello snap
 */
function hideSnapPreview() {
    const preview = document.getElementById('snapPreview');
    if (preview) {
        preview.classList.remove('show');
        setTimeout(() => {
            if (preview.parentNode) {
                preview.parentNode.removeChild(preview);
            }
        }, 200);
    }
}

/**
 * Applica lo snap alla finestra attiva
 * @param {string} position - Posizione dello snap
 * @param {string} layout - Tipo di layout
 */
function snapActiveWindow(position, layout) {
    // Ottieni la finestra da snappare
    const overlay = document.getElementById('snapLayoutOverlay');
    let window = null;
    
    if (overlay && overlay.hasAttribute('data-target-window')) {
        const windowId = overlay.getAttribute('data-target-window');
        window = document.getElementById(windowId);
    } else {
        window = document.querySelector('.app-window:not(.minimized)');
    }
    
    if (!window) return;
    
    // Rimuovi classi esistenti
    window.classList.remove('maximized', 'snapped-left', 'snapped-right', 
        'snapped-top-left', 'snapped-top-right', 'snapped-bottom-left', 'snapped-bottom-right');
    
    // Reset di stili inline che potrebbero interferire
    window.style.width = '';
    window.style.height = '';
    
    // Applica la classe di snap appropriata
    switch (position) {
        case 'left':
            window.classList.add('snapped-left');
            break;
        case 'right':
            window.classList.add('snapped-right');
            break;
        case 'top-left':
            window.classList.add('snapped-top-left');
            break;
        case 'top-right':
            window.classList.add('snapped-top-right');
            break;
        case 'bottom-left':
            window.classList.add('snapped-bottom-left');
            break;
        case 'bottom-right':
            window.classList.add('snapped-bottom-right');
            break;
    }
    
    // In un'implementazione reale, qui potremmo adattare altre finestre 
    // per completare il layout
}

/**
 * Massimizza una finestra
 * @param {HTMLElement} window - Elemento della finestra da massimizzare
 */
function maximizeWindow(window) {
    if (!window) return;
    
    window.classList.add('maximized');
    
    // Aggiorna l'icona del pulsante massimizza
    const maximizeButton = window.querySelector('.win-control-btn.maximize i');
    if (maximizeButton) {
        maximizeButton.className = 'fas fa-clone';
    }
}

/**
 * Configura le scorciatoie da tastiera per lo snap
 */
function setupSnapKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        // Verifica che ci sia una finestra attiva
        const activeWindow = document.querySelector('.app-window:not(.minimized)');
        if (!activeWindow) return;
        
        // Windows + Frecce per lo snap
        if (e.metaKey || e.ctrlKey) {  // Usiamo meta o ctrl come surrogato del tasto Windows
            switch (e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    snapActiveWindow('left', '2-vertical');
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    snapActiveWindow('right', '2-vertical');
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    maximizeWindow(activeWindow);
                    break;
                // Aggiungi altri shortcut se necessario
            }
        }
    });
}
