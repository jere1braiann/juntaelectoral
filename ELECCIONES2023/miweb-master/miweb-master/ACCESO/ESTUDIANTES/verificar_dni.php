<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ERROR</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

* {
    margin: 0;
    padding: 0;
    font-family: 'Poppins', sans-serif;
}
 body {
                display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    width: 100%;
    background: url('2.png') no-repeat;
    background-position: center;
    background-size: cover;
  }

        h1 {
            color: #333;
        }

        .error {
            color: red;
            font-size: 20px;
        }

        .success {
            color: green;
            font-size: 20px;
        }

        .container {
            min-height: 100vh;
            margin: 0 auto;
        }
        
    </style>
    <script>
        setTimeout(function() {
            window.location.href = "index.html";
        }, 8000); // Redireccionar después de 5 segundos (5000 milisegundos)
    </script>
</head>
<body>
    <div class="container">

        <?php
        session_start();

        // Verificar si se envió un DNI
        if (isset($_POST['dni'])) {
            // Obtener el DNI ingresado
            $dni = $_POST['dni'];

            if (strlen($dni) != 8) {
                echo '<h4 class="error mt-4">Error: El DNI debe tener 8 dígitos.</h4>';
            } else {
                // Realizar la conexión a la base de datos
                $host = 'localhost';
                $username = 'root';
                $password = '';
                $database = 'dni_database';
                $servername = "mysql:host=$host;dbname=$database";

                $conn = new mysqli($host, $username, $password, $database);

                // Verificar si la conexión fue exitosa
                if ($conn->connect_error) {
                    die('Error de conexión: ' . $conn->connect_error);
                }

                // Consulta SQL para verificar si el DNI está en la base de datos
                $query = "SELECT * FROM padron_electoral_general___hoja_1 WHERE dni = '$dni'";
                $result = $conn->query($query);

                // Verificar si se encontró algún registro
                if ($result->num_rows > 0) {
                    // Obtener la información del DNI
                    $row = $result->fetch_assoc();
                    $habilitado = $row['habilitado'];

                    if ($habilitado == "si") {
                        // Obtener el contador de ingresos del DNI
                        $getCountQuery = "SELECT count FROM padron_electoral_general___hoja_1 WHERE dni = '$dni'";
                        $countResult = $conn->query($getCountQuery);

                        if ($countResult->num_rows > 0) {
                            $countRow = $countResult->fetch_assoc();
                            $count = $countRow['count'];
                            if ($count >= 1) {
                                // El DNI ya ha sido ingresado previamente
                                echo '<h3 class="error mt-4">Usted ya ha ingresado anteriormente y no puede ingresar dos veces.</h3>';
                            } else {
                                // El DNI no ha sido ingresado previamente, actualizar el contador
                                $count = $countRow['count'] + 1;
                                $updateCountQuery = "UPDATE padron_electoral_general___hoja_1 SET count = $count WHERE dni = '$dni'";
                                $conn->query($updateCountQuery);

                                // Redireccionar al usuario a la página de votación
                                header("Location: /ELECCIONES2023/miweb-master/miweb-master/BOLETA/BOLETAUNICA/index.html");
                                exit();
                            }
                        } else {
                            // El DNI no existe en la tabla, insertar un nuevo registro con contador 1
                            $insertCountQuery = "INSERT INTO padron_electoral_general___hoja_1 (dni, count) VALUES ('$dni', 1)";
                            $conn->query($insertCountQuery);

                            // Redireccionar al usuario a la página de votación
                            header("Location: /ELECCIONES2023/miweb-master/miweb-master/BOLETA/BOLETAUNICA/index.html");
                            exit();
                        }
                    } else {
                        // El DNI no está habilitado para votar
                        echo '<h2 class="error mt-4">El DNI ' . $dni . ' no está habilitado para votar. Por favor, consulte en la mesa de fiscales.</h2>';
                    }
                } else {
                    echo '<h2 class="error mt-4">El DNI ' . $dni . ' no se encuentra en la base de datos.</h2>';
                }

                // Cerrar la conexión a la base de datos
                $conn->close();
            }
        }
        ?>
    </div>
</body>
</html>
