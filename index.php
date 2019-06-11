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
  <meta name="apple-mobile-web-app-title" content="KCPL SRC" />
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
        <input class="loginField" type="text" placeholder="Library Card/Phone #" maxlength="14" name="card" id="card"><br>
<br>
        <input class="loginField" type="submit" value="Login/Register" id="loginButton" name="loginButton"><br><br>
    </form>
	<p>To register, please enter a Library card/phone number then click Login/Register to begin.</p>
	  </div>
	
	</div>
  <div id="left"> <!-- More information panel on left side of the page -->
    <div class="details">
      <h1>Summer Reading Celebration Guidelines<h1>
      <h2>CHILDREN (Ages 2-12)</h2>
      Pick up a book log at the Library or register at kentonlibrary.org/src and start reading or listening to books.
      After 5 books or 2.5 hours of reading, return the log to the Library or enter online to receive a book prize.
      After 10 books or 5 hours of reading, return the log or enter online to receive a special glow-in-the-dark T-shirt OR a drawstring backpack (while supplies last) and a raffle ticket to win a grand prize: your choice of an art, science or technology basket.
      <h2>TEENS (Grades 6-12)</h2>
      Read or listen to books, audio books, E-books and magazine or attend a book related program to enter.
      Prizes awarded bi-weekly.
      <h2>ADULTS</h2>
      Read or listen to books, audio books, e-books and periodicals to enter.
      Enter at kentonlibrary.org/src or by visiting a branch.
      Prizes awarded bi-weekly. 
      <h2>Racing to Read</h2>
      Read 30 books to your class, children will collect the book prize to take home to start their personal library! Their teacher will also get a FREE book to add to the classroom library.  Read another 30 books to your class, children may collect 2nd free book to take home (while book supplies last).  Prizes are delivered on Racing to Read’s regularly scheduled June & July visits to your centers.  **Extra prizes will not be left for children who are not at school on prize day. Children must be present to get a prize.  

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

