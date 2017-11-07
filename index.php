<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
  <title>Home</title>
  <link href="assets/main.css" rel="stylesheet" type="text/css">
  <link href="assets/mobile.css" rel="stylesheet" type="text/css" media="screen and (max-device-width: 500px)">
  <link href="assets/desktop.css" rel="stylesheet" type="text/css" media="screen and (min-device-width:501px)">
</head>

<body>
  <div id="right">
    
    <div id="login"> <!-- Login form for website -->
      <img src="assets/KCPL_Logo-Horiz_Color.png" width="90%" alt="logo"/>
      <form action="log.php" method="post">
        <input class="loginField" type="text" placeholder="Library Card Number" maxlength="14" name="card" id="card" pattern="\d*"><br>
        <input class="loginField" type="password" placeholder="Pin" name="pin" id="pin"><br>
        <input class="loginField" type="submit" value="Login" id="loginButton" name="loginButton"><br><br>
        <a class="forgotPassword" href="https://catalog.kentonlibrary.org/eg/opac/password_reset" target="_blank">Forgot Password?</a> | <a class="forgotPassword" href="admin/index.php">Library Staff</a>
    </form>
	  </div>
	
	</div>
  <div id="left"> <!-- More information panel on left side of the page -->
    <div class="details">
      Welcome to the Kenton County Public Library Summer Reading Program.  Click on the link below to download the paper summer reading form, or login with your library card and pin to use the digital reading log.
    </div>
  </div>
</body>
</html>
