<?php
// Incluir el archivo de conexión a la base de datos
require_once '../conexion.php';

// Validar y obtener el parámetro de evento desde la URL, asegurando que sea un entero
$evento_id = isset($_GET['evento_id']) ? intval($_GET['evento_id']) : 0;

// Consulta SQL para contar registros por hora del evento
$sql = "
    SELECT 
        HOUR(fecha_registro) AS hora, 
        COUNT(*) AS total 
    FROM registro_asistencia_evento 
    WHERE id_evento = ? 
    GROUP BY hora
";

// Preparar y ejecutar la consulta con el ID del evento
$stmt = $pdo->prepare($sql);
$stmt->execute([$evento_id]);

// Almacenar los resultados en un array asociativo (hora => total)
$horas_registradas = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $hora = (int)$row['hora'];         
    $total = (int)$row['total'];        
    if ($total > 0) {
        $horas_registradas[$hora] = $total;
    }
}

// Construir el arreglo final con formato de hora "HH:00"
$data = [];
foreach ($horas_registradas as $h => $cantidad) {
    $label = str_pad($h, 2, '0', STR_PAD_LEFT) . ":00"; 
    $data[] = [
        'hora' => $label,
        'cantidad' => $cantidad
    ];
}

// Devolver los datos en formato JSON
echo json_encode($data);
?>
