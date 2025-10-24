<?php

require('fpdf.php');

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR);
}

require_once ROOT_PATH . "controladores/configuracion.controlador.php";
require_once ROOT_PATH . "modelos/configuracion.modelo.php";

require_once ROOT_PATH . 'modelos/eventolog.modelo.php';
require_once ROOT_PATH . 'controladores/eventolog.controlador.php';

date_default_timezone_set('America/Caracas');

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('img/logo.png', 10, 8, 50);

        $this->SetY(12);
        $this->SetX(65);

        $empresa = ControladorConfiguracion::ctrMostrarConfigracion(null, null);
        $nombreEmpresa = utf8_decode($empresa["nombre"]);
        
        // --- Manejo de RIF y Teléfono de la EMPRESA ---
        $rifEmpresa = '';
        if (isset($empresa["tipo_rif"]) && isset($empresa["num_rif"])) {
            $rifEmpresa = utf8_decode($empresa["tipo_rif"] . '-' . $empresa["num_rif"]);
        }

        $localizacionEmpresa = utf8_decode($empresa["direccion"]);
        
        $telefonoEmpresa = '';
        if (isset($empresa["prefijo_telefono"]) && isset($empresa["numero_telefono"])) {
            $telefonoEmpresa = utf8_decode($empresa["prefijo_telefono"] . '-' . $empresa["numero_telefono"]);
        }
        // --- FIN de Manejo de RIF y Teléfono de la EMPRESA ---

        $this->SetTextColor(0, 0, 0);

        $this->SetFont('Arial', 'B', 18);
        $this->Cell(0, 8, $nombreEmpresa, 0, 1, 'L');

        $this->SetFont('Arial', '', 10);
        $this->SetX(65);
        $this->Cell(0, 5, "RIF: " . $rifEmpresa, 0, 1, 'L');

        $this->SetX(65);
        $this->MultiCell(0, 5, utf8_decode("Dirección: ") . $localizacionEmpresa, 0, 'L');

        $this->SetX(65);
        $this->Cell(0, 5, utf8_decode("Teléfono: ") . $telefonoEmpresa, 0, 1, 'L');

        $this->Ln(15);
        $this->SetX(10);

        $this->SetFillColor(70, 130, 180);
        $this->SetTextColor(255);
        $this->SetFont('Arial', 'B', 16);

        $this->Ln(8);

        $this->Cell(0, 10, utf8_decode('REPORTE DE EVENTOS DEL SISTEMA'), 0, 1, 'C', true);
        $this->SetTextColor(0);
        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetFont('Arial', 'I', 9);
        $this->SetY(-15);

        $this->Cell(95, 5, utf8_decode('Página ') . $this->PageNo() . ' / {nb}', 0, 0, 'L');
        
        $this->Cell(172, 5, date('d/m/Y | g:i:a'), 0, 1, 'R');
        
        $this->Line(10, $this->GetY() - 0.5, 287, $this->GetY() - 0.5);
    }

    function NbLines($w, $txt)
    {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }

    // Nuevo método para obtener el margen superior de la página
    function GetPageTopMargin() {
        return $this->tMargin;
    }

    // Nuevo método para dibujar los encabezados de la tabla
    function DrawTableHeader() {
        $this->SetX(10);
        $this->SetFillColor(70, 130, 180); 
        $this->SetDrawColor(255, 255, 255); 
        $this->SetTextColor(255);
        $this->SetFont('Arial', 'B', 9);

        $this->Cell(15, 12, utf8_decode('ID'), 1, 0, 'C', 1);
        $this->Cell(37, 12, utf8_decode('Fecha y Hora'), 1, 0, 'C', 1);
        $this->Cell(50, 12, utf8_decode('Tipo de Evento'), 1, 0, 'C', 1);
        $this->Cell(70, 12, utf8_decode('Descripción'), 1, 0, 'C', 1); 
        // Cambiamos a una sola columna para la cédula del empleado, ya que tu tabla "empleado" la usa así.
        $this->Cell(55, 12, utf8_decode('Cédula Empleado'), 1, 0, 'C', 1); // Ancho ajustado
        $this->Cell(25, 12, utf8_decode('Tabla Afectada'), 1, 0, 'C', 1); 
        $this->Cell(25, 12, utf8_decode('ID Fila Afectada'), 1, 1, 'C', 1); 
        
        $this->SetTextColor(0); 
        $this->SetFont('Arial', '', 7); 
    }

    // Nuevo método para dibujar la tabla de eventos
    function DrawEventTable($eventos) {
        $this->SetFont('Arial', '', 7); 
        $rowColorToggle = 0; 

        if (is_array($eventos) && !empty($eventos)) {
            foreach ($eventos as $evento) {
                $descripcion = utf8_decode($evento["description"]);
                
                // --- Aquí, asumimos que 'employee_cedula' ya viene completo del modelo 'eventolog' ---
                // Si 'eventolog.modelo.php' te devuelve la cédula ya separada en tipo y num,
                // deberías cambiar esto. PERO, si tu tabla 'event_log' solo tiene 'employee_cedula'
                // como una FK a 'empleado.cedula', entonces 'employee_cedula' debe ser el valor completo.
                $cedula_empleado_completa = isset($evento["employee_cedula"]) ? utf8_decode($evento["employee_cedula"]) : 'N/A';
                
                $w = [
                    'id' => 15,
                    'timestamp' => 37,
                    'tipo' => 50,
                    'descripcion' => 70, 
                    'cedula_emp' => 55, // Ancho ajustado para la cédula completa
                    'tabla' => 25,       
                    'fila' => 25         
                ];

                $lineHeight = 8; 
                $descripcionLines = $this->NbLines($w['descripcion'], $descripcion);
                $rowHeight = $descripcionLines * $lineHeight;

                // Comprueba si la fila completa cabe en la página actual
                if ($this->GetY() + $rowHeight > $this->PageBreakTrigger && $this->GetY() > $this->GetPageTopMargin()) {
                    $this->AddPage('L'); 
                    $this->DrawTableHeader(); 
                }

                // Colores de fila
                if ($rowColorToggle % 2 == 0) {
                    $this->SetFillColor(255, 255, 255);
                } else {
                    $this->SetFillColor(245, 245, 245);
                }
                $this->SetDrawColor(65, 61, 61);

                $this->SetX(10);
                $initialY = $this->GetY();

                $this->Cell($w['id'], $rowHeight, utf8_decode($evento["id"]), 1, 0, 'C', true);
                $this->Cell($w['timestamp'], $rowHeight, utf8_decode($evento["timestamp"]), 1, 0, 'C', true);
                $this->Cell($w['tipo'], $rowHeight, utf8_decode($evento["event_type"]), 1, 0, 'L', true);

                $xBeforeDesc = $this->GetX();
                $yBeforeDesc = $this->GetY();
                $this->MultiCell($w['descripcion'], $lineHeight, $descripcion, 1, 'L', true);
                
                // Mueve el cursor de vuelta para las celdas restantes en la misma fila
                $this->SetXY($xBeforeDesc + $w['descripcion'], $initialY);

                // Celda para la cédula COMPLETA del empleado
                $this->Cell($w['cedula_emp'], $rowHeight, $cedula_empleado_completa, 1, 0, 'C', true);

                $this->Cell($w['tabla'], $rowHeight, utf8_decode($evento["affected_table"]), 1, 0, 'C', true);
                $this->Cell($w['fila'], $rowHeight, utf8_decode($evento["affected_row_id"]), 1, 1, 'C', true);

                $rowColorToggle++;
            }
        } else {
            $this->SetX(10);
            $this->Cell(270, 10, utf8_decode('No se encontraron eventos para mostrar.'), 1, 1, 'C');
        }

        $this->SetX(10);
        $this->Cell(270, 0, '', 'T', 1, 'C'); 
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L');
$pdf->SetAutoPageBreak(true, 20);

$pdf->SetTopMargin(75); 
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);
$pdf->SetX(10);

