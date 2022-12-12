<?php 
// Inicio sesiones, util para compartir variables a otros archivos php
session_start();

$servername = "localhost";
$username = "root";
$password = "";

$connection = new mysqli($servername, $username, $password);  # base de datos en localhost, de ahi credenciales
mysqli_select_db($connection,"prueba");
mysqli_query($connection, "SET NAMES 'utf8'");


// Recuperando del html
$user = strip_tags($_POST ['user']); # Strip tags es un método de seguridad para evitar comandos html y que se ejecuten algunas cosas (SQL injection)

$password = sha1(strip_tags($_POST ['password'])); 

$query = @mysqli_query($connection,'SELECT * FROM users 
WHERE user="'.mysqli_real_escape_string($connection,$user).'" 
AND password ="'.mysqli_escape_string($connection,$password).'"');


if ($exists = @mysqli_fetch_object($query)){
    $hoy = date("Y-m-d H:i:s");

    $_SESSION ['logged'] = 'yes';

    //extraemos todos los datos de este usuario en particular
    $query =mysqli_query($connection, "SELECT * FROM `users` WHERE `user` = '$user'");
    $row = mysqli_fetch_array($query);

    $_SESSION ['user'] = $user;
    $_SESSION ['user_id'] = $id;
    $_SESSION ['mail'] = $mail;

    mysqli_close($connection);

    echo "true";

    echo '<meta http-equiv="Refresh" content="1;https://github.com/dpflores">'; //para redirigirte a otra página con 1 segundo de delay

}
else{
    $_SESSION ['logged']='no';
    echo "false";
}

?>
##