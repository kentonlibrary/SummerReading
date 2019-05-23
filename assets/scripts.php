<?php
//MySQL Connection Settings
include("/var/www/html/summerReading/assets/settings.php");

$connection = new mysqli($servername, $username, $password, $db);

if($connection->connect_error){
  die("Connection Failed: " . $connection->connect_error);
}

function getAge( $birthDate ){
  $from = new DateTime($birthDate);
  $to   = new DateTime('today');
  return( $from->diff($to)->y );

}
?>
