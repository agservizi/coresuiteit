# CoreSuite IT - Interfaccia Windows 11

Questa libreria implementa un'interfaccia grafica in stile Windows 11 per CoreSuite IT, completa di effetti di trasparenza, notifiche, menu e componenti tipici dell'esperienza Windows 11.

## Caratteristiche

- 🪟 **Design Windows 11**: Interfaccia fedele a Windows 11 con bordi arrotondati e effetto Mica
- ✨ **Effetti Fluent**: Trasparenza, effetto Acrilico e animazioni fluide
- 🌗 **Tema chiaro/scuro**: Supporto completo per modalità chiara e scura
- 🔔 **Notifiche**: Sistema di notifiche toast in stile Windows 11
- 🖥️ **Componenti**: Start menu, taskbar, finestre app, esplora file e altro
- ⚙️ **Impostazioni**: Pannello impostazioni completo per personalizzare l'interfaccia

## Struttura dei file

- `win11.css` - Stili principali dell'interfaccia Windows 11
- `win11-components.css` - Componenti UI specifici (start menu, pannelli, ecc.)
- `win11-notifications.css` - Sistema di notifiche e toast
- `win11-fluent.css` - Effetti di trasparenza e fluent design
- `win11-animations.css` - Animazioni per componenti Windows 11
- `win11-explorer.css` - Stile per il file explorer
- `win11-settings.css` - Pannello impostazioni
- `win11.js` - Funzionalità JavaScript principale
- `win11-fluent.js` - Gestione effetti di trasparenza avanzati
- `win11-effects.js` - Effetti visivi e animazioni
- `win11-widgets.js` - Gestione dei widget di Windows 11
- `win11-settings.js` - Gestione pannello impostazioni
- `win11-integration.js` - Integrazione con il resto dell'applicazione

## Utilizzo

### Inclusione dei file

Includi i file CSS e JavaScript necessari nella tua pagina HTML:

```html
<!-- CSS -->
<link rel="stylesheet" href="assets/css/win11.css">
<link rel="stylesheet" href="assets/css/win11-components.css">
<link rel="stylesheet" href="assets/css/win11-notifications.css">
<link rel="stylesheet" href="assets/css/win11-fluent.css">
<link rel="stylesheet" href="assets/css/win11-animations.css">
<!-- Opzionali in base alle necessità -->
<link rel="stylesheet" href="assets/css/win11-explorer.css">
<link rel="stylesheet" href="assets/css/win11-settings.css">

<!-- JavaScript -->
<script src="assets/js/win11.js"></script>
<script src="assets/js/win11-fluent.js"></script>
<script src="assets/js/win11-effects.js"></script>
<!-- Opzionali in base alle necessità -->
<script src="assets/js/win11-widgets.js"></script>
<script src="assets/js/win11-settings.js"></script>
```

### Creare la taskbar

```html
<div class="win11-taskbar">
    <div class="taskbar-start">
        <button class="taskbar-icon" id="startMenuBtn" title="Start">
            <i class="fab fa-windows"></i>
        </button>
        <button class="taskbar-icon" title="Widget">
            <i class="fas fa-th-large"></i>
        </button>
    </div>
    <div class="taskbar-app-icons">
        <!-- Icone applicazioni -->
        <button class="taskbar-icon" title="Esplora File">
            <i class="far fa-folder"></i>
        </button>
    </div>
    <div class="win11-task-right">
        <button class="taskbar-icon" title="Notifiche">
            <i class="far fa-bell"></i>
        </button>
        <div class="taskbar-separator"></div>
        <div id="taskbarTime" class="taskbar-time">00:00</div>
    </div>
</div>
```

### Creare una finestra

```html
<div class="app-window" style="width: 800px; height: 600px; top: 100px; left: 150px;">
    <div class="app-title-bar">
        <div class="app-icon">
            <i class="fas fa-folder"></i>
        </div>
        <h1 class="app-title">Titolo finestra</h1>
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
        <!-- Contenuto della finestra -->
    </div>
</div>
```

### Mostrare una notifica

```javascript
Win11.notifications.showToast({
    title: 'Titolo notifica',
    message: 'Descrizione della notifica',
    type: 'info', // info, success, warning, error
    duration: 5000 // 5 secondi
});
```

### Tema scuro/chiaro

Per attivare il tema scuro, imposta l'attributo `data-theme` su `dark` nell'elemento HTML:

```html
<html lang="it" data-theme="dark">
```

O cambialo dinamicamente con JavaScript:

```javascript
// Attiva tema scuro
document.documentElement.setAttribute('data-theme', 'dark');
document.body.classList.add('dark-theme');

// Attiva tema chiaro
document.documentElement.setAttribute('data-theme', 'light');
document.body.classList.remove('dark-theme');
```

## Demo

Vedi il file `win11-demo.html` per un esempio completo di implementazione dell'interfaccia Windows 11.

## Compatibilità browser

L'interfaccia è stata testata e funziona correttamente sui seguenti browser:

- Google Chrome 90+
- Microsoft Edge 90+
- Firefox 88+
- Safari 14+

## Requisiti

- FontAwesome 6+ per le icone
- Font "Segoe UI Variable" o "Segoe UI" per una migliore aderenza al design di Windows 11
