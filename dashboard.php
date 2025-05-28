<?php
// Validar si se proporcionó un ID de evento por GET
if (!isset($_GET['evento_id'])) {
    die("Error: No se ha seleccionado un evento.");
}

// Obtener el ID del evento desde la URL
$evento_id = $_GET['evento_id'];

// Validar que el ID sea un número entero válido
if (!filter_var($evento_id, FILTER_VALIDATE_INT)) {
    die("Error: ID de evento inválido.");
}

// Conexión a la base de datos
require_once 'conexion.php';

// Consultar el nombre del evento según su ID
$stmt = $pdo->prepare("SELECT nombre FROM eventos WHERE id_evento = ?");
$stmt->execute([$evento_id]);
$evento = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar que el evento exista
if (!$evento) {
    die("Error: Evento no encontrado.");
}

// Guardar el nombre del evento para mostrarlo en el dashboard
$nombre_evento = $evento['nombre'];
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="css/style.css">
  <!-- Librería de gráficos AnyChart -->
  <script src="https://cdn.anychart.com/releases/8.11.0/js/anychart-bundle.min.js"></script>
  <script>
    // Enviar el ID del evento al archivo dashboard.js de forma segura
    const eventoId = <?= json_encode((int)$evento_id) ?>;
    window.eventoId = eventoId; // Hacerlo accesible globalmente para JS
  </script>
</head>
<body>
  
  <h1>Dashboard</h1>
  <!-- Sección para mostrar gráficos del evento seleccionado -->
  <div class="dashboard-section">
    <h2>Gráficos Específicos para el Evento: 
      <span style="color:#50A72C"><?= htmlspecialchars($nombre_evento) ?></span>
    </h2>
    <div class="charts-row">
      <div id="asistencia-hora" class="chart-container"></div>  
      <div id="aforo-chart" class="chart-container"></div>
    </div>
  </div>

  <!-- Sección para mostrar gráficos generales de todos los eventos -->
  <div class="dashboard-section">
    <h2>Gráficos Generales de Todos los Eventos</h2>
    <div id="tarjetas"></div>
    <div class="charts-row">
      <div id="departamento-chart" class="chart-container"></div>  
      <div id="estado-chart" class="chart-container"></div>      
      <div id="precio-chart" class="chart-container"></div>
    </div>
  </div>

  <script src="js/dashboard.js"></script>

</body>
</html>
