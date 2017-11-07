<?php
//MySQL Connection Settings

$servername = 'localhost';
$username = 'summerReading';
$password = 'WD46M0D6KKnWclBt';
$db = 'summer_reading';

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