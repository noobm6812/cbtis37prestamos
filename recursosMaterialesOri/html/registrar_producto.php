<!-- Lourdes Soto-->

<?php
$servername = "localhost"; 
$username = "root";
$password = "";
$dbname = "cbtis_prestamos";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario y sanitizar
$nombre_producto = $conn->real_escape_string($_POST["nombreProducto"]);
$cantidad_producto = $conn->real_escape_string($_POST["cantidadProducto"]);

// Consulta SQL para insertar nuevo registro
$sql = "INSERT INTO producto (nombreProducto, cantidadProducto) VALUES ('$nombre_producto', '$cantidad_producto')";

// Ejecución del query 
if ($conn->query($sql) === TRUE) {
    // Si la inserción es exitosa, muestra una alerta y redirige
    echo "<script>
            alert('PRODUCTO REGISTRADO EXITOSAMENTE');
            window.location.href = document.referrer; // Redirige a la página anterior
          </script>";
} else {
    // Si hay un error, muestra una alerta con el mensaje de error y redirige
    echo "<script>
            alert('ERROR: " . $conn->error . "');
            window.location.href = document.referrer; // Redirige a la página anterior
          </script>";
}

// Cerrar la conexión
$conn->close();
?>