/**
 * CoreSuite IT - Script principale
 * Aggiornato per integrare il tema Windows 11
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inizializza tutte le funzionalità
    initSidebar();
    initTooltips();
    initToasts();
    initCharts();
    initThemeToggle();
    
    // Gestisci il click su elementi collassabili
    handleCollapsibles();
    
    // Integrazione con Windows 11
    setupWindows11Integration();

    // Aggiungi listener per il pulsante "Chiudi"
    document.querySelectorAll('.win-control-btn.close').forEach(button => {
        button.addEventListener('click', function() {
            // Chiudi la finestra corrente
            const appWindow = this.closest('.app-window');
            if (appWindow) {
                appWindow.style.display = 'none'; // Nascondi la finestra
            }
        });
    });

    // Funzione per mostrare un avviso di conferma prima di eliminare
    window.confirmDelete = function(id, tipo) {
        return confirm('Sei sicuro di voler eliminare questo ' + tipo + '?');
    };

    // Funzione per mostrare un avviso di conferma prima di eliminare un documento
    window.confirmDeleteDoc = function(id) {
        return confirm('Sei sicuro di voler eliminare questo documento?');
    };
});

/**
 * Gestione della sidebar responsive
 */
function initSidebar() {
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const sidebar = document.getElementById('sidebar');
    
    // Se esiste il pulsante toggle per la sidebar
    if (sidebarToggleBtn) {
        sidebarToggleBtn.addEventListener('click', function() {
            document.body.classList.toggle('sidebar-toggled');
            sidebar.classList.toggle('show');
            
            // Salva lo stato della sidebar nel localStorage
            const sidebarState = sidebar.classList.contains('show');
            localStorage.setItem('sidebarOpen', sidebarState);
            
            // Aggiorna l'icona del pulsante
            const icon = sidebarToggleBtn.querySelector('i');
            if (icon) {
                if (sidebar.classList.contains('show')) {
                    icon.classList.replace('fa-bars', 'fa-times');
                } else {
                    icon.classList.replace('fa-times', 'fa-bars');
                }
            }
        });
    }
    
    // Ripristina lo stato della sidebar dal localStorage
    if (localStorage.getItem('sidebarOpen') === 'true' && window.innerWidth > 768) {
        document.body.classList.add('sidebar-toggled');
        sidebar.classList.add('show');
    }
    
    // Chiudi la sidebar su dispositivi mobili quando si fa clic su un link
    if (window.innerWidth < 768) {
        const sidebarLinks = document.querySelectorAll('#sidebar .nav-link');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function() {
                sidebar.classList.remove('show');
                document.body.classList.remove('sidebar-toggled');
            });
        });
    }
}

/**
 * Inizializzazione tooltip Bootstrap
 */
function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Inizializzazione toast notifications
 */
function initToasts() {
    const toastElList = [].slice.call(document.querySelectorAll('.toast'));
    toastElList.map(function(toastEl) {
        return new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 5000
        });
    });
    
    // Mostra tutti i toast
    toastElList.forEach(toast => {
        const toastInstance = bootstrap.Toast.getInstance(toast);
        if (toastInstance) {
            toastInstance.show();
        }
    });
}

/**
 * Gestione toggle tema chiaro/scuro
 */
function initThemeToggle() {
    const themeToggle = document.getElementById('themeToggle');
    const htmlElement = document.documentElement;
    
    // Controlla se esiste il toggle del tema
    if (themeToggle) {
        // Imposta il tema iniziale in base alla preferenza salvata
        const currentTheme = localStorage.getItem('theme') || 'light';
        htmlElement.setAttribute('data-theme', currentTheme);
        
        if (currentTheme === 'dark') {
            themeToggle.checked = true;
            document.body.classList.add('dark-theme');
        }
        
        // Gestisci il cambio del tema
        themeToggle.addEventListener('change', function() {
            if (this.checked) {
                htmlElement.setAttribute('data-theme', 'dark');
                document.body.classList.add('dark-theme');
                localStorage.setItem('theme', 'dark');
            } else {
                htmlElement.setAttribute('data-theme', 'light');
                document.body.classList.remove('dark-theme');
                localStorage.setItem('theme', 'light');
            }
        });
    }
}

