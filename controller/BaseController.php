<?php
/**
 * Controlador Base
 * Funciones de utilidad, seguridad CSRF, validación, iconos SVG profesionales y sesión
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/BaseModel.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Conexión única a la base de datos
 */
function db(): PDO
{
    static $db = null;
    if ($db === null) {
        $database = new Database();
        $db = $database->conectar();
    }
    return $db;
}

/**
 * Escape seguro para evitar ataques XSS
 */
function e($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

/**
 * Genera o devuelve el token CSRF actual
 */
function csrf(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

/**
 * Valida el token CSRF recibido por POST
 */
function validarCsrf(): void
{
    $tokenPost = (string) ($_POST['csrf'] ?? '');
    if (!hash_equals(csrf(), $tokenPost)) {
        throw new RuntimeException('Token de seguridad CSRF inválido o expirado.');
    }
}

/**
 * Exige una sesión activa de usuario
 */
function exigirSesion(): void
{
    if (empty($_SESSION['uid'])) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Redirección de página
 */
function redirigir(string $ruta): void
{
    header('Location: ' . $ruta);
    exit;
}

/**
 * Guarda un mensaje flash para notificaciones
 */
function notificar(string $mensaje, string $tipo = 'notice'): void
{
    $_SESSION['flash'] = ['mensaje' => $mensaje, 'tipo' => $tipo];
}

/**
 * Validación genérica de datos recibidos
 */
function validarDatos(array $campos, array $datos): array
{
    $salida = [];
    $referencias = ['cliente_id', 'vehiculo_id', 'mecanico_id', 'cita_id', 'orden_id', 'servicio_id', 'repuesto_id'];
    $opciones = [
        'metodo' => ['efectivo', 'tarjeta_credito', 'tarjeta_debito', 'yape'],
        'estado' => ['pendiente', 'en_proceso', 'completada', 'cancelada'],
    ];

    foreach ($campos as $campo => $tipo) {
        $valor = trim((string) ($datos[$campo] ?? ''));

        if ($tipo === 'number' && $valor !== '' && (!is_numeric($valor) || (float) $valor < 0)) {
            throw new RuntimeException("El campo '{$campo}' debe ser un número válido positivo.");
        }

        if ($tipo === 'integer' && $valor !== '' && (!ctype_digit($valor) || (int) $valor < 0)) {
            throw new RuntimeException("El campo '{$campo}' debe ser un número entero válido.");
        }

        if ($tipo === 'email' && $valor !== '' && !filter_var($valor, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('El correo electrónico ingresado no es válido.');
        }

        if (in_array($campo, $referencias, true) && ($valor === '' || !ctype_digit($valor) || (int) $valor < 1)) {
            throw new RuntimeException("Debe seleccionar una opción válida para '{$campo}'.");
        }

        if ($tipo === 'datetime-local' && $valor !== '') {
            $fecha = DateTime::createFromFormat('!Y-m-d\TH:i', $valor);
            if (!$fecha || $fecha->format('Y-m-d\TH:i') !== $valor) {
                $fecha = DateTime::createFromFormat('!Y-m-d H:i:s', $valor);
            }
            if (!$fecha) {
                throw new RuntimeException("Fecha y hora inválidas para '{$campo}'.");
            }
            $valor = $fecha->format('Y-m-d H:i:s');
        }

        if ($tipo === 'date' && $valor !== '') {
            $fecha = DateTime::createFromFormat('!Y-m-d', $valor);
            if (!$fecha || $fecha->format('Y-m-d') !== $valor) {
                throw new RuntimeException("Fecha no válida para '{$campo}'.");
            }
        }

        if (isset($opciones[$campo]) && !in_array($valor, $opciones[$campo], true)) {
            throw new RuntimeException("Opción no válida seleccionada para '{$campo}'.");
        }

        $salida[$campo] = ($valor === '') ? null : $valor;
    }

    return $salida;
}

/**
 * Generador de Iconos SVG Vectoriales Minimalistas (Sin emojis)
 */
function svgIcon(string $nombre, string $clase = 'ui-icon'): string
{
    $iconos = [
        'home' => '<svg class="' . $clase . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
        'clientes' => '<svg class="' . $clase . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        'vehiculos' => '<svg class="' . $clase . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.5 2.8C2.1 10.7 2 10.9 2 11v5c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/></svg>',
        'mecanicos' => '<svg class="' . $clase . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
        'citas' => '<svg class="' . $clase . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/></svg>',
        'ordenes' => '<svg class="' . $clase . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M9 12h6"/><path d="M9 16h6"/><path d="M9 8h6"/></svg>',
        'servicios' => '<svg class="' . $clase . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 15 5-5M7 10l5 5M12 15l5-5M17 10l5 5"/><rect width="20" height="14" x="2" y="5" rx="2"/></svg>',
        'repuestos' => '<svg class="' . $clase . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>',
        'pagos' => '<svg class="' . $clase . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>',
        'reportes' => '<svg class="' . $clase . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="18" y1="20" y2="10"/><line x1="12" x2="12" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="14"/></svg>',
        'plus' => '<svg class="' . $clase . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>',
        'edit' => '<svg class="' . $clase . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>',
        'trash' => '<svg class="' . $clase . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>',
        'check' => '<svg class="' . $clase . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
        'alert' => '<svg class="' . $clase . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>',
        'printer' => '<svg class="' . $clase . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>',
        'arrow-right' => '<svg class="' . $clase . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" x2="19" y1="12" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>',
        'logout' => '<svg class="' . $clase . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>',
        'dollar' => '<svg class="' . $clase . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>',
        'clock' => '<svg class="' . $clase . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
        'save' => '<svg class="' . $clase . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>'
    ];

    return $iconos[$nombre] ?? '';
}

/**
 * Catálogo centralizado de módulos del Taller Motor Express
 */
function modulos(): array
{
    return [
        'clientes' => [
            'nombre' => 'Clientes',
            'url' => 'clientes.php',
            'icono' => 'clientes',
            'desc' => 'Gestión y directorio de propietarios de vehículos.',
            'imagen' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&q=80'
        ],
        'vehiculos' => [
            'nombre' => 'Vehículos',
            'url' => 'vehiculos.php',
            'icono' => 'vehiculos',
            'desc' => 'Registro de placa, marca, modelo y año de fabricación.',
            'imagen' => 'https://images.unsplash.com/photo-1486006920555-c77dce18193b?w=600&q=80'
        ],
        'mecanicos' => [
            'nombre' => 'Mecánicos',
            'url' => 'mecanicos.php',
            'icono' => 'mecanicos',
            'desc' => 'Especialistas técnicos automotrices y asignación.',
            'imagen' => 'https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?w=600&q=80'
        ],
        'citas' => [
            'nombre' => 'Citas de Mantenimiento',
            'url' => 'citas.php',
            'icono' => 'citas',
            'desc' => 'Programación de atención y cronograma de ingresos.',
            'imagen' => 'https://images.unsplash.com/photo-1517524008697-84bbe3c3fd98?w=600&q=80'
        ],
        'ordenes' => [
            'nombre' => 'Órdenes de Trabajo',
            'url' => 'ordenes.php',
            'icono' => 'ordenes',
            'desc' => 'Diagnóstico de fallas, repuestos, servicios y avance.',
            'imagen' => 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=600&q=80'
        ],
        'servicios' => [
            'nombre' => 'Servicios',
            'url' => 'servicios.php',
            'icono' => 'servicios',
            'desc' => 'Catálogo de servicios de mantenimiento y precios.',
            'imagen' => 'https://images.unsplash.com/photo-1487754180451-c456f719a1fc?w=600&q=80'
        ],
        'repuestos' => [
            'nombre' => 'Repuestos y Stock',
            'url' => 'repuestos.php',
            'icono' => 'repuestos',
            'desc' => 'Inventario de autopartes, repuestos y costos.',
            'imagen' => 'https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?w=600&q=80'
        ],
        'pagos' => [
            'nombre' => 'Pagos y Cobros',
            'url' => 'pagos.php',
            'icono' => 'pagos',
            'desc' => 'Efectivo, Tarjetas y Yape vinculados a órdenes.',
            'imagen' => 'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?w=600&q=80'
        ],
        'reportes' => [
            'nombre' => 'Reportes y Métricas',
            'url' => 'reportes.php',
            'icono' => 'reportes',
            'desc' => 'Ingresos, servicios concluidos y vehículos atendidos.',
            'imagen' => 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=600&q=80'
        ]
    ];
}
