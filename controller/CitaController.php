<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Cita.php';

class CitaController
{
    public Cita $modelo;
    public string $titulo = 'Programación de Citas de Mantenimiento';
    public array $campos = [
        'vehiculo_id' => 'vehiculo_id',
        'mecanico_id' => 'mecanico_id',
        'fecha' => 'datetime-local',
    ];

    public function __construct()
    {
        $this->modelo = new Cita(db());
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
                notificar('Cita cancelada y eliminada.');
            } else {
                if (empty($_POST['fecha'])) {
                    throw new RuntimeException('Debe programar la fecha y hora de la cita.');
                }
                $datos = validarDatos($this->campos, $_POST);
                $this->modelo->guardar($datos, $id);
                notificar($id > 0 ? 'Cita reprogramada con éxito.' : 'Cita agendada correctamente.');
            }

            redirigir('citas.php');
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return 'No se pudo guardar la cita. Verifique vehículo y mecánico seleccionados.';
        } catch (Throwable $ex) {
            return $ex->getMessage();
        }
    }
}

