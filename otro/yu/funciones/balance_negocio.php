<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Balance de Negocio</title>
    <script src="../assets/js/chart.umd.js"></script>
    <?php include "../assets/head_gerente.php"?>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-group input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .button-group {
            margin-top: 20px;
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
            margin-top: 10px;
        }
        .summary-list {
            text-align: left;
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .summary-list h3 {
            margin-top: 0;
            color: #333;
        }
        .summary-list ul {
            list-style-type: none;
            padding: 0;
        }
        .summary-list li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
        }
        .summary-list li:last-child {
            border-bottom: none;
        }
        .summary-list .label {
            font-weight: bold;
        }
        .summary-list .value {
            font-family: monospace;
        }
    </style>
</head>
<body>
<?php include '../assets/lista_gerente.php'; ?>
<div class="container">
    <h2 style="text-align: center;">Balance General del Negocio</h2>

    <div class="form-group">
        <label for="startDate">Fecha de Inicio:</label>
        <input type="date" id="startDate">
        <label for="endDate">Fecha de Fin:</label>
        <input type="date" id="endDate">
        <button onclick="applyFilters()">Aplicar Filtros</button>
    </div>

    <div id="chartContainer" style="width: 100%; max-width: 600px; margin: auto;">
        <canvas id="balanceChart"></canvas>
    </div>
    <div id="errorMessage" class="error-message" style="display:none;"></div>
    
    <div class="summary-list" id="summaryList">
        <h3>Resumen Detallado</h3>
        <ul>
            <li id="totalVentasLi"><span class="label">Total de Ventas:</span> <span class="value" id="totalVentasSpan">$0.00</span></li>
            <li id="totalCostosLi"><span class="label">Total de Costo de Productos:</span> <span class="value" id="totalCostosSpan">$0.00</span></li>
            <li id="totalGastosLi"><span class="label">Total de Otros Gastos:</span> <span class="value" id="totalGastosSpan">$0.00</span></li>
            <li id="totalPerdidasLi"><span class="label">Total de Pérdidas:</span> <span class="value" id="totalPerdidasSpan">$0.00</span></li>
            <li id="gananciaPerdidaNetaLi"><span class="label">Balance Neto:</span> <span class="value" id="gananciaPerdidaNetaSpan">$0.00</span></li>
        </ul>
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

    document.addEventListener('DOMContentLoaded', () => {
        const today = new Date();
        const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
        const todayFormatted = today.toISOString().split('T')[0];
        document.getElementById('startDate').value = firstDayOfMonth;
        document.getElementById('endDate').value = todayFormatted;
        fetchBalance(firstDayOfMonth, todayFormatted);
    });

    function applyFilters() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        fetchBalance(startDate, endDate);
    }

    function fetchBalance(start, end) {
        const errorMessageDiv = document.getElementById('errorMessage');
        const chartContainer = document.getElementById('chartContainer');

        fetch(`balance_negocio_funcion.php?start_date=${start}&end_date=${end}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al obtener los datos del balance.');
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    errorMessageDiv.textContent = data.error;
                    errorMessageDiv.style.display = 'block';
                    chartContainer.style.display = 'none';
                    document.getElementById('summaryList').style.display = 'none';
                    return;
                }
                
                errorMessageDiv.style.display = 'none';
                chartContainer.style.display = 'block';
                document.getElementById('summaryList').style.display = 'block';

                const totalVentas = data.total_ventas;
                const totalGananciaNeta = data.total_ganancia_neta;
                const totalPerdidaNeta = data.total_perdida_neta;
                const totalGastos = data.total_gastos;
                const totalPerdidas = data.total_perdidas;
                const totalCostoProductos = data.total_costos_productos;

                updateSummaryList(totalVentas, totalGananciaNeta, totalPerdidaNeta, totalGastos, totalPerdidas, totalCostoProductos);
                drawBalanceChart(totalVentas, totalGananciaNeta, totalPerdidaNeta, totalGastos, totalPerdidas, totalCostoProductos);
            })
            .catch(error => {
                console.error('Error:', error);
                errorMessageDiv.textContent = 'Ocurrió un error al cargar los datos. Por favor, inténtalo de nuevo.';
                errorMessageDiv.style.display = 'block';
                chartContainer.style.display = 'none';
                document.getElementById('summaryList').style.display = 'none';
            });
    }

    function updateSummaryList(ventas, gananciaNeta, perdidaNeta, gastos, perdidas, costosProductos) {
        document.getElementById('totalVentasSpan').textContent = '$' + parseFloat(ventas).toFixed(2);
        document.getElementById('totalCostosSpan').textContent = '$' + parseFloat(costosProductos).toFixed(2);
        document.getElementById('totalGastosSpan').textContent = '$' + parseFloat(gastos).toFixed(2);
        document.getElementById('totalPerdidasSpan').textContent = '$' + parseFloat(perdidas).toFixed(2);
        
        const balanceSpan = document.getElementById('gananciaPerdidaNetaSpan');
        if (gananciaNeta > 0) {
            balanceSpan.textContent = '$' + parseFloat(gananciaNeta).toFixed(2);
            balanceSpan.style.color = '#009688';
            document.getElementById('gananciaPerdidaNetaLi').querySelector('.label').textContent = 'Ganancia Neta:';
        } else if (perdidaNeta > 0) {
            balanceSpan.textContent = '$' + parseFloat(perdidaNeta).toFixed(2);
            balanceSpan.style.color = '#9C27B0';
            document.getElementById('gananciaPerdidaNetaLi').querySelector('.label').textContent = 'Pérdida Neta:';
        } else {
            balanceSpan.textContent = '$0.00';
            balanceSpan.style.color = '#333';
            document.getElementById('gananciaPerdidaNetaLi').querySelector('.label').textContent = 'Balance Neto:';
        }
    }

    function drawBalanceChart(ventas, gananciaNeta, perdidaNeta, gastos, perdidas, costosProductos) {
        if (myChart) {
            myChart.destroy();
        }
        
        const ctx = document.getElementById('balanceChart').getContext('2d');
        
        const labels = ['Ventas', 'Costo de Productos', 'Otros Gastos', 'Pérdidas'];
        const values = [ventas, costosProductos, gastos, perdidas];
        const colors = [
            '#4CAF50', // Verde para Ventas
            '#FFC107', // Amarillo para Costos de Productos
            '#F44336', // Rojo para Otros Gastos
            '#FF4444'  // Rojo claro para Pérdidas
        ];

        if (gananciaNeta > 0) {
            labels.push('Ganancia Neta');
            values.push(gananciaNeta);
            colors.push('#009688'); // Turquesa para Ganancia Neta
        } else if (perdidaNeta > 0) {
            labels.push('Pérdida Neta');
            values.push(perdidaNeta);
            colors.push('#9C27B0'); // Púrpura para Pérdida Neta
        }

        const data = {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: colors,
                borderColor: '#fff',
                borderWidth: 2
            }]
        };

        const config = {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                const value = context.parsed;
                                return label + '$' + parseFloat(value).toFixed(2);
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Balance General por Categoría'
                    }
                }
            }
        };

        myChart = new Chart(ctx, config);
    }
</script>
</body>
</html>