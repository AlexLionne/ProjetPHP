
<?php
require("db.class.php");
require("log.class.php");
require("config.php");

session_start();
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form
      $login = $_POST['login'];
      $password = $_POST['password'];
      $connection->login($login,$password,$log);

   }

?>

<html lang="en" >
<head>
  <title>Rendez-vous</title>
      <link rel="stylesheet" href="kube.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <script src="js/kube.js"></script>
</head>
<body style="padding:5%">


  <form class="form w50" action="index.php" method="post" >
  <h2 style="margin-bottom:30px"class="title">Se connecter</h1>

        <div class="form-item">
     <label>Login</label>
     <input type="text" name="login" required>
      </div>

      <div class="form-item">
   <label>Mot de passe</label>
   <input type="text" name="password" required>
  </div>


  <input type="submit" class="button">
  </form>

<script>

//fermeture des messages
setTimeout(function(){
    //do what you need here
    $('.message.focus').message('close');
}, 2000);


</script>
</body>
</html>
