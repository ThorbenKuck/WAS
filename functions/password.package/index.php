<?php 

require 'password.functions.php';

if(!PasswordCompat\binary\check()) {
	echo "(Warning): Das Passwort-Package ist nicht mit der Vorgegebenen PHP-Version Kompatibel!";
}




?>