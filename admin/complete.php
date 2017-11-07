<head>
  <style>
    /* CSS for hiding the checkbox and hiding the image once checked */
    .hiddenCheckbox{
      display: none;
    }
    
    :checked + .hiddenLabel{
      display: none;
    }
  </style>
</head>

<?php
include('../assets/scripts.php');

$barcode=$_GET['cardNumber'];
  

$results = $connection->query("SELECT reader.readerFirstName, reader.readerLastName, reader.readerCategory, reader.readerID FROM reader, account WHERE account.accountID = reader.accountID AND barcode = '$barcode'");  //

foreach($results as $result){
  ?>
<h2><?php echo $result['readerFirstName'] . " " . $result['readerLastName'];?></h2>
  <?php
  $readerCategory = $result['readerCategory'];
  if($readerCategory == 'olderChild'){ //If child is older child
    $readerID = $result['readerID'];
    
    $loggedHours = $connection->query("SELECT SUM(timeRead) AS minutes FROM olderChildLog WHERE readerID = '$readerID'");
    $minutes = mysqli_fetch_array($loggedHours);
    $totalMinutes = $minutes['minutes'] ;
    
    $awardedTime = $connection->query("SELECT SUM(timeAwarded) AS awarded FROM olderChildAward WHERE readerID = '$readerID'");
    $awardedMinutes = mysqli_fetch_array($awardedTime);
    $totalAwarded = $awardedMinutes['awarded'];
    
    $remainingMinutes = $totalMinutes - $totalAwarded;
        
      $bookGivenCheck = $connection->query("SELECT COUNT(*) AS book FROM olderChildAward WHERE readerID = '$readerID'");
      
      $bookGiven = mysqli_fetch_array($bookGivenCheck);
      $bookAward = $bookGiven['book'];
    
    while($remainingMinutes >= 150){
      
      if( $bookAward == 0 ){
        ?>
          <input onChange="level1('<?php echo $result['readerID'] . "', '" . $readerCategory; ?>')" type="checkbox" id="<?php echo $readerID . 'book' . $bookAward?>" class="hiddenCheckbox" style="">
          <label for="<?php echo $readerID . 'book' . $bookAward;?>" class="hiddenLabel">
          <img src="../assets/bookaward.png" height="80px"></label>
        <?php
        $bookAward++;
      }
      elseif( $bookAward == 1 ){
        ?>
          <input onChange="level2(''<?php echo $result['readerID'] . "', '" . $readerCategory; ?>'" type="checkbox" id="<?php echo $readerID . 'book' . $bookAward?>" class="hiddenCheckbox" style="">
          <label for="<?php echo $readerID . 'book' . $bookAward;?>" class="hiddenLabel">
          <img src="../assets/tshirt.png" height="80px"></label>
        <?php
        $bookAward++;
      }
      elseif( $bookAward >= 2 ){
        ?>
          <input onChange="level3('<?php echo $result['readerID'] . "', '" . $readerCategory; ?>')" type="checkbox" id="<?php echo $readerID . 'book' . $bookAward?>" class="hiddenCheckbox" style="">
          <label for="<?php echo $readerID . 'book' . $bookAward;?>" class="hiddenLabel">
          <img src="../assets/challenge.jpg" height="80px"></label>
        <?php
        $bookAward++;
      }
        $remainingMinutes = $remainingMinutes - 150;
    }
  }
    elseif($readerCategory == 'youngChild'){ //If reader is young child
      $readerID = $result['readerID'];
      $loggedHours = $connection->query("SELECT COUNT(*) AS minutes FROM youngChildLog WHERE readerID = '$readerID'");

      $minutes = mysqli_fetch_array($loggedHours);
      $totalMinutes = $minutes['minutes'] ;

      $awardedTime = $connection->query("SELECT SUM(booksAwarded) AS awarded FROM youngChildAward WHERE readerID = '$readerID'");

      $awardedMinutes = mysqli_fetch_array($awardedTime);
      $totalAwarded = $awardedMinutes['awarded'];

      $remainingMinutes = $totalMinutes - $totalAwarded;

        $bookGivenCheck = $connection->query("SELECT COUNT(*) AS book FROM youngChildAward WHERE readerID = '$readerID'");

        $bookGiven = mysqli_fetch_array($bookGivenCheck);
        $bookAward = $bookGiven['book'];

      while($remainingMinutes >= 5){

        if( $bookAward == 0 ){
          ?>
            <img src="../assets/bookaward.png" height="80px">
          <?php
          $bookAward++;
        }
        elseif( $bookAward == 1 ){
          ?>
            <img src="../assets/tshirt.png" height="80px">
          <?php
          $bookAward++;
        }
        elseif( $bookAward >= 2 ){
          ?>
            <img src="../assets/challenge.jpg" height="80px">
          <?php
          $bookAward++;
        }
          $remainingMinutes = $remainingMinutes - 150;
      }
    }
}


?>