<?php
include('../assets/scripts.php');
$readerType = $_GET['reader'];
$readerID = $_GET['readerID'];
$awardType = $_GET['type'];

if($readerType == 'olderChild'){
  $bookGivenCheck = $connection->query("INSERT INTO olderChildAward (readerID, timeAwarded, awardType) VALUES ('$readerID', 150, '$awardType')");
}

if($readerType == 'youngChild'){
  $bookGivenCheck = $connection->query("INSERT INTO olderChildAward (readerID, booksAwarded, awardType) VALUES ('$readerID', 5, '$awardType')");
}
?>