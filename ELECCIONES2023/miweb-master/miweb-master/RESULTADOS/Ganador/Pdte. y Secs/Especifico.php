<?php
    // Verificar si el usuario ha iniciado sesión
    session_start();
    if (!isset($_SESSION['loggedin'])) {
    // El usuario no ha iniciado sesión, redireccionar al inicio de sesión
    header("Location: login.php");
    exit;
   }
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

// Consulta para obtener los votos por lista en las categorías de presidencia y secretarias
$sql = "SELECT lista, presidencia, secretarias FROM escrutinio_final";
$result = $conn->query($sql);

// Array para almacenar los datos de la consulta
$dataPresidencia = array();
$dataSecretarias = array();

// Verificar si se obtuvieron resultados de la consulta
if ($result->num_rows > 0) {
    // Recorrer los resultados y agregarlos al array correspondiente
    while ($row = $result->fetch_assoc()) {
        $lista = $row["lista"];
        $votosPresidencia = $row["presidencia"];
        $votosSecretarias = $row["secretarias"];
        $dataPresidencia[] = array($lista, $votosPresidencia);
        $dataSecretarias[] = array($lista, $votosSecretarias);
    }
} else {
    echo "No se encontraron resultados en la tabla.";
}

// Calcular el total de votos
$totalVotos = 0;
foreach ($dataPresidencia as $row) {
    $totalVotos += $row[1];
}
foreach ($dataSecretarias as $row) {
    $totalVotos += $row[1];
}

// Cerrar la conexión a la base de datos
$conn->close();

// Generar el gráfico de barras utilizando Chart.js
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gráfico de Barras</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <style>
        /* Estilo para el contenedor del gráfico */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

* {
    margin: 0;
    padding: 0;
    font-family: 'Poppins', sans-serif;
}
        body {
            justify-content: center;
    align-items: center;
    min-height: 100vh;
    width: 100%;
    background: url('90.png') no-repeat;
    background-position: center;
    background-size: cover;
    color: white;
    text-align: center;
        }

        h2 {
            text-align: center;
        }
#chart-container {
    width: 1150px; /* Ancho deseado */
    height: 750px; /* Alto deseado */
    margin: 0 auto; /* Centrar el contenedor en la página */
    border-radius: 8px;
    background-color: rgba(0, 0, 0, 0.6);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
}
    </style>
</head>
<body>
    <div id="chart-container">
        <canvas id="myChart"></canvas>
    </div>

    <p>
        <?php
        // Obtener la lista con más votos en la categoría de presidencia
        $ganadorPresidencia = '';
        $votosMaxPresidencia = 0;
        foreach ($dataPresidencia as $row) {
            if ($row[1] > $votosMaxPresidencia) {
                $ganadorPresidencia = $row[0];
                $votosMaxPresidencia = $row[1];
            }
        }

        // Obtener la lista con más votos en la categoría de secretarias
        $ganadorSecretarias = '';
        $votosMaxSecretarias = 0;
        foreach ($dataSecretarias as $row) {
            if ($row[1] > $votosMaxSecretarias) {
                $ganadorSecretarias = $row[0];
                $votosMaxSecretarias = $row[1];
            }
        }

        echo "Ganador en Presidencia: $ganadorPresidencia (Votos: $votosMaxPresidencia)<br>";
        echo "Ganador en Secretarias: $ganadorSecretarias (Votos: $votosMaxSecretarias)<br>";
        echo "Total de votos contabilizados: $totalVotos";
        ?>
    </p>

    <script>
                setTimeout(function(){
        location.reload();
    }, 180000);
    var ctx = document.getElementById('myChart').getContext('2d');
    var data = {
        labels: [
            <?php foreach ($dataPresidencia as $row) { ?>
                '<?php echo $row[0]; ?>',
            <?php } ?>
        ],
        datasets: [{
            label: 'Votos en Presidencia',
            data: [
                <?php foreach ($dataPresidencia as $row) { ?>
                    <?php echo $row[1]; ?>,
                <?php } ?>
            ],
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
        },
        {
            label: 'Votos en Secretarias',
            data: [
                <?php foreach ($dataSecretarias as $row) { ?>
                    <?php echo $row[1]; ?>,
                <?php } ?>
            ],
            backgroundColor: 'rgba(255, 99, 132, 0.6)',
        }],
    };
    var options = {
        scales: {
            y: {
                beginAtZero: true,
                precision: 0,
            }
        }
    };
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: options
    });
    </script>
</body>
</html>
