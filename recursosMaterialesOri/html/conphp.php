<?php
//Lourdes Soto 
//Sarah del Carmen
$servername = "localhost"; 
$username = "root";
$password = "";
$dbname = "cbtis_prestamos";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el producto seleccionado
$productoSeleccionado = $_POST['nombreProducto']; 

// Preparar la consulta
if (!empty($productoSeleccionado)) {
    $stmt = $conn->prepare("SELECT * FROM prestamos WHERE nombreProducto = ?");
    $stmt->bind_param("s", $productoSeleccionado);
    $stmt->execute();
    $result = $stmt->get_result();

    // Mostrar resultados
    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Préstamos para el Producto</title>
        <link rel="icon" href="icon.ico">
        <style>
        body {
    font-family: "Arial", sans-serif;
    background: linear-gradient(to right, #ffffff, #e0f7fa); 
    margin: 0;
    padding: 0;
    color: #333;
    animation: fadeIn 1s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

header {

    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    display: flex;
    justify-content: space-around;
    transition: 0.7;
    padding: 30px 20px;
    z-index: 10;
}

.Header.abajo {
    background: #c36bdd;
    padding: 15px 20px;
}

.menu-icon {
    display: none;
}

.menu-icon i {
    color: white;
    font-size: 30px;



}

.Header .logo {
    width: 100px;
    height: 100px;
    position: relative;
    color: #fff;
    font-weight: bold;
    font-size: 2em;
    letter-spacing: 2px;
    transition: 1.5s;
    text-decoration: none;
}

.navbar {
    padding: 10px 150px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 30px;
    position: relative;
    height: 70px;

}

.Header .menu {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
}

.Header .menu .item {
    list-style-type: none;
    color: rgb(0, 0, 0);
    text-decoration: none;
    font-weight: bold;

}

.Header .menu .item .link {
    position: relative;
    font-family: Arial, Helvetica, sans-serif;
    margin: 0 15px;
    text-decoration: none;
    color: #500e0e;
    font-weight: 600;
    font-size: 20px;

}

.menu .item .link:hover {
    color: rgb(53, 51, 51);
    border-radius: 15px;
}

.h1 {
margin-top: 150px;
    text-align: center;
    padding: 20px;
    background: rgba(255, 255, 255, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

h1 {
    margin: 0;
    font-size: 2.5em;
    
}

.table-container {
    max-width: 800px;
    margin: 20px auto;
    background: rgba(255, 255, 255, 0.9); 
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2); 
    animation: slideIn 0.5s ease;
    transition: transform 0.2s, box-shadow 0.2s; 
}

.table-container:hover {
    transform: scale(1.05); 
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3); 
}

@keyframes slideIn {
    from { transform: translateY(-20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 2em;
    color: #4CAF50; 
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd;
}

th {
    background: rgba(76, 175, 80, 0.7); 
    color: white;
}

tr:nth-child(even) {
    background-color: rgba(255, 255, 255, 0.1);
}

tr:hover {
    background-color: rgba(76, 175, 80, 0.2); 
}

@media (max-width: 600px) {
    h1 {
        font-size: 2em;
    }

    h2 {
        font-size: 1.5em;
    }
}

            table {
   width: 80%; /* Ajustar el ancho de la tabla */
   margin: 20px auto; /* Centrar la tabla */
   border-collapse: collapse;
   margin-top: 20px;
   border-radius: 10px; /* Esquinas redondeadas */
   overflow: hidden; /* Para que las esquinas redondeadas se apliquen correctamente */
}

h1 {
   margin: 0;
   font-size: 2.5em;
   
   background: rgba(255, 255, 255, 0.8); /* Fondo blanco con opacidad */
   padding: 10px; /* Espaciado interno */
   border-radius: 10px; /* Esquinas redondeadas */
   display: inline-block; /* Para que el fondo se ajuste al tamaño del texto */
   box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Sombra para un toque chic */
}
        </style>
    </head>
    <body>
      <header class="Header">
            <a href="../index.html" class="logo">
                <img src="img/logo.png" class="logo" alt="logo">
            </a>
    
            <nav class="navbar">
                <ul class="menu">
                    <li class="item"><a href="menuAdministrador.html" class="link">Menu</a></li>

                </ul>
                <div class="menu-icon">
                    <i class="menubtn" onclick="togglemenu()"><img src="menu-btn.png" width="30" height="30"
                            alt="boton menu"> </i>
                </div>
    
            </nav>
        </header>
    
</div>
        <div class=h1>
            <h1>Consulta de Préstamos</h1>
        </div>
        <div class="table-container">';

    if ($result->num_rows > 0) {
        echo "<h2>Préstamos para el producto: $productoSeleccionado</h2>";
        echo "<table><tr><th>ID del préstamo</th><th>Fecha de pedido</th><th>Cantidad solicitada</th><th>Fecha de entrega</th><th>ID del usuario</th><th>Nombre del producto</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . htmlspecialchars($row['idPrestamo']) . "</td><td>" . htmlspecialchars($row['fechaDePedido']) . "</td><td>" . htmlspecialchars($row['cantSolicitada']) . "</td><td>" . htmlspecialchars($row['fechaEntrega']) . "</td><td>" . htmlspecialchars($row ['idUsuario']) . "</td><td>" . htmlspecialchars($row['nombreProducto']) . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No se encontraron préstamos para el producto seleccionado.</p>";
    }

    echo '</div>
    </body>
    </html>';

    $stmt->close();
} else {
    echo "<p>Por favor, selecciona un producto.</p>";
}

$conn->close();
?>