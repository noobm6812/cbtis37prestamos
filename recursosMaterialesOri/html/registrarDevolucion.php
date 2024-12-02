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

$message = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['idDevolucion'])) { // Verificar si se envió el ID de devolución
        $idDevolucion = $_POST['idDevolucion'];
        $estadoDevolucion = 1; // Establecer estado de devolución en 1 (entregado)
        
        // Actualizar el estado de la devolución
        $sql_actualizar = "UPDATE devoluciones SET estadoDevolucion = ? WHERE idDevolucion = ?";
        $actualizar = $conn->prepare($sql_actualizar);
        $actualizar->bind_param("ii", $estadoDevolucion, $idDevolucion);
        
        if ($actualizar->execute()) {
            // Verificar cuántas filas fueron afectadas
            if ($actualizar->affected_rows > 0) {
                $message = "Devolución marcada como entregada.";
            } else {
                $message = "No se encontró ninguna devolución con ese ID: " . $idDevolucion; // Mensaje de depuración
            }
        } else {
            $message = "Error al actualizar el estado de la devolución: " . $actualizar->error;
        }
        $actualizar->close();
    } elseif (isset($_POST['estadoDevolucion'])) { // Verificar si se envió el checkbox
        $idDevolucion = $_POST['idDevolucion'];
        $estadoDevolucion = $_POST['estadoDevolucion'];
        
        // Actualizar el estado de la devolución
        $sql_actualizar = "UPDATE devoluciones SET estadoDevolucion = ? WHERE idDevolucion = ?";
        $actualizar = $conn->prepare($sql_actualizar);
        $actualizar->bind_param("ii", $estadoDevolucion, $idDevolucion);
        
        if ($actualizar->execute()) {
            // Verificar cuántas filas fueron afectadas
            if ($actualizar->affected_rows > 0) {
                $message = "Devolución marcada como entregada.";
            } else {
                $message = "No se encontró ninguna devolución con ese ID: " . $idDevolucion; // Mensaje de depuración
            }
        } else {
            $message = "Error al actualizar el estado de la devolución: " . $actualizar->error;
        }
        $actualizar->close();
    }
}

// Devolver el mensaje como respuesta
echo $message;

$conn->close();
?>