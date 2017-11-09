<?php
include('assets/scripts.php'); //File with connection information and functions

if(isset($_POST['loginButton'])){ //Check if login form has been submitted
	
	$barcode = $_POST['card'];
	
	$query = "SELECT accountID FROM account WHERE barcode = ?";
	
	if( $stmt = $connection->prepare($query)){
		$stmt->bind_param("s", $barcode);
		$stmt->execute();
		
		$stmt->bind_result($accountID);
		$stmt->fetch();
		
		if(isset($accountID)){
			
			session_start();
			$_SESSION['accountID'] = $accountID;
			
			header("Location: log.php");
		}
		else{
			header("Location: register.php?barcode=" . $barcode);
		}
		
		
		$stmt->close();
	}
	
	$connection->close();
	
}

?>
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
      <form action="" method="post">
        <input class="loginField" type="text" placeholder="Library Card Number" maxlength="14" name="card" id="card" pattern="\d*"><br>
<br>
        <input class="loginField" type="submit" value="Login" id="loginButton" name="loginButton"><br><br>
        <a class="forgotPassword" href="admin/index.php">Library Staff Login</a>
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
