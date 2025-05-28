anychart.onDocumentReady(function () {
  // Obtener el ID del evento
  const eventoId = window.eventoId;

  
  // Carga y genera un gráfico de líneas que muestra la asistencia por hora a un evento específico.
  function cargarAsistenciaPorHora() {
    fetch(`data/asistencia_por_hora.php?evento_id=${eventoId}`)
      .then(res => res.json())
      .then(data => {
        // Filtrar datos con formato de hora válido (HH:mm) y ordenarlos cronológicamente
        const datosOrdenados = data
          .filter(d => typeof d.hora === 'string' && /^\d{2}:\d{2}$/.test(d.hora))
          .sort((a, b) => {
            const [ha, ma] = a.hora.split(':').map(Number);
            const [hb, mb] = b.hora.split(':').map(Number);
            return (ha * 60 + ma) - (hb * 60 + mb);
          })
          .map(d => [d.hora, d.cantidad]);

        // Crear gráfico de líneas
        const chart = anychart.line();
        chart.data(datosOrdenados);
        chart.title("Asistencia por Hora");
        chart.yScale().ticks().interval(1); // Intervalo de 1 en el eje Y
        chart.tooltip().format(function () {
          return " Registros: " + this.value;
        });
        chart.container("asistencia-hora");
        chart.draw();
      });
  }


  // Carga y genera un gráfico de pastel (pie chart) que muestra la cantidad de eventos por departamento
  function cargarEventosPorDepartamento() {
    fetch('data/asistencia_por_departamento.php')
      .then(res => res.json())
      .then(data => {
        const chart = anychart.pie(data);
        chart.title("Eventos por Departamento");
        chart.container("departamento-chart");
        chart.draw();
      });
  }

 

  // Crea un gráfico de columnas para comparar el aforo máximo permitido con los registrados
  function cargarAforoVsRegistrados() {
    fetch(`data/eventos_data.php?evento_id=${eventoId}`)
      .then(res => res.json())
      .then(data => {
        const chart = anychart.column();
        chart.data([
          ["Aforo Máximo", parseInt(data.aforo_maximo) || 0],
          ["Registrados", parseInt(data.registrados) || 0]
        ]);
        chart.title("Aforo vs Registrados");
        chart.tooltip().format(function () {
          return this.x + ": " + this.value;
        });
        chart.container("aforo-chart");
        chart.draw();
      });
  }


  /**
   * Carga estadísticas generales, actualiza tarjetas informativas, y crea varios gráficos:
   * - Dona de estados de eventos
   * - Histograma de precios de eventos
   */
  function cargarEstadisticasGenerales() {
    fetch('data/estadisticas_generales.php')
      .then(res => res.json())
      .then(data => {
        // Gráfico de dona para estados de eventos
        const chartEstados = anychart.pie([
          ["Activo", data.activos],
          ["Pospuesto", data.pospuestos],
          ["Cancelado", data.cancelados]
        ]);
        chartEstados.innerRadius("40%");
        chartEstados.title("Estados de los Eventos");
        chartEstados.container("estado-chart");
        chartEstados.draw();


        //Tarjetas estadísticas en HTML
        const tarjetas = document.getElementById("tarjetas");
        if (tarjetas) {
          tarjetas.innerHTML = `
            <div>Total eventos: ${data.total}</div>
            <div>Eventos activos: ${data.activos}</div>
            <div>Promedio asistentes/evento: ${data.promedio}</div>
            <div>Evento con mayor ocupación: ${data.mas_ocupado}</div>
            <div>Ingresos proyectados totales: $${data.ingresos}</div>
          `;
        }


        // Histograma de precios
        if (Array.isArray(data.precios) && data.precios.length > 0) {
          const precios = data.precios.map(Number);
          const bins = [0, 50000, 100000, 150000, 200000, 250000, 1000000]; // Rango de precios
          const labels = [];
          const counts = Array(bins.length - 1).fill(0); // Inicializar contadores por rango

          // Clasificar precios en rangos
          precios.forEach(precio => {
            for (let i = 0; i < bins.length - 1; i++) {
              if (precio >= bins[i] && precio < bins[i + 1]) {
                counts[i]++;
                break;
              }
              // Precios mayores que el último bin
              if (i === bins.length - 2 && precio >= bins[i + 1]) {
                counts[i]++;
              }
            }
          });

          // Crear etiquetas para los rangos
          for (let i = 0; i < bins.length - 1; i++) {
            labels.push(`$${bins[i].toLocaleString()} - $${(bins[i + 1] - 1).toLocaleString()}`);
          }

          const dataHistograma = labels.map((label, i) => [label, counts[i]]);

          const chartPrecios = anychart.column();
          chartPrecios.data(dataHistograma);
          chartPrecios.title("Precios de Eventos (Histograma)");
          chartPrecios.xAxis().title("Rango de Precios");
          chartPrecios.yAxis().title("Cantidad de Eventos");
          chartPrecios.yScale().ticks().interval(1);
          chartPrecios.tooltip().format(function () {
            return "Número de eventos: " + this.value;
          });
          chartPrecios.container("precio-chart");
          chartPrecios.draw();
        } else {
          // Si no hay datos de precios, mostrar mensaje
          document.getElementById("precio-chart").innerHTML = "No hay datos de precios.";
        }
      });
  }

  // Ejecutar todas las funciones para mostrar los gráficos y estadísticas en la carga del documento
  cargarAsistenciaPorHora();
  cargarEventosPorDepartamento();
  cargarAforoVsRegistrados();
  cargarEstadisticasGenerales();
});
