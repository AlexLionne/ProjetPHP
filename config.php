<?php
$log = new log;
$user = 'root';
$password = 'root';
$db = 'iut-info-tlse3';
$host = 'localhost';
$port = 8889;
//session_destroy();
$connection = new db;
$connection->connection($host,$user,$password,$db);
?>
