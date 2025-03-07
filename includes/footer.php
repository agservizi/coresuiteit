</div> <!-- Chiusura div content-container -->
            </main> <!-- Chiusura main -->
        </div> <!-- Chiusura row -->
    </div> <!-- Chiusura container-fluid -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Gestione del toggle menu per dispositivi mobili
        document.addEventListener('DOMContentLoaded', function() {
            const mediaQuery = window.matchMedia('(max-width: 768px)');
            const sidebar = document.getElementById('sidebar');
            
            function handleScreenChange(e) {
                if (e.matches) {
                    sidebar.classList.add('collapse');
                } else {
                    sidebar.classList.remove('collapse');
                }
            }
            
            // Inizializza
            handleScreenChange(mediaQuery);
            
            // Aggiungi listener
            mediaQuery.addEventListener('change', handleScreenChange);
        });
    </script>
</body>
</html>
