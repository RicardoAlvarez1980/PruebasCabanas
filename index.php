<?php
// Incluir el archivo de conexión a la base de datos y clases necesarias
require_once 'includes/Database.php';

require_once 'funcionalidades/clientes/listarClientes.php';
require_once 'funcionalidades/reservas/listarReservas.php';
require_once 'funcionalidades/cabanas/listarCabanas.php';

// Obtener datos
$clientes = obtenerClientes();
$cabanas = obtenerCabanas();
$reservas = obtenerReservas();

// Función para imprimir mensajes de alerta
function imprimirMensaje($mensaje) {
    if (!empty($mensaje)) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                ' . htmlspecialchars($mensaje) . '
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
    }
}

// Verificar si se está enviando el formulario para agregar cliente, cabaña o reserva
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tipo_formulario'])) {
        switch ($_POST['tipo_formulario']) {
            case 'cliente':
                $dni = $_POST['dni'];
                $nombre = $_POST['nombre'];
                $direccion = $_POST['direccion'];
                $telefono = $_POST['telefono'];
                $email = $_POST['email'];

                try {
                    // Insertar cliente en la base de datos
                    $conexion = Database::obtenerInstancia()->obtenerConexion();
                    $stmt = $conexion->prepare("INSERT INTO clientes (dni, nombre, direccion, telefono, email) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$dni, $nombre, $direccion, $telefono, $email]);
                    $mensaje = "Cliente agregado correctamente.";
                } catch (PDOException $e) {
                    if ($e->errorInfo[1] == 1062) { // Código de error para clave duplicada
                        $mensaje = "Error: El cliente con DNI '$dni' ya está registrado.";
                    } else {
                        $mensaje = "Error al agregar el cliente: " . $e->getMessage();
                    }
                }
                break;

            case 'cabana':
                $numero = $_POST['numero'];
                $capacidad = $_POST['capacidad'];
                $descripcion = $_POST['descripcion'];
                $costo_diario = $_POST['costo_diario'];

                try {
                    // Insertar cabaña en la base de datos
                    $conexion = Database::obtenerInstancia()->obtenerConexion();
                    $stmt = $conexion->prepare("INSERT INTO cabanas (numero, capacidad, descripcion, costo_diario) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$numero, $capacidad, $descripcion, $costo_diario]);
                    $mensaje = "Cabaña agregada correctamente.";
                } catch (PDOException $e) {
                    if ($e->errorInfo[1] == 1062) { // Código de error para clave duplicada
                        $mensaje = "Error: La cabaña con número '$numero' ya está registrada.";
                    } else {
                        $mensaje = "Error al agregar la cabaña: " . $e->getMessage();
                    }
                }
                break;

            case 'reserva':
                $fecha_inicio = $_POST['fecha_inicio'];
                $fecha_fin = $_POST['fecha_fin'];
                $cliente_dni = $_POST['cliente'];
                $cabana_numero = $_POST['cabana'];

                try {
                    // Insertar reserva en la base de datos
                    $conexion = Database::obtenerInstancia()->obtenerConexion();
                    $stmt = $conexion->prepare("INSERT INTO reservas (fecha_inicio, fecha_fin, cliente_dni, cabana_numero) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$fecha_inicio, $fecha_fin, $cliente_dni, $cabana_numero]);
                    $mensaje = "Reserva agregada correctamente.";
                } catch (PDOException $e) {
                    if ($e->errorInfo[1] == 1062) { // Código de error para clave duplicada
                        $mensaje = "Error: La reserva para la cabaña '$cabana_numero' en las fechas seleccionadas ya existe.";
                    } else {
                        $mensaje = "Error al agregar la reserva: " . $e->getMessage();
                    }
                }
                break;

            default:
                // Manejo de error o acción predeterminada
                break;
        }

        // Redirigir para evitar envío repetido del formulario
        header("Location: index.php?mensaje=" . urlencode($mensaje));
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Clientes, Cabañas y Reservas</title>
    <!-- Enlace a Bootstrap CSS desde CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Listado de Clientes, Cabañas y Reservas</h1>

        <!-- Función para imprimir el mensaje -->
        <?php imprimirMensaje(isset($_GET['mensaje']) ? $_GET['mensaje'] : ''); ?>

        <!-- Botón de agregar alineado a la derecha -->
        <div class="text-right mb-3">
            <!-- Enlaces a los formularios de alta -->
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalAgregarCliente">
                Agregar Cliente
            </button>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalAgregarCabana">
                Agregar Cabaña
            </button>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalAgregarReserva">
                Agregar Reserva
            </button>
        </div>

        <!-- Tabla de Clientes -->
        <h2>Listado de Clientes</h2>
        <table class="table table-striped">
            <!-- Encabezados de la tabla -->
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($clientes)) : ?>
                    <?php foreach ($clientes as $cliente) : ?>
                        <tr>
                            <td><?php echo $cliente->getDni(); ?></td>
                            <td><?php echo $cliente->getNombre(); ?></td>
                            <td><?php echo $cliente->getDireccion(); ?></td>
                            <td><?php echo $cliente->getTelefono(); ?></td>
                            <td><?php echo $cliente->getEmail(); ?></td>
                            <td>
                                <a href="funcionalidades/clientes/editarCliente.php?id=<?php echo htmlspecialchars($cliente->getDni()); ?>" class="btn btn-primary btn-sm">Editar</a>
                                <a href="funcionalidades/clientes/eliminarCliente.php?id=<?php echo htmlspecialchars($cliente->getDni()); ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                <a href="funcionalidades/clientes/verCliente.php?dni=<?php echo $cliente->getDni(); ?>" class="btn btn-info btn-sm">Ver Detalles</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6">No hay clientes registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Tabla de Cabañas -->
        <h2>Listado de Cabañas</h2>
        <table class="table table-striped">
            <!-- Encabezados de la tabla -->
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Capacidad</th>
                    <th>Descripción</th>
                    <th>Costo Diario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($cabanas)) : ?>
                    <?php foreach ($cabanas as $cabana) : ?>
                        <tr>
                            <td><?php echo $cabana->getNumero(); ?></td>
                            <td><?php echo $cabana->getCapacidad(); ?></td>
                            <td><?php echo $cabana->getDescripcion(); ?></td>
                            <td><?php echo $cabana->getCostoDiario(); ?></td>
                            <td>
                                <a href="funcionalidades/cabanas/editarCabana.php?id=<?php echo htmlspecialchars($cabana->getNumero()); ?>" class="btn btn-primary btn-sm">Editar</a>
                                <a href="funcionalidades/cabanas/eliminarCabana.php?id=<?php echo htmlspecialchars($cabana->getNumero()); ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                <a href="funcionalidades/cabanas/verCabana.php?numero=<?php echo $cabana->getNumero(); ?>" class="btn btn-info btn-sm">Ver Detalles</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5">No hay cabañas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Tabla de Reservas -->
        <h2>Listado de Reservas</h2>
        <table class="table table-striped">
            <!-- Encabezados de la tabla -->
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Cliente DNI</th>
                    <th>Cabaña Número</th>
                    <th>Días</th>
                    <th>Costo Diario</th>
                    <th>Costo Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($reservas)) : ?>
                    <?php foreach ($reservas as $reserva) : ?>
                        <tr>
                            <td><?php echo $reserva->getNumero(); ?></td>
                            <td><?php echo $reserva->getFechaInicio(); ?></td>
                            <td><?php echo $reserva->getFechaFin(); ?></td>
                            <td><?php echo $reserva->getCliente()->getDni(); ?></td>
                            <td><?php echo $reserva->getCabana()->getNumero(); ?></td>
                            <td><?php echo $reserva->calcularDiferenciaDias(); ?></td>
                            <td><?php echo $reserva->getCabana()->getCostoDiario(); ?></td>
                            <td><?php echo $reserva->calcularCostoTotal(); ?></td>
                            <td>
                                <a href="funcionalidades/reservas/editarReserva.php?id=<?php echo htmlspecialchars($reserva->getNumero()); ?>" class="btn btn-primary btn-sm">Editar</a>
                                <a href="funcionalidades/reservas/eliminarReserva.php?id=<?php echo htmlspecialchars($reserva->getNumero()); ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                <a href="funcionalidades/reservas/verReserva.php?id=<?php echo $reserva->getNumero(); ?>" class="btn btn-info btn-sm">Ver Detalles</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6">No hay reservas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Modales para agregar Cliente, Cabaña y Reserva -->
         <!-- Modal Agregar Cliente -->
    <div class="modal fade" id="modalAgregarCliente" tabindex="-1" role="dialog" aria-labelledby="modalAgregarClienteLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarClienteLabel">Agregar Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarCliente" method="POST" action="index.php">
                        <input type="hidden" name="tipo_formulario" value="cliente">
                        <div class="form-group">
                            <label for="dni">DNI:</label>
                            <input type="text" class="form-control" id="dni" name="dni" required>
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección:</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono:</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar Cliente</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Agregar Cabaña -->
    <div class="modal fade" id="modalAgregarCabana" tabindex="-1" role="dialog" aria-labelledby="modalAgregarCabanaLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarCabanaLabel">Agregar Cabaña</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarCabana" method="POST" action="funcionalidades/cabanas/altaCabanas.php">
                        <!-- Puedes ajustar el action según la estructura de tu proyecto -->
                        <div class="form-group">
                            <label for="numero">Número:</label>
                            <input type="text" class="form-control" id="numero" name="numero" required>
                        </div>
                        <div class="form-group">
                            <label for="capacidad">Capacidad:</label>
                            <input type="number" class="form-control" id="capacidad" name="capacidad" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción:</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="costo_diario">Costo Diario:</label>
                            <input type="text" class="form-control" id="costo_diario" name="costo_diario" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar Cabaña</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Agregar Reserva -->
    <div class="modal fade" id="modalAgregarReserva" tabindex="-1" role="dialog" aria-labelledby="modalAgregarReservaLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarReservaLabel">Agregar Reserva</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarReserva" method="POST" action="funcionalidades/reservas/altaReservas.php">
                        <!-- Puedes ajustar el action según la estructura de tu proyecto -->
                        <div class="form-group">
                            <label for="fecha_inicio">Fecha Inicio:</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                        </div>
                        <div class="form-group">
                            <label for="fecha_fin">Fecha Fin:</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                        </div>
                        <div class="form-group">
                            <label for="cliente">Cliente:</label>
                            <select class="form-control" id="cliente" name="cliente" required>
                                <!-- Aquí puedes iterar sobre tus clientes disponibles para seleccionar uno -->
                                <?php foreach ($clientes as $cliente) : ?>
                                    <option value="<?php echo $cliente->getDni(); ?>"><?php echo $cliente->getNombre(); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cabana">Cabaña:</label>
                            <select class="form-control" id="cabana" name="cabana" required>
                                <!-- Aquí puedes iterar sobre tus cabañas disponibles para seleccionar una -->
                                <?php foreach ($cabanas as $cabana) : ?>
                                    <option value="<?php echo $cabana->getNumero(); ?>"><?php echo $cabana->getNumero(); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar Reserva</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir jQuery desde CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- JS de Bootstrap (para funcionalidades de modal) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>