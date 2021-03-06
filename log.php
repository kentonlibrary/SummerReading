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
    $title = $_POST['title'];
    $rating = $_POST['rating'];
    
    //SQL Block to insert time into database
    $query = $connection->prepare("INSERT INTO teenLog (readerID, title, rating) VALUES (?, ?, ?)");
    $query->bind_param("isi", $readerID, $title, $rating);
    $query->execute();
    $query->close();
  }
if($_POST['readerCategory'] == 'adult'){ //Starts code loop for younger child
    $readerID = $_POST['readerID'];
    $title = $_POST['title'];
    
    //SQL Block to insert time into database
    $query = $connection->prepare("INSERT INTO adultLog (readerID, title) VALUES (?, ?)");
    $query->bind_param("is", $readerID, $title);
    $query->execute();
    $query->close();
  }
  if($_POST['readerCategory'] == 'r2r'){ //Starts code loop for younger child
    $readerID = $_POST['readerID'];
    $title = $_POST['title'];
    
    //SQL Block to insert time into database
    $query = $connection->prepare("INSERT INTO r2rLog (readerID, bookTitle) VALUES (?, ?)");
    $query->bind_param("is", $readerID, $title);
    $query->execute();
    $query->close();
  }
}


//Looks up users in logged in account from database

$barcode = $_COOKIE["Barcode"];

