<?php
require("db.class.php");
   $user = 'root';
   $password = 'root';
   $db = 'iut-info-tlse3';
   $host = 'localhost';
   $port = 8889;
   //session_destroy();
   $connection = new db;
   $co = $connection->connection($host,$user,$password,$db);
   session_start();


   $user_check = $_SESSION['user'];

   $ses_sql = mysqli_query($co,"select id_user from user where login = '$user_check' ");

   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);

   $login_session = $row['id_user'];

   if(!isset($_SESSION['user'])){
      header("location:index.php");
   }
?>
