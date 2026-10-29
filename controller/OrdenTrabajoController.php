<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/OrdenTrabajo.php';

class OrdenTrabajoController
{
    public OrdenTrabajo $modelo;
    public string $titulo = 'Órdenes de Trabajo y Reparaciones';
    public array $campos = [
        'cita_id' => 'cita_id',
        'diagnostico' => 'textarea',
        'estado' => 'estado',
        'fecha' => 'date',
    ];

    public function __construct()
    {
        $this->modelo = new OrdenTrabajo(db());
    }

    public function procesar(): string
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return '';
        }

        try {
            validarCsrf();
            $id = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: 0;
            $accion = $_POST['accion'] ?? '';

            if ($accion === 'eliminar') {
                $this->modelo->eliminar($id);
                notificar('Orden de trabajo eliminada.');
            } else {
                if (empty($_POST['cita_id'])) {
                    throw new RuntimeException('Debe asociar una cita previa.');
                }
                if (empty(trim($_POST['diagnostico'] ?? ''))) {
                    throw new RuntimeException('Debe ingresar el diagnóstico de la falla mecánica.');
                }
                if (empty($_POST['fecha'])) {
                    $_POST['fecha'] = date('Y-m-d');
                }

                $datos = validarDatos($this->campos, $_POST);
                $ordenId = $this->modelo->guardar($datos, $id);

                // Servicios seleccionados
                $servicios = $_POST['servicios'] ?? [];
                if (!is_array($servicios)) {
                    $servicios = [];
                }
                $this->modelo->sincronizarServicios($ordenId, $servicios);

                // Repuestos seleccionados con cantidades
                $repuestosPost = $_POST['repuestos'] ?? [];
                $repuestosProcesados = [];
                if (is_array($repuestosPost)) {
                    foreach ($repuestosPost as $repId => $datosRep) {
                        if (!empty($datosRep['activo'])) {
                            $repuestosProcesados[] = [
                                'id' => (int)$repId,
                                'cantidad' => max(1, (int)($datosRep['cantidad'] ?? 1))
                            ];
                        }
                    }
                }
                $this->modelo->sincronizarRepuestos($ordenId, $repuestosProcesados);

                notificar($id > 0 ? 'Orden de trabajo actualizada con éxito.' : 'Orden de trabajo generada correctamente.');
            }

            redirigir('ordenes.php');
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return 'Error de base de datos al guardar la orden de trabajo.';
        } catch (Throwable $ex) {
            return $ex->getMessage();
        }
    }
}

