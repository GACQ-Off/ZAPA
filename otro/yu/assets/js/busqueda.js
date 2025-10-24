document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const clearFiltersBtn = document.getElementById('clearFilters');
        const empleadosTableBody = document.getElementById('empleadosTableBody');
        const paginationContainer = document.getElementById('paginationContainer');
        const prevPageBtn = document.getElementById('prevPage');
        const nextPageBtn = document.getElementById('nextPage');
        const pageInfoSpan = document.getElementById('pageInfo');

        

        // Función para renderizar la tabla de empleados
        function renderEmpleadosTable(empleados) {
            empleadosTableBody.innerHTML = ''; // Limpiar tabla

            if (empleados.length > 0) {
                empleados.forEach(empleado => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="attendance__table-cell">${empleado.cedula_emple}</td>
                        <td class="attendance__table-cell"><a href="../funciones/historial_pagos.php?Empleado=${empleado.cedula_emple}">${empleado.nombre_emp}</a></td>
                        <td class="attendance__table-cell">${empleado.apellido_emp}</td>
                        <td class="attendance__table-cell">${empleado.cargo}</td>
                        <td class="attendance__table-cell">
                            <a title="editar" href="../editar/editar_empleado.php?Empleado=${empleado.cedula_emple}">
                                <span class="material-symbols-rounded ico-edit md-24 fill-1 wght-18 leading-none error"></span>
                            </a>
                            <button type="button" title="Eliminar" class="btn-icon" onclick="showDeleteModal('${empleado.cedula_emple}', '${escapeHtml(empleado.nombre_emp + " " + empleado.apellido_emp)}');">
                                <span class="material-symbols-rounded ico-delete md-24 fill-1 wght-18 leading-none error"></span>
                            </button>
                            <a title="pago" href="../funciones/pago_empleados.php?Empleado=${empleado.cedula_emple}">
                                <span class="material-symbols-rounded ico-local_atm md-24 fill-1 wght-18 leading-none blue-light"></span>
                            </a>
                        </td>
                    `;
                    empleadosTableBody.appendChild(row);
                });
                // Asegurarse de que la tabla esté visible y el mensaje "No hay registros" oculto
                if (empleadosTableBody.closest('table')) empleadosTableBody.closest('table').style.display = 'table';
                const noRecordsRow = document.getElementById('noRecordsRow');
                if (noRecordsRow) noRecordsRow.style.display = 'none';

            } else {
                // Mostrar mensaje "No hay empleados disponibles" si no hay datos
                empleadosTableBody.innerHTML = '<tr id="noRecordsRow"><td colspan="5" class="attendance__table-cell">No hay empleados disponibles.</td></tr>';
                if (empleadosTableBody.closest('table')) empleadosTableBody.closest('table').style.display = 'table'; // Mantener la tabla visible para el mensaje
            }
        }

        // Función para actualizar los controles de paginación
        function updatePaginationControls() {
            pageInfoSpan.textContent = `Página ${currentPage} de ${totalPages}`;
            prevPageBtn.disabled = (currentPage === 1);
            nextPageBtn.disabled = (currentPage === totalPages || totalPages === 0);
            paginationContainer.style.display = (totalPages > 1) ? 'flex' : 'none'; // Ocultar paginación si solo hay 1 página
        }

        // Función para obtener los empleados vía AJAX
        async function fetchEmpleados(page = 1) {
            const searchTerm = searchInput.value;

            const params = new URLSearchParams();
            params.append('ajax', '1'); // Indicar que es una solicitud AJAX
            params.append('page', page);
            if (searchTerm) params.append('search', searchTerm);

            try {
                const response = await fetch(`lista_empleado.php?${params.toString()}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                
                currentPage = data.current_page;
                totalPages = data.total_pages;

                renderEmpleadosTable(data.empleados);
                updatePaginationControls();

            } catch (error) {
                console.error('Error al cargar los empleados:', error);
                empleadosTableBody.innerHTML = `<tr><td colspan="5" class="attendance__table-cell" style="color: red;">Error al cargar los empleados: ${error.message}</td></tr>`;
                updatePaginationControls(); // Actualizar para reflejar el estado de error
            }
        }

        // Función para mostrar el modal de confirmación de eliminación
        // Esta función se hace global para que pueda ser llamada desde el onclick dinámico
        window.showDeleteModal = function(cedula_emple, nombre_completo) {
            let dialog = document.getElementById(`confirmDeleteModal_${cedula_emple}`);
            if (!dialog) {
                // Si el modal no existe (porque se recreó la tabla), crearlo dinámicamente
                dialog = document.createElement('dialog');
                dialog.id = `confirmDeleteModal_${cedula_emple}`;
                dialog.className = 'confirm-dialog';
                dialog.innerHTML = `
                    <h3 class="dialog-title">Confirmar Eliminación</h3>
                    <p class="dialog-message">
                        ¿Estás seguro de que deseas eliminar al empleado
                        <strong>${nombre_completo}</strong>
                        (Cédula: ${cedula_emple})?
                    </p>
                    <p class="dialog-warning">Al eliminarlo, solo se marcará como inactivo.</p>
                    <div class="dialog-actions">
                        <button type="button" class="btn btn--secondary" onclick="document.getElementById('confirmDeleteModal_${cedula_emple}').close();">Cancelar</button>
                        <a href="../eliminar/eliminar_empleado.php?Empleado=${cedula_emple}" class="btn btn--danger">Eliminar</a>
                    </div>
                `;
                document.body.appendChild(dialog);
            }
            dialog.showModal();
        }

        // Función para escapar HTML, útil para datos que van a 'onclick'
        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }

        // --- Event Listeners ---
        // Búsqueda dinámica
        searchInput.addEventListener('input', () => fetchEmpleados(1)); // Reset a la página 1 en cada búsqueda

        // Limpiar búsqueda
        clearFiltersBtn.addEventListener('click', () => {
            searchInput.value = '';
            fetchEmpleados(1); // Limpiar y recargar la primera página
        });

        // Paginación
        prevPageBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                fetchEmpleados(currentPage - 1);
            }
        });

        nextPageBtn.addEventListener('click', () => {
            if (currentPage < totalPages) {
                fetchEmpleados(currentPage + 1);
            }
        });

        // Cargar los empleados iniciales al cargar la página
        fetchEmpleados(currentPage);
    });