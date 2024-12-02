<?php
// Soto Verdugo Fedra Liliana
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "cbtis_prestamos"; 

// Establecer la zona horaria
date_default_timezone_set('America/Mexico_City'); // Cambia a tu zona horaria

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Conexión fallida: ' . $conn->connect_error]);
    exit();
}

// Recibir datos del formulario
$idUsuario = $_POST['idUsuario'];
$nombreProducto = $_POST['nombreProducto'];
$cantSolicitada = $_POST['cantSolicitada'];

// Obtener la fecha y hora actual
$fechaDePedido = date("Y-m-d H:i:s");
$fechaEntrega = date("Y-m-d H:i:s", strtotime("+30 minutes"));

// Verificar si el producto existe y obtener la cantidad disponible
$checkProductSql = "SELECT cantidadProducto FROM producto WHERE nombreProducto = ?";
$checkStmt = $conn->prepare($checkProductSql);
$checkStmt->bind_param("s", $nombreProducto);
$checkStmt->execute();
$checkStmt->bind_result($cantidadProducto);
$checkStmt->fetch();
$checkStmt->close();

// Verificar si el producto existe
if ($cantidadProducto === null) {
    echo json_encode(['success' => false, 'message' => 'El producto no existe en la base de datos.']);
    exit();
}

// Verificar si hay suficientes existencias
if ($cantSolicitada > $cantidadProducto) {
    echo json_encode(['success' => false, 'message' => 'No hay suficientes existencias del producto en este momento.']);
    exit();
}

// Preparar la consulta SQL para insertar el préstamo
$sql = "INSERT INTO prestamos (idUsuario, nombreProducto, cantSolicitada, fechaDePedido, fechaEntrega) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isiss", $idUsuario, $nombreProducto, $cantSolicitada, $fechaDePedido, $fechaEntrega);

if ($stmt->execute()) {
	 $ultimoId = $conn->insert_id; // Este es el ID de la fila que acabas de insertar
    // Actualizar la cantidad del producto en la tabla producto
    $sql_actualizar_producto = "UPDATE producto SET cantidadProducto = cantidadProducto - ? WHERE nombreProducto = ?";
    $stmt_actualizar = $conn->prepare($sql_actualizar_producto);
    $stmt_actualizar->bind_param("is", $cantSolicitada, $nombreProducto);

    if (!$stmt_actualizar->execute()) {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la cantidad del producto: ' . $stmt_actualizar->error]);
        $stmt_actualizar->close();
        exit();
    }

    $stmt_actualizar->close();
    echo json_encode(['success' => true, 'message' => 'Préstamo registrado con éxito. ID de préstamo: ' . $ultimoId]);
} else {
     error_log('Error al registrar el préstamo: ' . $stmt->error); // Agrega esto para depuración
    echo json_encode(['success' => false, 'message' => 'Error al registrar el préstamo: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>