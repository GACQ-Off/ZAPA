<?php
// PHP Configuration:
// Disable error displaying on the frontend for production environments to prevent information disclosure.
// This is a good security practice.
ini_set('display_errors', 0);
error_reporting(0);

// --- Data Fetching ---
// Initialize variables for the data retrieval methods.
$item = null;
$valor = null;

// Fetch sales and client data using your custom controller methods.
// Ensure these controller classes and methods are correctly defined and accessible.
$ventas = ControladorVentas::ctrMostrarVentas($item, $valor);
$clientes = ControladorClientes::ctrMostrarClientes($item, $valor);

// Initialize arrays to process and store client data and their total sales.
$arrayClientes = array();      // Stores 'ID_TYPE-ID_NUMBER' => 'Full Name' (e.g., ['V-12345678' => 'Juan Perez'])
$sumaTotalClientes = array();  // Stores 'ID_TYPE-ID_NUMBER' => Total Sales Amount (e.g., ['V-12345678' => 2500.00])

// --- Data Processing: Aggregate sales by client ---
// This nested loop iterates through all sales and all clients to match them.
// For very large datasets, consider optimizing this part, e.g., by fetching joined data
// from the database or creating an indexed lookup array for clients.
foreach ($ventas as $valueVentas) {
    foreach ($clientes as $valueClientes) {
        // Compare client ID type and number from sales record with client data.
        if (
            $valueClientes["tipo_ced"] == $valueVentas["tipo_ced_cliente"] &&
            $valueClientes["num_ced"] == $valueVentas["num_ced_cliente"]
        ) {
            $cedulaCompleta = $valueClientes["tipo_ced"] . '-' . $valueClientes["num_ced"];
            $nombreCompleto = $valueClientes["nombre"] . " " . $valueClientes["apellido"];

            // Store the full name of the client if not already stored.
            if (!isset($arrayClientes[$cedulaCompleta])) {
                $arrayClientes[$cedulaCompleta] = $nombreCompleto;
            }

            // Initialize total sales for the client if it's the first time encountering them.
            if (!isset($sumaTotalClientes[$cedulaCompleta])) {
                $sumaTotalClientes[$cedulaCompleta] = 0;
            }

            // Add the current sale's total to the client's accumulated total.
            $sumaTotalClientes[$cedulaCompleta] += floatval($valueVentas["total"]);
        }
    }
}

// ======================================================================
// === Crucial Step: Sort Clients by Total Sales in Descending Order ===
// ======================================================================
// arsort() sorts an array in descending order, maintaining key-value associations.
// This ensures that the client with the highest sales appears first in the data fed to the chart.
arsort($sumaTotalClientes); 
// Now, $sumaTotalClientes is ordered from the highest sales total to the lowest.
// ======================================================================


// --- Chart Dimensions Calculation ---
// These calculations determine the dynamic size of the Morris.js chart
// to accommodate all buyers and allow for scrolling.

// Count the number of unique buyers with recorded sales.
$numCompradores = count($sumaTotalClientes);

// Height allocated per bar/buyer in the chart. Adjust this value:
// - Increase if bars or labels appear too cramped vertically.
// - Decrease if there's too much empty space between bars.
// A value between 30px and 40px is generally suitable for Morris.js.
$alturaPorComprador = 35; // Pixels per buyer in the chart.

// Width allocated per "column" or buyer entry in the chart. Adjust this value:
// - Increase if horizontal labels overlap or are truncated.
// - Decrease if there's too much empty space horizontally.
// A value between 120px and 150px is often suitable, depending on name length.
$anchoPorComprador = 20; // Pixels per buyer.

// Minimum height for the chart. This prevents the chart from looking too small or empty
// if there are very few buyers.
$alturaMinimaGrafico = 300; // e.g., 300px.

// Minimum width for the chart. Useful if there are few buyers and you want the chart
// to occupy a decent horizontal space.
$anchoMinimoGrafico = 700; // e.g., 700px.

