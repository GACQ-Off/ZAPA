<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Balance de Productos</title>
    <script src="../assets/js/chart.umd.js"></script>
    <?php include "../assets/head_gerente.php"?>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .button-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        canvas {
            max-width: 100%;
            height: auto;
        }
        .error-message {
            color: red;
            font-weight: bold;
            text-align: center;
        }

        /* Estilos de la Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 8px;
        }
        .close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close-button:hover,
        .close-button:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="date"], input[type="text"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        #paginationControls {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
        }
        #paginationControls button {
            margin: 0 5px;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
<?php include '../assets/lista_gerente.php'; ?>
<div class="container">
    <h2 style="text-align: center;" id="chartTitle">Top 10 Productos Más Vendidos del Año Actual</h2>

    <div id="chartContainer">
        <canvas id="myChart"></canvas>
    </div>
    <div id="errorMessage" class="error-message" style="display:none;"></div>
    
    <div class="button-group">
        <button id="toggleSortBtn" onclick="toggleSortOrder('main')">Mostrar Menos Vendidos</button>
        <button onclick="openModal()">Mostrar Más</button>
    </div>
</div>

<div id="filterModal" class="modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal()">&times;</span>
        <h3>Aplicar Filtros</h3>
        <div class="form-group">
            <label for="startDate">Fecha de Inicio:</label>
            <input type="date" id="startDate">
        </div>
        <div class="form-group">
            <label for="endDate">Fecha de Fin:</label>
            <input type="date" id="endDate">
        </div>
        <div class="form-group">
            <label for="categoryInput">Filtrar por Categoría:</label>
            <input type="text" id="categoryInput" list="categoriesList" placeholder="Busca una categoría...">
            <datalist id="categoriesList"></datalist>
        </div>
        <button onclick="applyFilters()">Aplicar Filtros</button>

        <div id="chartModalContainer" style="margin-top: 20px;">
            <canvas id="myModalChart"></canvas>
        </div>
        <div id="modalErrorMessage" class="error-message" style="display:none;"></div>
        
        <div class="button-group">
            <button id="toggleModalSortBtn" onclick="toggleSortOrder('modal')">Mostrar Menos Vendidos</button>
        </div>
        
        <div id="paginationControls" style="display:none;">
            <button id="prevPageBtn" onclick="changePage(-1)">Anterior</button>
            <span id="pageInfo"></span>
            <button id="nextPageBtn" onclick="changePage(1)">Siguiente</button>
        </div>
    </div>
</div>

<script>
        document.addEventListener("DOMContentLoaded", function() {
        const inputFecha = document.getElementById("startDate");
        const hoy = new Date();
        const anio = hoy.getFullYear();
        let mes = hoy.getMonth() + 1;
        let dia = hoy.getDate();
        mes = mes < 10 ? '0' + mes : mes;
        dia = dia < 10 ? '0' + dia : dia;
        const fechaMaxima = `${anio}-${mes}-${dia}`;
        inputFecha.max = fechaMaxima;
    });
        document.addEventListener("DOMContentLoaded", function() {
        const inputFecha = document.getElementById("endDate");
        const hoy = new Date();
        const anio = hoy.getFullYear();
        let mes = hoy.getMonth() + 1;
        let dia = hoy.getDate();
        mes = mes < 10 ? '0' + mes : mes;
        dia = dia < 10 ? '0' + dia : dia;
        const fechaMaxima = `${anio}-${mes}-${dia}`;
        inputFecha.max = fechaMaxima;
    });
    let myChart = null;
    let modalChart = null;
    let sortOrderMain = 'DESC';
    let sortOrderModal = 'DESC';
    let allFilteredProducts = [];
    let currentPage = 0;
    const productsPerPage = 10;
    
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const categoryInput = document.getElementById('categoryInput');
    const categoriesList = document.getElementById('categoriesList');
    const errorMessageDiv = document.getElementById('errorMessage');
    const modalErrorMessageDiv = document.getElementById('modalErrorMessage');
    const chartTitleElement = document.getElementById('chartTitle');
    const modal = document.getElementById('filterModal');
    const paginationControls = document.getElementById('paginationControls');
    const pageInfoSpan = document.getElementById('pageInfo');
    const prevPageBtn = document.getElementById('prevPageBtn');
    const nextPageBtn = document.getElementById('nextPageBtn');
    let currentModalFilters = {};

    let categoriesMap = {};

    document.addEventListener('DOMContentLoaded', () => {
        loadCategories();
        fetchProducts('main', getThisYearDates(), 10);
    });

    function openModal() {
        modal.style.display = "block";
    }

    function closeModal() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            closeModal();
        }
    }

    function applyFilters() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        const categoryName = categoryInput.value;
        const categoryId = categoriesMap[categoryName] || '';

        currentModalFilters = {
            start: startDate,
            end: endDate,
            category: categoryId
        };
        
        fetchProducts('modal', currentModalFilters, null);
    }

    function getThisYearDates() {
        const today = new Date();
        const firstDayOfYear = new Date(today.getFullYear(), 0, 1).toISOString().split('T')[0];
        const todayFormatted = today.toISOString().split('T')[0];
        return { start: firstDayOfYear, end: todayFormatted, category: '' };
    }

    function fetchProducts(target, filters, limit) {
        const { start, end, category } = filters;
        const currentSortOrder = target === 'main' ? sortOrderMain : sortOrderModal;
        const titleText = currentSortOrder === 'DESC' ? 'Más Vendidos' : 'Menos Vendidos';

        const params = new URLSearchParams({
            start_date: start,
            end_date: end,
            order: currentSortOrder,
            id_categoria: category
        });

        if (limit !== null) {
            params.append('limit', limit);
        }

        const errorContainer = target === 'main' ? errorMessageDiv : modalErrorMessageDiv;

        fetch(`balance_funcion.php?${params.toString()}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al obtener los datos del servidor.');
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    errorContainer.textContent = data.error;
                    errorContainer.style.display = 'block';
                    if (target === 'main') {
                         document.getElementById('chartContainer').style.display = 'none';
                    } else {
                         document.getElementById('chartModalContainer').style.display = 'none';
                         paginationControls.style.display = 'none';
                    }
                    return;
                }
                
                errorContainer.style.display = 'none';
                
                if (target === 'main') {
                    document.getElementById('chartContainer').style.display = 'block';
                    const mainTitle = `Top 10 Productos ${titleText} del ${start} al ${end}`;
                    drawChart(myChart, 'myChart', data.productos, mainTitle);
                } else {
                    document.getElementById('chartModalContainer').style.display = 'block';
                    allFilteredProducts = data.productos;
                    currentPage = 0;
                    updateModalChart();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorContainer.textContent = 'Ocurrió un error al cargar los datos. Por favor, inténtalo de nuevo.';
                errorContainer.style.display = 'block';
                if (target === 'main') {
                    document.getElementById('chartContainer').style.display = 'none';
                } else {
                    document.getElementById('chartModalContainer').style.display = 'none';
                    paginationControls.style.display = 'none';
                }
            });
    }

    function updateModalChart() {
        const start = currentPage * productsPerPage;
        const end = start + productsPerPage;
        const productsToDisplay = allFilteredProducts.slice(start, end);
        
        const currentSortOrder = sortOrderModal;
        const titleText = currentSortOrder === 'DESC' ? 'Más Vendidos' : 'Menos Vendidos';
        const modalTitle = `Productos de la Categoría ${categoryInput.value} (${titleText})`;

        if (productsToDisplay.length > 0) {
            paginationControls.style.display = 'flex';
            const totalPages = Math.ceil(allFilteredProducts.length / productsPerPage);
            pageInfoSpan.textContent = `Página ${currentPage + 1} de ${totalPages}`;
            prevPageBtn.disabled = currentPage === 0;
            nextPageBtn.disabled = currentPage >= totalPages - 1;
        } else {
            paginationControls.style.display = 'none';
        }

        drawChart(modalChart, 'myModalChart', productsToDisplay, modalTitle);
    }
    
    function changePage(direction) {
        currentPage += direction;
        updateModalChart();
    }
    
    function drawChart(chartInstance, canvasId, productos, titleText) {
        if (chartInstance) {
            chartInstance.destroy();
        }
        
        const ctx = document.getElementById(canvasId).getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: productos.map(p => p.producto),
                datasets: [{
                    label: 'Cantidad Vendida',
                    data: productos.map(p => p.cantidad_vendida),
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    barPercentage: 0.5,
                    categoryPercentage: 0.8
                }]
            },
            options: {
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            afterLabel: function(context) {
                                let index = context.dataIndex;
                                let ganancia = productos[index].cantidad_ganada;
                                return 'Ganancia: $' + parseFloat(ganancia).toFixed(2);
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: titleText
                    }
                }
            }
        });

        if (canvasId === 'myChart') {
            myChart = chart;
        } else {
            modalChart = chart;
        }
    }

    function toggleSortOrder(target) {
        if (target === 'main') {
            const toggleBtn = document.getElementById('toggleSortBtn');
            sortOrderMain = sortOrderMain === 'DESC' ? 'ASC' : 'DESC';
            toggleBtn.textContent = sortOrderMain === 'DESC' ? 'Mostrar Menos Vendidos' : 'Mostrar Más Vendidos';
            fetchProducts('main', getThisYearDates(), 10);
        } else if (target === 'modal') {
            const toggleBtn = document.getElementById('toggleModalSortBtn');
            sortOrderModal = sortOrderModal === 'DESC' ? 'ASC' : 'DESC';
            toggleBtn.textContent = sortOrderModal === 'DESC' ? 'Mostrar Menos Vendidos' : 'Mostrar Más Vendidos';
            fetchProducts('modal', currentModalFilters, null);
        }
    }

    function loadCategories() {
        fetch('balance_categoria.php')
            .then(response => response.json())
            .then(data => {
                if (data.categorias) {
                    categoriesList.innerHTML = '';
                    data.categorias.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.nombre_categoria;
                        categoriesList.appendChild(option);
                        categoriesMap[category.nombre_categoria] = category.id_categoria;
                    });
                }
            });
    }
</script>
</body>
</html>