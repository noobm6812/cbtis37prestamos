<?php
// Fedra Soto 
// Iniciar la sesión
session_start();

// Validar que se reciban datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $id = htmlspecialchars($_POST['idUsuario']);
    $contraseña = htmlspecialchars($_POST["Contraseña"]);

    // Valido que los campos no estén vacíos
    if (empty($id) || empty($contraseña)) {
        echo "Todos los campos son obligatorios.";
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
    $sql = "SELECT idUsuario, Contraseña FROM usuario WHERE idUsuario=? ";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si hay registros
    if ($result->num_rows > 0) {
        // Obtener el resultado
        $row = $result->fetch_assoc();
        
        // Verificar la contraseña
        if ($contraseña === $row['Contraseña']) {
            // Guardar información en la sesión si es necesario
            $_SESSION['idUsuario'] = $row['idUsuario'];
            // Redireccionar al usuario
            header("Location: menuUsuario.html"); 
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "No se encontró el usuario.";
    }

    // Cerrar conexión
    $stmt->close();
    $conn->close();
} else {
    echo "Por favor, ingrese datos válidos.";
}
?>