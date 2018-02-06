<?php
include('assets/scripts.php'); //File with connection information and functions

if(isset($_GET['logout'])){
  unset($_COOKIE['Barcode']);
  setcookie("Barcode", '', time() - 3600);
}


if(isset($_POST['loginButton'])){ //Check if login form has been submitted
	
	$barcode = $_POST['card'];
  
  setcookie("Barcode", $_POST['card'], time() + (3600 * 24 * 90));
  header("Location: log.php");

  
}

if(isset($_COOKIE["Barcode"])){
  header("Location: log.php");
}

?>
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
<meta name="viewport" content = "width = device-width, initial-scale = 1.0, minimum-scale = 1, maximum-scale = 1, user-scalable = no" />
  <meta name="apple-mobile-web-app-title" content="KCPL SCR" />
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black" />
  <title>Home</title>
  <link rel="apple-touch-icon" href="assets/Stamp_iPhone.jpg">
  <link rel="apple-touch-icon" sizes="120x120" href="assets/Stamp_iPhone.jpg">
  <link href="assets/main.css" rel="stylesheet" type="text/css">
  <link href="assets/mobile.css" rel="stylesheet" type="text/css" media="screen and (max-device-width: 500px)">
  <link href="assets/desktop.css" rel="stylesheet" type="text/css" media="screen and (min-device-width:501px)">
  <link rel="apple-touch-startup-image" href="assets/splash.jpg">
</head>

<body>
  <div id="right">
    
    <div id="login"> <!-- Login form for website -->
      <img src="assets/KCPL_Logo-Horiz_Color.png" width="90%" alt="logo"/>
      <form action="" method="post">
        <input class="loginField" type="text" placeholder="Library Card Number" maxlength="14" name="card" id="card"><br>
<br>
        <input class="loginField" type="submit" value="Login" id="loginButton" name="loginButton"><br><br>
        <a class="forgotPassword" href="admin/index.php">Library Staff Login</a>
    </form>
	  </div>
	
	</div>
  <div id="left"> <!-- More information panel on left side of the page -->
    <div class="details">
      Welcome to the Kenton County Public Library Summer Reading Program.  Click on the link below to download the paper summer reading form, or login with your library card for identification to use the digital reading log.
    </div>
  </div>
  
<script type="text/javascript">
$(document).ready(function(){
        // iOS web app full screen hacks.
        if(window.navigator.standalone == true) {
                // make all link remain in web app mode.
                $('a').click(function() {
                        window.location = $(this).attr('href');
            return false;
                });
        }
});
</script>

</body>
</html>

