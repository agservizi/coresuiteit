/**
 * CoreSuite IT - Windows 11 Effects
 * Effetti e funzionalità avanzate per l'interfaccia Windows 11
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inizializza tutti gli effetti
    initMicaEffect();
    initAcrylicEffect();
    initWindowControls();
    initTaskbar();
    initSystemTray();
    initStartMenu();
    initFlyoutAnimations();
    initDynamicBlur();
});

/**
 * Implementa l'effetto Mica (effetto di trasparenza con blur del desktop)
 */
function initMicaEffect() {
    // Seleziona tutti gli elementi che dovrebbero avere l'effetto Mica
    const micaElements = document.querySelectorAll('.app-window, .app-sidebar, .win11-card');
    
    // Funzione per aggiornare l'effetto in base alla posizione di scorrimento
    function updateMicaEffect() {
        const scrollY = window.scrollY;
        
        micaElements.forEach(element => {
            const rect = element.getBoundingClientRect();
            const elementTop = rect.top + scrollY;
            const offset = scrollY - elementTop;
            
            // Crea un effetto parallasse sottile con la trasparenza
            element.style.backgroundPosition = `0px ${offset * 0.05}px`;
        });
    }
    
    // Aggiorna l'effetto Mica durante lo scorrimento
    window.addEventListener('scroll', updateMicaEffect);
    
    // Inizializza l'effetto
    updateMicaEffect();
}

/**
 * Implementa l'effetto Acrylic (effetto di sforamento con blur più intenso)
 */
function initAcrylicEffect() {
    // Elementi che dovrebbero avere l'effetto Acrylic
    const acrylicElements = document.querySelectorAll('.win11-start-panel, .notification-panel, .user-panel');
    
    acrylicElements.forEach(element => {
        // Aggiungi evento di mouse per effetto di interazione
        element.addEventListener('mouseenter', () => {
            element.style.backdropFilter = 'blur(40px) saturate(125%)';
        });
        
        element.addEventListener('mouseleave', () => {
            element.style.backdropFilter = 'blur(30px) saturate(125%)';
        });
    });
}

/**
 * Gestisce i pulsanti della finestra Windows 11
 */
function initWindowControls() {
    // Gestione pulsante minimize
    const minimizeBtn = document.querySelector('.win-control-btn.minimize');
    if (minimizeBtn) {
        minimizeBtn.addEventListener('click', function() {
            const appWindow = document.querySelector('.app-window');
            appWindow.classList.add('minimized');
            setTimeout(() => {
                appWindow.classList.remove('minimized');
            }, 300);
        });
    }
    
    // Gestione pulsante maximize/restore
    const maximizeBtn = document.querySelector('.win-control-btn.maximize');
    if (maximizeBtn) {
        maximizeBtn.addEventListener('click', function() {
            const appWindow = document.querySelector('.app-window');
            appWindow.classList.toggle('maximized');
            
            const icon = this.querySelector('i');
            if (appWindow.classList.contains('maximized')) {
                icon.classList.replace('fa-square', 'fa-clone');
            } else {
                icon.classList.replace('fa-clone', 'fa-square');
            }
        });
    }
    
    // Gestione pulsante close
    const closeBtn = document.querySelector('.win-control-btn.close');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            const appWindow = document.querySelector('.app-window');
            appWindow.classList.add('closing');
            
            // Simula la chiusura mostrandone la transizione
            setTimeout(() => {
                appWindow.classList.remove('closing');
                // Qui puoi aggiungere codice per reindirizzare o effettivamente chiudere
            }, 500);
        });
    }
}

/**
 * Inizializza la taskbar Windows 11
 */
function initTaskbar() {
    // Aggiorna l'orologio nella taskbar
    function updateTaskbarClock() {
        const taskbarTime = document.getElementById('taskbarTime');
        if (taskbarTime) {
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            taskbarTime.textContent = hours + ':' + minutes;
        }
    }
    
    // Aggiorna l'orologio ogni minuto
    updateTaskbarClock();
    setInterval(updateTaskbarClock, 60000);
    
    // Gestione hover effetti
    const taskbarIcons = document.querySelectorAll('.taskbar-icon');
    taskbarIcons.forEach(icon => {
        icon.addEventListener('mouseenter', function() {
            this.classList.add('taskbar-icon-hover');
        });
        
        icon.addEventListener('mouseleave', function() {
            this.classList.remove('taskbar-icon-hover');
        });
        
        // Aggiungi effetto click
        icon.addEventListener('click', function() {
            this.classList.add('taskbar-icon-active');
            setTimeout(() => {
                this.classList.remove('taskbar-icon-active');
            }, 200);
        });
    });
}

/**
 * Inizializza il system tray di Windows 11
 */
function initSystemTray() {
    const systemTrayIcons = document.querySelectorAll('.win11-task-right .taskbar-icon');
    
    // Aggiungere effetti hover e click anche per questi
    systemTrayIcons.forEach(icon => {
        icon.addEventListener('mouseenter', function() {
            this.classList.add('taskbar-icon-hover');
        });
        
        icon.addEventListener('mouseleave', function() {
            this.classList.remove('taskbar-icon-hover');
        });
    });
}

/**
 * Inizializza il menu Start di Windows 11
 */