$query = "SELECT accountID, branch FROM account WHERE barcode = ?";
	
	if( $stmt = $connection->prepare($query)){
		$stmt->bind_param("s", $barcode);
		$stmt->execute();
		
		$stmt->bind_result($accountID, $branch);
		$stmt->fetch();
		
		if(isset($accountID)){
			
			session_start();
			$_SESSION['accountID'] = $accountID;
      $_SESSION['branch'] = $branch;
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
$results = $connection->query("SELECT reader.readerFirstName, reader.readerLastName, reader.readerCategory, reader.readerID, reader.readerSchool, account.lastLogin FROM reader, account WHERE reader.accountID = account.accountID AND reader.accountID = '$accountID'");


  $updateLoginQuery = "UPDATE account SET lastLogin = NOW() WHERE barcode = ?";
  if( $stmt = $connection->prepare($updateLoginQuery)){
    $stmt->bind_param("s", $barcode);
		$stmt->execute();		
		$stmt->close();
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
  <title>Log</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="assets/main.css" rel="stylesheet" type="text/css">
  <link href="assets/mobile.css" rel="stylesheet" type="text/css" media="screen and (max-device-width:550px)">
  <link href="assets/desktop.css" rel="stylesheet" type="text/css" media="screen and (min-device-width:551px)">
  <link rel="apple-touch-icon" href="assets/Stamp_iPhone.jpg">
  <link rel="apple-touch-icon" sizes="120x120" href="assets/Stamp_iPhone.jpg">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>

</style>
</head>

<body>
  <script>
function ShowHelp() {
  document.getElementById('tutorial').style.display = 'block';
};
    
function HideHelp() {
  document.getElementById('tutorial').style.display = 'none';
};
    
function showlast(){
  $('#' + getAnchor()).collapse("show");
  
}
    
function getAnchor(){
  var currentURL = document.URL,
      urlParts = currentURL.split('#');
  
  return (urlParts.length > 1) ? urlParts[1] : null;
}
    
function bookTitleValidation(form){
  var bookTitle = form.title.value;
  if(bookTitle.length < 2){
    alert("Please enter a book title");
    return false;
  }
  else{
    return true;
  }
}
    
window.onload = showlast;
</script>
  <div class="editInfo" style="text-align: right">
    <a style="color:white;" href="javascript:ShowHelp();">Help &nbsp &nbsp</a>
    <a style="color:white;" href="index.php?logout=true">Logoff &nbsp</a>
    <?php
    if( $_SESSION['branch'] != 'r2r'){
    ?>
    <a style="color:white;" href="information.php">&nbsp Edit Information</a>
    <?php } ?>
  </div>
  <?php
  foreach( $results as $result){ //Loops through found users in database
    if($result['readerCategory'] == 'olderChild'){ //Loop for older children
      $readerID = $result['readerID'];
      
      //SQL Block
      $logs = $connection->query("SELECT SUM(timeRead) AS loggedTime FROM olderChildLog WHERE readerID = '$readerID' AND timestamp >= '$startDate'");
      $awarded = $connection->query("SELECT COUNT(*) AS awarded FROM olderChildAward WHERE readerID = '$readerID' AND DATE(timestamp) >= '$startDate'");
      $timeLogged = mysqli_fetch_array($logs)['loggedTime'];
      $prized = mysqli_fetch_array($awarded)['awarded'];
      
      //Reset Image Variables
      $rewarded = 0;
      $complete = 0;
      $remainder = 0;
      $totalMinutesNeeded = 150;
      
      while($timeLogged > 0){ //While there is time that has been unprocessed
        if($complete > 1){ $totalMinutesNeeded = 300; }
          if($timeLogged >= $totalMinutesNeeded){ //If more than 150 minutes (2.5 Hours) have not been counted
            $complete += 1;  //Add 1 to the completed challenges count
            $timeLogged = $timeLogged - $totalMinutesNeeded; //Remove 150 minutes from unprocessed time
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
      <form action="#<?php echo $readerID;?>" method="post">
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
        <img src="assets/tiger.png" height="150px" alt="booker" class="booker bookerCP <?php echo $dn; ?>"/> <!-- Show a completed booker image.  $dn either make image opaque if award has been given, or left alone if award has not been given -->
      <?php 
        if($dn == ""){
      ?>
      <p>Come to the branch to claim your prize!</p>
      <?php
      }
        $complete--; //Subtract 1 from the added images loop counter
      };
      
      
      $percentage = 100 - ( ( $remainder / $totalMinutesNeeded ) * 100 ); //Create a percentage for how full to fill the booker image on the remaining minutes
      ?>
      <div class="bookerContainer">
        <img src="assets/tiger.png" alt="booker" class="booker bookerIP" style="-webkit-clip-path: inset(<?php echo $percentage; ?>% 0 0 0); clip-path: inset(<?php echo $percentage; ?>% 0 0 0); height: 100%"/> 
        <div class="bookerText"><?php echo $remainder . "/" . $totalMinutesNeeded . "<br>minutes";?></div>
        
      </div>
    </div>
  </div>
  <?php
    }
    if($result['readerCategory'] == 'youngChild'){ //Loops through young children on the logged in account
      $readerID = $result['readerID'];
      
      //SQL Block
      $logs = $connection->query("SELECT (SELECT COUNT(*) FROM youngChildLog WHERE readerID = '$readerID' AND DATE(timestamp) >= '$startDate') + (SELECT COUNT(*) FROM eventRating WHERE readerID = '$readerID' AND DATE(timestamp) >= '$startDate') AS loggedBooks");
      $awarded = $connection->query("SELECT COUNT(*) AS awarded FROM youngChildAward WHERE readerID = '$readerID' AND DATE(timestamp) >= '$startDate'");
      $loggedBooks = mysqli_fetch_array($logs)['loggedBooks']; //Find how many books child has logged
      $prized = mysqli_fetch_array($awarded)['awarded']; //How many book prizes have been awarded
      
      
      //Reset Image Variables
      $rewarded = 0;
      $complete = 0;
      $remainder = 0;
			$totalBooksNeeded = 5;
			$usingLong = false;
      
      while($loggedBooks > 0){ //While books still need placed on shelf
				if($complete > 1){ 
						$totalBooksNeeded = 10;
						$usingLong = true;
				}
        if($loggedBooks >= $totalBooksNeeded){ //If there are more than 5 books
          $complete += 1; //Add 1 to full shelf counter
          $loggedBooks = $loggedBooks - $totalBooksNeeded; //Substract 5 books from unprocessed books
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
      <form action="#<?php echo $readerID;?>" onsubmit="return bookTitleValidation(this)" method="post" name="<?php echo $readerID;?>" id="<?php echo $readerID;?>">
        <input type="hidden" name="readerID" id="readerID" value="<?php echo $readerID;?>">
        <input type="hidden" name="readerCategory" id="readerCategory" value="<?php echo $result['readerCategory'];?>">
        <font size="+3">What book did <?php echo $result['readerFirstName'];?> read?</font><br>
        <input class="title" type="text" id="title" name="title" style="vertical-align: middle">
        <input type="image" style="vertical-align: middle" width="50px" value="submit" src="assets/add.png" alt="submit Button">
      </form>
    </div>
    <div class="booksRight">
      <?php
			if(!$usingLong){
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
				<?php } 
			}
			if($usingLong){
				while ( $complete > 0){//While there are completed shelves that need to be shown
					if($complete > 2){
						if( $prized > 0 ){ //If not all prized awarded have been marked
							$dn = "DN";
							$prized--;
						}
						else{ //If all prizes have been marked
							$dn = ""; //Do not mark out the next image
						}
					 ?>
						<img src="assets/Lbook10.png" height="75px" alt="book" class="book <?php echo $dn; ?>">
					<?php
						$complete--; //Subtract 1 from completed loop
					}
					if($complete <= 2){
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
					}
				}
				
				if($usingLong){
					if($remainder > 0){ //If there is a remainder of books left
					?>
					<img src="assets/Lbook<?php echo $remainder;?>.png" height="75px" alt="book" class="book"> <!-- show image that corrisponds with the remaining books -->
					<?php } 
				}
				else{
					if($remainder > 0){ //If there is a remainder of books left
					?>
					<img src="assets/book<?php echo $remainder;?>.png" height="75px" alt="book" class="book"> <!-- show image that corrisponds with the remaining books -->
					<?php } 
				}
			}
			?>
    </div> 
  </div>
<?php
    }
        if($result['readerCategory'] == 'teen'){ //Loops throug teens on the logged in account
      $readerID = $result['readerID'];
      
      //SQL Block
      $logs = $connection->query("SELECT (SELECT COUNT(*) FROM teenLog WHERE WEEK(timestamp, 1) = WEEK(CURDATE(), 1) AND readerID = '$readerID') + (SELECT COUNT(*) FROM eventRating WHERE readerID = '$readerID' AND WEEK(timestamp, 1) = WEEK(CURDATE(), 1)) AS count");
      $loggedBooks = mysqli_fetch_array($logs, MYSQLI_ASSOC); //Find how many books child has logged
  ?>
  <br class="mobile-only"><button class="mobile-only mobileButton" data-toggle="collapse" data-target="#<?php echo $result['readerID'];?>"><?php echo $result['readerFirstName'] . " " . $result['readerLastName'];?></button>
  <div class="books collapse" id="<?php echo $result['readerID'];?>">
    <div class="booksLeft">
      <form action="#<?php echo $result['readerID'];?>" onsubmit="return bookTitleValidation(this)" method="post" name="<?php echo $readerID;?>" id="<?php echo $readerID;?>">
        <input type="hidden" name="readerID" id="readerID" value="<?php echo $readerID;?>">
        <input type="hidden" name="readerCategory" id="readerCategory" value="<?php echo $result['readerCategory'];?>">
        <font size="+3">What book did <?php echo $result['readerFirstName'];?> read?</font><br>
        <input class="title" type="text" id="title" name="title" style="vertical-align: middle">
        <img src="assets/add.png" width="50px" alt="Submit" onclick="openModel(<?php echo $readerID;?>)"/>
</div>
    <div class="booksRight">
        <div class="bookContainer">
          <font color="black" size="+2"><?php echo $result['readerFirstName'] . " has " . $loggedBooks['count'] . " entries in this week drawing.";?></font><br>
          <font color="#F26722" size="+2">
          <?php
          while($loggedBooks['count'] > 0){
            echo "# ";
            $loggedBooks['count'] = $loggedBooks['count'] - 1;
          }
      
          ?>
          </font>

      </div>
    </div> 
  </div>
  
<div id="rating[<?php echo $result['readerID'];?>]" class="ratingModal">
  <h2>What did you think of your book?</h2>
  <input type="image" name="rating" id="rating" class="emotion" width="80px" value="5" src="assets/Grin.png" alt="Submit">
  <input type="image" name="rating" id="rating" class="emotion" width="80px" value="4" src="assets/Smile.png" alt="Submit">
  <input type="image" name="rating" id="rating" class="emotion" width="80px" value="3" src="assets/Frown.png" alt="Submit">
  <input type="image" name="rating" id="rating" class="emotion" width="80px" value="2" src="assets/Sad.png" alt="Submit">
  <input type="image" name="rating" id="rating" class="emotion" width="80px" value="1" src="assets/Angry.png" alt="Submit">
</div>
</form>  
<?php
    }
if($result['readerCategory'] == 'r2r'){ //Loop for Racing to Read
      $readerID = $result['readerID'];
      
      //SQL Block
      $logs = $connection->query("SELECT COUNT(bookTitle) AS booksLogged FROM r2rLog WHERE readerID = '$readerID' AND DATE(timestamp) >= '$startDate'");
      $awarded = $connection->query("SELECT COUNT(*) AS awarded FROM r2rAward WHERE readerID = '$readerID' AND DATE(timestamp) >= '$startDate'");
      $timeLogged = mysqli_fetch_array($logs)['booksLogged'];
      $prized = mysqli_fetch_array($awarded)['awarded'];
      
      //Reset Image Variables
      $rewarded = 0;
      $complete = 0;
      $remainder = 0;
      $totalMinutesNeeded = 30;
      
      while($timeLogged > 0){ //While there is time that has been unprocessed
          if($timeLogged >= $totalMinutesNeeded){ //If more than 150 minutes (2.5 Hours) have not been counted
            $complete += 1;  //Add 1 to the completed challenges count
            $timeLogged = $timeLogged - $totalMinutesNeeded; //Remove 150 minutes from unprocessed time
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
      <form action="#<?php echo $readerID;?>" onsubmit="return bookTitleValidation(this)" method="post" name="<?php echo $readerID;?>" id="<?php echo $readerID;?>">
        <input type="hidden" name="readerID" id="readerID" value="<?php echo $readerID;?>">
        <input type="hidden" name="readerCategory" id="readerCategory" value="<?php echo $result['readerCategory'];?>">
        <font size="+3">What book did <?php echo $result['readerFirstName'];?> read?</font><br>
        <input class="title" type="text" id="title" name="title" style="vertical-align: middle">
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
        <img src="assets/tree.png" height="150px" alt="booker" class="booker bookerCP <?php echo $dn; ?>"/> <!-- Show a completed booker image.  $dn either make image opaque if award has been given, or left alone if award has not been given -->
      <?php
        $complete--; //Subtract 1 from the added images loop counter
      };
      
      
      $percentage = 100 - ( ( $remainder / $totalMinutesNeeded ) * 100 ); //Create a percentage for how full to fill the booker image on the remaining minutes
      ?>
      <div class="bookerContainer">
        <img src="assets/tree.png" alt="booker" class="booker bookerIP" style="-webkit-clip-path: inset(<?php echo $percentage; ?>% 0 0 0); clip-path: inset(<?php echo $percentage; ?>% 0 0 0); height: 100%"/> 
        <div class="bookerText"><?php echo $remainder . "/" . $totalMinutesNeeded . "<br>Books";?></div>
        
      </div>
    </div>
  </div>
  <?php
    }
    
    if($result['readerCategory'] == 'adult'){ //Loops throug teens on the logged in account
      $readerID = $result['readerID'];
      
      //SQL Block
      $logs = $connection->query("SELECT (SELECT COUNT(*) FROM adultLog WHERE readerID = '$readerID' AND DATE(timestamp) >= '$startDate') + (SELECT COUNT(*) FROM eventRating WHERE readerID = '$readerID' AND DATE(timestamp) >= '$startDate') AS count");
      $loggedBooks = mysqli_fetch_array($logs, MYSQLI_ASSOC); //Find how many books child has logged
  ?>
  <br class="mobile-only"><button class="mobile-only mobileButton" data-toggle="collapse" data-target="#<?php echo $result['readerID'];?>"><?php echo $result['readerFirstName'] . " " . $result['readerLastName'];?></button>
  <div class="books collapse" id="<?php echo $result['readerID'];?>">
    <div class="booksLeft">
      <form action="#<?php echo $readerID;?>" method="post" onsubmit="return bookTitleValidation(this)" name="<?php echo $readerID;?>" id="<?php echo $readerID;?>">
        <input type="hidden" name="readerID" id="readerID" value="<?php echo $readerID;?>">
        <input type="hidden" name="readerCategory" id="readerCategory" value="<?php echo $result['readerCategory'];?>">
        <font size="+3">What book did <?php echo $result['readerFirstName'];?> read?</font><br>
        <input class="title" type="text" id="title" name="title" style="vertical-align: middle">
        <input type="image" style="vertical-align: middle" width="50px" value="submit" src="assets/add.png" alt="submit Button">
      </form>
    </div>
    <div class="booksRight">
        <div class="bookContainer">
          <font color="black" size="+2"><?php echo $result['readerFirstName'] . " has " . $loggedBooks['count'] . " entries in Summer Reading.";?></font><br>
          <font color="#00B0DB" size="+2">
          <?php
          while($loggedBooks['count'] > 0){
            echo "# ";
            $loggedBooks['count'] = $loggedBooks['count'] - 1;
          }
      
          ?>
          </font>
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

<div class="tutorial" id="tutorial" onclick='HideHelp()'>
  <div id="topArrows">
    Look here to sign out, edit your information, and bring up this help screen.<img src="assets/arrow.svg" height="60px"></img>
  </div>
  <div id="tutorialTitle">
    <h1>Thank you for reading this summer with KCPL</h1>
    <p>Use this site to log your reading to gain prizes throughout the summer.</p>
  </div>
</div>  
<script>
function openModel(readerID){
  ratingModal = document.getElementById("rating[" + readerID + "]").style.display = 'inline-block';
}  
  
$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});
</script>
</body>
</html>

<?php
//Close SQL Connection
$results->close();
$connection->close();
?>
