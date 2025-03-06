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
  // Carica i dati delle transazioni dal database
  loadTransactionData();

  // Carica i dati dei servizi dal database
  loadServiceData();
}

// Funzione per caricare i dati delle transazioni dal database
function loadTransactionData() {
  fetch('api/get_transaction_data.php')
    .then(response => response.json())
    .then(data => {
      // Grafico transazioni
      var ctxTransazioni = document.getElementById('transazioniChart').getContext('2d');
      var transazioniChart = new Chart(ctxTransazioni, {
        type: 'line',
        data: {
          labels: data.labels,
          datasets: [{
            label: 'Transazioni',
            data: data.data,
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
    })
    .catch(error => console.error('Errore caricamento dati transazioni:', error));
}

// Funzione per caricare i dati dei servizi dal database
function loadServiceData() {
  fetch('api/get_service_data.php')
    .then(response => response.json())
    .then(data => {
      // Grafico servizi
      var ctxServizi = document.getElementById('serviziChart').getContext('2d');
      var serviziChart = new Chart(ctxServizi, {
        type: 'doughnut',
        data: {
          labels: data.labels,
          datasets: [{
            data: data.data,
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
    })
    .catch(error => console.error('Errore caricamento dati servizi:', error));
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
