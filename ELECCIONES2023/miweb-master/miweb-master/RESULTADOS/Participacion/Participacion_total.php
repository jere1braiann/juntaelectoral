<!DOCTYPE html>
<html>
<head>
    <title>Resultados</title>
    <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

* {
    margin: 0;
    padding: 0;
    font-family: 'Poppins', sans-serif;
}
        .rectangulo {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 10px;
            margin-top: 0px;
        }

        .cuadro {
            background-color: transparent;
            width: 450px;
            height: 550px;
        }

        /* Estilos para ajustar el tamaño de los iframes */
        .cuadro iframe {
            width: 100%;
            height: 100%;
            border: none;

        }
        body {
background-color: BLUE;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            justify-content: center;
            text-align: center;
        }

        h1 {
            text-align: center;
        }

        .resultados {
            text-align: center;
            margin-top: 50px;
        }
        .grafico {
            width: 500px;
            height: 500px;
            margin: 0 auto;
        }

        .resultados p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .resultados .porcentaje {
            font-size: 24px;
            font-weight: bold;
        }

        .resultados .barra {
            width: 300px;
            height: 20px;
            background-color: red;
            position: relative;
            margin: 0 auto;
            overflow: hidden;
            animation: fillBar 2s ease-in-out forwards;
        }

        .resultados .fill {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background-color: green;
            width: 0;
            animation: fillAnimation 2s ease-in-out forwards;
        }

        @keyframes fillAnimation {
            0% {
                width: 0;
            }
            100% {
                width: <?php echo $porcentajeParticipacion; ?>%;
            }
        }

        @keyframes fillBar {
            0% {
                width: 0;
            }
            100% {
                width: 100%;
            }
        }

    </style>
</head>
<body>
    <h1>Participación total</h1>
    <h2></h2>
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

    // Consulta SQL para obtener los datos de la tabla
    $sql = "SELECT dni, apellido_nombre, count, boleta_count FROM padron_electoral_general___hoja_1";
    

    // Ejecutar la consulta y obtener los resultados
    $result = $conn->query($sql);

    // Variables para contabilizar el total de estudiantes que votaron
    $totalEstudiantes = 145;
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
    echo "Del total de " . $totalEstudiantes . " estudiantes, votaron " . $votaron . " (" . $porcentajeParticipacion . "%).";

    // Cerrar la conexión
    $conn->close();
    ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
        setTimeout(function(){
        location.reload();
    }, 180000);
    var participacion = <?php echo $porcentajeParticipacion; ?>;
    var noVotaron = 100 - participacion;

    var ctx = document.createElement('canvas');
    ctx.id = 'barraCircular';
    ctx.classList.add('grafico');
    document.body.appendChild(ctx);

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Votaron', 'No Votaron'],
            datasets: [{
                data: [participacion, noVotaron],
                backgroundColor: ['green', 'RED'],
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
 <div class="rectangulo">
        <div class="cuadro">
            <iframe src="/ELECCIONES2023/miweb-master/miweb-master/RESULTADOS/Participacion/CURSOS/1ERO.PHP"></iframe>
        </div>
        <div class="cuadro">
            <iframe src="/ELECCIONES2023/miweb-master/miweb-master/RESULTADOS/Participacion/CURSOS/2DO.PHP"></iframe>
        </div>
        <div class="cuadro">
            <iframe src="/ELECCIONES2023/miweb-master/miweb-master/RESULTADOS/Participacion/CURSOS/3ERO.PHP"></iframe>
        </div>
        <div class="cuadro">
            <iframe src="/ELECCIONES2023/miweb-master/miweb-master/RESULTADOS/Participacion/CURSOS/4TO.PHP"></iframe>
        </div>
        <div class="cuadro">
            <iframe src="/ELECCIONES2023/miweb-master/miweb-master/RESULTADOS/Participacion/CURSOS/5TO.PHP"></iframe>
        </div>
        <div class="cuadro">
            <iframe src="/ELECCIONES2023/miweb-master/miweb-master/RESULTADOS/Participacion/CURSOS/6TO.php"></iframe>
        </div>
    </div>
</body>
</html>
