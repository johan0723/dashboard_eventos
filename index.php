<?php
require_once 'conexion.php'; // Conexión a la base de datos usando PDO

// Obtener todos los eventos activos ordenados por fecha de inicio descendente
try {
    $eventos = $pdo->query("SELECT * FROM eventos WHERE estado = 'activo' ORDER BY fecha_inicio DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error al obtener eventos: " . htmlspecialchars($e->getMessage()));
}

// Determinar cuál evento ha sido seleccionado
//  Se toma el id desde GET o se usa el primero de la lista si no hay parámetro
$evento_id = isset($_GET['evento_id']) ? intval($_GET['evento_id']) : ($eventos[0]['id_evento'] ?? null);

// Buscar los datos del evento seleccionado dentro del arreglo de eventos
$evento_seleccionado = null;
foreach ($eventos as $ev) {
    if ($ev['id_evento'] == $evento_id) {
        $evento_seleccionado = $ev;
        break;
    }
}

// Obtener cuántos usuarios están registrados al evento seleccionado
$registrados = 0;
if ($evento_seleccionado) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM registro_asistencia_evento WHERE id_evento = ?");
    $stmt->execute([$evento_seleccionado['id_evento']]);
    $registrados = $stmt->fetchColumn();
}

// Generar las opciones del selector de eventos en el formulario
$opciones_eventos = '';
foreach ($eventos as $evento) {
    $selected = ($evento['id_evento'] == $evento_id) ? 'selected' : '';
    $opciones_eventos .= '<option value="' . htmlspecialchars($evento['id_evento']) . '" ' . $selected . '>' . htmlspecialchars($evento['nombre']) . '</option>';
}

// Mostrar la información del evento seleccionado
// Incluye nombre, descripción, fecha, ubicación, aforo y barra de ocupación
// Si hay evento, se muestra un botón para ir al dashboard
$evento_info_html = '';
if ($evento_seleccionado) {
    $aforo = max(1, (int)$evento_seleccionado['aforo_maximo']); // Se asegura de evitar división por cero
    $porcentaje = min(100, round(($registrados / $aforo) * 100)); // Porcentaje de ocupación
    $fecha_inicio = date('d/m/Y H:i', strtotime($evento_seleccionado['fecha_inicio']));
    $ubicacion = htmlspecialchars($evento_seleccionado['ubicacion']);
    $direccion = $evento_seleccionado['direccion'] ? ', ' . htmlspecialchars($evento_seleccionado['direccion']) : '';

    // HTML de la información del evento
    $evento_info_html = '
        <div class="evento-info">
            <h2>' . htmlspecialchars($evento_seleccionado['nombre']) . '</h2>
            <p><strong>Descripción:</strong> ' . nl2br(htmlspecialchars($evento_seleccionado['descripcion'])) . '</p>
            <p><strong>Fecha:</strong> ' . $fecha_inicio . '</p>
            <p><strong>Ubicación:</strong> ' . $ubicacion . $direccion . '</p>
            <p><strong>Aforo máximo:</strong> ' . (int)$evento_seleccionado['aforo_maximo'] . ' &nbsp; | &nbsp; <strong>Registrados:</strong> ' . (int)$registrados . '</p>
            <div class="aforo-bar">
                <div class="aforo-bar-inner" style="width: ' . $porcentaje . '%;">
                    ' . $porcentaje . '%
                </div>
            </div>
        </div>
        <form action="dashboard.php" method="GET" style="text-align:right;">
            <input type="hidden" name="evento_id" value="' . htmlspecialchars($evento_seleccionado['id_evento']) . '">
            <button type="submit" class="btn-dashboard">Ver Dashboard</button>
        </form>
    ';
} else {
    // Si no hay evento válido
    $evento_info_html = '<p class="error-msg">No hay información del evento seleccionado.</p>';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Selector de Eventos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css"> 
    <script>
        // Función JS: Cambia la informacion del evento sin recargar la página
        function onEventoChange(sel) {
            window.location.href = "?evento_id=" + sel.value;
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Seleccionar Evento</h1>
        <!--Formulario con lista desplegable de eventos -->
        <form method="GET" action="index.php">
            <label for="evento_id">Evento:</label>
            <select name="evento_id" id="evento_id" onchange="onEventoChange(this)">
                <?= $opciones_eventos ?>
            </select>
        </form>

        <!--Sección con la información del evento-->
        <?= $evento_info_html ?>
    </div>
</body>
</html>

