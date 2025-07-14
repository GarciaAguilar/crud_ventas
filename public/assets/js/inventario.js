document.addEventListener('DOMContentLoaded', function() {
    const tablaProductos = document.getElementById('tablaProductos');
    const modalProducto = new bootstrap.Modal(document.getElementById('modalProducto'));
    const formProducto = document.getElementById('formProducto');
    const btnGuardar = document.getElementById('btnGuardar');
    const modalTitulo = document.getElementById('modalTitulo');
    const productoId = document.getElementById('productoId');
    const buscarProducto = document.getElementById('buscarProducto');
    const btnBuscar = document.getElementById('btnBuscar');
    
    // Variables para optimizar la búsqueda
    let productosOriginal = [];
    let timeoutBusqueda = null;

    // Cargar productos al iniciar
    cargarProductos();

    // Configurar formulario
    btnGuardar.addEventListener('click', guardarProducto);
    
    // Optimizar búsqueda en tiempo real
    buscarProducto.addEventListener('input', function() {
        const termino = this.value.trim().toLowerCase();
        
        // Limpiar timeout anterior
        if (timeoutBusqueda) {
            clearTimeout(timeoutBusqueda);
        }
        
        // Establecer nuevo timeout para evitar muchas búsquedas
        timeoutBusqueda = setTimeout(() => {
            if (termino.length === 0) {
                // Si no hay término, mostrar todos los productos
                mostrarProductos(productosOriginal);
            } else if (termino.length >= 1) {
                // Filtrar productos localmente para búsqueda rápida
                const productosFiltrados = productosOriginal.filter(producto => 
                    producto.nombre.toLowerCase().includes(termino) ||
                    (producto.categoria && producto.categoria.toLowerCase().includes(termino)) ||
                    producto.id_producto.toString().includes(termino)
                );
                mostrarProductos(productosFiltrados);
            }
        }, 200); // Esperar 200ms antes de filtrar
    });
    
    // Mantener funcionalidad del botón de búsqueda para búsquedas específicas
    btnBuscar.addEventListener('click', function() {
        const termino = buscarProducto.value.trim();
        if (termino.length >= 2) {
            buscarProductosServidor(termino);
        } else {
            cargarProductos();
        }
    });
    
    // Buscar con Enter
    buscarProducto.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            const termino = this.value.trim();
            if (termino.length >= 2) {
                buscarProductosServidor(termino);
            } else {
                cargarProductos();
            }
        }
    });

    // Función para cargar productos
    function cargarProductos() {
        fetch(window.CONTROLLERS_URL + 'InventarioController.php?action=index')
            .then(response => response.json())
            .then(data => {
                productosOriginal = data; // Guardar productos originales
                mostrarProductos(data);
            })
            .catch(error => {
                console.error('Error cargando productos:', error);
                tablaProductos.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-danger">Error al cargar productos</td>
                    </tr>
                `;
            });
    }
    
    // Función optimizada para mostrar productos
    function mostrarProductos(productos) {
        tablaProductos.innerHTML = '';
        
        if (productos.length === 0) {
            tablaProductos.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center">No se encontraron productos</td>
                </tr>
            `;
            return;
        }
        
        productos.forEach(producto => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${producto.id_producto}</td>
                <td>${producto.nombre}</td>
                <td>$${parseFloat(producto.precio).toFixed(2)}</td>
                <td>
                    <span class="badge ${producto.stock > 10 ? 'bg-success' : producto.stock > 0 ? 'bg-warning' : 'bg-danger'}">
                        ${producto.stock}
                    </span>
                </td>
                <td>${producto.categoria || '-'}</td>
                <td>
                    <span class="badge ${producto.estado == 1 ? 'bg-success' : 'bg-secondary'}">
                        ${producto.estado == 1 ? 'Activo' : 'Inactivo'}
                    </span>
                </td>
                <td>
                    <button class="btn btn-sm btn-warning btn-editar" data-id="${producto.id_producto}" title="Editar">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-eliminar" data-id="${producto.id_producto}" title="Eliminar">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            tablaProductos.appendChild(tr);
        });
        
        // Configurar event listeners para los botones
        configurarEventListeners();
    }
    
    // Función para configurar event listeners de los botones
    function configurarEventListeners() {
        // Event listeners para botones de editar
        document.querySelectorAll('.btn-editar').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                editarProducto(id);
            });
        });
        
        // Event listeners para botones de eliminar
        document.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                eliminarProducto(id);
            });
        });
    }
    
    // Función para búsqueda en servidor (para búsquedas más específicas)
    function buscarProductosServidor(termino) {
        fetch(window.CONTROLLERS_URL + `InventarioController.php?action=buscar&termino=${encodeURIComponent(termino)}`)
            .then(response => response.json())
            .then(data => {
                productosOriginal = data; // Actualizar productos originales
                mostrarProductos(data);
            })
            .catch(error => {
                console.error('Error buscando productos:', error);
                tablaProductos.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-danger">Error al buscar productos</td>
                    </tr>
                `;
            });
    }

    // Función para guardar producto
    function guardarProducto() {
        const formData = new FormData(formProducto);
        const action = productoId.value ? 'actualizar' : 'crear';
        
        fetch(window.CONTROLLERS_URL + `InventarioController.php?action=${action}`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                modalProducto.hide();
                formProducto.reset();
                cargarProductos();
                Swal.fire('Éxito', result.message || 'Producto guardado correctamente', 'success');
            } else {
                Swal.fire('Error', result.message || 'Error al guardar el producto', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Error al guardar el producto', 'error');
        });
    }

    // Función para editar producto
    function editarProducto(id) {
        fetch(window.CONTROLLERS_URL + `InventarioController.php?action=editar&id=${id}`)
            .then(response => response.json())
            .then(producto => {
                if (producto) {
                    // Llenar el formulario con los datos del producto
                    document.getElementById('productoId').value = producto.id_producto;
                    document.getElementById('nombre').value = producto.nombre;
                    document.getElementById('descripcion').value = producto.descripcion || '';
                    document.getElementById('precio').value = producto.precio;
                    document.getElementById('costo').value = producto.costo || '';
                    document.getElementById('stock').value = producto.stock;
                    document.getElementById('categoria').value = producto.categoria || '';
                    document.getElementById('proveedor').value = producto.proveedor || '';
                    document.getElementById('estado').value = producto.estado;
                    
                    modalTitulo.textContent = 'Editar Producto';
                    modalProducto.show();
                } else {
                    Swal.fire('Error', 'No se pudo cargar el producto', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Error al cargar el producto', 'error');
            });
    }

    // Función para eliminar producto
    function eliminarProducto(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esta acción!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('id', id);
                
                fetch(window.CONTROLLERS_URL + 'InventarioController.php?action=eliminar', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        Swal.fire('¡Eliminado!', 'El producto ha sido eliminado.', 'success');
                        cargarProductos();
                    } else {
                        Swal.fire('Error', result.message || 'No se pudo eliminar el producto', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Error al eliminar el producto', 'error');
                });
            }
        });
    }

    // Limpiar formulario al abrir modal para nuevo producto
    document.querySelector('[data-bs-target="#modalProducto"]').addEventListener('click', function() {
        formProducto.reset();
        productoId.value = '';
        modalTitulo.textContent = 'Nuevo Producto';
    });

});
