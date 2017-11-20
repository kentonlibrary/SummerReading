<?php
if(isset($_POST['branch'])){
  setcookie("Branch", $_POST['branch'], time + (3600 * 24));
  header('Location: award.php');
  
}
?>
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
  <title>Home</title>
  <link href="../assets/main.css" rel="stylesheet" type="text/css">
  <link href="../assets/mobile.css" rel="stylesheet" type="text/css" media="screen and (max-device-width: 500px)">
  <link href="../assets/desktop.css" rel="stylesheet" type="text/css" media="screen and (min-device-width:501px)">
</head>

<body>
  <div id="right">
    
    <div id="login">
      <img src="../assets/KCPL_Logo-Horiz_Color.png" width="90%" alt="logo"/>
      <form action="" method="post">
        <select name="branch" id="branch">
          <option value="Covington">Covington</option>
          <option value="Durr">William E. Durr</option>
          <option value="Erlanger">Erlanger</option>
        </select>
        <br>
        <input class="loginField" type="submit" value="Login" id="loginButton" name="loginButton"><br><br>
        <a class="forgotPassword" href="../index.php">Patron Login</a>
    </form>
	  </div>
	
	</div>
  <div id="left">
    <div class="details">
      This is the login page for our staff.
    </div>
  </div>
</body>
</html>
