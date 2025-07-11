<?php include '../../includes/header.php'; ?>

<div class="container mt-4">
    <h2><i class="bi bi-receipt"></i> Listado de Facturas</h2>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>N° Factura</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($facturas) && is_array($facturas)): ?>
                    <?php foreach($facturas as $factura): ?>
                    <tr>
                        <td><?= htmlspecialchars($factura['numero_factura']) ?></td>
                        <td><?= date('d/m/Y', strtotime($factura['fecha_emision'])) ?></td>
                        <td><?= htmlspecialchars($factura['nombre_cliente'] ?? 'Consumidor Final') ?></td>
                        <td>$<?= number_format($factura['total'], 2) ?></td>
                        <td>
                            <span class="badge bg-<?= $factura['estado'] ? 'success' : 'danger' ?>">
                                <?= $factura['estado'] ? 'Válida' : 'Anulada' ?>
                            </span>
                        </td>
                        <td>
                            <a href="/Crud_Ventas/public/facturas/?action=generar_pdf&id=<?= $factura['id_venta'] ?>" 
                               class="btn btn-sm btn-primary" target="_blank">
                                <i class="bi bi-file-earmark-pdf"></i> PDF
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No hay facturas disponibles</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="../../public/assets/js/facturas.js"></script>
<?php include '../../includes/footer.php'; ?>