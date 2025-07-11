document.addEventListener('DOMContentLoaded', function() {
    const tablaProductos = document.getElementById('tablaProductos');
    const modalProducto = new bootstrap.Modal(document.getElementById('modalProducto'));
    const formProducto = document.getElementById('formProducto');
    const btnGuardar = document.getElementById('btnGuardar');
    const modalTitulo = document.getElementById('modalTitulo');
    const productoId = document.getElementById('productoId');
    const buscarProducto = document.getElementById('buscarProducto');
    const btnBuscar = document.getElementById('btnBuscar');

    // Cargar productos al iniciar
    cargarProductos();

    // Configurar formulario
    btnGuardar.addEventListener('click', guardarProducto);
    
    // Buscar productos
    btnBuscar.addEventListener('click', buscarProductos);
    buscarProducto.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') buscarProductos();
    });

    // Función para cargar productos
    function cargarProductos() {
        fetch('/Crud_Ventas/controllers/InventarioController.php?action=index')
            .then(response => response.json())
            .then(data => {
                tablaProductos.innerHTML = '';
                data.forEach(producto => {
                    tablaProductos.innerHTML += `
                        <tr>
                            <td>${producto.id_producto}</td>
                            <td>${producto.nombre}</td>
                            <td>$${parseFloat(producto.precio).toFixed(2)}</td>
                            <td>${producto.stock}</td>
                            <td>${producto.categoria || '-'}</td>
                            <td>
                                <span class="badge ${producto.estado == 1 ? 'bg-success' : 'bg-secondary'}">
                                    ${producto.estado == 1 ? 'Activo' : 'Inactivo'}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning btn-editar" data-id="${producto.id_producto}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-eliminar" data-id="${producto.id_producto}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });

                // Agregar eventos a los botones
                document.querySelectorAll('.btn-editar').forEach(btn => {
                    btn.addEventListener('click', cargarProductoEditar);
                });

                document.querySelectorAll('.btn-eliminar').forEach(btn => {
                    btn.addEventListener('click', eliminarProducto);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudieron cargar los productos', 'error');
            });
    }

    // Función para cargar datos de producto a editar
    function cargarProductoEditar(e) {
        const id = e.currentTarget.getAttribute('data-id');
        
        fetch(`/Crud_Ventas/controllers/InventarioController.php?action=editar&id=${id}`)
            .then(response => response.json())
            .then(producto => {
                modalTitulo.textContent = 'Editar Producto';
                productoId.value = producto.id_producto;
                document.getElementById('nombre').value = producto.nombre;
                document.getElementById('descripcion').value = producto.descripcion;
                document.getElementById('precio').value = producto.precio;
                document.getElementById('costo').value = producto.costo;
                document.getElementById('stock').value = producto.stock;
                document.getElementById('categoria').value = producto.categoria;
                document.getElementById('proveedor').value = producto.proveedor;
                document.getElementById('estado').value = producto.estado;
                
                modalProducto.show();
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudo cargar el producto', 'error');
            });
    }

    // Función para guardar producto (crear o actualizar)
    function guardarProducto() {
        const formData = new FormData(formProducto);
        const url = productoId.value 
            ? '/Crud_Ventas/controllers/InventarioController.php?action=editar'
            : '/Crud_Ventas/controllers/InventarioController.php?action=crear';

        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: productoId.value ? 'Producto actualizado' : 'Producto creado',
                    showConfirmButton: false,
                    timer: 1500
                });
                modalProducto.hide();
                formProducto.reset();
                cargarProductos();
            } else {
                Swal.fire('Error', 'No se pudo guardar el producto', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Ocurrió un error al guardar', 'error');
        });
    }

    // Función para eliminar producto
    function eliminarProducto(e) {
        const id = e.currentTarget.getAttribute('data-id');
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminarlo!'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('id', id);
                
                fetch('/Crud_Ventas/controllers/InventarioController.php?action=eliminar', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        Swal.fire(
                            '¡Eliminado!',
                            'El producto ha sido eliminado.',
                            'success'
                        );
                        cargarProductos();
                    } else {
                        Swal.fire('Error', 'No se pudo eliminar el producto', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Ocurrió un error al eliminar', 'error');
                });
            }
        });
    }

    // Función para buscar productos
    function buscarProductos() {
        const termino = buscarProducto.value.trim();
        
        if (termino.length < 2) {
            cargarProductos();
            return;
        }

        fetch(`/Crud_Ventas/controllers/InventarioController.php?action=buscar&termino=${termino}`)
            .then(response => response.json())
            .then(data => {
                tablaProductos.innerHTML = '';
                if (data.length === 0) {
                    tablaProductos.innerHTML = `
                        <tr>
                            <td colspan="7" class="text-center">No se encontraron productos</td>
                        </tr>
                    `;
                } else {
                    data.forEach(producto => {
                        tablaProductos.innerHTML += `
                            <tr>
                                <td>${producto.id_producto}</td>
                                <td>${producto.nombre}</td>
                                <td>$${parseFloat(producto.precio).toFixed(2)}</td>
                                <td>${producto.stock}</td>
                                <td>${producto.categoria || '-'}</td>
                                <td>
                                    <span class="badge ${producto.estado == 1 ? 'bg-success' : 'bg-secondary'}">
                                        ${producto.estado == 1 ? 'Activo' : 'Inactivo'}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning btn-editar" data-id="${producto.id_producto}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger btn-eliminar" data-id="${producto.id_producto}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });

                    // Agregar eventos a los botones
                    document.querySelectorAll('.btn-editar').forEach(btn => {
                        btn.addEventListener('click', cargarProductoEditar);
                    });

                    document.querySelectorAll('.btn-eliminar').forEach(btn => {
                        btn.addEventListener('click', eliminarProducto);
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Error al buscar productos', 'error');
            });
    }

    // Evento para nuevo producto
    document.querySelector('[data-bs-target="#modalProducto"]').addEventListener('click', function() {
        modalTitulo.textContent = 'Nuevo Producto';
        productoId.value = '';
        formProducto.reset();
    });
});