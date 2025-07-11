<?php
// Incluir el sistema de rutas
require_once realpath(dirname(__DIR__, 2)) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'path.php';

// Incluir FPDF desde la ubicación personalizada
require_once PUBLIC_PATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'fpdf' . DIRECTORY_SEPARATOR . 'fpdf.php';

// Validar que las variables necesarias existan
if (!isset($venta) || !isset($factura)) {
    die('Error: Datos de venta o factura no disponibles');
}

// Crear instancia de FPDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Encabezado con número de factura
$pdf->Cell(0, 10, 'FACTURA #' . $factura['numero_factura'], 0, 1, 'C');
$pdf->Ln(10);

// Datos de la empresa (personaliza estos valores)
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode('Tienda Electrónica'), 0, 1);
$pdf->Cell(0, 10, 'NRC: 1234567-1', 0, 1);
$pdf->Cell(0, 10, utf8_decode('Dirección Comercial 123'), 0, 1);
$pdf->Cell(0, 10, 'Tel: (503) 2223-4s56', 0, 1);
$pdf->Ln(10);

// Datos del cliente
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode('Datos del Cliente:'), 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode('Nombre: ' . ($venta['nombre_cliente'] ?? 'CONSUMIDOR FINAL')), 0, 1);

$pdf->Ln(10);

// Detalle de productos (encabezado)
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(20, 10, 'Cant.', 1, 0, 'C');
$pdf->Cell(100, 10, utf8_decode('Descripción'), 1, 0);
$pdf->Cell(35, 10, 'P. Unitario', 1, 0, 'R');
$pdf->Cell(35, 10, 'Subtotal', 1, 1, 'R');

// Productos
$pdf->SetFont('Arial', '', 12);
foreach($venta['productos'] as $producto) {
    $pdf->Cell(20, 10, $producto['cantidad'], 1, 0, 'C');
    $pdf->Cell(100, 10, utf8_decode($producto['nombre']), 1, 0);
    $pdf->Cell(35, 10, number_format($producto['precio_unitario'], 2), 1, 0, 'R');
    $pdf->Cell(35, 10, number_format($producto['subtotal'], 2), 1, 1, 'R');
}

// Totales
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(120, 10, 'Subtotal:', 0, 0, 'R');
$pdf->Cell(35, 10, number_format($venta['subtotal'], 2), 0, 1, 'R');
$pdf->Cell(120, 10, 'IVA 13%:', 0, 0, 'R');
$pdf->Cell(35, 10, number_format($venta['impuestos'], 2), 0, 1, 'R');
$pdf->Cell(120, 10, 'TOTAL:', 0, 0, 'R');
$pdf->Cell(35, 10, number_format($venta['total'], 2), 0, 1, 'R');
$pdf->Ln(15);

// Información de pago
$pdf->Cell(0, 10, utf8_decode('Monto Recibido: ') . number_format($venta['monto_recibido'], 2), 0, 1);
$pdf->Cell(0, 10, utf8_decode('Cambio: ') . number_format($venta['cambio'], 2), 0, 1);
$pdf->Ln(10);

// Pie de página
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, utf8_decode('Fecha y hora: ') . date('d/m/Y H:i', strtotime($venta['fecha_venta'])), 0, 1);
$pdf->Cell(0, 10, utf8_decode('¡Gracias por su compra!'), 0, 1, 'C');

// Generar PDF
$pdf->Output('I', 'Factura_' . $factura['numero_factura'] . '.pdf');
exit();
?>