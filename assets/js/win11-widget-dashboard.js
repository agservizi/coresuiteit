/**
 * CoreSuite IT - Windows 11 Widget Dashboard
 * Implementa una dashboard di widget personalizzati in stile Windows 11
 */

document.addEventListener('DOMContentLoaded', function() {
    initWidgetDashboard();
});

/**
 * Inizializza la dashboard dei widget
 */
function initWidgetDashboard() {
    // Verifica se il pannello dei widget esiste già
    let widgetsPanel = document.getElementById('widgetsPanel');
    
    if (!widgetsPanel) {
        // Crea il pannello
        createWidgetsPanel();
    } else {
        // Aggiorna il contenuto
        updateWidgetsPanel(widgetsPanel);
    }
    
    // Aggiungi pulsante widget alla taskbar se non esiste
    const widgetBtn = document.querySelector('.taskbar-icon[title="Widget"]');
    if (widgetBtn) {
        widgetBtn.addEventListener('click', toggleWidgetsPanel);
    }
}

/**
 * Crea il pannello dei widget
 */
function createWidgetsPanel() {
    const widgetsPanel = document.createElement('div');
    widgetsPanel.id = 'widgetsPanel';
    widgetsPanel.className = 'widgets-panel';
    
    // Struttura di base
    widgetsPanel.innerHTML = `
        <div class="widgets-header">
            <div class="widget-user">
                <img src="assets/img/avatar.jpg" alt="User" class="widget-avatar">
                <span id="widgetGreeting">Buongiorno, Utente</span>
            </div>
            <div class="widget-actions">
                <button class="win11-btn win11-btn-icon widget-settings-btn" title="Impostazioni Widget">
                    <i class="fas fa-cog"></i>
                </button>
                <button class="win11-btn win11-btn-icon" id="closeWidgetsBtn" title="Chiudi">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div class="widget-search">
            <div class="search-container">
                <i class="fas fa-search"></i>
                <input type="text" class="search-input" placeholder="Cerca sul web">
            </div>
        </div>
        
        <div class="widgets-grid" id="widgetsGrid"></div>
        
        <div class="widgets-add-container">
            <button class="win11-btn win11-btn-icon add-widget-btn" title="Aggiungi widget">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(widgetsPanel);
    
    // Carica i widget
    loadWidgets();
    
    // Aggiungi gestori eventi
    setupWidgetEvents(widgetsPanel);
    
    // Aggiorna il saluto
    updateGreeting();
    
    return widgetsPanel;
}

/**
 * Carica i widget predefiniti
 */
function loadWidgets() {
    const widgetsGrid = document.getElementById('widgetsGrid');
    if (!widgetsGrid) return;
    
    // Widget meteo
    const weatherWidget = createWidget('weather', 'Meteo', 'large');
    weatherWidget.innerHTML = `
        <div class="widget-content">
            <div class="weather-now">
                <div class="weather-icon">
                    <i class="fas fa-sun"></i>
                </div>
                <div class="weather-info">
                    <div class="weather-temp">23°</div>
                    <div class="weather-desc">Soleggiato</div>
                    <div class="weather-location">Milano</div>
                </div>
            </div>
            <div class="weather-forecast">
                <div class="forecast-day">
                    <span>Lun</span>
                    <i class="fas fa-sun"></i>
                    <span>24°</span>
                </div>
                <div class="forecast-day">
                    <span>Mar</span>
                    <i class="fas fa-cloud"></i>
                    <span>22°</span>
                </div>
                <div class="forecast-day">
                    <span>Mer</span>
                    <i class="fas fa-cloud-rain"></i>
                    <span>19°</span>
                </div>
                <div class="forecast-day">
                    <span>Gio</span>
                    <i class="fas fa-cloud-sun"></i>
                    <span>21°</span>
                </div>
                <div class="forecast-day">
                    <span>Ven</span>
                    <i class="fas fa-sun"></i>
                    <span>25°</span>
                </div>
            </div>
        </div>
    `;
    widgetsGrid.appendChild(weatherWidget);
    
    // Widget clienti (specifico per CoreSuite)
    const clientiWidget = createWidget('clienti', 'Clienti', 'medium');
    clientiWidget.innerHTML = `
        <div class="widget-content">
            <div class="widget-stats">
                <div class="widget-stat-item">
                    <div class="stat-value">128</div>
                    <div class="stat-label">Totale clienti</div>
                </div>
                <div class="widget-stat-item">
                    <div class="stat-value">+3</div>
                    <div class="stat-label">Nuovi questo mese</div>
                </div>
            </div>
            <div class="widget-chart">
                <div class="chart-bar" style="height: 60%"></div>
                <div class="chart-bar" style="height: 75%"></div>
                <div class="chart-bar" style="height: 40%"></div>
                <div class="chart-bar" style="height: 80%"></div>
                <div class="chart-bar active" style="height: 90%"></div>
            </div>
            <div class="widget-action-btn">
                <button class="win11-btn win11-btn-sm">Vedi tutti</button>
            </div>
        </div>
    `;
    widgetsGrid.appendChild(clientiWidget);
    
    // Widget calendario
    const calendarWidget = createWidget('calendario', 'Calendario', 'medium');
    calendarWidget.innerHTML = `
        <div class="widget-content">
            <div class="calendar-date">Oggi, ${getCurrentFormattedDate()}</div>
            <div class="calendar-events">
                <div class="event">
                    <div class="event-time">14:30</div>
                    <div class="event-details">
                        <div class="event-title">Meeting con cliente</div>
                        <div class="event-location">Sala riunioni</div>
                    </div>
                </div>
                <div class="event">
                    <div class="event-time">16:00</div>
                    <div class="event-details">
                        <div class="event-title">Revisione progetto</div>
                        <div class="event-location">Zoom</div>
                    </div>
                </div>
            </div>
        </div>
    `;
    widgetsGrid.appendChild(calendarWidget);
    
    // Widget attività
    const taskWidget = createWidget('tasks', 'Attività', 'medium');
    taskWidget.innerHTML = `
        <div class="widget-content">
            <div class="task-list">
                <div class="task-item">
                    <input type="checkbox" id="task1">
                    <label for="task1">Inviare preventivo a Cliente XYZ</label>
                </div>
                <div class="task-item">
                    <input type="checkbox" id="task2">
                    <label for="task2">Preparare fatture mensili</label>
                </div>
                <div class="task-item">
                    <input type="checkbox" id="task3" checked>
                    <label for="task3">Aggiornare sito web</label>
                </div>
                <div class="task-item">
                    <input type="checkbox" id="task4">
                    <label for="task4">Meeting con il team</label>
                </div>
            </div>
            <button class="add-task-btn">
                <i class="fas fa-plus"></i> Aggiungi attività
            </button>
        </div>
    `;
    widgetsGrid.appendChild(taskWidget);
    
    // Widget finanza
    const financeWidget = createWidget('finance', 'Finanza', 'large');
    financeWidget.innerHTML = `
        <div class="widget-content">
            <div class="finance-summary">
                <div class="finance-total">
                    <div class="finance-label">Entrate mensili</div>
                    <div class="finance-value">€ 24,850</div>
                    <div class="finance-trend positive">
                        <i class="fas fa-arrow-up"></i> 8.5%
                    </div>
                </div>
                <div class="finance-chart">
                    <div class="finance-bar" style="height: 30%"></div>
                    <div class="finance-bar" style="height: 50%"></div>
                    <div class="finance-bar" style="height: 70%"></div>
                    <div class="finance-bar" style="height: 60%"></div>
                    <div class="finance-bar" style="height: 80%"></div>
                    <div class="finance-bar current" style="height: 90%"></div>
                </div>
            </div>
            <div class="finance-details">
                <div class="finance-row">
                    <div class="finance-client">Azienda Alpha</div>
                    <div class="finance-amount">€ 8,500</div>
                </div>
                <div class="finance-row">
                    <div class="finance-client">Gruppo Beta</div>
                    <div class="finance-amount">€ 6,200</div>
                </div>
                <div class="finance-row">
                    <div class="finance-client">Studio Gamma</div>
                    <div class="finance-amount">€ 5,100</div>
                </div>
                <div class="finance-row">
                    <div class="finance-client">Altri clienti</div>
                    <div class="finance-amount">€ 5,050</div>
                </div>
            </div>
        </div>
    `;
    widgetsGrid.appendChild(financeWidget);
}

/**
 * Crea un elemento widget
 * @param {string} id - ID del widget
 * @param {string} title - Titolo del widget
 * @param {string} size - Dimensione (small, medium, large)
 * @returns {HTMLElement} - Elemento widget
 */
function createWidget(id, title, size = 'medium') {
    const widget = document.createElement('div');
    widget.className = `widget-card widget-${size}`;
    widget.id = `widget-${id}`;
    widget.setAttribute('data-widget-id', id);
    
    // Intestazione widget
    const header = document.createElement('div');
    header.className = 'widget-header';
    
    header.innerHTML = `
        <div class="widget-title">${title}</div>
        <div class="widget-controls">
            <button class="widget-btn widget-refresh" title="Aggiorna">
                <i class="fas fa-sync-alt"></i>
            </button>
            <button class="widget-btn widget-customize" title="Personalizza">
                <i class="fas fa-cog"></i>
            </button>
        </div>
    `;
    
    widget.appendChild(header);
    
    // Aggiungi gli eventi
    widget.querySelector('.widget-refresh').addEventListener('click', (e) => {
        e.stopPropagation();
        refreshWidget(id);
    });
    
    widget.querySelector('.widget-customize').addEventListener('click', (e) => {
        e.stopPropagation();
        customizeWidget(id);
    });
    
    // Abilita il trascinamento
    enableWidgetDrag(widget);
    
    return widget;
}

/**
 * Abilita il trascinamento per i widget
 * @param {HTMLElement} widget - Elemento widget
 */
function enableWidgetDrag(widget) {
    const header = widget.querySelector('.widget-header');
    if (!header) return;
    
    header.addEventListener('mousedown', (e) => {
        // Solo se non si clicca su un pulsante
        if (e.target.closest('.widget-btn')) return;
        
        // Preparazione al drag
        const startX = e.clientX;
        const startY = e.clientY;
        const startLeft = widget.offsetLeft;
        const startTop = widget.offsetTop;
        
        widget.classList.add('widget-dragging');
        
        // Funzione di trascinamento
        const drag = (e) => {
            const deltaX = e.clientX - startX;
            const deltaY = e.clientY - startY;
            
            widget.style.left = `${startLeft + deltaX}px`;
            widget.style.top = `${startTop + deltaY}px`;
        };
        
        // Funzione di rilascio
        const endDrag = () => {
            document.removeEventListener('mousemove', drag);
            document.removeEventListener('mouseup', endDrag);
            widget.classList.remove('widget-dragging');
            
            // Salva la posizione
            saveWidgetPosition(widget.getAttribute('data-widget-id'), widget.style.left, widget.style.top);
        };
        
        document.addEventListener('mousemove', drag);
        document.addEventListener('mouseup', endDrag);
    });
}

/**
 * Aggiorna il saluto in base all'ora del giorno
 */
function updateGreeting() {
    const greetingElement = document.getElementById('widgetGreeting');
    if (!greetingElement) return;
    
    const hour = new Date().getHours();
    let greeting;
    
    if (hour >= 5 && hour < 12) {
        greeting = 'Buongiorno';
    } else if (hour >= 12 && hour < 18) {
        greeting = 'Buon pomeriggio';
    } else {
        greeting = 'Buonasera';
    }
    
    // Ottieni il nome utente
    const username = getUserName() || 'Utente';
    greetingElement.textContent = `${greeting}, ${username}`;
}

/**
 * Ottiene il nome utente
 * @returns {string} Nome utente
 */
function getUserName() {
    // In un'app reale, otterrebbe il nome dalla sessione
    return document.querySelector('.user-name')?.textContent || 'Utente';
}

/**
 * Aggiorna la data corrente formattata
 * @returns {string} Data formattata
 */
function getCurrentFormattedDate() {
    const now = new Date();
    const options = { weekday: 'long', day: 'numeric', month: 'long' };
    let formatted = now.toLocaleDateString('it-IT', options);
    return formatted.charAt(0).toUpperCase() + formatted.slice(1);
}

/**
 * Configura gli eventi per il pannello widget
 * @param {HTMLElement} panel - Pannello widget
 */
function setupWidgetEvents(panel) {
    // Chiudi il pannello
    panel.querySelector('#closeWidgetsBtn').addEventListener('click', hideWidgetsPanel);
    
    // Pulsante impostazioni
    panel.querySelector('.widget-settings-btn').addEventListener('click', showWidgetSettings);
    
    // Pulsante aggiunta widget
    panel.querySelector('.add-widget-btn').addEventListener('click', showWidgetGallery);
    
    // Ricerca
    const searchInput = panel.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value.trim();
                if (searchTerm) {
                    // Mostra un toast di conferma
                    if (typeof Win11.notifications !== 'undefined') {
                        Win11.notifications.showToast({
                            title: 'Ricerca',
                            message: `Ricerca di "${searchTerm}" avviata...`,
                            type: 'info',
                            duration: 3000
                        });
                    }
                    this.value = '';
                }
            }
        });
    }
    
    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && panel.classList.contains('show')) {
            hideWidgetsPanel();
        }
    });
}

/**
 * Mostra il pannello dei widget
 */
function showWidgetsPanel() {
    const widgetsPanel = document.getElementById('widgetsPanel');
    if (widgetsPanel) {
        widgetsPanel.classList.add('show');
        
        // Aggiorna il saluto
        updateGreeting();
        
        // Aggiorna widget con dati freschi
        refreshAllWidgets();
        
        // Evidenzia il pulsante nella taskbar
        const widgetBtn = document.querySelector('.taskbar-icon[title="Widget"]');
        if (widgetBtn) {
            widgetBtn.classList.add('active');
        }
    }
}

/**
 * Nasconde il pannello dei widget
 */
function hideWidgetsPanel() {
    const widgetsPanel = document.getElementById('widgetsPanel');
    if (widgetsPanel) {
        widgetsPanel.classList.remove('show');
        
        // Rimuovi evidenziazione del pulsante
        const widgetBtn = document.querySelector('.taskbar-icon[title="Widget"]');
        if (widgetBtn) {
            widgetBtn.classList.remove('active');
        }
    }
}

/**
 * Alterna visibilità del pannello widget
 */
function toggleWidgetsPanel() {
    const widgetsPanel = document.getElementById('widgetsPanel');
    if (widgetsPanel) {
        if (widgetsPanel.classList.contains('show')) {
            hideWidgetsPanel();
        } else {
            // Nascondi altri pannelli aperti
            document.querySelectorAll('.win11-start-panel.show, .notification-panel.show').forEach(panel => {
                panel.classList.remove('show');
            });
            
            showWidgetsPanel();
        }
    }
}

/**
 * Aggiorna un widget specifico
 * @param {string} widgetId - ID del widget da aggiornare
 */
function refreshWidget(widgetId) {
    const widget = document.querySelector(`[data-widget-id="${widgetId}"]`);
    if (!widget) return;
    
    // Mostra animazione di aggiornamento
    widget.classList.add('widget-refreshing');
    
    // In una vera implementazione, qui si recupererebbero i dati dal server
    setTimeout(() => {
        widget.classList.remove('widget-refreshing');
        
        // Simulazione aggiornamento dati
        switch (widgetId) {
            case 'weather':
                // Aggiorna dati meteo
                updateWeatherWidget(widget);
                break;
            case 'clienti':
                // Aggiorna dati clienti
                updateClientiWidget(widget);
                break;
            case 'calendario':
                // Aggiorna eventi
                updateCalendarWidget(widget);
                break;
            case 'tasks':
                // Aggiorna attività
                updateTasksWidget(widget);
                break;
            case 'finance':
                // Aggiorna dati finanziari
                updateFinanceWidget(widget);
                break;
        }
        
        // Mostra toast di conferma
        if (typeof Win11.notifications !== 'undefined') {
            Win11.notifications.showToast({
                title: 'Widget aggiornato',
                message: `Il widget ${widgetId} è stato aggiornato.`,
                type: 'success',
                duration: 2000
            });
        }
    }, 1000);
}

/**
 * Aggiorna tutti i widget
 */
function refreshAllWidgets() {
    document.querySelectorAll('[data-widget-id]').forEach(widget => {
        refreshWidget(widget.getAttribute('data-widget-id'));
    });
}

/**
 * Mostra la galleria dei widget disponibili
 */
function showWidgetGallery() {
    // Implementazione nella vita reale: mostrare un modale con i widget disponibili
    if (typeof Win11.notifications !== 'undefined') {
        Win11.notifications.showToast({
            title: 'Galleria Widget',
            message: 'Funzionalità in arrivo nelle prossime versioni.',
            type: 'info',
            duration: 3000
        });
    }
}

/**
 * Mostra le impostazioni dei widget
 */
function showWidgetSettings() {
    // Implementazione nella vita reale: mostrare un pannello di impostazioni
    if (typeof Win11.notifications !== 'undefined') {
        Win11.notifications.showToast({
            title: 'Impostazioni Widget',
            message: 'Funzionalità in arrivo nelle prossime versioni.',
            type: 'info',
            duration: 3000
        });
    }
}

/**
 * Personalizza un widget
 * @param {string} widgetId - ID del widget da personalizzare
 */
function customizeWidget(widgetId) {
    // Implementazione nella vita reale: mostrare opzioni di personalizzazione
    if (typeof Win11.notifications !== 'undefined') {
        Win11.notifications.showToast({
            title: 'Personalizzazione Widget',
            message: `Personalizzazione del widget ${widgetId} in arrivo presto.`,
            type: 'info',
            duration: 3000
        });
    }
}

/**
 * Salva la posizione di un widget
 * @param {string} widgetId - ID del widget
 * @param {string} left - Posizione sinistra
 * @param {string} top - Posizione superiore
 */
function saveWidgetPosition(widgetId, left, top) {
    // In un'app reale, salverebbe nel localStorage o sul server
    console.log(`Posizione widget ${widgetId} salvata: left=${left}, top=${top}`);
}

/**
 * Funzioni di aggiornamento specifiche per ogni widget
 */
function updateWeatherWidget(widget) {
    // Simulazione aggiornamento meteo
    const temps = [21, 22, 23, 24, 25, 26];
    const temp = temps[Math.floor(Math.random() * temps.length)];
    const tempElement = widget.querySelector('.weather-temp');
    if (tempElement) tempElement.textContent = `${temp}°`;
}

function updateClientiWidget(widget) {
    // Simulazione aggiornamento clienti
    const totals = [128, 129, 130, 131, 132];
    const total = totals[Math.floor(Math.random() * totals.length)];
    const totalElement = widget.querySelector('.stat-value');
    if (totalElement) totalElement.textContent = total;
}

function updateCalendarWidget(widget) {
    // Aggiorna la data
    const dateElement = widget.querySelector('.calendar-date');
    if (dateElement) dateElement.textContent = `Oggi, ${getCurrentFormattedDate()}`;
}

function updateTasksWidget(widget) {
    // Non fa nulla di speciale in questa versione demo
}

function updateFinanceWidget(widget) {
    // Non fa nulla di speciale in questa versione demo
}
