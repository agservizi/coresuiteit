/**
 * CoreSuite IT - Script principale
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
