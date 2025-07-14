<?php include '../includes/header.php'; ?>

<div class="container mt-4">
    <h2><i class="bi bi-box-seam"></i> Gestión de Inventario</h2>
    
    <div class="d-flex justify-content-between mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalProducto">
            <i class="bi bi-plus-circle"></i> Nuevo Producto
        </button>
        <div class="input-group" style="width: 350px;">
            <input type="text" id="buscarProducto" class="form-control" placeholder="Buscar por nombre, categoría o ID...">
            <button class="btn btn-outline-secondary" type="button" id="btnBuscar" title="Búsqueda avanzada">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>N°</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Categoría</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaProductos">
                <!-- Los productos se cargarán via AJAX -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para Crear/Editar Producto -->
<div class="modal fade" id="modalProducto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitulo">Nuevo Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formProducto">
                    <input type="hidden" id="productoId" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="precio" class="form-label">Precio *</label>
                                <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="costo" class="form-label">Costo</label>
                                <input type="number" step="0.01" class="form-control" id="costo" name="costo">
                            </div>
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stock *</label>
                                <input type="number" class="form-control" id="stock" name="stock" required>
                            </div>
                            <div class="mb-3">
                                <label for="categoria" class="form-label">Categoría</label>
                                <input type="text" class="form-control" id="categoria" name="categoria">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="proveedor" class="form-label">Proveedor</label>
                                <input type="text" class="form-control" id="proveedor" name="proveedor">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnGuardar">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Configuración de URLs para JavaScript
    window.CONTROLLERS_URL = '<?= CONTROLLERS_URL ?>';
    window.ASSETS_URL = '<?= ASSETS_URL ?>';
</script>
<script src="../public/assets/js/inventario.js"></script>
<?php include '../includes/footer.php'; ?>