$pdf->DrawTableHeader();

// Asumiendo que ctrMostrarEventosLog te devuelve 'employee_cedula' como el valor completo
$eventos = ControladorEventoLog::ctrMostrarEventosLog(null, null);

$pdf->DrawEventTable($eventos);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// --- Importante: Obtener la cédula COMPLETA de la sesión ---
// 'Sistema/Desconocido' debe existir en tu tabla 'empleado.cedula' si lo usas como FK
$empleadoCedulaParaLog = (isset($_SESSION["cedula"]) && $_SESSION["cedula"] != "") ? $_SESSION["cedula"] : "Sistema/Desconocido";

$logData = array(
    "event_type" => "Generación de Reporte PDF",
    "description" => "Se generó un reporte de Eventos del Sistema en formato PDF.",
    "employee_cedula" => $empleadoCedulaParaLog, // Aquí se pasa la cédula COMPLETA
    "affected_table" => "event_log",
    "affected_row_id" => "N/A"
);

// --- La llamada a ctrGuardarEventoLog debe coincidir con la firma esperada ---
// Asumiendo que ctrGuardarEventoLog ahora espera 'employee_cedula' como un solo parámetro
ControladorEventoLog::ctrGuardarEventoLog(
    $logData["event_type"],
    $logData["description"],
    $logData["employee_cedula"], // Pasar el campo completo aquí
    $logData["affected_table"],
    $logData["affected_row_id"]
);

$pdf->Output();