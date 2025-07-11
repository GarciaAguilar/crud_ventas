# 🛒 Sistema de Gestión de Ventas, Inventario y Facturación

## 🛠️ Tecnologías Utilizadas

- **Backend:** PHP 8.2
- **Base de Datos:** MariaDB
- **Frontend:** HTML5, CSS3, Bootstrap 5, JavaScript
- **Iconos:** Bootstrap Icons
- **PDF:** FPDF Library
- **Servidor:** XAMPP

## 📦 Estructura del Proyecto

```
Crud_Ventas/
├── config/
│   ├── database.php          # Configuración de base de datos
│   └── path.php              # Configuración de rutas
├── controllers/
│   ├── ClienteController.php
│   ├── FacturaController.php
│   ├── InventarioController.php
│   └── VentaController.php
├── models/
│   ├── BaseModel.php
│   ├── Cliente.php
│   ├── Factura.php
│   ├── Inventario.php
│   └── Venta.php
├── views/
│   ├── dashboard.php
│   ├── inventario.php
│   ├── facturas/
│   └── ventas/
├── public/
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── fpdf/
│   ├── facturas/
│   ├── inventario/
│   └── ventas/
└── includes/
    ├── header.php
    └── footer.php
```

## 🚀 Instalación y Configuración

### Pasos de Instalación

1. **Clonar o descargar el proyecto**
   ```bash
   git clone [url-del-repositorio]
   # O descargar y extraer en c:\xampp\htdocs\
   ```

2. **Configurar la base de datos**
   - Crear una nueva base de datos llamada `crud_ventas`
   - Importar el archivo SQL que se encuentra dentro de la carpeta config, inclui 2 archivos sql uno generado con navicat para mariadb y el otro con phpmyadmin, ambos contienen la misma información

3. **Configurar conexión de base de datos**
   ```php
   // config/database.php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'crud_ventas');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

4. **Acceder al sistema**
   - Abrir navegador: http://localhost/Crud_Ventas/


## 📊 Datos de Prueba


### Productos de Inventario para ingresar

💻 1. Laptop Gamer
Nombre: NitroForce G5

Descripción: Laptop con procesador Intel i7, 16GB RAM, SSD 512GB, GPU RTX 4060. Perfecta para juegos exigentes.

Precio: 1299.00

Proveedor: TecnoDigital SA

Costo: 980.00

Stock: 40

Categoría: Electrónica

Estado: Activo

🪑 2. Silla Ergonómica
Nombre: Silla ErgoPlus

Descripción: Silla de oficina con respaldo ajustable, soporte lumbar y ruedas anti-deslizantes.

Precio: 175.00

Proveedor: Muebles Flex

Costo: 120.00

Stock: 75

Categoría: Muebles

Estado: Activo

### Clientes que estan en la bd para seleeccionar en venta

- Ana Torres
- Pedro Sánchez
- María López
- Carlos Ruiz
- Lucía Mendoza



## 🔧 Uso del Sistema

### 1. Dashboard Principal
- Acceder desde: `http://localhost/Crud_Ventas/views/dashboard.php`
- Vista general del sistema con navegación principal

### 2. Gestión de Inventario
- **URL:** `/Crud_Ventas/views/inventario.php`
- **Funciones:**
  - Ver lista de productos
  - Agregar nuevos productos
  - Editar información de productos
  - Control de stock

### 3. Proceso de Ventas
- **URL:** `/Crud_Ventas/public/ventas/`
- **Flujo completo:**
  1. Crear nueva venta
  2. Seleccionar cliente (opcional)
  3. Agregar productos al carrito
  4. Calcular totales automáticamente
  5. Procesar pago
  6. Generar factura automáticamente

### 4. Sistema de Facturación
- **URL:** `/Crud_Ventas/public/facturas/`
- **Características:**
  - Numeración automática: FAC-001-2025, FAC-002-2025, etc.
  - Reinicio de numeración cada año
  - Generación de PDF automática
  - Visualización y descarga de facturas

## 📄 Estados del Sistema

### Estados de Ventas
- **1:** Pendiente de Pago
- **2:** Completada (Pagada) por defecto
- **3:** Anulada

### Estados de Facturas
- **1:** Válida por defecto
- **0:** Anulada

## 🎯 Flujo de Trabajo Típico

1. **Agregar Productos al Inventario**
   - Registrar productos con precios y stock

2. **Crear una Venta**
   - Seleccionar cliente
   - Agregar productos desde el inventario
   - El sistema calcula IVA (13%) automáticamente

3. **Procesar Pago**
   - Registrar monto recibido
   - Calcular cambio automáticamente
   - Actualizar estado a "Completada"

4. **Facturación Automática**
   - Se genera factura automáticamente al completar venta
   - Numeración secuencial por año
   - PDF disponible inmediatamente

5. **Gestión Post-Venta**
   - Ver detalles de venta
   - Reimprimir facturas
   - Consultar historial

