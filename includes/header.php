<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'path.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ventas</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Estilos personalizados -->
    <link href="<?= asset('css/sidebar.css') ?>" rel="stylesheet">

</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <strong>Tienda Electrónica</strong>
        </div>
        <a href="<?= url('views/dashboard.php') ?>"><i class="bi bi-house-door-fill me-2"></i>Inicio</a>
        <a href="<?= url('views/inventario.php') ?>"><i class="bi bi-box-seam-fill me-2"></i>Inventario</a>
        <a href="<?= VENTAS_URL ?>"><i class="bi bi-cart-fill me-2"></i>Ventas</a>
        <a href="<?= FACTURAS_URL ?>"><i class="bi bi-receipt-cutoff me-2"></i>Facturas</a>
    </div>
    <!-- Contenido principal -->
    <div class="content">