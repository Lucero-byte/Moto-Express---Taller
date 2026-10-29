# Sistema de Gestión para Taller de Reparación de Vehículos: "Motor Express"

Proyecto web desarrollado en **PHP** bajo la arquitectura **MVC (Modelo - Vista - Controlador)** simple, modular y didáctica, diseñado para la gestión integral del taller automotriz **"Motor Express"** (Caso SENATI).

---

## 🏎️ Características del Sistema

1. **Arquitectura MVC Simple y Limpia**:
   - `model/`: Clases entidad (`Cliente`, `Vehiculo`, `Mecanico`, `Cita`, `OrdenTrabajo`, `Servicio`, `Repuesto`, `Pago`, `Usuario`) con herencia de `BaseModel` y consultas preparadas con PDO.
   - `controller/`: Controladores para procesar lógica, validación y redirección (`ClienteController`, `VehiculoController`, etc.).
   - `views/`: Vistas organizadas por módulo (`index.php`, `form.php`, `lista.php`), layout maestro y autenticación.
   - `config/`: Archivo único `database.php` con conexión PDO segura.
   - `database/`: Script SQL listo para importar (`motor_express.sql`).

2. **Módulos y Operaciones CRUD**:
   - **Clientes**: Registro y administración de propietarios.
   - **Vehículos**: Placa, marca, modelo, año de fabricación y vinculación con cliente.
   - **Mecánicos**: Nombre y especialidad técnica.
   - **Citas para Mantenimiento**: Programación de fecha/hora, vehículo y mecánico asignado.
   - **Órdenes de Trabajo**: Registro de diagnósticos de fallas mecánicas, estado y vinculación de servicios y repuestos.
   - **Servicios de Reparación**: Catálogo de mano de obra y precios.
   - **Control de Repuestos**: Inventario de autopartes, precios unitarios y control de stock.
   - **Pagos**: Registro de cobros mediante **Efectivo, Tarjeta de Crédito, Tarjeta de Débito y Yape**.

3. **Reportes Requeridos**:
   - 🛠️ **Servicios realizados**: Conteo de ejecuciones e ingresos generados.
   - 🚗 **Vehículos atendidos**: Historial de atenciones, propietario y fecha de última visita.
   - ⏳ **Reparaciones pendientes**: Órdenes en estado pendiente o en proceso con mecánico y diagnóstico.
   - 💰 **Ingresos obtenidos**: Total global y desglose por método de pago (Efectivo, Tarjetas, Yape).

4. **Interfaz y Diseño Solicitado**:
   - **Header de dos niveles**:
     - *Nivel 1 (Superior)*: Barra institucional con nombre del taller, usuario conectado y botón de cierre de sesión.
     - *Nivel 2 (Inferior)*: Navbar horizontal de navegación rápida.
   - **Menú vertical derecho**: Panel lateral (`aside`) con accesos rápidos directos y estado operativo del taller.
   - **Dashboard**: Panel principal con tarjetas de métricas (KPIs) y tarjetas interactivas con imágenes automotrices.
   - **Footer**: Pie de página informativo.
   - **Diseño Adaptable (Responsive)**: Compatible con computadoras, tabletas y teléfonos móviles.
   - **Seguridad**:
     - Sentencias SQL preparadas (`PDO::prepare`) que protegen contra inyección SQL.
     - Validación y escape con `htmlspecialchars` contra ataques XSS.
     - Tokens CSRF en todos los formularios POST.
     - Hasheo seguro de contraseñas con `password_hash()` (Bcrypt).

---

## 🚀 Guía de Instalación en XAMPP

1. **Copiar el proyecto**:
   Asegúrate de que la carpeta del proyecto esté ubicada en:
   ```text
   C:\xampp\htdocs\taller_automotriz\
   ```

2. **Iniciar servicios en XAMPP**:
   - Abre el **XAMPP Control Panel**.
   - Inicia los módulos **Apache** y **MySQL**.