function initStartMenu() {
    const startButton = document.getElementById('startMenuBtn');
    const startMenu = document.getElementById('startMenuPanel');
    
    if (startButton && startMenu) {
        // Toggle menu start
        startButton.addEventListener('click', function(e) {
            e.stopPropagation();
            startMenu.classList.toggle('show');
            startButton.classList.toggle('active');
        });
        
        // Chiudi quando si clicca fuori
        document.addEventListener('click', function(e) {
            if (!startMenu.contains(e.target) && e.target !== startButton) {
                startMenu.classList.remove('show');
                startButton.classList.remove('active');
            }
        });
        
        // Effetti hover sulle app
        const appItems = document.querySelectorAll('.app-item');
        appItems.forEach(app => {
            app.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = 'var(--win11-shadow)';
            });
            
            app.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });
    }
}

/**
 * Animazioni per i pannelli flyout (menu a comparsa)
 */
function initFlyoutAnimations() {
    const flyouts = [
        { button: 'userProfileBtn', panel: 'userPanel' },
        { button: 'notificationBtn', panel: 'notificationPanel' }
    ];
    
    flyouts.forEach(flyout => {
        const btn = document.getElementById(flyout.button);
        const panel = document.getElementById(flyout.panel);
        
        if (btn && panel) {
            // Toggle panel
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                panel.classList.toggle('show');
            });
            
            // Chiudi quando si clicca fuori
            document.addEventListener('click', function(e) {
                if (!panel.contains(e.target) && e.target !== btn) {
                    panel.classList.remove('show');
                }
            });
            
            // Animazione entrata/uscita
            panel.addEventListener('transitionstart', function() {
                if (this.classList.contains('show')) {
                    this.style.transform = 'translateY(0)';
                    this.style.opacity = '1';
                } else {
                    this.style.transform = 'translateY(-10px)';
                    this.style.opacity = '0';
                }
            });
        }
    });
}

/**
 * Gestisce l'effetto blur dinamico al passaggio del mouse
 */
function initDynamicBlur() {
    // Aumenta l'effetto blur al passaggio del mouse
    const blurElements = document.querySelectorAll('.win11-card, .app-navbar, .win11-start-panel, .notification-panel');
    
    blurElements.forEach(el => {
        el.addEventListener('mouseenter', function() {
            this.style.backdropFilter = 'blur(30px) saturate(180%)';
        });
        
        el.addEventListener('mouseleave', function() {
            this.style.backdropFilter = 'blur(20px) saturate(150%)';
        });
    });
}

/**
 * Simula il movimento della finestra Windows 11
 */
function initWindowDrag() {
    const titlebar = document.querySelector('.app-title-bar');
    const appWindow = document.querySelector('.app-window');
    
    if (titlebar && appWindow) {
        let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
        
        titlebar.addEventListener('mousedown', dragMouseDown);
        
        function dragMouseDown(e) {
            e.preventDefault();
            // Get the mouse cursor position at startup
            pos3 = e.clientX;
            pos4 = e.clientY;
            document.addEventListener('mouseup', closeDragElement);
            document.addEventListener('mousemove', elementDrag);
        }
        
        function elementDrag(e) {
            e.preventDefault();
            // Calculate the new cursor position
            pos1 = pos3 - e.clientX;
            pos2 = pos4 - e.clientY;
            pos3 = e.clientX;
            pos4 = e.clientY;
            // Set the element's new position
            appWindow.style.top = (appWindow.offsetTop - pos2) + "px";
            appWindow.style.left = (appWindow.offsetLeft - pos1) + "px";
        }
        
        function closeDragElement() {
            document.removeEventListener('mouseup', closeDragElement);
            document.removeEventListener('mousemove', elementDrag);
        }
    }
}

/**
 * Mostra toast notification stile Windows 11
 * @param {string} title - Titolo della notifica
 * @param {string} message - Messaggio della notifica
 * @param {string} type - Tipo: info, success, warning, error
 * @param {number} duration - Durata in ms (default: 5000ms)
 */
function showWin11Toast(title, message, type = 'info', duration = 5000) {
    // Crea container se non esiste
    let toastContainer = document.getElementById('win11-toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'win11-toast-container';
        toastContainer.className = 'win11-toast-container';
        document.body.appendChild(toastContainer);
    }
    
    // Imposta icona in base al tipo
    let icon = '';
    switch(type) {
        case 'success': icon = 'fa-check-circle'; break;
        case 'warning': icon = 'fa-exclamation-triangle'; break;
        case 'error': icon = 'fa-times-circle'; break;
        default: icon = 'fa-info-circle';
    }
    
    // Crea il toast
    const toastId = 'toast-' + Date.now();
    const toast = document.createElement('div');
    toast.id = toastId;
    toast.className = `win11-toast win11-toast-${type}`;
    toast.innerHTML = `
        <div class="win11-toast-icon">
            <i class="fas ${icon}"></i>
        </div>
        <div class="win11-toast-content">
            <div class="win11-toast-title">${title}</div>
            <div class="win11-toast-message">${message}</div>
        </div>
        <button class="win11-toast-close">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // Aggiungi al container
    toastContainer.appendChild(toast);
    
    // Animazione ingresso
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);
    
    // Aggiungi chiusura al click
    toast.querySelector('.win11-toast-close').addEventListener('click', () => {
        closeToast(toast);
    });
    
    // Chiusura automatica
    if (duration > 0) {
        setTimeout(() => {
            closeToast(toast);
        }, duration);
    }
    
    function closeToast(toast) {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }
}
