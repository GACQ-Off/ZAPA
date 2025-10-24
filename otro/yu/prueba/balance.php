<?php
session_start();
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    require_once 'conexion.php'; // Cambiar a la ruta local
    header('Content-Type: application/json');
    $fecha_inicio = $_GET['start_date'];
    $fecha_fin = $_GET['end_date'];
    try {
        $sql = "
            SELECT
                p.nom_pro AS producto,
                SUM(dv.cantidad_vendida) AS cantidad_vendida
            FROM
                detalle_venta dv
            JOIN
                ventas v ON dv.id_ventas = v.id_ventas
            JOIN
                producto p ON dv.id_pro = p.id_pro
            WHERE
                v.fecha_venta BETWEEN ? AND ?
            GROUP BY
                p.nom_pro
            ORDER BY
                cantidad_vendida DESC;
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($data);
        $stmt->close();
    } catch(Exception $e) {
        echo json_encode(['error' => 'Error en la consulta: ' . $e->getMessage()]);
        exit;
    } finally {
        $conn->close();
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance de Productos</title>
    <?php include "head_gerente.php"?>
    <script src="d3.min.js"></script> <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .chart-container { display: flex; justify-content: space-around; margin-top: 20px; }
        .chart { width: 45%; }
        h2 { text-align: center; }
        svg text { fill: black; font-size: 12px; }
        .tooltip { position: absolute; background: #fff; padding: 8px; border: 1px solid #ccc; border-radius: 4px; pointer-events: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1); font-size: 14px; }
    </style>
</head>
<body>
    <h1>Balance de Productos Vendidos</h1>
    <div>
        <label for="start_date">Fecha de inicio:</label>
        <input type="date" id="start_date">
        <label for="end_date">Fecha de fin:</label>
        <input type="date" id="end_date">
        <button onclick="fetchDataAndDraw()">Generar Gráficos</button>
    </div>
    <div class="chart-container">
        <div class="chart" id="bar-chart-top">
            <h2>Más Vendidos</h2>
        </div>
        <div class="chart" id="pie-chart-bottom">
            <h2>Menos Vendidos</h2>
        </div>
    </div>
    <script>
        const margin = { top: 20, right: 30, bottom: 40, left: 90 };
        const width = 500 - margin.left - margin.right;
        const height = 400 - margin.top - margin.bottom;
        
        function fetchDataAndDraw() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            if (!startDate || !endDate) {
                alert('Por favor, selecciona un rango de fechas.');
                return;
            }
            d3.select("#bar-chart-top svg").remove();
            d3.select("#pie-chart-bottom svg").remove();
            
            fetch(`?start_date=${startDate}&end_date=${endDate}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    if (data.length === 0) {
                        alert('No se encontraron datos para el rango de fechas seleccionado.');
                        return;
                    }
                    const topProducts = data.slice(0, 5);
                    const bottomProducts = data.slice(-5);
                    drawBarChart(topProducts, "#bar-chart-top");
                    drawPieChart(bottomProducts, "#pie-chart-bottom");
                })
                .catch(error => {
                    console.error('Error al obtener los datos:', error);
                    alert('Ocurrió un error al cargar los datos.');
                });
        }
        function drawBarChart(data, containerId) {
            const svg = d3.select(containerId)
                .append("svg")
                .attr("width", width + margin.left + margin.right)
                .attr("height", height + margin.top + margin.bottom)
                .append("g")
                .attr("transform", `translate(${margin.left},${margin.top})`);
            const x = d3.scaleLinear()
                .domain([0, d3.max(data, d => d.cantidad_vendida)])
                .range([0, width]);
            const y = d3.scaleBand()
                .domain(data.map(d => d.producto))
                .range([0, height])
                .padding(0.1);
            svg.append("g")
                .call(d3.axisLeft(y));
            svg.append("g")
                .attr("transform", `translate(0,${height})`)
                .call(d3.axisBottom(x));
            svg.selectAll("rect")
                .data(data)
                .enter()
                .append("rect")
                .attr("x", x(0))
                .attr("y", d => y(d.producto))
                .attr("width", d => x(d.cantidad_vendida))
                .attr("height", y.bandwidth())
                .attr("fill", "steelblue")
                .on("mouseover", handleMouseOver)
                .on("mouseout", handleMouseOut);
            svg.append("text")
                .attr("transform", `translate(${width / 2}, ${height + margin.bottom - 5})`)
                .style("text-anchor", "middle")
                .text("Cantidad Vendida");
        }
        function drawPieChart(data, containerId) {
            const radius = Math.min(width, height) / 2;
            const svg = d3.select(containerId)
                .append("svg")
                .attr("width", width + margin.left + margin.right)
                .attr("height", height + margin.top + margin.bottom)
                .append("g")
                .attr("transform", `translate(${width / 2 + margin.left}, ${height / 2 + margin.top})`);
            const color = d3.scaleOrdinal()
                .domain(data.map(d => d.producto))
                .range(d3.schemeCategory10);
            const pie = d3.pie()
                .value(d => d.cantidad_vendida);
            const arc = d3.arc()
                .innerRadius(0)
                .outerRadius(radius);
            const arcs = svg.selectAll("arc")
                .data(pie(data))
                .enter()
                .append("g")
                .attr("class", "arc")
                .on("mouseover", handleMouseOver)
                .on("mouseout", handleMouseOut);
            arcs.append("path")
                .attr("d", arc)
                .attr("fill", d => color(d.data.producto));
            arcs.append("text")
                .attr("transform", d => `translate(${arc.centroid(d)})`)
                .attr("text-anchor", "middle")
                .text(d => d.data.producto)
                .style("font-size", "10px");
        }
        function handleMouseOver(event, d) {
            const tooltip = d3.select("body").append("div")
                .attr("class", "tooltip")
                .html(`Producto: ${d.data.producto}<br>Cantidad: ${d.data.cantidad_vendida}`);
            tooltip.style("left", (event.pageX + 10) + "px")
                   .style("top", (event.pageY - 20) + "px");
        }
        function handleMouseOut() {
            d3.select(".tooltip").remove();
        }
    </script>
</body>
</html>