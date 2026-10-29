<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Pago.php';

class PagoController
{
    public Pago $modelo;
    public string $titulo = 'Registro y Control de Pagos';
    public array $campos = [
        'orden_id' => 'orden_id',
        'monto' => 'number',
        'metodo' => 'metodo',
        'fecha' => 'date',
    ];

    public function __construct()
    {
        $this->modelo = new Pago(db());
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
                notificar('Registro de pago eliminado.');
            } else {
                if (empty($_POST['orden_id'])) {
                    throw new RuntimeException('Debe asociar una orden de trabajo al pago.');
                }
                $monto = (float)($_POST['monto'] ?? 0);
                if ($monto <= 0) {
                    throw new RuntimeException('El monto a cobrar debe ser mayor a 0.00.');
                }
                if (empty($_POST['fecha'])) {
                    $_POST['fecha'] = date('Y-m-d');
                }

                $datos = validarDatos($this->campos, $_POST);
                $this->modelo->guardar($datos, $id);
                notificar($id > 0 ? 'Pago actualizado.' : 'Pago registrado satisfactoriamente.');
            }

            redirigir('pagos.php');
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return 'Error al procesar el pago. Verifique los datos ingresados.';
        } catch (Throwable $ex) {
            return $ex->getMessage();
        }
    }
}

