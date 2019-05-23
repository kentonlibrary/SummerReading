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
include('../../assets/scripts.php');

$startDate = $_GET['startDate'];
$stopDate = $_GET['stopDate'];
$branch = $_GET['branch'];

$results = $connection->query("SELECT reader.readerFirstName, reader.readerLastName, account.phoneNumber, account.emailAddress FROM reader, account, teenLog WHERE account.accountID = reader.accountID AND teenLog.readerID = reader.readerID AND reader.readerCategory = 'teen' AND DATE(teenLog.timestamp) BETWEEN '$startDate' AND '$stopDate' AND account.branch = '$branch' ORDER BY RAND() LIMIT 1");

$result = $results->fetch_array(MYSQLI_ASSOC);

  ?>
<h2><?php echo $result['readerFirstName'] . " " . $result['readerLastName'];?></h2>
  <?php
echo $result['phoneNumber'] . "<br>" . $result['emailAddress'];


?>