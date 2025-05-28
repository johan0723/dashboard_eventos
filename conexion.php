<?php
$host = 'localhost';         
$db   = 'dashboard_eventos';        
$user = 'root';              
$pass = 'nomelase123';           
$charset = 'utf8mb4';        

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,     // Muestra errores como excepciones
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,           // Devuelve resultados como arrays asociativos
    PDO::ATTR_EMULATE_PREPARES   => false,                      // Usa consultas preparadas reales
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>
