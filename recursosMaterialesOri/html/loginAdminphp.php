

<?php

//Lourdes Soto 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $id = intval($_POST['idAdministrador']); // Asegúrate de que sea un entero
    $contraseña = htmlspecialchars($_POST["contraseña"]);

    // Valido que los campos no estén vacíos
    if (empty($id) || empty($contraseña)) {
        echo "<script>alert('Todos los campos son obligatorios.'); window.location.href='loginAdmin.html';</script>";
        exit();
    }

    // Asigno valores a las variables para conexión
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "cbtis_prestamos";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Establecer la codificación de caracteres
    $conn->set_charset("utf8");

    // Consultar con SQL
    $sql = "SELECT idAdministrador, contraseña FROM administrador WHERE idAdministrador=?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param("i", $id); // Cambiado a solo "i"
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si hay registros
    if ($result->num_rows > 0) {
        // Obtener el resultado
        $row = $result->fetch_assoc();
        
        // Verificar la contraseña
        if ($contraseña === $row['contraseña']) { // Reemplaza esto si usas hashing
            $_SESSION['idAdministrador'] = $row['idAdministrador'];
            header("Location: menuAdministrador.html"); 
            exit();
        } else {
            echo "<script>alert('Contraseña incorrecta.'); window.location.href='loginAdmin.html';</script>";
        }
    } else {
        echo "<script>alert('No se encontró el administrador.'); window.location.href='loginAdmin.html';</script>";
    }

    // Cerrar conexión
    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Por favor, ingrese un ID válido.'); window.location.href='loginAdmin.html';</script>";
}
?>