document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    const productosSeleccionados = [];
    let subtotal = 0;
    const modalPago = new bootstrap.Modal(document.getElementById('modalPago'));
    
    // Elementos del DOM
    const buscarCliente = document.getElementById('buscarCliente');
    const resultadosCliente = document.getElementById('resultadosCliente');
    const infoCliente = document.getElementById('infoCliente');
    const btnProcesarVenta = document.getElementById('btnProcesarVenta');
    const modalMontoRecibido = document.getElementById('modal_monto_recibido');
    const modalCambio = document.getElementById('modal_cambio');
    
    // Buscar cliente
    buscarCliente.addEventListener('input', function() {
        if(this.value.length > 2) {
            fetch(`/Crud_Ventas/controllers/ClienteController.php?action=buscar&termino=${encodeURIComponent(this.value)}`)
                .then(response => response.json())
                .then(data => {
                    resultadosCliente.innerHTML = '';
                    if(data.length > 0) {
                        resultadosCliente.style.display = 'block';
                        data.forEach(cliente => {
                            const item = document.createElement('button');
                            item.type = 'button';
                            item.className = 'list-group-item list-group-item-action';
                            item.innerHTML = `
                                <strong>${cliente.nombre}</strong><br>
                                <small>${cliente.telefono || 'Sin teléfono'} | ${cliente.email || 'Sin email'}</small>
                            `;
                            item.addEventListener('click', function() {
                                document.getElementById('id_cliente').value = cliente.id_cliente;
                                infoCliente.innerHTML = `
                                    <strong>Cliente seleccionado:</strong> ${cliente.nombre}<br>
                                    <small>${cliente.direccion || 'Sin dirección'}</small>
                                `;
                                infoCliente.style.display = 'block';
                                resultadosCliente.style.display = 'none';
                                buscarCliente.value = '';
                            });
                            resultadosCliente.appendChild(item);
                        });
                    } else {
                        resultadosCliente.innerHTML = '<div class="list-group-item">No se encontraron clientes</div>';
                        resultadosCliente.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error buscando clientes:', error);
                    resultadosCliente.innerHTML = '<div class="list-group-item text-danger">Error al buscar clientes</div>';
                    resultadosCliente.style.display = 'block';
                });
        } else {
            resultadosCliente.style.display = 'none';
        }
    });
    
    // Buscar producto
    document.getElementById('buscarProducto').addEventListener('input', function() {
        const termino = this.value.toLowerCase();
        document.querySelectorAll('.producto-item').forEach(item => {
            const nombre = item.getAttribute('data-nombre').toLowerCase();
            item.style.display = nombre.includes(termino) ? 'block' : 'none';
        });
    });
    
    // Seleccionar productos
    document.getElementById('productosDisponibles').addEventListener('click', function(e) {
        const item = e.target.closest('.producto-item');
        if(item) {
            const id = item.getAttribute('data-id');
            const nombre = item.getAttribute('data-nombre');
            const precio = parseFloat(item.getAttribute('data-precio'));
            const stock = parseInt(item.getAttribute('data-stock'));
            
            // Verificar si ya está agregado
            const existente = productosSeleccionados.find(p => p.id_producto == id);
            
            if(existente) {
                if(existente.cantidad < stock) {
                    existente.cantidad++;
                } else {
                    Swal.fire('Stock insuficiente', `No hay suficiente stock para ${nombre}`, 'warning');
                    return;
                }
            } else {
                productosSeleccionados.push({
                    id_producto: id,
                    nombre: nombre,
                    precio_unitario: precio,
                    cantidad: 1,
                    stock: stock
                });
            }
            
            actualizarTablaProductos();
        }
    });
    
    // Actualizar tabla de productos seleccionados
    function actualizarTablaProductos() {
        const tbody = document.querySelector('#tablaProductosVenta tbody');
        tbody.innerHTML = '';
        
        subtotal = 0;
        
        productosSeleccionados.forEach((producto, index) => {
            const subtotalProducto = producto.precio_unitario * producto.cantidad;
            subtotal += subtotalProducto;
            
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${producto.nombre}</td>
                <td>
                    <input type="number" class="form-control form-control-sm cantidad-producto" 
                           min="1" max="${producto.stock}" value="${producto.cantidad}"
                           data-index="${index}">
                </td>
                <td>$${producto.precio_unitario.toFixed(2)}</td>
                <td>$${subtotalProducto.toFixed(2)}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger btn-eliminar-producto" 
                            data-index="${index}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
        
        // Calcular totales con 13% de IVA
        const iva = subtotal * 0.13;
        const total = subtotal + iva;
        
        document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('iva').textContent = `$${iva.toFixed(2)}`;
        document.getElementById('total').textContent = `$${total.toFixed(2)}`;
        
        // Actualizar campo oculto con los productos
        document.getElementById('productosSeleccionados').value = JSON.stringify(
            productosSeleccionados.map(p => ({
                id_producto: p.id_producto,
                cantidad: p.cantidad
            }))
        );
        
        // Habilitar/deshabilitar botón de procesar
        btnProcesarVenta.disabled = productosSeleccionados.length === 0;
    }
    
    // Event delegation para cambios en cantidad y eliminar
    document.querySelector('#tablaProductosVenta').addEventListener('click', function(e) {
        if(e.target.classList.contains('btn-eliminar-producto') || 
           e.target.closest('.btn-eliminar-producto')) {
            const index = e.target.getAttribute('data-index') || 
                          e.target.closest('.btn-eliminar-producto').getAttribute('data-index');
            productosSeleccionados.splice(index, 1);
            actualizarTablaProductos();
        }
    });
    
    document.querySelector('#tablaProductosVenta').addEventListener('change', function(e) {
        if(e.target.classList.contains('cantidad-producto')) {
            const index = e.target.getAttribute('data-index');
            const nuevaCantidad = parseInt(e.target.value);
            
            if(nuevaCantidad > 0 && nuevaCantidad <= productosSeleccionados[index].stock) {
                productosSeleccionados[index].cantidad = nuevaCantidad;
                actualizarTablaProductos();
            } else {
                Swal.fire('Cantidad inválida', `La cantidad debe ser entre 1 y ${productosSeleccionados[index].stock}`, 'warning');
                e.target.value = productosSeleccionados[index].cantidad;
            }
        }
    });
    
    // Procesar venta (mostrar modal de pago)
    btnProcesarVenta.addEventListener('click', function() {
        if(productosSeleccionados.length === 0) {
            Swal.fire('Error', 'Debe agregar al menos un producto a la venta', 'error');
            return;
        }
        
        // Calcular con 13% de IVA
        const iva = subtotal * 0.13;
        const total = subtotal + iva;
        
        // Llenar datos en el modal
        document.getElementById('modal_id_cliente').value = document.getElementById('id_cliente').value;
        document.getElementById('modal_productos').value = document.getElementById('productosSeleccionados').value;
        document.getElementById('modal_subtotal').value = `$${subtotal.toFixed(2)}`;
        document.getElementById('modal_iva').value = `$${iva.toFixed(2)}`;
        document.getElementById('modal_total').value = `$${total.toFixed(2)}`;
        
        // Configurar validación del monto recibido
        modalMontoRecibido.min = total;
        modalMontoRecibido.value = total.toFixed(2);
        modalCambio.value = '$0.00';
        
        // Mostrar modal
        modalPago.show();
    });
    
    // Calcular cambio en tiempo real
    modalMontoRecibido.addEventListener('input', function() {
        const total = parseFloat(document.getElementById('modal_total').value.replace('$', ''));
        const recibido = parseFloat(this.value) || 0;
        const cambio = recibido - total;
        
        const cambioTexto = cambio >= 0 ? `$${cambio.toFixed(2)}` : 'Monto insuficiente';
        document.getElementById('modal_cambio').value = cambioTexto;
        document.getElementById('modal_cambio_hidden').value = cambio >= 0 ? cambio.toFixed(2) : '0';
    });
    
    // Enviar formulario de pago
    document.getElementById('formPago').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const total = parseFloat(document.getElementById('modal_total').value.replace('$', ''));
        const recibido = parseFloat(modalMontoRecibido.value);
        
        if(recibido < total) {
            Swal.fire('Error', 'El monto recibido no puede ser menor al total', 'error');
            return;
        }
        
        // Mostrar confirmación
        Swal.fire({
            title: '¿Confirmar venta?',
            text: 'Esta acción registrará la venta y el pago',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, confirmar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.isConfirmed) {
                this.submit();
            }
        });
    });
});