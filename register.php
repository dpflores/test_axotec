<?php

$servername = "localhost";
$username = "root";
$password = "";

$connection = new mysqli($servername, $username, $password);  # base de datos en localhost, de ahi credenciales
mysqli_select_db($connection,"prueba");
mysqli_query($connection, "SET NAMES 'utf8'");

$user = strip_tags($_POST ['user']); # Strip tags es un método de seguridad para evitar comandos html y que se ejecuten algunas cosas (SQL injection)

$mail = strip_tags($_POST ['mail']);

$password = sha1(strip_tags($_POST ['password'])); 

$password_for_length = strip_tags($_POST ['password']);

$len_pass = strlen($password_for_length);

$today = date("Y-m-d H:i:s"); # Timestamp

if ($len_pass<8) {
    echo "La contraseña tien que tener al menos 8 caracteres";
    die();
}

# para comparar password 
$r_password = sha1(strip_tags($_POST ['r_password']));

# Aceptó los terminos o no
$accepted = isset($_POST ['terms']);

if ($user == NULL || $mail == NULL || $password == NULL || $r_password == NULL){
    echo "No pueden haber campos vacios";
    die();
}

if ($accepted == NULL){
    echo "Por favor, acepta los términos";
    die();
}

$query = @mysqli_query($connection,"SELECT `user` FROM users WHERE user='$user'");
$row = mysqli_fetch_array($query);

if ($row[0] == $user){
    echo "Nombre de usuario ya registrado";
    die();
}

else{
    $query = @mysqli_query($connection,"SELECT `mail` FROM users WHERE mail='$mail'");
    $row = mysqli_fetch_array($query);

    if ($row[0] == $mail){
        echo "email ya registrado";
        die();
    }

    else {
        if ($password != $r_password) {
            echo "Las contraseñas no coinciden";
        }
        else{
            
            $query = @mysqli_query($connection, "INSERT INTO `users` (`id`, `fecha`, `user`, `password`, `mail`)
            VALUES (NULL, '$today', '$user', '$password', '$mail')");

            $to = $mail;

            $subject = "Bienvenido al servidor IOT";

            $message = 'Hola,'.$user.'tu usuario es: '.$user.',  ya puedes loguearte en el sistema';

            $headers = 'From: delpiero22.flores@gmai.com'."\r\n".'Reply-To:
             delpiero22.flores@gmail.com'."\r\n".'X-Mailer: PHP/'. phpversion();

             mail($to,$subject,$message, $headers);     # solo funciona en un hosting real

             echo "Registrado";
             echo "<br>";
             echo "mail enviado";
        }
    }
}

?>