<?php include '../../includes/header.php'; ?>

<div class="container mt-4">
    <h2><i class="bi bi-cart"></i> Listado de Ventas</h2>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <a href="<?= VENTAS_URL ?>?action=crear" class="btn btn-primary mb-3">
        <i class="bi bi-plus-circle"></i> Nueva Venta
    </a>
    
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($ventas) && is_array($ventas) && count($ventas) > 0): ?>
                <?php foreach($ventas as $venta): ?>
                <tr>
                    <td><?= date('d/m/Y H:i', strtotime($venta['fecha_venta'])) ?></td>
                    <td><?= htmlspecialchars($venta['nombre_cliente'] ?? 'Consumidor Final') ?></td>
                    <td>$<?= number_format($venta['total'], 2) ?></td>
                    <td>
                        <?php if($venta['estado'] == 1): ?>
                            <span class="badge bg-warning">Pendiente Pago</span>
                        <?php elseif($venta['estado'] == 2): ?>
                            <span class="badge bg-success">Completada</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Anulada</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= VENTAS_URL ?>?action=detalle&id=<?= $venta['id_venta'] ?>" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i> Ver
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No hay ventas registradas</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>