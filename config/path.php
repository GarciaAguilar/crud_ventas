<?php
// Usamos realpath() para obtener la ruta absoluta sin ../
define('ROOT_PATH', realpath(dirname(__DIR__)));
define('CONTROLLERS_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'controllers');
define('MODELS_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'models');
define('VIEWS_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'views');
define('PUBLIC_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'public');
define('CONFIG_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'config');
define('INCLUDES_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'includes');

// URLs base para el navegador
define('BASE_URL', '/');
define('PUBLIC_URL', BASE_URL . 'public/');
define('ASSETS_URL', PUBLIC_URL . 'assets/');
define('CSS_URL', ASSETS_URL . 'css/');
define('JS_URL', ASSETS_URL . 'js/');
define('CONTROLLERS_URL', BASE_URL . 'controllers/');
define('VIEWS_URL', BASE_URL . 'views/');

// URLs específicas para módulos
define('VENTAS_URL', PUBLIC_URL . 'ventas/');
define('FACTURAS_URL', PUBLIC_URL . 'facturas/');
define('INVENTARIO_URL', PUBLIC_URL . 'inventario/');

// Función helper para incluir archivos
function requireFrom($basePath, $relativePath) {
    $fullPath = $basePath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
    if (file_exists($fullPath)) {
        require_once $fullPath;
    } else {
        throw new Exception("Archivo no encontrado: $fullPath");
    }
}

// Función helper para generar URLs
function url($path = '') {
    return BASE_URL . ltrim($path, '/');
}

// Función helper para generar URLs de assets
function asset($path = '') {
    return ASSETS_URL . ltrim($path, '/');
}
?>