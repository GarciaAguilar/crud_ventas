document.addEventListener('DOMContentLoaded', function() {
    // Anular factura
    document.querySelectorAll('.btn-anular').forEach(btn => {
        btn.addEventListener('click', function() {
            const idFactura = this.getAttribute('data-id');
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, anular',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`../../controllers/FacturaController.php?action=anular&id=${idFactura}`)
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                location.reload();
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        });
                }
            });
        });
    });
});