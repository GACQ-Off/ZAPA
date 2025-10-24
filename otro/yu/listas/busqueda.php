<div class="search-container">
    <label for="searchInput">Buscar:</label>
    <input type="text" id="searchInput" placeholder="Escribe para buscar...">
</div>

<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="yourTableBody">
            <tr>
                <td colspan="5" style="text-align: center;">Cargando datos...</td>
            </tr>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const yourTableBody = document.getElementById('yourTableBody');

    function renderTable(data) {
        yourTableBody.innerHTML = '';

        if (data.length > 0) {
            data.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.id}</td>
                    <td>${item.nombre}</td>
                    <td>${item.descripcion}</td>
                    <td>${item.estado}</td>
                    <td>
                        <a href="editar.php?id=${item.id}">Editar</a>
                        <a href="eliminar.php?id=${item.id}">Eliminar</a>
                    </td>
                `;
                yourTableBody.appendChild(row);
            });
        } else {
            yourTableBody.innerHTML = '<tr><td colspan="5" style="text-align: center;">No se encontraron resultados.</td></tr>';
        }
    }

    async function fetchData() {
        const searchTerm = searchInput.value;

        const params = new URLSearchParams();
        params.append('ajax', '1');
        if (searchTerm) {
            params.append('search', searchTerm);
        }

        try {
            const response = await fetch(`tu_archivo_php_con_datos.php?${params.toString()}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            
            renderTable(data.data_key); 

        } catch (error) {
            console.error('Error al cargar los datos:', error);
            yourTableBody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: red;">Error al cargar los datos.</td></tr>';
        }
    }

    searchInput.addEventListener('input', fetchData);

    fetchData();
});
</script>