<?php
include('assets/scripts.php'); //File with connection information and functions

if(isset($_POST['readerCategory'])){ //Checks to see if readerCategory is set to see if a form needs to be submitted, or to load the page normally
  if($_POST['readerCategory'] == 'olderChild'){ //Starts code loop for older child
    $readerID = $_POST['readerID'];
    $minutes = $_POST['minutes'];

    //SQL Block to insert time into database
    $query = $connection->prepare("INSERT INTO olderChildLog (readerID, timeRead) VALUES (?, ?)");
    $query->bind_param("ii", $readerID, $minutes);
    $query->execute();
    $query->close();
  }
  if($_POST['readerCategory'] == 'youngChild'){ //Starts code loop for younger child
    $readerID = $_POST['readerID'];
    $title = $_POST['title'];
    
    //SQL Block to insert time into database
    $query = $connection->prepare("INSERT INTO youngChildLog (readerID, bookTitle) VALUES (?, ?)");
    $query->bind_param("is", $readerID, $title);
    $query->execute();
    $query->close();
  }
  if($_POST['readerCategory'] == 'teen'){ //Starts code loop for younger child
    $readerID = $_POST['readerID'];
    $title = $_POST['titleTeen'];
    
    //SQL Block to insert time into database
    $query = $connection->prepare("INSERT INTO teenLog (readerID, title) VALUES (?, ?)");
    $query->bind_param("is", $readerID, $title);
    $query->execute();
    $query->close();
  }
}


//Looks up users in logged in account from database

$barcode = $_COOKIE["Barcode"];

$query = "SELECT accountID FROM account WHERE barcode = ?";
	
	if( $stmt = $connection->prepare($query)){
		$stmt->bind_param("s", $barcode);
		$stmt->execute();
		
		$stmt->bind_result($accountID);
		$stmt->fetch();
		
		if(isset($accountID)){
			
			session_start();
			$_SESSION['accountID'] = $accountID;
		}
    elseif( $barcode == "" ){
      header("Location: index.php");
    }
		else{
			header("Location: register.php?barcode=" . $barcode);
		}
		
		
		$stmt->close();
	}

$accountID = $_SESSION['accountID'];
$results = $connection->query("SELECT reader.readerFirstName, reader.readerLastName, reader.readerCategory, reader.readerID FROM reader WHERE accountID = '$accountID'");

