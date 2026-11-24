<footer class="pie-pagina">
            <p>&copy; <?= date('Y') ?> <strong>Clínica Central</strong> · Sistema Integral de Gestión Clínica</p>
        </footer>
    </div>

    <script>
        function filtrarTabla() {
            const input = document.getElementById('buscarPaciente');
            if (!input) return;
            const filtro = input.value.toLowerCase();
            const filas = document.querySelectorAll('#tablaPacientes tbody tr');
            filas.forEach(fila => {
                const texto = fila.textContent.toLowerCase();
                fila.style.display = texto.includes(filtro) ? '' : 'none';
            });
        }
    </script>
</body>
</html>