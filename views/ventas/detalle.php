<?php include '../../includes/header.php'; ?>

<!-- Estilos adicionales para tablas -->
<link href="<?= asset('css/table.css') ?>" rel="stylesheet">

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-receipt"></i> Detalle de Venta #<?= $venta['id_venta'] ?></h2>
                <div>
                    <a href="<?= VENTAS_URL ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Listado
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Información de la Venta -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información de la Venta</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>ID Venta:</strong></td>
                            <td><?= $venta['id_venta'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Fecha:</strong></td>
                            <td><?= date('d/m/Y H:i:s', strtotime($venta['fecha_venta'])) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Cliente:</strong></td>
                            <td><?= htmlspecialchars($venta['nombre_cliente'] ?? 'Consumidor Final') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Estado:</strong></td>
                            <td>
                                <?php if($venta['estado'] == 1): ?>
                                    <span class="badge bg-warning">Pendiente Pago</span>
                                <?php elseif($venta['estado'] == 2): ?>
                                    <span class="badge bg-success">Completada</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Anulada</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Información del Pago -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-cash"></i> Información del Pago</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Subtotal:</strong></td>
                            <td>$<?= number_format($venta['subtotal'], 2) ?></td>
                        </tr>
                        <tr>
                            <td><strong>IVA (13%):</strong></td>
                            <td>$<?= number_format($venta['impuestos'], 2) ?></td>
                        </tr>
                        <tr class="table-active">
                            <td><strong>Total:</strong></td>
                            <td><strong>$<?= number_format($venta['total'], 2) ?></strong></td>
                        </tr>
                        <?php if($venta['estado'] == 2 && isset($venta['monto_recibido'])): ?>
                        <tr>
                            <td><strong>Monto Recibido:</strong></td>
                            <td>$<?= number_format($venta['monto_recibido'], 2) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Cambio:</strong></td>
                            <td>$<?= number_format($venta['cambio'] ?? 0, 2) ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos de la Venta -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-cart-check"></i> Productos Vendidos</h5>
                </div>
                <div class="card-body">
                    <?php if(isset($venta['productos']) && count($venta['productos']) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-end">Precio Unitario</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($venta['productos'] as $producto): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($producto['nombre']) ?></td>
                                        <td class="text-center"><?= $producto['cantidad'] ?></td>
                                        <td class="text-end">$<?= number_format($producto['precio_unitario'], 2) ?></td>
                                        <td class="text-end">$<?= number_format($producto['subtotal'], 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="table-secondary">
                                    <tr>
                                        <th colspan="3" class="text-end">Subtotal:</th>
                                        <th class="text-end">$<?= number_format($venta['subtotal'], 2) ?></th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">IVA (13%):</th>
                                        <th class="text-end">$<?= number_format($venta['impuestos'], 2) ?></th>
                                    </tr>
                                    <tr class="table-primary">
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th class="text-end">$<?= number_format($venta['total'], 2) ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> No se encontraron productos para esta venta.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