?>
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content = "width = device-width, initial-scale = 1.0, minimum-scale = 1, maximum-scale = 1, user-scalable = no" />
  <meta name="apple-mobile-web-app-title" content="KCPL SRC" />
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black" />
  <title>Log</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="assets/main.css" rel="stylesheet" type="text/css">
  <link href="assets/mobile.css" rel="stylesheet" type="text/css" media="screen and (max-device-  width:480px)">
  <link href="assets/desktop.css" rel="stylesheet" type="text/css" media="screen and (min-device-width:481px)">
  <link rel="apple-touch-icon" href="assets/Stamp_iPhone.jpg">
  <link rel="apple-touch-icon" sizes="120x120" href="assets/Stamp_iPhone.jpg">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
  <?php
  foreach( $results as $result){ //Loops through found users in database
    if($result['readerCategory'] == 'olderChild'){ //Loop for older children
      $readerID = $result['readerID'];
      
      //SQL Block
      $logs = $connection->query("SELECT SUM(timeRead) AS loggedTime FROM olderChildLog WHERE readerID = '$readerID'");
      $awarded = $connection->query("SELECT COUNT(*) AS awarded FROM olderChildAward WHERE readerID = '$readerID'");
      $timeLogged = mysqli_fetch_array($logs)['loggedTime'];
      $prized = mysqli_fetch_array($awarded)['awarded'];
      
      //Reset Image Variables
      $rewarded = 0;
      $complete = 0;
      $remainder = 0;
      
      while($timeLogged > 0){ //While there is time that has been unprocessed
        if($timeLogged >= 150){ //If more than 150 minutes (2.5 Hours) have not been counted
          $complete += 1;  //Add 1 to the completed challenges count
          $timeLogged = $timeLogged - 150; //Remove 150 minutes from unprocessed time
        }
        else{ //If there is less than 150 minute remaining
          $remainder = $timeLogged;
          $timeLogged = $timeLogged - $timeLogged; //Resets time Logged to 0
        }
      }
  ?>
  <button class="mobile-only mobileButton" data-toggle="collapse" data-target="#<?php echo $readerID;?>"><?php echo $result['readerFirstName'] . " " . $result['readerLastName'];?></button>
  <div class="hours collapse" id="<?php echo $readerID;?>">
    <div class="hoursLeft">
      <form action="" method="post">
        <input type="hidden" name="readerID" id="readerID" value="<?php echo $readerID;?>">
        <input type="hidden" name="readerCategory" id="readerCategory" value="<?php echo $result['readerCategory'];?>">
        <font size="+3">How many minutes did <?php echo $result['readerFirstName'];?> read?</font><br>
        <input class="minutes" type="text" id="minutes" name="minutes" pattern="\d*" style="vertical-align: middle" maxlength="2">
        <input type="image" style="vertical-align: middle" width="50px" value="submit" src="assets/add.png" alt="submit Button">
      </form>
    </div>
    <div class="hoursRight">
      <?php
      while ( $complete > 0){ //While there are unshown completed logs images
        if($prized > 0){ // If not all prizes have been marked
          $dn = "DN"; //Mark the next image as awarded
          $prized--; //Subtract 1 from awarded loop
        }
        else{ //If all price images have been marked
          $dn = ""; //Leave the next image unmarked
        }
        ?>
        <img src="assets/booker.png" height="150px" alt="booker" class="booker bookerCP <?php echo $dn; ?>"/> <!-- Show a completed booker image.  $dn either make image opaque if award has been given, or left alone if award has not been given -->
      <?php
        $complete--; //Subtract 1 from the added images loop counter
      };
      
      $percentage = 100 - ( ( $remainder / 150 ) * 100 ); //Create a percentage for how full to fill the booker image on the remaining minutes
      ?>
      <img src="assets/booker.png" height="150px" alt="booker" class="booker bookerIP" style="-webkit-clip-path: inset(<?php echo $percentage; ?>% 0 0 0); clip-path: inset(<?php echo $percentage; ?>% 0 0 0);"/> 
    </div>
  </div>
  <?php
    }
    if($result['readerCategory'] == 'youngChild'){ //Loops throug young children on the logged in account
      $readerID = $result['readerID'];
      
      //SQL Block
      $logs = $connection->query("SELECT COUNT(*) AS loggedBooks FROM youngChildLog WHERE readerID = '$readerID'");
      $awarded = $connection->query("SELECT COUNT(*) AS awarded FROM youngChildAward WHERE readerID = '$readerID'");
      $loggedBooks = mysqli_fetch_array($logs)['loggedBooks']; //Find how many books child has logged
      $prized = mysqli_fetch_array($awarded)['awarded']; //How many book prizes have been awarded
      
      
      //Reset Image Variables
      $rewarded = 0;
      $complete = 0;
      $remainder = 0;
      
      while($loggedBooks > 0){ //While books still need placed on shelf
        if($loggedBooks >= 5){ //If there are more than 5 books
          $complete += 1; //Add 1 to full shelf counter
          $loggedBooks = $loggedBooks - 5; //Substract 5 books from unprocessed books
        }
        else{ //If there are less than 5 books remaining
          $remainder = $loggedBooks;
          $loggedBooks = $loggedBooks - $loggedBooks; //Resets log to 0
        }
      }
  ?>
  <br class="mobile-only"><button class="mobile-only mobileButton" data-toggle="collapse" data-target="#<?php echo $result['readerID'];?>"><?php echo $result['readerFirstName'] . " " . $result['readerLastName'];?></button>
  <div class="books collapse" id="<?php echo $result['readerID'];?>">
    <div class="booksLeft">
      <form action="" method="post">
        <input type="hidden" name="readerID" id="readerID" value="<?php echo $readerID;?>">
        <input type="hidden" name="readerCategory" id="readerCategory" value="<?php echo $result['readerCategory'];?>">
        <font size="+3">What book did <?php echo $result['readerFirstName'];?> read?</font><br>
        <input class="title" type="text" id="title" name="title" style="vertical-align: middle">
        <input type="image" style="vertical-align: middle" width="50px" value="submit" src="assets/add.png" alt="submit Button">
      </form>
    </div>
    <div class="booksRight">
      <?php
      while ( $complete > 0){ //While there are completed shelves that need to be shown
        if( $prized > 0 ){ //If not all prized awarded have been marked
          $dn = "DN";
          $prized--;
        }
        else{ //If all prizes have been marked
          $dn = ""; //Do not mark out the next image
        }
       ?>
        <img src="assets/book5.png" height="75px" alt="book" class="book <?php echo $dn; ?>">
      <?php
        $complete--; //Subtract 1 from completed loop
      };
      if($remainder > 0){ //If there is a remainder of books left
      ?>
      <img src="assets/book<?php echo $remainder;?>.png" height="75px" alt="book" class="book"> <!-- show image that corrisponds with the remaining books -->
      <?php } ?>
    </div> 
  </div>
<?php
    }
        if($result['readerCategory'] == 'teen'){ //Loops throug teens on the logged in account
      $readerID = $result['readerID'];
      
      //SQL Block
      $logs = $connection->query("SELECT COUNT(*) as count FROM teenLog WHERE WEEK(timestamp, 1) = WEEK(CURDATE(), 1) AND readerID = '$readerID'");
      $loggedBooks = mysqli_fetch_array($logs, MYSQLI_ASSOC); //Find how many books child has logged
  ?>
  <br class="mobile-only"><button class="mobile-only mobileButton" data-toggle="collapse" data-target="#<?php echo $result['readerID'];?>"><?php echo $result['readerFirstName'] . " " . $result['readerLastName'];?></button>
  <div class="books collapse" id="<?php echo $result['readerID'];?>">
    <div class="booksLeft">
      <form action="" method="post">
        <input type="hidden" name="readerID" id="readerID" value="<?php echo $readerID;?>">
        <input type="hidden" name="readerCategory" id="readerCategory" value="<?php echo $result['readerCategory'];?>">
        <font size="+3">What book did <?php echo $result['readerFirstName'];?> read?</font><br>
        <input class="title" type="text" id="titleTeen" name="titleTeen" style="vertical-align: middle">
        <input type="image" style="vertical-align: middle" width="50px" value="submit" src="assets/add.png" alt="submit Button">
      </form>
    </div>
    <div class="booksRight">
        <div class="bookContainer">
          <font color="black" size="+2"><?php echo $result['readerFirstName'] . " has " . $loggedBooks['count'] . " entry's in this week drawing.";?></font>

      </div>
    </div> 
  </div>
<?php
    }
  }
?>
<div class="barcode" style="background-color: white; padding: 10px; text-align: justify;">
  <p style="font-family: 'code39azalearegular'; font-size: 48px; text-align: center; margin-bottom: -10px">*<?php echo $barcode; ?>*</p> 
  <p style="text-align: center;"><?php echo $barcode; ?></p>
</div>
</body>
</html>

<?php
//Close SQL Connection
$results->close();
$connection->close();
?>
