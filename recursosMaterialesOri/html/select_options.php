<?php
//Fedra Soto
//Ramon Mendez
//Sarah del Carmen

// Conectar a la base de datos
$con = mysqli_connect("localhost", "root", "", "cbtis_prestamos");

// Verificar la conexión
if (!$con) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Consulta para obtener los nombres de los productos
$sql = "SELECT nombreProducto FROM producto"; // Asegúrate de que 'productos' es el nombre correcto de tu tabla
$result = mysqli_query($con, $sql);

// Generar las opciones del dropdown
$options = '';
while ($row = mysqli_fetch_assoc($result)) {
    $options .= '<option value="' . $row['nombreProducto'] . '">' . $row['nombreProducto'] . '</option>';
}

// Cerrar conexión
mysqli_close($con);

// Devolver las opciones como JSON
echo json_encode($options);
?>