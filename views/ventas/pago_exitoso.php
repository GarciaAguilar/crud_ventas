<?php include '../../includes/header.php'; ?>

<!-- Estilos específicos para pago exitoso -->
<link href="/Crud_Ventas/public/assets/css/pago_exitoso.css" rel="stylesheet">

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-success">
                <div class="card-header bg-success text-white text-center">
                    <h3><i class="bi bi-check-circle-fill"></i> ¡Pago Procesado Exitosamente!</h3>
                </div>
                <div class="card-body text-center">
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">¡Venta Completada!</h4>
                        <p>La venta #<?= $venta['id_venta'] ?> ha sido procesada correctamente.</p>
                        <hr>
                        <p class="mb-0">Su factura <?= $factura['numero_factura'] ?> ha sido generada automáticamente.</p>
                    </div>

                    <!-- Resumen de la venta -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="bi bi-receipt"></i> Detalles de la Venta</h6>
                                    <p><strong>Venta:</strong> #<?= $venta['id_venta'] ?></p>
                                    <p><strong>Factura:</strong> <?= $factura['numero_factura'] ?></p>
                                    <p><strong>Cliente:</strong> <?= htmlspecialchars($venta['nombre_cliente'] ?? 'Consumidor Final') ?></p>
                                    <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($venta['fecha_venta'])) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title"><i class="bi bi-cash"></i> Información del Pago</h6>
                                    <p><strong>Total:</strong> $<?= number_format($venta['total'], 2) ?></p>
                                    <p><strong>Monto Recibido:</strong> $<?= number_format($venta['monto_recibido'], 2) ?></p>
                                    <p><strong>Cambio:</strong> $<?= number_format($venta['cambio'] ?? 0, 2) ?></p>
                                    <span class="badge bg-success">Pagado</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="/Crud_Ventas/public/facturas/?action=generar_pdf&id=<?= $venta['id_venta'] ?>" 
                           class="btn btn-primary btn-lg me-md-2" target="_blank">
                            <i class="bi bi-file-earmark-pdf"></i> Ver Factura PDF
                        </a>
                        <a href="/Crud_Ventas/public/ventas/?action=detalle&id=<?= $venta['id_venta'] ?>" 
                           class="btn btn-info btn-lg me-md-2">
                            <i class="bi bi-eye"></i> Ver Detalle
                        </a>
                        <a href="/Crud_Ventas/public/ventas/" 
                           class="btn btn-secondary btn-lg">
                            <i class="bi bi-list"></i> Ver Todas las Ventas
                        </a>
                    </div>

                    <div class="mt-4">
                        <a href="/Crud_Ventas/public/ventas/?action=crear" 
                           class="btn btn-outline-success">
                            <i class="bi bi-plus-circle"></i> Realizar Nueva Venta
                        </a>
                    </div>
                </div>
            </div>

            <!-- Auto-abrir PDF después de 3 segundos -->
            <div class="mt-3">
                <div class="alert alert-info text-center" id="autoOpenAlert">
                    <i class="bi bi-info-circle"></i> 
                    La factura PDF se abrirá automáticamente en <span id="countdown">3</span> segundos...
                    <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="cancelAutoOpen()">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript específico para pago exitoso -->
<script>
// Establecer la URL de la factura para el JavaScript
window.facturaUrl = '/Crud_Ventas/public/facturas/?action=generar_pdf&id=<?= $venta['id_venta'] ?>';
</script>
<script src="/Crud_Ventas/public/assets/js/pago_exitoso.js"></script>

<?php include '../../includes/footer.php'; ?>
