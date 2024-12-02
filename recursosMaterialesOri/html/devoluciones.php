<?php
// Guerrero Dominguez Kael Santiago
$servername = "localhost"; 
$username = "root";
$password = "";
$dbname = "cbtis_prestamos";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idPrestamo = $_POST['idPrestamo'];
    $fechaDevolucion = date('Y-m-d'); 
    $estadoDevolucion = isset($_POST['estadoDevolucion']) ? 1 : 0; // 1 para "entregado", 0 para "no entregado"

    // Verificar si el idPrestamo existe
    $sql_verificar = "SELECT COUNT(*) FROM prestamos WHERE idPrestamo = ?";
    $verificar = $conn->prepare($sql_verificar);
    $verificar->bind_param("i", $idPrestamo);
    $verificar->execute();
    $verificar->bind_result($count);
    $verificar->fetch();
    $verificar->close();

    if ($count > 0) {
        // Obtener la cantidad solicitada del préstamo
        $sql_obtener_cantidad = "SELECT nombreProducto, cantSolicitada FROM prestamos WHERE idPrestamo = ?";
        $obtener = $conn->prepare($sql_obtener_cantidad);
        $obtener->bind_param("i", $idPrestamo);
        $obtener->execute();
        $obtener->bind_result($nombreProducto, $cantSolicitada);
        $obtener->fetch();
        $obtener->close();

        // Registrar la devolución
        $sql_devolucion = "INSERT INTO devoluciones (idPrestamo, fechaDevolucion, estadoDevolucion) VALUES (?, ?, ?)";
        $devolucion = $conn->prepare($sql_devolucion);
        $devolucion->bind_param("isi", $idPrestamo, $fechaDevolucion, $estadoDevolucion);
        
        if ($devolucion->execute()) {
            if ($devolucion->affected_rows > 0) {
                // Obtener el ID de la devolución generada
                $idDevolucionGenerada = $devolucion->insert_id; // Obtener el ID de la última inserción
                
                // Actualizar la cantidad del producto en la tabla productos
                $sql_actualizar_producto = "UPDATE producto SET cantidadProducto = cantidadProducto + ? WHERE nombreProducto = ?";
                $actualizar_producto = $conn->prepare($sql_actualizar_producto);
                $actualizar_producto->bind_param("is", $cantSolicitada, $nombreProducto);
                
                if ($actualizar_producto->execute()) {
                    echo "Devolución registrada exitosamente. ID de la devolución: " . $idDevolucionGenerada;
                } else {
                    echo "Error al actualizar la cantidad del producto: " . $actualizar_producto->error;
                }

                $actualizar_producto->close();
            }
        } else {
            echo "Error al registrar la devolución: " . $devolucion->error;
        }
        $devolucion->close();
    } else {
        echo "No se encontró ningún préstamo con ese ID: " . $idPrestamo;
    }
}

// Obtener préstamos para el formulario
$sql_prestamos = "SELECT idPrestamo FROM prestamos";
$result = $conn->query($sql_prestamos);

$conn->close();
?>