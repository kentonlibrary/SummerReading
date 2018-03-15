<?php
include('assets/scripts.php'); //File with connection information and functions

if(isset($_GET['logout'])){
  unset($_COOKIE['Barcode']);
  setcookie("Barcode", '', time() - 3600);
  
  session_start();
  unset($_SESSION);
  session_destroy();
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
    </form>
	  </div>
	
	</div>
  <div id="left"> <!-- More information panel on left side of the page -->
    <div class="details">
      <h2>Adults:</h2>
      Earn one raffle ticket for every checkout receipt. One winner will be drawn every week, win great prizes!
      <h2>Kids: (ages 2-12)</h2>
Pick up a booklog OR register online at src.kentonlibrary.org and start reading or listening to books.
After 10 books or 5 hours of reading, return the log to receive your 2018 SRC T-Shirt OR a drawstring bag (NOTE: drawstring bags are for
ages 5 and up ONLY; all prizers are awarded while supplies last) and a raf e ticket for a chance to win one of 3 grand prizes: a Technology Basket, a Sports Basket, or an Art Basket.
Keep reading for more chances to win the grand prizes
      
      <h2>Teens: (grades 6-12)</h2>
READ & WIN! Read or listen to any book or magazine or attend any program to enter.
Enter at src.kentonlibrary.org or your local KCPL branch.
One winner per branch will be drawn every week for prizes!
      <h2>Questions?</h2>
(859) 962 - 4000 www.kentonlibrary.org/src Prizes provided by Friends of KCPL.
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

