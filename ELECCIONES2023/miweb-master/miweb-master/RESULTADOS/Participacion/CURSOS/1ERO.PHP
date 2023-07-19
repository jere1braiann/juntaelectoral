<!DOCTYPE html>
<html>
<head>
    <title>Gráfico de Participación - Primer Año</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: transparent;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            text-align: center;

        }

        h1 {
            text-align: center;
        }

        .grafico {
            width: 300px;
            height: 300px;
            margin: 20px auto;
        }
    </style>
</head>
<body>
    <h1>Gráfico de Participación - Primer Año</h1>

    <?php
    // Configuración de la conexión a la base de datos
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'dni_database';

    // Crear la conexión
    $conn = new mysqli($host, $username, $password, $database);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error en la conexión: " . $conn->connect_error);
    }

    // Consulta SQL para obtener los datos de participación en primer año
    $sql = "SELECT dni, apellido_nombre, count, boleta_count FROM padron_electoral_general___hoja_1 WHERE curso = 'PRIMER AÑO'";

    // Ejecutar la consulta y obtener los resultados
    $result = $conn->query($sql);

    // Variables para contabilizar el total de estudiantes y los que votaron
    $totalEstudiantes = 29;
    $votaron = 0;

    // Verificar si se encontraron resultados
    if ($result->num_rows > 0) {
        // Recorrer los resultados y realizar el cálculo
        while ($row = $result->fetch_assoc()) {
            // Verificar si la fila cumple con la lógica de contar solo los que tienen 'count' y 'boleta_count' iguales a 1
            if ($row['count'] == 1 && $row['boleta_count'] == 1) {
                $votaron++;
            } elseif ($row['count'] != 0 || $row['boleta_count'] != 0) {
                echo "Error: DNI " . $row['dni']  , $row['apellido_nombre'] . " tiene un valor inconsistente en 'count' y 'boleta_count'.";
            }
        }
    } else {
        echo "No se encontraron resultados en la tabla.";
    }

    // Calcular el porcentaje de participación
    $porcentajeParticipacion = ($votaron / $totalEstudiantes) * 100;

    // Mostrar el resultado
    echo "Del total de " . $totalEstudiantes . " estudiantes de primer año, votaron " . $votaron . " (" . $porcentajeParticipacion . "%).";
    ?>

    <div class="grafico">
        <canvas id="barraCircular"></canvas>
    </div>

    <script>
        var participacion = <?php echo $porcentajeParticipacion; ?>;
        var noVotaron = 100 - participacion;

        var ctx = document.getElementById('barraCircular').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Votaron', 'No Votaron'],
                datasets: [{
                    data: [participacion, noVotaron],
                    backgroundColor: ['green', '#555'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                cutoutPercentage: 80,
                animation: {
                    animateRotate: true,
                    animateScale: false
                }
            }
        });
    </script>
</body>
</html>
