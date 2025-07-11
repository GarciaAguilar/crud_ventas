let countdown = 3;
let autoOpenTimer;
let countdownTimer;

function startAutoOpen() {
    const countdownElement = document.getElementById('countdown');
    const alertElement = document.getElementById('autoOpenAlert');
    
    countdownTimer = setInterval(() => {
        countdown--;
        countdownElement.textContent = countdown;
        
        if (countdown <= 0) {
            clearInterval(countdownTimer);
            alertElement.style.display = 'none';
            // Abrir la factura PDF - La URL se establecerá desde PHP
            if (window.facturaUrl) {
                window.open(window.facturaUrl, '_blank');
            }
        }
    }, 1000);
}

function cancelAutoOpen() {
    clearInterval(countdownTimer);
    document.getElementById('autoOpenAlert').style.display = 'none';
}

// Iniciar el countdown cuando la página se carga
document.addEventListener('DOMContentLoaded', startAutoOpen);
