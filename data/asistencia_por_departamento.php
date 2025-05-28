<?php
// Incluir archivo de conexión a la base de datos
require_once '../conexion.php';

// Consulta SQL para obtener la cantidad de eventos activos por departamento
$sql = "
    SELECT departamento, COUNT(*) as cantidad
    FROM eventos
    WHERE estado = 'activo'
    GROUP BY departamento
";

// Ejecutar la consulta SQL usando PDO
$result = $pdo->query($sql);

// Inicializar arreglo para almacenar los datos que serán convertidos a JSON
$data = [];

// Recorrer cada fila de resultados y formatearla en un arreglo asociativo
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $data[] = [
        "x" => $row['departamento'],   
        "value" => (int)$row['cantidad'] 
    ];
}

// Devolver los datos en formato JSON
echo json_encode($data);
?>
