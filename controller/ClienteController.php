<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Cliente.php';

class ClienteController
{
    public Cliente $modelo;
    public string $titulo = 'Administración de Clientes';
    public array $campos = [
        'nombre' => 'text',
        'telefono' => 'text',
        'email' => 'email',
    ];

    public function __construct()
    {
        $this->modelo = new Cliente(db());
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
                notificar('Cliente eliminado correctamente.');
            } else {
                if (empty(trim($_POST['nombre'] ?? ''))) {
                    throw new RuntimeException('El nombre del cliente es obligatorio.');
                }
                $datos = validarDatos($this->campos, $_POST);
                $this->modelo->guardar($datos, $id);
                notificar($id > 0 ? 'Cliente actualizado con éxito.' : 'Cliente registrado con éxito.');
            }

            redirigir('clientes.php');
        } catch (PDOException $ex) {
            error_log($ex->getMessage());
            return 'No se pudo completar la operación. Verifique que no existan registros vinculados o duplicados.';
        } catch (Throwable $ex) {
            return $ex->getMessage();
        }
    }
}

