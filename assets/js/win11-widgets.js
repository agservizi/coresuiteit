/**
 * CoreSuite IT - Windows 11 Widgets
 * Gestione dei widget in stile Windows 11
 */

document.addEventListener('DOMContentLoaded', function() {
    initWidgetsPanel();
    initWidgetsData();
    initContextMenu();
    initSnapLayout();
    initBlurEffects();
});

/**
 * Inizializza il pannello widgets di Windows 11
 */
function initWidgetsPanel() {
    // Crea il pulsante widgets nella taskbar se non esiste
    if (!document.querySelector('.taskbar-icon[title="Widget"]')) {
        const widgetsBtn = document.createElement('button');
        widgetsBtn.className = 'taskbar-icon';
        widgetsBtn.title = 'Widget';
        widgetsBtn.innerHTML = '<i class="fas fa-th-large"></i>';
        
        // Inserisce prima dell'icona Start o come primo elemento
        const startBtn = document.getElementById('startMenuBtn');
        const taskbarIcons = document.querySelector('.taskbar-app-icons');
        
        if (startBtn && taskbarIcons) {
            taskbarIcons.insertBefore(widgetsBtn, startBtn);
        }
    }
    
    // Crea il pannello widgets se non esiste
    if (!document.getElementById('widgetsPanel')) {
        const widgetsPanel = document.createElement('div');
        widgetsPanel.id = 'widgetsPanel';
        widgetsPanel.className = 'widgets-panel';
        widgetsPanel.innerHTML = `
            <div class="widgets-header">
                <div class="widget-user">
                    <img src="assets/img/avatar.jpg" alt="User" class="widget-avatar">
                    <span>Buongiorno, ${getCurrentUserName()}</span>
                </div>
                <button class="win11-btn win11-btn-icon" id="closeWidgets">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="widgets-search">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Cerca sul web">
            </div>
            <div class="widgets-grid">
                <div class="widget-card weather">
                    <div class="widget-header">
                        <div class="widget-title">Meteo</div>
                        <button class="widget-more"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                    <div class="widget-body">
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
                                <div>Lun</div>
                                <i class="fas fa-cloud-sun"></i>
                                <div>21°</div>
                            </div>
                            <div class="forecast-day">
                                <div>Mar</div>
                                <i class="fas fa-cloud"></i>
                                <div>19°</div>
                            </div>
                            <div class="forecast-day">
                                <div>Mer</div>
                                <i class="fas fa-cloud-rain"></i>
                                <div>18°</div>
                            </div>
                            <div class="forecast-day">
                                <div>Gio</div>
                                <i class="fas fa-cloud-sun"></i>
                                <div>20°</div>
                            </div>
                            <div class="forecast-day">
                                <div>Ven</div>
                                <i class="fas fa-sun"></i>
                                <div>22°</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="widget-card news">
                    <div class="widget-header">
                        <div class="widget-title">Notizie</div>
                        <button class="widget-more"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                    <div class="widget-body">
                        <div class="news-item">
                            <img src="assets/img/news-1.jpg" alt="News" class="news-img">
                            <div class="news-content">
                                <div class="news-title">Nuove misure per le aziende digitali</div>
                                <div class="news-source">ANSA.it</div>
                            </div>
                        </div>
                        <div class="news-item">
                            <img src="assets/img/news-2.jpg" alt="News" class="news-img">
                            <div class="news-content">
                                <div class="news-title">Innovazione tecnologica nelle PMI</div>
                                <div class="news-source">Il Sole 24 Ore</div>
                            </div>
                        </div>
                        <div class="news-item">
                            <img src="assets/img/news-3.jpg" alt="News" class="news-img">
                            <div class="news-content">
                                <div class="news-title">Mercati in crescita nel settore tech</div>
                                <div class="news-source">Repubblica.it</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="widget-card calendar">
                    <div class="widget-header">
                        <div class="widget-title">Calendario</div>
                        <button class="widget-more"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                    <div class="widget-body">
                        <div class="calendar-date">
                            <div class="current-date">${getCurrentFormattedDate()}</div>
                        </div>
                        <div class="calendar-events">
                            <div class="event">
                                <div class="event-time">10:00</div>
                                <div class="event-details">
                                    <div class="event-title">Meeting con cliente</div>
                                    <div class="event-location">Sala riunioni</div>
                                </div>
                            </div>
                            <div class="event">
                                <div class="event-time">14:30</div>
                                <div class="event-details">
                                    <div class="event-title">Revisione progetti</div>
                                    <div class="event-location">Online</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="widget-card tasks">
                    <div class="widget-header">
                        <div class="widget-title">Attività</div>
                        <button class="widget-more"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                    <div class="widget-body">
                        <div class="task-list">
                            <div class="task-item">
                                <input type="checkbox" id="task1">
                                <label for="task1">Completare report mensile</label>
                            </div>
                            <div class="task-item">
                                <input type="checkbox" id="task2" checked>
                                <label for="task2">Inviare email al cliente</label>
                            </div>
                            <div class="task-item">
                                <input type="checkbox" id="task3">
                                <label for="task3">Aggiornare database</label>
                            </div>
                            <div class="task-item">
                                <input type="checkbox" id="task4">
                                <label for="task4">Preparare presentazione</label>
                            </div>
                        </div>
                        <button class="add-task-btn">
                            <i class="fas fa-plus"></i> Aggiungi attività
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(widgetsPanel);
        
        // Gestione del pulsante di chiusura
        document.getElementById('closeWidgets').addEventListener('click', function() {
            widgetsPanel.classList.remove('show');
        });
        
        // Aggiungi evento click al pulsante widget
        const widgetsButton = document.querySelector('.taskbar-icon[title="Widget"]');
        if (widgetsButton) {
            widgetsButton.addEventListener('click', function(e) {
                e.stopPropagation();
                
                // Chiudi altri pannelli aperti
                document.querySelectorAll('.win11-start-panel.show, .notification-panel.show, .calendar-panel.show, .action-center.show').forEach(panel => {
                    panel.classList.remove('show');
                });
                
                // Mostra/nascondi pannello widgets
                widgetsPanel.classList.toggle('show');
            });
            
            // Chiudi al click fuori
            document.addEventListener('click', function(e) {
                if (widgetsPanel.classList.contains('show') && 
                    !widgetsPanel.contains(e.target) && 
                    e.target !== widgetsButton) {
                    widgetsPanel.classList.remove('show');
                }
            });
        }
    }
}

/**
 * Inizializza i dati per i widget
 */
function initWidgetsData() {
    // Aggiorna dati meteo
    updateWeatherData();
    
    // Aggiorna notizie
    updateNewsData();
    
    // Aggiorna eventi calendario
    updateCalendarEvents();
    
    // Gestione attività
    initTasksWidget();
}

/**
 * Simula aggiornamento dati meteo
 */
function updateWeatherData() {
    // Simuliamo una chiamata API con dati fittizi
    setTimeout(() => {
        // Qui potremmo fare una vera chiamata API
    }, 1500);
}

/**
 * Simula aggiornamento notizie
 */
function updateNewsData() {
    // Simuliamo una chiamata API con dati fittizi
    setTimeout(() => {
        // Qui potremmo fare una vera chiamata API
    }, 2000);
}

/**
 * Simula aggiornamento eventi calendario
 */
function updateCalendarEvents() {
    // Simuliamo una chiamata API con dati fittizi
    setTimeout(() => {
        // Qui potremmo fare una vera chiamata API
    }, 1800);
}

/**
 * Gestione del widget attività
 */
function initTasksWidget() {
    // Gestione checkbox attività
    document.querySelectorAll('.task-item input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.nextElementSibling;
            if (this.checked) {
                label.style.textDecoration = 'line-through';
                label.style.opacity = '0.6';
            } else {
                label.style.textDecoration = 'none';
                label.style.opacity = '1';
            }
        });
    });
    
    // Gestione pulsante aggiungi attività
    const addTaskBtn = document.querySelector('.add-task-btn');
    if (addTaskBtn) {
        addTaskBtn.addEventListener('click', function() {
            const taskList = document.querySelector('.task-list');
            const taskId = 'task' + (document.querySelectorAll('.task-item').length + 1);
            const newTask = document.createElement('div');
            newTask.className = 'task-item new-task';
            
            newTask.innerHTML = `
                <input type="checkbox" id="${taskId}">
                <label for="${taskId}" contenteditable="true">Nuova attività</label>
            `;
            taskList.appendChild(newTask);
            
            // Focus e seleziona il testo per modificare
            const label = newTask.querySelector('label');
            setTimeout(() => {
                label.focus();
                const range = document.createRange();
                range.selectNodeContents(label);
                const sel = window.getSelection();
                sel.removeAllRanges();
                sel.addRange(range);
            }, 10);
            
            // Gestisci checkbox della nuova attività
            newTask.querySelector('input[type="checkbox"]').addEventListener('change', function() {
                const label = this.nextElementSibling;
                if (this.checked) {
                    label.style.textDecoration = 'line-through';
                    label.style.opacity = '0.6';
                } else {
                    label.style.textDecoration = 'none';
                    label.style.opacity = '1';
                }
            });
            
            // Animazione ingresso
            setTimeout(() => newTask.classList.remove('new-task'), 10);
        });
    }
}

/**
 * Inizializza il menu contestuale in stile Windows 11
 */
function initContextMenu() {
    document.addEventListener('contextmenu', function(e) {
        // Applica solo se il click è sul body/background o su elementi speciali
        if (e.target === document.body || 
            e.target.classList.contains('desktop-item') ||
            e.target.closest('.desktop-item') ||
            e.target.classList.contains('widgets-panel')) {
            e.preventDefault();
            
            // Rimuovi eventuali menu contestuali esistenti
            const existingMenu = document.querySelector('.win11-context-menu');
            if (existingMenu) {
                existingMenu.remove();
            }
            
            // Crea il nuovo menu contestuale
            createContextMenu(e.clientX, e.clientY, e.target);
        }
    });
    
    // Chiudi il menu al click altrove
    document.addEventListener('click', function() {
        const contextMenu = document.querySelector('.win11-context-menu');
        if (contextMenu) {
            contextMenu.remove();
        }
    });
}

/**
 * Crea un menu contestuale in stile Windows 11
 */
function createContextMenu(x, y, target) {
    const contextMenu = document.createElement('div');
    contextMenu.className = 'win11-context-menu';
    
    // Menu items basati sul target
    let menuItems = [];
    
    if (target === document.body) {
        // Menu per desktop
        menuItems = [
            { icon: 'fa-sync', text: 'Aggiorna', action: () => location.reload() },
            { separator: true },
            { icon: 'fa-th-large', text: 'Visualizza', action: () => {} },
            { icon: 'fa-sort', text: 'Ordina per', action: () => {} },
            { separator: true },
            { icon: 'fa-folder-plus', text: 'Nuova cartella', action: () => {} },
            { icon: 'fa-file', text: 'Nuovo file', action: () => {} },
            { separator: true },
            { icon: 'fa-cog', text: 'Personalizza', action: () => {} }
        ];
    } else if (target.classList.contains('widgets-panel') || target.closest('.widgets-panel')) {
        // Menu per widget panel
        menuItems = [
            { icon: 'fa-sync', text: 'Aggiorna widget', action: () => initWidgetsData() },
            { icon: 'fa-plus', text: 'Aggiungi widget', action: () => {} },
            { icon: 'fa-minus', text: 'Rimuovi widget', action: () => {} },
            { separator: true },
            { icon: 'fa-cog', text: 'Impostazioni widget', action: () => {} }
        ];
    } else if (target.classList.contains('desktop-item') || target.closest('.desktop-item')) {
        // Menu per elementi desktop
        menuItems = [
            { icon: 'fa-folder-open', text: 'Apri', action: () => {} },
            { icon: 'fa-external-link-alt', text: 'Apri con...', action: () => {} },
            { separator: true },
            { icon: 'fa-cut', text: 'Taglia', action: () => {} },
            { icon: 'fa-copy', text: 'Copia', action: () => {} },
            { separator: true },
            { icon: 'fa-trash', text: 'Elimina', action: () => {} },
            { icon: 'fa-edit', text: 'Rinomina', action: () => {} },
            { separator: true },
            { icon: 'fa-info-circle', text: 'Proprietà', action: () => {} }
        ];
    }
    
    // Costruisci il menu HTML
    menuItems.forEach(item => {
        if (item.separator) {
            const separator = document.createElement('div');
            separator.className = 'context-menu-separator';
            contextMenu.appendChild(separator);
        } else {
            const menuItem = document.createElement('div');
            menuItem.className = 'context-menu-item';
            menuItem.innerHTML = `<i class="fas ${item.icon}"></i>${item.text}`;
            menuItem.addEventListener('click', (e) => {
                e.stopPropagation();
                contextMenu.remove();
                if (typeof item.action === 'function') item.action();
            });
            contextMenu.appendChild(menuItem);
        }
    });
    
    // Posiziona il menu
    contextMenu.style.top = y + 'px';
    contextMenu.style.left = x + 'px';
    document.body.appendChild(contextMenu);
    
    // Aggiusta la posizione se il menu esce dallo schermo
    const menuRect = contextMenu.getBoundingClientRect();
    if (menuRect.right > window.innerWidth) {
        contextMenu.style.left = (window.innerWidth - menuRect.width - 5) + 'px';
    }
    if (menuRect.bottom > window.innerHeight) {
        contextMenu.style.top = (window.innerHeight - menuRect.height - 5) + 'px';
    }
    
    // Animazione apertura
    setTimeout(() => contextMenu.classList.add('show'), 10);
}

/**
 * Inizializza i layout snap di Windows 11
 */
function initSnapLayout() {
    // Gestione hover su pulsante massimizza
    const maximizeBtn = document.querySelector('.win-control-btn.maximize');
    if (!maximizeBtn) return;
    
    let snapOptions = document.getElementById('win11-snap-options');
    if (!snapOptions) {
        // Crea il pannello delle opzioni di snap
        snapOptions = document.createElement('div');
        snapOptions.id = 'win11-snap-options';
        snapOptions.className = 'win11-snap-options';
        snapOptions.innerHTML = `
            <div class="snap-options-grid">
                <div class="snap-option left-half" data-layout="left-half"></div>
                <div class="snap-option right-half" data-layout="right-half"></div>
                <div class="snap-option quad top-left" data-layout="top-left"></div>
                <div class="snap-option quad top-right" data-layout="top-right"></div>
                <div class="snap-option quad bottom-left" data-layout="bottom-left"></div>
                <div class="snap-option quad bottom-right" data-layout="bottom-right"></div>
            </div>
        `;
        document.body.appendChild(snapOptions);
    }
    
    // Mostra opzioni di snap al passaggio del mouse sul pulsante massimizza
    maximizeBtn.addEventListener('mouseenter', function() {
        const btnRect = maximizeBtn.getBoundingClientRect();
        snapOptions.style.top = (btnRect.bottom + 10) + 'px';
        snapOptions.style.left = (btnRect.left - snapOptions.offsetWidth / 2 + btnRect.width / 2) + 'px';
        snapOptions.classList.add('show');
    });
    
    maximizeBtn.addEventListener('mouseleave', function(e) {
        if (!snapOptions.contains(e.relatedTarget)) {
            snapOptions.classList.remove('show');
        }
    });
    
    snapOptions.addEventListener('mouseleave', function(e) {
        if (!maximizeBtn.contains(e.relatedTarget)) {
            snapOptions.classList.remove('show');
        }
    });
    
    // Implementa la funzionalità di snap
    const snapOptionsList = snapOptions.querySelectorAll('.snap-option');
    snapOptionsList.forEach(option => {
        option.addEventListener('click', function() {
            const layout = this.getAttribute('data-layout');
            applySnapLayout(layout);
            snapOptions.classList.remove('show');
        });
    });
}

/**
 * Applica un layout snap alla finestra
 * @param {string} layout - Il tipo di layout da applicare
 */
function applySnapLayout(layout) {
    const appWindow = document.querySelector('.app-window');
    if (!appWindow) return;
    
    // Rimuovi tutti i layout precedenti
    appWindow.classList.remove(
        'maximized', 'snapped-left-half', 'snapped-right-half',
        'snapped-top-left', 'snapped-top-right',
        'snapped-bottom-left', 'snapped-bottom-right'
    );
    
    // Applica il nuovo layout
    switch (layout) {
        case 'left-half':
            appWindow.classList.add('snapped-left-half');
            break;
        case 'right-half':
            appWindow.classList.add('snapped-right-half');
            break;
        case 'top-left':
            appWindow.classList.add('snapped-top-left');
            break;
        case 'top-right':
            appWindow.classList.add('snapped-top-right');
            break;
        case 'bottom-left':
            appWindow.classList.add('snapped-bottom-left');
            break;
        case 'bottom-right':
            appWindow.classList.add('snapped-bottom-right');
            break;
    }
}

/**
 * Ottiene il nome dell'utente corrente
 * @returns {string} - Nome dell'utente
 */
function getCurrentUserName() {
    // Prova a prendere il nome utente dalla sessione, altrimenti usa un default
    const userElement = document.querySelector('.user-name');
    return userElement ? userElement.textContent : 'Utente';
}

/**
 * Ottiene la data corrente formattata
 * @returns {string} - Data formattata
 */
function getCurrentFormattedDate() {
    const now = new Date();
    const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
    return now.toLocaleDateString('it-IT', options);
}

/**
 * Inizializza effetti di blur avanzati
 */
function initBlurEffects() {
    // Gli elementi che devono avere effetti di blur dinamici
    const blurElements = document.querySelectorAll(
        '.win11-start-panel, .notification-panel, .user-panel, ' +
        '.win11-card, .app-navbar, .app-sidebar, .win11-context-menu, ' +
        '.widgets-panel, .win11-snap-options'
    );
    
    blurElements.forEach(element => {
        // Aggiungi blur dinamico
        element.addEventListener('mouseenter', function() {
            this.style.backdropFilter = 'blur(30px) saturate(180%)';
        });
        
        element.addEventListener('mouseleave', function() {
            this.style.backdropFilter = 'blur(20px) saturate(150%)';
        });
        
        // Aggiungi effetto bordo luminoso
        element.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            this.style.background = `
                linear-gradient(
                    135deg, 
                    var(--win11-mica-bg),
                    var(--win11-mica-bg)
                ),
                radial-gradient(
                    circle at ${x}px ${y}px,
                    rgba(255, 255, 255, 0.1) 0%,
                    transparent 70%
                )
            `;
        });
    });
}
