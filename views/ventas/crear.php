<?php include '../../includes/header.php'; ?>

<div class="container mt-4">
    <h2><i class="bi bi-cart-plus"></i> Nueva Venta</h2>
    
    <div class="row">
        <div class="col-md-5">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Cliente</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Buscar Cliente:</label>
                        <input type="text" id="buscarCliente" class="form-control" placeholder="Nombre">
                        <div id="resultadosCliente" class="list-group mt-2" style="display:none;"></div>
                        <input type="hidden" id="id_cliente" name="id_cliente">
                    </div>
                    <div id="infoCliente" class="alert alert-info" style="display:none;"></div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Productos Disponibles</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <input type="text" id="buscarProducto" class="form-control" placeholder="Buscar producto...">
                    </div>
                    <div id="productosDisponibles" class="list-group" style="max-height: 400px; overflow-y: auto;">
                        <?php foreach($productosDisponibles as $producto): ?>
                        <div class="list-group-item producto-item" 
                             data-id="<?= $producto['id_producto'] ?>"
                             data-nombre="<?= htmlspecialchars($producto['nombre']) ?>"
                             data-precio="<?= $producto['precio'] ?>"
                             data-stock="<?= $producto['stock'] ?>">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong><?= htmlspecialchars($producto['nombre']) ?></strong><br>
                                    <small>Stock: <?= $producto['stock'] ?></small>
                                </div>
                                <div class="text-end">
                                    $<?= number_format($producto['precio'], 2) ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-7">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Productos a Vender</h5>
                </div>
                <div class="card-body">
                    <form id="formVenta">
                        <input type="hidden" name="productos" id="productosSeleccionados">
                        <table class="table" id="tablaProductosVenta">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th width="100">Cantidad</th>
                                    <th width="120">Precio</th>
                                    <th width="120">Subtotal</th>
                                    <th width="50"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Se llenará dinámicamente -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">Subtotal</th>
                                    <th id="subtotal">$0.00</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th colspan="3">IVA (13%)</th>
                                    <th id="iva">$0.00</th>
                                    <th></th>
                                </tr>
                                <tr class="table-active">
                                    <th colspan="3">Total</th>
                                    <th id="total">$0.00</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                        <button type="button" id="btnProcesarVenta" class="btn btn-primary btn-lg">
                            <i class="bi bi-cash"></i> Procesar Venta y Pago
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Pago -->
<div class="modal fade" id="modalPago" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Confirmar Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formPago" action="/Crud_Ventas/public/ventas/?action=procesar_pago" method="POST">
                    <input type="hidden" name="id_cliente" id="modal_id_cliente">
                    <input type="hidden" name="productos" id="modal_productos">
                    <input type="hidden" name="cambio" id="modal_cambio_hidden">
                    
                    <div class="mb-3">
                        <label class="form-label">Subtotal:</label>
                        <input type="text" class="form-control" id="modal_subtotal" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">IVA (13%):</label>
                        <input type="text" class="form-control" id="modal_iva" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total a Pagar:</label>
                        <input type="text" class="form-control" id="modal_total" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Monto Recibido:</label>
                        <input type="number" class="form-control" id="modal_monto_recibido" name="monto_recibido" step="0.01" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cambio:</label>
                        <input type="text" class="form-control" id="modal_cambio" readonly>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Confirmar Pago
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../../public/assets/js/ventas.js"></script>
<?php include '../../includes/footer.php'; ?>