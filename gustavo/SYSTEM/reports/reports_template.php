<?php
include "../fpdf186/fpdf.php";

class PDF extends FPDF
{
    protected $reportTitle;
    protected $rif;
    protected $ubicacion;
    protected $telefono;
    protected $f_fundacion;
    protected $mail;
    protected $logoRuta;
    protected $logo_v;
    protected $nombreInstitucion;
    protected $ci_director;
    protected $nombres_director;
    protected $apellidos_director;
    
    public function setReportTitle($title) {
        $this->reportTitle = $title;
    }

    public function setTrapicheData(array $data) {
        $this->logoRuta = $data['logo_trapiche'] ?? 'img/default.png';
    $this->logo_v = "../uploads/trapiche/{$this->logoRuta}"; 
        $this->nombreInstitucion = $data['nombre_trapiche'] ?? 'Nombre de la Institución';
        $this->ci_director = $data['ci_director_a_trapiche'] ?? 'CI';
        $this->nombres_director = $data['nombres_director_a_trapiche'] ?? 'Nombres';
        $this->apellidos_director = $data['apellidos_director_a_trapiche'] ?? 'Apellidos';
        $this->rif = $data['rif_trapiche'] ?? 'RIF';
        $this->ubicacion = $data['ubicacion_trapiche'] ?? 'Ubicación';
        $this->telefono = $data['telefono_trapiche'] ?? 'Teléfonos';
        $this->f_fundacion = $data['f_fundacion_trapiche'] ?? 'Fecha de Fundación';
        $this->mail = $data['mail_trapiche'] ?? 'E-Mail';
    }
    
    function Header()
    {
    if (file_exists($this->logo_v)) { 
        $this->Image($this->logo_v, 10, 5, 40, 12.5   );
    } else {
        $this->SetFont("Arial", "B", 12);
        $this->Cell(13, 13, utf8_decode('LOGO'), 0, 0, 'L'); 
    }
        $this->SetFont("Arial", "B", 12);
        $this->Cell(25);
        $this->Cell(140, 4, utf8_decode($this->nombreInstitucion), 0, 0, "C");
        $this->Cell(5);
        $this->SetFont("Arial", "", 12);
        $this->Cell(140, 5, utf8_decode('(RIF): J-'.$this->rif), 0, 0, "L");
        $this->Ln(10);
        $this->SetFont("Arial", "B", 14);
        $this->cell(25);
        $title = $this->reportTitle ?: "Reporte General";
        $this->Cell(140, 5, utf8_decode($title), 0, 1, "C");
        $this->Ln(2);
        $this->Cell(100);
        $this->SetFont("Arial", "", 12);
        $this->Cell(25, 4, utf8_decode("Fecha de Generación: ") . date("d/m/Y"), 0, 1, "R");
        $this->Ln(4);
    }
    function Footer()
    {
        $this->SetY(-30);
        $this->SetFont('Arial', '', 8);
        $nombre_completo_director = "{$this->nombres_director} {$this->apellidos_director} (CI: {$this->ci_director})";
        $this->Cell(90, 4, utf8_decode("Elaborado bajo la dirección de: {$nombre_completo_director}"), 0, 0, 'L');
        $this->Cell(0, 4, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'R');
        $this->Ln(4);
        $this->Cell(140, 4, utf8_decode('Ubicación: '.$this->ubicacion), 0, 0, "L");
        $this->Ln(4);
        $this->Cell(140, 4, utf8_decode('Fecha de Fundación: '.$this->f_fundacion), 0, 0, "L");
        $this->Ln(4);
        $this->Cell(140, 4, utf8_decode( 'Teléfono(s) de Contacto: '.$this->telefono), 0, 0, "L");
        $this->Ln(4);
        $this->Cell(140, 4, utf8_decode('Correo Electrónico: '.$this->mail), 0, 0, "L");
        $this->Ln(4);
    }
    function TablaHeader($header, $w) 
    {
        $this->SetFillColor(111, 185, 177);
        $this->SetTextColor(0, 0, 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.1);
        $this->SetFont('Arial','B',10);

        for($i=0; $i<count($header); $i++) {
            $this->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C', true);
        }
        $this->Ln();
    }
}