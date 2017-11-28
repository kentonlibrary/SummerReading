<?php
include('../assets/scripts.php');
$readerType = $_GET['reader'];
$readerID = $_GET['readerID'];
$awardType = $_GET['type'];
$branch = $_GET['branch'];

if($readerType == 'olderChild'){
  $bookGivenCheck = $connection->query("INSERT INTO olderChildAward (readerID, timeAwarded, awardType, branch) VALUES ('$readerID', 150, '$awardType', '$branch')");
}



if($readerType == 'youngChild'){
  $bookGivenCheck = $connection->query("INSERT INTO youngChildAward (readerID, booksAwarded, awardType, branch) VALUES ('$readerID', 5, '$awardType', '$branch')");
}
?>