<?php
// Usamos realpath() para obtener la ruta absoluta sin ../
define('ROOT_PATH', realpath(dirname(__DIR__)));
define('CONTROLLERS_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'controllers');
define('MODELS_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'models');
define('VIEWS_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'views');
define('PUBLIC_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'public');

// Función helper para incluir archivos
function requireFrom($basePath, $relativePath) {
    $fullPath = $basePath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
    if (file_exists($fullPath)) {
        require_once $fullPath;
    } else {
        throw new Exception("Archivo no encontrado: $fullPath");
    }
}
?>