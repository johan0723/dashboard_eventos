<?php
// Importa el archivo de conexión a la base de datos
require_once '../conexion.php';

// Obtiene el ID del evento desde la URL, asegurándose de que sea un entero
$evento_id = isset($_GET['evento_id']) ? intval($_GET['evento_id']) : 0;

// Consulta SQL para obtener el aforo máximo y el número de registros de asistencia
$sql = "
    SELECT 
        e.aforo_maximo, 
        COUNT(r.id_registro) AS registrados 
    FROM eventos e 
    LEFT JOIN registro_asistencia_evento r ON e.id_evento = r.id_evento 
    WHERE e.id_evento = ?
    GROUP BY e.id_evento, e.aforo_maximo
";

// Prepara y ejecuta la consulta con el ID del evento
$stmt = $pdo->prepare($sql);
$stmt->execute([$evento_id]);

// Obtiene los datos como arreglo asociativo
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// Devuelve los resultados en formato JSON
echo json_encode($data);
?>
