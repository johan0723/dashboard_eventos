<?php
// Incluir archivo de conexión a la base de datos
require_once '../conexion.php';

// Total de eventos
$total = $pdo->query("SELECT COUNT(*) FROM eventos")->fetchColumn();

// Eventos por estado
$activos = $pdo->query("SELECT COUNT(*) FROM eventos WHERE estado='activo'")->fetchColumn();
$pospuestos = $pdo->query("SELECT COUNT(*) FROM eventos WHERE estado='pospuesto'")->fetchColumn();
$cancelados = $pdo->query("SELECT COUNT(*) FROM eventos WHERE estado='cancelado'")->fetchColumn();

// Promedio de asistentes por evento
$promedio = $pdo->query("
    SELECT AVG(asistentes) FROM (
        SELECT COUNT(*) as asistentes 
        FROM registro_asistencia_evento 
        GROUP BY id_evento
    ) as sub
")->fetchColumn();

// Evento más ocupado (con más registros de asistencia)
$mas_ocupado = $pdo->query("
    SELECT e.nombre 
    FROM eventos e 
    JOIN registro_asistencia_evento r ON e.id_evento = r.id_evento 
    GROUP BY e.id_evento 
    ORDER BY COUNT(r.id_registro) DESC 
    LIMIT 1
")->fetchColumn();

// Ingresos totales (solo eventos pagados)
$ingresos = $pdo->query("
    SELECT SUM(e.precio_entrada) 
    FROM eventos e 
    JOIN registro_asistencia_evento r ON e.id_evento = r.id_evento
    WHERE e.es_gratuito = 0
")->fetchColumn();

// Precios de todos los eventos
$precios = $pdo->query("SELECT precio_entrada FROM eventos")->fetchAll(PDO::FETCH_COLUMN);

echo json_encode([
    "total" => (int)$total,
    "activos" => (int)$activos,
    "pospuestos" => (int)$pospuestos,
    "cancelados" => (int)$cancelados,
    "promedio" => round($promedio),
    "mas_ocupado" => $mas_ocupado,
    "ingresos" => (float)$ingresos,
    "precios" => array_map('floatval', $precios)
]);
?>