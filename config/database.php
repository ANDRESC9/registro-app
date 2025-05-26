<?php
// Configuración de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "soacha_app"; 


// $servername = "sql204.infinityfree.com";
// $username = "if0_38928703";
// $password = "yUOPGRNmRGi0js"; 
// $dbname = "if0_38928703_registro_app"; 

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