/**
 * Inizializzazione grafici con Chart.js
 */
function initCharts() {
    // Verifica se Chart.js è disponibile
    if (typeof Chart === 'undefined') return;
    
    // Grafico delle vendite settimanali (se esiste il canvas)
    const weeklyChart = document.getElementById('weeklyChart');
    if (weeklyChart) {
        new Chart(weeklyChart, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab', 'Dom'],
                datasets: [{
                    label: 'Vendite',
                    data: [15, 21, 18, 24, 23, 19, 14],
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#4e73df',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
    
    // Grafico a torta delle categorie (se esiste il canvas)
    const categoriesChart = document.getElementById('categoriesChart');
    if (categoriesChart) {
        new Chart(categoriesChart, {
            type: 'doughnut',
            data: {
                labels: ['Telefonia', 'Energia', 'Pagamenti', 'Spedizioni', 'Servizi'],
                datasets: [{
                    data: [35, 25, 20, 15, 5],
                    backgroundColor: [
                        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'
                    ],
                    hoverOffset: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '70%'
            }
        });
    }
}

/**
 * Gestisce elementi collassabili
 */
function handleCollapsibles() {
    const collapsibles = document.querySelectorAll('[data-toggle="collapse"]');
    
    collapsibles.forEach(element => {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('data-target'));
            
            if (target) {
                target.classList.toggle('show');
                
                // Cambia icona se presente
                const icon = this.querySelector('.collapse-icon');
                if (icon) {
                    if (target.classList.contains('show')) {
                        icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
                    } else {
                        icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
                    }
                }
            }
        });
    });
}

/**
 * Crea e mostra una notifica toast
 * @param {string} title - Titolo della notifica
 * @param {string} message - Messaggio della notifica
 * @param {string} type - Tipo di notifica: success, warning, danger, info
 */
function showNotification(title, message, type = 'info') {
    // Crea il container se non esiste
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }
    
    // Crea un ID univoco per il toast
    const toastId = 'toast-' + Date.now();
    
    // Crea il toast HTML
    const toastHTML = `
    <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-${type} text-white">
            <strong class="me-auto">${title}</strong>
            <small>adesso</small>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            ${message}
        </div>
    </div>
    `;
    
    // Aggiungi il toast al container
    toastContainer.innerHTML += toastHTML;
    
    // Inizializza e mostra il toast
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: 5000
    });
    
    toast.show();
    
    // Rimuovi il toast dal DOM dopo che è stato nascosto
    toastElement.addEventListener('hidden.bs.toast', function() {
        this.remove();
    });
}

/**
 * Funzione per confermare eliminazione
 */
function confirmDelete(id, type) {
  return confirm('Sei sicuro di voler eliminare questo ' + type + '?');
}

/**
 * Funzione per formattare gli importi come valuta
 */
function formatCurrency(amount) {
  return parseFloat(amount).toLocaleString('it-IT', {
    style: 'currency',
    currency: 'EUR'
  });
}

/**
 * Setup integrazione Windows 11
 */
function setupWindows11Integration() {
    // Se una pagina deve essere caricata in una finestra Windows 11
    const pageContent = document.querySelector('.page-content');
    if (pageContent) {
        // Converti il contenuto della pagina in una finestra Windows 11
        convertToWin11Window(pageContent);
    }
    
    // Converti alert e notifiche in toast Windows 11
    convertAlertsToWin11Toast();
    
    // Converti modali in stile Windows 11
    enhanceModalsWithWin11Style();
}

/**
 * Converte un elemento in una finestra Windows 11
 * @param {HTMLElement} element - Elemento da convertire
 */
