<?php
//Ramon Mendez 
// Crear conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cbtis_prestamos";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión 
if ($conn->connect_error) {
    die("Conexión Fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$id =$_POST["ID"];
$Articulo = $_POST["Articulo"];
$edificioSalon = $_POST["nombre"];
$salon = $_POST["nombreUsuario"];
$CantArti = $_POST["CantArti"];
$Nombre = $_POST["Nombre"];
$Correo = $_POST["correo"];
$Telefono = $_POST["contra"];

$FechSalid = $_POST["FechSalid"];
$HoraSalid = $_POST["HoraSalid"];

// Diseño la consulta SQL de INSERTAR nuevo registro
$sql = "INSERT INTO prestamos(ID, Articulo, edificioSalon, salon, CantArti, Nombre, Telefono, Correo, FechSalid, HoraSalid) VALUES ('$id','$Articulo', '$edificioSalon', '$salon', '$CantArti', '$Nombre', '$Telefono', '$Correo', '$FechSalid', '$HoraSalid')";

// Verificar si ya existe el artículo
$checkSql = "SELECT * FROM prestamos WHERE Articulo = '$Articulo'";
$result = $conn->query($checkSql);

if ($result->num_rows > 0) {
    echo "ERROR: El artículo ya ha sido registrado.";
} else {
    // Si no existe, proceder a insertar
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('haz sido registrado con exito, pasa por tu libro =)');</script>";
    } else {
        echo "ERROR: " . $sql . "<br>" . $conn->error;
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>