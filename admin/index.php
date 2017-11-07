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
      <form action="award.php" method="post">
        <input class="loginField" type="text" placeholder="Library Card Number" maxlength="14" name="card" id="card" pattern="\d*"><br>
        <input class="loginField" type="password" placeholder="Pin" name="pin" id="pin"><br>
        <input class="loginField" type="submit" value="Login" id="loginButton" name="loginButton"><br><br>
        <a class="forgotPassword" href="https://catalog.kentonlibrary.org/eg/opac/password_reset" target="_blank">Forgot Password?</a> | <a class="forgotPassword" href="../index.php">Patron Login</a>
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
