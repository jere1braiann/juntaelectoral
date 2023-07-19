<?php
// Obtener el DNI enviado por POST
if (isset($_POST['dni'])) {
    $dni = $_POST['dni'];

    // Realizar la conexión a la base de datos
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'dni_database';
    $conn = new mysqli($host, $username, $password, $database);

    // Verificar si la conexión fue exitosa
    if ($conn->connect_error) {
        die('Error de conexión: ' . $conn->connect_error);
    }

    // Consulta SQL para verificar si el DNI está habilitado para votar
    $query = "SELECT habilitado FROM padron_electoral_general___hoja_1 WHERE dni = $dni";
    $result = $conn->query($query);

    // Verificar si se encontró algún registro
    if ($result->num_rows > 0) {
        // Obtener el estado de habilitación del DNI
        $row = $result->fetch_assoc();
        $habilitado = $row['habilitado'];

        if ($habilitado == 'si') {
            // El DNI está habilitado, continuar con el proceso completo

            // Consulta SQL para verificar si el DNI cumple las condiciones necesarias para votar
            $checkQuery = "SELECT boleta_count FROM padron_electoral_general___hoja_1 WHERE dni = $dni";
            $checkResult = $conn->query($checkQuery);

            // Verificar si se encontró algún registro
            if ($checkResult->num_rows > 0) {
                // Obtener el valor de boleta_count del DNI
                $checkRow = $checkResult->fetch_assoc();
                $boletaCount = $checkRow['boleta_count'];

                if ($boletaCount == 0) {
                    // Actualizar el contador en la base de datos
                    $updateQuery = "UPDATE padron_electoral_general___hoja_1 SET boleta_count = 1, hora = CURRENT_TIMESTAMP WHERE dni = $dni";
                    $conn->query($updateQuery);

                    echo "true"; // El DNI cumple las condiciones necesarias para votar y se actualizó el contador
                } else {
                    echo "duplicate"; // El DNI ya se ingresó anteriormente
                }
            } else {
                echo "false"; // El DNI no está en la base de datos
            }
        } else {
            echo "not_allowed"; // El DNI no está habilitado para votar
        }
    } else {
        echo "not_found"; // El DNI no está en la base de datos
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}
?>