function convertToWin11Window(element) {
    // Ottieni il titolo della pagina
    const pageTitle = document.title.replace(' - CoreSuite IT', '');
    
    // Salva il contenuto originale
    const originalContent = element.innerHTML;
    
    // Crea la struttura della finestra
    const windowHtml = `
        <div class="app-title-bar">
            <div class="app-icon">
                <i class="fas fa-window-maximize"></i>
            </div>
            <h1 class="app-title">${pageTitle}</h1>
            <div class="win-controls">
                <button class="win-control-btn minimize" title="Minimizza">
                    <i class="fas fa-minus"></i>
                </button>
                <button class="win-control-btn maximize" title="Massimizza">
                    <i class="fas fa-square"></i>
                </button>
                <button class="win-control-btn close" title="Chiudi">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="app-window-content">
            ${originalContent}
        </div>
    `;
    
    // Avvolgi il contenuto originale nella finestra Windows 11
    element.innerHTML = windowHtml;
    element.classList.add('app-window');
    
    // Gestisci gli eventi della finestra (se Win11.windowManager non è stato inizializzato prima)
    const minimizeBtn = element.querySelector('.win-control-btn.minimize');
    const maximizeBtn = element.querySelector('.win-control-btn.maximize');
    const closeBtn = element.querySelector('.win-control-btn.close');
    
    if (minimizeBtn) {
        minimizeBtn.addEventListener('click', () => {
            element.classList.add('minimizing');
            setTimeout(() => {
                element.classList.remove('minimizing');
            }, 300);
        });
    }
    
    if (maximizeBtn) {
        maximizeBtn.addEventListener('click', () => {
            element.classList.toggle('maximized');
            const icon = maximizeBtn.querySelector('i');
            if (element.classList.contains('maximized')) {
                icon.className = 'fas fa-clone';
            } else {
                icon.className = 'fas fa-square';
            }
        });
    }
    
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = 'dashboard.php';
            }
        });
    }
    
    // Rendi la finestra trascinabile tramite la barra del titolo
    const titleBar = element.querySelector('.app-title-bar');
    if (titleBar) {
        makeWindowDraggable(titleBar, element);
    }
}

/**
 * Rende trascinabile una finestra tramite la barra del titolo
 */
function makeWindowDraggable(handle, window) {
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

/**
 * Converte gli alert in toast Windows 11
 */
function convertAlertsToWin11Toast() {
    // Cerca alert messages
    const alertMessages = document.querySelectorAll('.alert');
    alertMessages.forEach(alert => {
        let type = 'info';
        if (alert.classList.contains('alert-success')) type = 'success';
        if (alert.classList.contains('alert-warning')) type = 'warning';
        if (alert.classList.contains('alert-danger')) type = 'error';
        
        const title = type.charAt(0).toUpperCase() + type.slice(1);
        const message = alert.textContent.trim();
        
        // Nascondi l'alert originale
        alert.style.display = 'none';
        
        // Mostra il toast Windows 11 se la funzione esiste
        if (typeof Win11 !== 'undefined' && Win11.notifications) {
            setTimeout(() => {
                Win11.notifications.showToast({
                    title: title,
                    message: message,
                    type: type,
                    duration: 5000
                });
            }, 200);
        }
    });
}

/**
 * Migliora i modali con lo stile Windows 11
 */
function enhanceModalsWithWin11Style() {
    // Applica gli stili Windows 11 ai modali Bootstrap
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        // Aggiungi classe per effetti fluent
        modal.querySelector('.modal-content')?.classList.add('win11-fluent-panel');
        
        // Stile pulsanti nel modal footer
        const modalFooter = modal.querySelector('.modal-footer');
        if (modalFooter) {
            const primaryBtn = modalFooter.querySelector('.btn-primary');
            if (primaryBtn) primaryBtn.classList.add('win11-btn', 'win11-btn-primary');
            
            const secondaryBtn = modalFooter.querySelector('.btn-secondary');
            if (secondaryBtn) secondaryBtn.classList.add('win11-btn');
        }
    });
}