// Calculate the total height Morris.js needs to draw all bars without compressing them.
// It takes the maximum of the minimum height or the calculated height based on the number of buyers.
$alturaCalculadaParaMorris = max($alturaMinimaGrafico, $numCompradores * $alturaPorComprador);

// Calculate the total width Morris.js needs to draw all bars horizontally without overlapping.
$anchoCalculadoParaMorris = max($anchoMinimoGrafico, $numCompradores * $anchoPorComprador);

// --- Scroll Container Dimensions ---
// These define the visible area of the chart and control when scrollbars appear.

// Maximum height for the visible chart area. If the calculated chart height exceeds this,
// a vertical scrollbar will appear. Adjust this based on your dashboard layout.
$alturaMaximaContenedorScrollVertical = 400; // e.g., 400px.

// Maximum width for the visible chart area. If the calculated chart width exceeds this,
// a horizontal scrollbar will appear. Adjust this based on the column width in your layout.
$anchoMaximoContenedorScrollHorizontal = 800; // e.g., 800px.
?>

<div class="box box-primary">
    
    <div class="box-header with-border">
        <h3 class="box-title">Cuadro Comparativo de Compradores</h3>
    </div>

    <div class="box-body">
        
        <div class="chart-responsive">
            <div style="width: <?php echo $anchoMaximoContenedorScrollHorizontal; ?>px; overflow-x: auto;">
                <div style="max-height: <?php echo $alturaMaximaContenedorScrollVertical; ?>px; overflow-y: auto;">
                    <div class="chart" id="bar-chart2" 
                         style="height: <?php echo $alturaCalculadaParaMorris; ?>px; 
                                width: <?php echo $anchoCalculadoParaMorris; ?>px;">
                    </div>
                </div>
            </div>

        </div> </div> </div> <script>
// Ensure that required libraries (jQuery and Morris.js, Raphaël.js) are loaded
// in your page before this script executes.

// Initialize the Morris.js bar chart for buyers.
var barCompradores = new Morris.Bar({
    element: 'bar-chart2', // The ID of the DIV element where the chart will be drawn.
    resize: true,          // Allows the chart to attempt resizing. While dynamic width/height
                           // are calculated, this can still be helpful if the parent container resizes.
    data: [
        <?php
        // Generate the JavaScript data array for Morris.js from the PHP data.
        // The foreach loop will now iterate through $sumaTotalClientes in its sorted order (descending by sales).
        foreach ($sumaTotalClientes as $cedula => $monto) {
            // Format the client's name (first letter of each word capitalized, with UTF-8 support).
            $nombre = ucwords(mb_strtolower($arrayClientes[$cedula], 'UTF-8'));
            // Print each data point in the JSON format expected by Morris.js.
            // addslashes() is crucial to escape any single quotes in names, preventing JS errors.
            // number_format() ensures correct numeric formatting for amounts.
            echo "{ y: '" . addslashes($nombre) . "', a: " . number_format($monto, 2, '.', '') . " },";
        }
        ?>
    ],
    barColors: ['#FF8000'], // Bar color (orange, matching your image).
    xkey: 'y',              // The key from your data that will be used for the X-axis (here, buyer names).
    ykeys: ['a'],           // The key from your data that will be used for the Y-axis (here, sales amounts).
    labels: ['Ventas'],     // The label that will appear in the tooltip for the 'a' value (Ventas).
    preUnits: 'Bs ',        // A prefix for values displayed on the Y-axis and in tooltips.
    hideHover: 'auto',      // Hides the tooltip when the mouse is not over a bar.
    // xLabelAngle: 45      // Optional: Uncomment to tilt X-axis labels if they are very long
                           // and horizontal scrolling isn't preferred or sufficient alone.
});

// Window resize event handler:
// Although Morris.js has 'resize: true', an explicit redraw might still be necessary
// if the chart's container dimensions change for reasons other than a direct window resize.
// If you don't observe rendering issues when resizing the window, this line can be optional.
$(window).on('resize', function(){
    barCompradores.redraw();
});
</script>