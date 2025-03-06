// Script per inizializzare tooltip e popover di Bootstrap
document.addEventListener('DOMContentLoaded', function() {
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
  
  var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
  popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
  });
  
  // Inizializza i chart nella dashboard se esistono
  if (document.getElementById('transazioniChart')) {
    initCharts();
  }
});

// Funzione per inizializzare i grafici della dashboard
function initCharts() {
  // Grafico transazioni
  var ctxTransazioni = document.getElementById('transazioniChart').getContext('2d');
  var transazioniChart = new Chart(ctxTransazioni, {
    type: 'line',
    data: {
      labels: ['Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab', 'Dom'],
      datasets: [{
        label: 'Transazioni',
        data: [12, 19, 3, 5, 10, 3, 7], // Sostituisci con i dati dal database
        borderColor: '#007bff',
        backgroundColor: 'rgba(0, 123, 255, 0.1)',
        borderWidth: 2,
        fill: true
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
  
  // Grafico servizi
  var ctxServizi = document.getElementById('serviziChart').getContext('2d');
  var serviziChart = new Chart(ctxServizi, {
    type: 'doughnut',
    data: {
      labels: ['Pagamenti', 'Telefonia', 'Energia', 'Spedizioni', 'Servizi Digitali'],
      datasets: [{
        data: [35, 20, 15, 20, 10], // Sostituisci con i dati dal database
        backgroundColor: [
          '#007bff',
          '#28a745',
          '#ffc107',
          '#dc3545',
          '#6c757d'
        ]
      }]
    },
    options: {
      responsive: true
    }
  });
}

// Funzione per confermare eliminazione
function confirmDelete(id, type) {
  return confirm('Sei sicuro di voler eliminare questo ' + type + '?');
}

// Funzione per formattare gli importi come valuta
function formatCurrency(amount) {
  return parseFloat(amount).toLocaleString('it-IT', {
    style: 'currency',
    currency: 'EUR'
  });
}
