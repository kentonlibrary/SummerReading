<?php 

session_start();

?>
<head>
  <style>
    /* CSS for hiding the checkbox and hiding the image once checked */
    .hiddenCheckbox{
      display: none;
    }
    
    :checked + .hiddenLabel{
      display: none;
    }
    
    .selectedChoice {
        border-color: green;
        border-style: solid;
        border-radius: 100px;
        border-width: 5px;
    }    

  </style>
</head>
<?php
include('../../assets/scripts.php');

if(isset($_GET['readerID'])){

  $eventQuery = $connection->prepare("INSERT INTO eventRating (eventID, readerID, rating) VALUES (?, ?, ?)");
	if ( $eventQuery->bind_param("iis", $_SESSION['event'], $_GET['readerID'], $_GET['rating']) ){} 
   else{
    print_r( $eventQuery->error );
  }
	
	$eventQuery->execute();
}

$barcode=$_GET['cardNumber'];  



$results = $connection->query("SELECT reader.readerFirstName, reader.readerLastName, reader.readerCategory, reader.readerID FROM reader, account WHERE account.accountID = reader.accountID AND barcode = '$barcode' AND reader.readerCategory = 'teen' AND NOT EXISTS (SELECT 1 FROM eventRating WHERE reader.readerID = eventRating.readerID)");  //
?>
<h1>Please rate our event!</h1>
<?php
foreach($results as $result){
  ?>
<div class="teen">
  <?php $readerID = $result['readerID'];?>
  
  <h2><?php echo $result['readerFirstName'] . " " . $result['readerLastName'];?></h2>
  <img id="<?php echo $readerID;?>[scale5]" src="assets/Grin.png" width="100" height="100" alt="" onclick="selectEmotion(this)"/>
  <img id="<?php echo $readerID;?>[scale4]" src="assets/Smile.png" width="100" height="100" alt="" onclick="selectEmotion(this)"/>
  <img id="<?php echo $readerID;?>[scale3]" src="assets/Frown.png" width="100" height="100" alt="" onclick="selectEmotion(this)"/>
  <img id="<?php echo $readerID;?>[scale2]" src="assets/Sad.png" width="100" height="100" alt="" onclick="selectEmotion(this)"/>
  <img id="<?php echo $readerID;?>[scale1]" src="assets/Angry.png" width="100" height="100" alt="" onclick="selectEmotion(this)"/>
  <input type="hidden" id="cardNumber" name="cardNumber" value="<?php echo $barcode ?>">
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
</div>

<?php
}
?>