<?php
// Importar la clase FPDF
require('fpdf.php');

// Definir una clase que hereda de FPDF
class MiPDF extends FPDF {
    // Método para agregar contenido al PDF
    function AgregarContenido() {
        // Agregar texto al PDF
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(40, 10, '¡Hola, mundo!');
    }
}

// Crear una instancia de la clase MiPDF
$pdf = new MiPDF();

// Agregar una página al PDF
$pdf->AddPage();

// Agregar contenido al PDF
$pdf->AgregarContenido();

// Salida del PDF
$pdf->Output();
?>
