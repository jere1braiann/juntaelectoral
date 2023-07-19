<!DOCTYPE html>
<html>
<head>
  <title>Formulario de ingreso de DNI</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
    background: url('4.png') no-repeat;
    background-position: center;
    background-size: cover;

  }
  
    .container {
      max-width: 500px;
      margin: 100px auto;
      background-color: transparent;
      padding: 0px;
      border-radius: 30px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    }
    
    .container h2 {
      text-align: center;
      margin-bottom: 0px;
      color: #ffffff;
    }
    
    .form-group label {
      font-weight: bold;
      color: #ffffff;
    }
    
    .form-control {
      border-radius: 10px;
      background-color: #7b63a1;
      color: #ffffff;
    }
    
    .btn-primary {
      width: 100%;
      background-color: #9752e6;
      border-color: #9752e6;
      background-color: #0c031d;
      padding: 10px 20px;
      align-items: center;
      justify-content: space-between;
      color: #ffffff;
      margin-top: 10px;
      font-size: 14px;
      
    }
    
    .btn-primary:hover {
      background-color: #8e47d2;
      border-color: #8e47d2;
      justify-content: space-between;
      
    }
    
    .fa-lock {
      color: #ffffff;
      opacity: 0.8;
    }
    
    /* Estilo futurista del encabezado */
    
    
    /* Estilo del pie de página */


    /* Estilos de los resultados */
    .alert-heading {
      margin-top: 0;
      margin-bottom: 1rem;
    }
    
    .alert p {
      margin-bottom: 0.5rem;
    }
    
    .btn-habilitar {
      width: 100%;
      background-color: greenyellow;
      border-color: #a85ee6;
    }
    
    .btn-habilitar:hover {
      background-color: #9b47d2;
      border-color: #9b47d2;
    }
    
    /* Estilo para resaltar en rojo */
    .alert-danger {
      background: transparent;
      border-color: #f5c6cb;
      color: #c11325;
      padding: 0.75rem 1.25rem;
      margin-bottom: 1rem;
      border-radius: 0.25rem;
    }
  </style>
</head>
<body>
    
  <div class="container">
    <h2><i class="fas fa-lock"></i> Formulario de ingreso de DNI</h2>
    <?php
    // Verificar si el usuario ha iniciado sesión
    session_start();
    if (!isset($_SESSION['loggedin'])) {
    // El usuario no ha iniciado sesión, redireccionar al inicio de sesión
    header("Location: login.php");
    exit;
   }
    
    // Datos de conexión a la base de datos
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'dni_database';
    $conn = new mysqli($host, $username, $password, $database);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Variables para almacenar los resultados
    $curso = "";
    $apellidoNombre = "";
    $resultMessage = "";

    // Verificar si se ha enviado el formulario y se ha proporcionado el DNI
    if (isset($_POST['dni'])) {
        // Obtener el DNI ingresado
        $dni = $_POST['dni'];

        // Consulta a la base de datos
        $sql = "SELECT curso, apellido_nombre, habilitado FROM padron_electoral_general___hoja_1 WHERE dni = '$dni'";
        $result = $conn->query($sql);

        // Verificar si se encontraron resultados
        if ($result->num_rows > 0) {
            // Obtener el primer resultado
            $row = $result->fetch_assoc();
            $curso = $row["curso"];
            $apellidoNombre = $row["apellido_nombre"];
            $habilitado = $row["habilitado"];

            // Mostrar los resultados con estilos
            echo '<div class="alert alert-success">';
            echo '<h4 class="alert-heading">Resultados de la consulta:</h4>';
            echo '<p><strong>Curso:</strong> ' . $curso . '</p>';
            echo '<p><strong>Apellido y Nombre:</strong> ' . $apellidoNombre . '</p>';
            echo '<p><strong>Habilitado para votar:</strong> ' . $habilitado . '</p>';
            echo '</div>';
            echo '<form action="consulta_dni.php" method="POST">';
            echo '<input type="hidden" name="dni" value="' . $dni . '">';
            if ($habilitado === 'no') {
                echo '<button type="submit" name="habilitar" class="btn btn-habilitar">Habilitar para votar</button>';
            } else {
                echo '<button type="button" class="btn btn-habilitar" disabled>Habilitado para votar</button>';
            }
            echo '</form>';
        } else {
            // Mostrar mensaje si no se encontraron resultados
            echo '<div class="alert alert-danger">';
            echo '<h4 class="alert-heading">No se encontraron resultados.</h4>';
            echo '</div>';
        }
    }

    // Manejar el formulario enviado para habilitar el voto
    if (isset($_POST['habilitar'])) {
        // Obtener el DNI ingresado
        $dni = $_POST['dni'];

        // Actualizar el valor de la columna "habilitado" en la tabla
        $updateSql = "UPDATE padron_electoral_general___hoja_1 SET habilitado = 'si' WHERE dni = '$dni'";
        if ($conn->query($updateSql) === TRUE) {
            echo '<div class="alert alert-success">';
            echo 'El DNI ha sido habilitado para votar.';
            echo '</div>';
        } else {
            echo '<div class="alert alert-danger">';
            echo 'Error al habilitar el DNI para votar: ' . $conn->error;
            echo '</div>';
        }
    }

    // Cerrar la conexión
    $conn->close();
    ?>
    <form action="consulta_dni.php" method="POST">
        <div class="form-group">
            <label for="dni"><i class="fas fa-user"></i> DNI:</label>
            <input type="text" id="dni" name="dni" class="form-control" required>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Consultar</button>
        </div>
            <div class="buttons">
    <button type="button" class="btn btn-primary" onclick="window.location.href='/ELECCIONES2023/miweb-master/miweb-master/RESULTADOS/index.html'">
  <i class="fas fa-chart-bar"></i> Resultados Finales
</button>
    </form>
  </div>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
