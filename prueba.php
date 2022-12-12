<?php

$servername = "localhost";
$username = "root";
$password = "";

$connection = new mysqli($servername, $username, $password);  # base de datos en localhost, de ahi credenciales
mysqli_select_db($connection,"prueba");
mysqli_query($connection, "SET NAMES 'utf8'");

$serie = strip_tags($_POST ['serie']);
$accelx = strip_tags($_POST ['accelx']);
$accely = strip_tags($_POST ['accely']);
$accelz = strip_tags($_POST ['accelz']);

mysqli_query($connection, "INSERT INTO `datos` (`id`, `fecha`, `serie`, `accel_x`, `accel_y`, `accel_z`) VALUES (NULL, current_timestamp(), '$serie', '$accelx', '$accely', '$accelz');");

mysqli_close($connection);

echo "Datos ingresados correctamente"
?>


