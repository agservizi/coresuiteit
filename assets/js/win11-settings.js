/**
 * CoreSuite IT - Windows 11 Settings Panel
 * Gestione del pannello impostazioni in stile Windows 11
 */

document.addEventListener('DOMContentLoaded', function() {
    initSettingsPanel();
});

/**
 * Inizializza il pannello impostazioni di Windows 11
 */
function initSettingsPanel() {
    // Crea il pannello delle impostazioni se non esiste
    if (!document.getElementById('win11SettingsPanel')) {
        createSettingsPanel();
    }
    
    // Aggiungi listener di eventi per i pulsanti delle impostazioni
    document.querySelectorAll('[data-settings-open]').forEach(btn => {
        btn.addEventListener('click', () => {
            openSettingsPanel();
        });
    });
}

/**
 * Crea l'intera struttura del pannello impostazioni
 */
function createSettingsPanel() {
    const settingsPanel = document.createElement('div');
    settingsPanel.id = 'win11SettingsPanel';
    settingsPanel.className = 'win11-settings-panel';
    
    // Crea la struttura HTML del pannello
    settingsPanel.innerHTML = `
        <div class="win11-settings-sidebar">
            <div class="settings-sidebar-header">
                <h5 class="settings-sidebar-title">Impostazioni</h5>
            </div>
            <div class="settings-nav-item active" data-settings-section="system">
                <div class="settings-nav-icon">
                    <i class="fas fa-laptop"></i>
                </div>
                <span>Sistema</span>
            </div>
            <div class="settings-nav-item" data-settings-section="personalization">
                <div class="settings-nav-icon">
                    <i class="fas fa-paint-brush"></i>
                </div>
                <span>Personalizzazione</span>
            </div>
            <div class="settings-nav-item" data-settings-section="apps">
                <div class="settings-nav-icon">
                    <i class="fas fa-cubes"></i>
                </div>
                <span>App</span>
            </div>
            <div class="settings-nav-item" data-settings-section="accounts">
                <div class="settings-nav-icon">
                    <i class="fas fa-user"></i>
                </div>
                <span>Account</span>
            </div>
            <div class="settings-nav-item" data-settings-section="notifications">
                <div class="settings-nav-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <span>Notifiche</span>
            </div>
            <div class="settings-nav-item" data-settings-section="privacy">
                <div class="settings-nav-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <span>Privacy e sicurezza</span>
            </div>
            <div class="settings-nav-item" data-settings-section="updates">
                <div class="settings-nav-icon">
                    <i class="fas fa-sync"></i>
                </div>
                <span>Aggiornamenti</span>
            </div>
        </div>
        <div class="win11-settings-content">
            <button class="settings-close-btn" id="settingsCloseBtn">
                <i class="fas fa-times"></i>
            </button>
            
            <!-- Section: System -->
            <div class="settings-section active" data-section="system">
                <div class="settings-section-header">
                    <div class="settings-section-icon">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <h2 class="settings-section-title">Sistema</h2>
                </div>
                
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h3 class="settings-card-title">Display</h3>
                    </div>
                    <div class="settings-card-body">
                        <div class="settings-item">
                            <div class="settings-item-info">
                                <h4 class="settings-item-title">Modalità scura</h4>
                                <p class="settings-item-desc">Cambia l'aspetto di CoreSuite in modalità scura</p>
                            </div>
                            <div class="settings-item-control">
                                <label class="win11-toggle">
                                    <input type="checkbox" id="darkModeToggleSettings">
                                    <span class="win11-toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="settings-item">
                            <div class="settings-item-info">
                                <h4 class="settings-item-title">Luminosità</h4>
                                <p class="settings-item-desc">Regola la luminosità dello schermo</p>
                            </div>
                            <div class="settings-item-control">
                                <input type="range" class="win11-slider" min="0" max="100" value="80">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h3 class="settings-card-title">Alimentazione</h3>
                    </div>
                    <div class="settings-card-body">
                        <div class="settings-item">
                            <div class="settings-item-info">
                                <h4 class="settings-item-title">Risparmio energetico</h4>
                                <p class="settings-item-desc">Attiva la modalità di risparmio energetico</p>
                            </div>
                            <div class="settings-item-control">
                                <label class="win11-toggle">
                                    <input type="checkbox">
                                    <span class="win11-toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Section: Personalization -->
            <div class="settings-section" data-section="personalization">
                <div class="settings-section-header">
                    <div class="settings-section-icon">
                        <i class="fas fa-paint-brush"></i>
                    </div>
                    <h2 class="settings-section-title">Personalizzazione</h2>
                </div>
                
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h3 class="settings-card-title">Sfondo</h3>
                    </div>
                    <div class="settings-card-body">
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="wallpaper-preview">
                                    <img src="assets/img/win11-wallpaper.jpg" class="img-fluid rounded" alt="Current wallpaper">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h3 class="settings-card-title">Colori</h3>
                    </div>
                    <div class="settings-card-body">
                        <div class="settings-item">
                            <div class="settings-item-info">
                                <h4 class="settings-item-title">Colore accento</h4>
                                <p class="settings-item-desc">Scegli il colore principale dell'interfaccia</p>
                            </div>
                            <div class="settings-item-control">
                                <input type="color" value="#0078d4">
                            </div>
                        </div>
                        
                        <div class="settings-item">
                            <div class="settings-item-info">
                                <h4 class="settings-item-title">Effetti di trasparenza</h4>
                                <p class="settings-item-desc">Attiva gli effetti di trasparenza in stile Windows 11</p>
                            </div>
                            <div class="settings-item-control">
                                <label class="win11-toggle">
                                    <input type="checkbox" checked>
                                    <span class="win11-toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Altri pannelli verrebbero aggiunti qui -->
        </div>
    `;
    
    document.body.appendChild(settingsPanel);
    
    // Gestione cambio sezioni
    const navItems = settingsPanel.querySelectorAll('.settings-nav-item');
    const sections = settingsPanel.querySelectorAll('.settings-section');
    
    navItems.forEach(item => {
        item.addEventListener('click', function() {
            const sectionName = this.getAttribute('data-settings-section');
            
            // Rimuovi la classe active da tutti i nav items
            navItems.forEach(navItem => navItem.classList.remove('active'));
            // Aggiungi la classe active all'item corrente
            this.classList.add('active');
            
            // Nascondi tutte le sezioni
            sections.forEach(section => section.classList.remove('active'));
            // Mostra la sezione target
            settingsPanel.querySelector(`.settings-section[data-section="${sectionName}"]`).classList.add('active');
        });
    });
    
    // Chiudi le impostazioni
    document.getElementById('settingsCloseBtn').addEventListener('click', closeSettingsPanel);
    
    // Dark mode toggle
    const darkModeToggle = document.getElementById('darkModeToggleSettings');
    if (darkModeToggle) {
        // Sincronizza con lo stato attuale
        const currentTheme = document.documentElement.getAttribute('data-theme');
        darkModeToggle.checked = currentTheme === 'dark';
        
        // Gestisci il cambio
        darkModeToggle.addEventListener('change', function() {
            const themeToggle = document.getElementById('themeToggle');
            if (themeToggle) {
                themeToggle.checked = this.checked;
                // Trigger change event sul toggle originale
                themeToggle.dispatchEvent(new Event('change'));
            } else {
                // Fallback se il toggle principale non esiste
                if (this.checked) {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    document.body.classList.add('dark-theme');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.setAttribute('data-theme', 'light');
                    document.body.classList.remove('dark-theme');
                    localStorage.setItem('theme', 'light');
                }
            }
        });
    }
}

/**
 * Apre il pannello impostazioni
 */
function openSettingsPanel() {
    const settingsPanel = document.getElementById('win11SettingsPanel');
    if (settingsPanel) {
        settingsPanel.classList.add('show');
        
        // Nastri modali aperti
        document.querySelectorAll('.win11-start-panel.show, .notification-panel.show, .calendar-panel.show, .action-center.show').forEach(panel => {
            panel.classList.remove('show');
        });
    }
}

/**
 * Chiude il pannello impostazioni
 */
function closeSettingsPanel() {
    const settingsPanel = document.getElementById('win11SettingsPanel');
    if (settingsPanel) {
        settingsPanel.classList.remove('show');
    }
}