3. **Crear e importar la base de datos**:
   - Ingresa a phpMyAdmin: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
   - Haz clic en la pestaña **Importar** (o en la pestaña **SQL**).
   - Selecciona el archivo:
     ```text
     c:\xampp\htdocs\taller_automotriz\database\motor_express.sql
     ```
   - Haz clic en **Continuar** / **Ejecutar**.
   *(El script creará automáticamente la base de datos `motor_express`, todas las tablas con sus relaciones foráneas y datos iniciales de prueba)*.

4. **Abrir el sistema en el navegador**:
   - Ingresa a la URL:
     ```text
     http://localhost/taller_automotriz/
     ```

---

## 🔑 Credenciales de Acceso

El script SQL incluye un usuario administrador de prueba precargado:

- **Correo electrónico:** `admin@motorexpress.com`
- **Contraseña:** `admin12345`

*También puedes registrar cualquier nuevo usuario con tu nombre, correo y contraseña usando el botón **"Registrarme"** en la pantalla de inicio de sesión.*

---

## 📁 Estructura del Proyecto

```text
taller_automotriz/
│
├── config/
│   └── database.php                # Conexión PDO a MySQL
│
├── database/
│   └── motor_express.sql           # Script SQL con estructura y datos de prueba
│
├── assets/
│   └── css/
│       └── style.css               # Estilos responsivos, header de 2 niveles y menú derecho
│
├── model/
│   ├── BaseModel.php               # Clase base abstracta con métodos CRUD y PDO
│   ├── Usuario.php                 # Modelo de usuarios y login
│   ├── Cliente.php                 # Modelo de clientes
│   ├── Vehiculo.php                # Modelo de vehículos
│   ├── Mecanico.php                # Modelo de mecánicos
│   ├── Cita.php                    # Modelo de citas
│   ├── OrdenTrabajo.php            # Modelo de órdenes, servicios y repuestos vinculados
│   ├── Servicio.php                # Modelo de servicios
│   ├── Repuesto.php                # Modelo de repuestos e inventario
│   └── Pago.php                    # Modelo de pagos
│
├── controller/
│   ├── BaseController.php          # Validaciones, CSRF, sesiones y helpers
│   ├── AuthController.php          # Login, registro y cierre de sesión
│   ├── ClienteController.php       # Controlador CRUD Clientes
│   ├── VehiculoController.php      # Controlador CRUD Vehículos
│   ├── MecanicoController.php      # Controlador CRUD Mecánicos
│   ├── CitaController.php          # Controlador CRUD Citas
│   ├── OrdenTrabajoController.php  # Controlador CRUD Órdenes de Trabajo
│   ├── ServicioController.php      # Controlador CRUD Servicios
│   ├── RepuestoController.php      # Controlador CRUD Repuestos
│   ├── PagoController.php          # Controlador CRUD Pagos
│   └── ReporteController.php       # Controlador de Reportes y KPIs
│
├── views/
│   ├── layout.php                  # Layout con Header de 2 niveles, aside derecho y footer
│   ├── login.php                   # Pantalla de acceso y registro
│   ├── logout.php                  # Cierre de sesión
│   ├── home.php                    # Dashboard con tarjetas e imágenes
│   ├── clientes.php & clientes/    # Vistas CRUD Clientes
│   ├── vehiculos.php & vehiculos/  # Vistas CRUD Vehículos
│   ├── mecanicos.php & mecanicos/  # Vistas CRUD Mecánicos
│   ├── citas.php & citas/          # Vistas CRUD Citas
│   ├── ordenes.php & ordenes/      # Vistas CRUD Órdenes de trabajo
│   ├── servicios.php & servicios/  # Vistas CRUD Servicios
│   ├── repuestos.php & repuestos/  # Vistas CRUD Repuestos
│   ├── pagos.php & pagos/          # Vistas CRUD Pagos
│   └── reportes.php & reportes/    # Vistas de los 4 reportes
│
└── index.php                       # Redirección inicial hacia el login
```

