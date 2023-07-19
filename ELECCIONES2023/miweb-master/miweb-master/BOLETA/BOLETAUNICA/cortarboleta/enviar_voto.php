<?php
// Obtener los valores de selección del formulario
$president = $_POST['president'];
$secretary = $_POST['secretary'];

// Verificar qué combinación se cumplió
if ($president === 'Lista PEV' && $secretary === 'Lista MIVP') {
    // Ejecutar consulta correspondiente a la combinación 1
    $consulta = "UPDATE escrutinio_final SET presidencia = CASE WHEN id = 1 THEN presidencia + 1 ELSE presidencia END, secretarias = CASE WHEN id = 2 THEN secretarias + 1 ELSE secretarias END WHERE id IN (1, 2)";
} else if ($president === 'Lista PEV' && $secretary === 'Voto en blanco') {
    // Ejecutar consulta correspondiente a la combinación 2
    $consulta = "UPDATE escrutinio_final SET presidencia = CASE WHEN id = 1 THEN presidencia + 1 ELSE presidencia END, secretarias = CASE WHEN id = 3 THEN secretarias + 1 ELSE secretarias END WHERE id IN (1, 3)";
} else if ($president === 'Lista MIVP' && $secretary === 'Lista PEV') {
    // Ejecutar consulta correspondiente a la combinación 3
    $consulta = "UPDATE escrutinio_final SET presidencia = CASE WHEN id = 2 THEN presidencia + 1 ELSE presidencia END, secretarias = CASE WHEN id = 1 THEN secretarias + 1 ELSE secretarias END WHERE id IN (1, 2)";
} else if ($president === 'Lista MIVP' && $secretary === 'Voto en blanco') {
    // Ejecutar consulta correspondiente a la combinación 4
    $consulta = "UPDATE escrutinio_final SET presidencia = CASE WHEN id = 2 THEN presidencia + 1 ELSE presidencia END, secretarias = CASE WHEN id = 3 THEN secretarias + 1 ELSE secretarias END WHERE id IN (2, 3)";
} else if ($president === 'Voto en blanco' && $secretary === 'Lista PEV') {
    // Ejecutar consulta correspondiente a la combinación 5
    $consulta = "UPDATE escrutinio_final SET presidencia = CASE WHEN id = 3 THEN presidencia + 1 ELSE presidencia END, secretarias = CASE WHEN id = 1 THEN secretarias + 1 ELSE secretarias END WHERE id IN (1, 3)";
} else if ($president === 'Voto en blanco' && $secretary === 'Lista MIVP') {
    // Ejecutar consulta correspondiente a la combinación 6
    $consulta = "UPDATE escrutinio_final SET presidencia = CASE WHEN id = 3 THEN presidencia + 1 ELSE presidencia END, secretarias = CASE WHEN id = 2 THEN secretarias + 1 ELSE secretarias END WHERE id IN (2, 3)";
} else if ($president === 'Voto en blanco' && $secretary === 'Voto en blanco') {
    // Ejecutar consulta correspondiente a la combinación 7
    $consulta = "UPDATE escrutinio_final SET presidencia = presidencia + 1, secretarias = secretarias + 1 WHERE id = 3";
}

// Realizar la conexión a la base de datos
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'dni_database';

$conn = new mysqli($host, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Ejecutar la consulta
if ($conn->query($consulta) === TRUE) {
    echo "¡El voto se envió correctamente!";
} else {
    echo "Error al enviar el voto: " . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
