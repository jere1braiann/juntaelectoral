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
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Consulta para obtener los datos de la tabla
$sql = "SELECT lista, presidencia, secretarias FROM escrutinio_final";
$result = $conn->query($sql);

// Array para almacenar los datos de la consulta
$data = array();

// Verificar si se obtuvieron resultados de la consulta
if ($result->num_rows > 0) {
    // Recorrer los resultados y agregarlos al array
    while ($row = $result->fetch_assoc()) {
        $lista = $row["lista"];
        $total = $row["presidencia"] + $row["secretarias"];
        $data[] = array($lista, $total);
    }
} else {
    echo "No se encontraron resultados en la tabla.";
}

// Cerrar la conexión a la base de datos
$conn->close();

// Calcular la suma total
$total = 0;
foreach ($data as $row) {
    $total += $row[1];
}

// Calcular los porcentajes
$porcentajes = array();
foreach ($data as $row) {
    $porcentaje = ($row[1] / $total) * 100;
    $porcentajes[] = $porcentaje;
}

// Generar el gráfico circular utilizando Chart.js
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gráfico Circular</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

* {
    margin: 0;
    padding: 0;
    font-family: 'Poppins', sans-serif;
}
        /* Estilo para el contenedor del gráfico */
        body {
            justify-content: center;
    align-items: center;
    min-height: 100vh;
    width: 100%;
    background: url('6.png') no-repeat;
    background-position: center;
    background-size: cover;
}


        h1 {
            text-align: center;
            color: #fff;
            height: 30px;

        }
        #chart-container {
            width: 350px; /* Ancho deseado */
            height: 400px; /* Alto deseado */
            margin: 0 auto; /* Centrar el contenedor en la página */
        }
    button{
        width: 60px; /* Ancho deseado */
            height: 99px; /* Alto deseado */
            margin: 0 auto; /* Centrar el contenedor en la página */
    }

    </style>
</head>
<body>
<H1>ELECCIONES GENERALES</H1>

    <div id="chart-container">
        <canvas id="myChart"></canvas>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <button class="btn btn-primary btn-lg btn-block" onclick="verResultadosEspecificos()">Ver Resultados Específicos</button>
            </div>
        </div>

    <script>
                setTimeout(function(){
        location.reload();
    }, 180000);
                function verResultadosEspecificos() {
            // Redireccionar a index.html
            window.location.href = "/ELECCIONES2023/miweb-master/miweb-master/RESULTADOS/Ganador/Pdte. y Secs/Especifico.php";
        }
    var ctx = document.getElementById('myChart').getContext('2d');
    var data = {
        labels: [
            <?php foreach ($data as $row) { ?>
                '<?php echo $row[0]; ?>',
            <?php } ?>
        ],
        datasets: [{
            data: [
                <?php foreach ($porcentajes as $porcentaje) { ?>
                    <?php echo $porcentaje; ?>,
                <?php } ?>
            ],
            backgroundColor: [
                'rgba(255, 99, 132, 0.6)',
                'rgba(54, 162, 235, 0.6)',
                'rgba(255, 245, 245, 0.6)',
            ],
        }],
    };
    var options = {};
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: data,
        options: options
    });
    </script>
</body>
</html>
