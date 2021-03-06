<?php 

session_start();

?><head>
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
include('../assets/scripts.php');

if(isset($_GET['readerID'])){

  $eventQuery = $connection->prepare("INSERT INTO eventRating (eventID, readerID, rating) VALUES (?, ?, ?)");
	if ( $eventQuery->bind_param("iis", $_SESSION['event'], $_GET['readerID'], $_GET['rating']) ){} 
   else{
    print_r( $eventQuery->error );
  }
	
	$eventQuery->execute();
}

$barcode=$_GET['cardNumber'];  

$eventID = $_SESSION['event'];
$programCatagories = implode("','", $_SESSION['programCatagories']);
$programCatagories = "'".$programCatagories."'";

$results = $connection->query("SELECT reader.readerFirstName, reader.readerLastName, reader.readerID FROM reader, account WHERE account.accountID = reader.accountID AND account.barcode = '$barcode' AND reader.readerCategory IN ($programCatagories) AND NOT EXISTS ( SELECT 1 FROM eventRating WHERE reader.readerID = eventRating.readerID AND eventRating.eventID = '$eventID');");  // 

?>
<div id="names">
  <h1>Who is checking in?</h1>
  <?php
  
  foreach($results as $result){
   ?>
  
  <input type="button" id="reader<?php echo $result['readerID'];?>button" class="personButton" value="<?php echo $result['readerFirstName'];?>" onClick="openReader(<?php echo $result['readerID']?>)">
  
  <?php 
  }
  
  ?>
  
</div>  



<div id="attendee">
    <h1>Please rate our event!</h1>
    <?php
    foreach($results as $result){
      $readerID = $result['readerID'];
      ?>
    <div class="teen" id="<?php echo $readerID; ?>" style="display: none;">

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
</div>

<?php
}
?>