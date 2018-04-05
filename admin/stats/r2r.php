<?php
include('../../assets/scripts.php'); //File with connection information and functions

if(isset($_POST['bookType'])){
    $readerID = $_POST['readerID'];
    $bookType = $_POST['bookType'];
    $numberOfBooks = $_POST['numberOfBooks'];

    //SQL Block to insert time into database
    $query = $connection->prepare("INSERT INTO r2rAward (readerID, booksAwarded, awardType) VALUES (?, ?, ?)");
    $query->bind_param("iis", $readerID, $numberOfBooks, $bookType);
    $query->execute();
    $query->close();
}

$bookTotals['toddler'] = 0;
$bookTotals['picture'] = 0;
$bookTotals['easyReader'] = 0;
$bookTotals['teacher'] = 0;





$statsQuery = "SELECT barcode, accountID FROM account WHERE branch = 'r2r' ORDER BY barcode";
$stmt = $connection->query($statsQuery);

?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Racing to Read</title>
</head>

<body>
  <a href="../../r2r/r2rRegistration.php">Registration</a>
  <h1>Racing to Read Statistics</h1>
  <?php
  $letters = range('A', 'Z');
  
  foreach ($letters as $letter){
    echo "<a href='#" . strtolower($letter) . "'>&nbsp $letter &nbsp</a>";
  }

  foreach($stmt as $result){
    echo "<h3 id='" . strtolower($result['barcode'][0]) . "'>" . $result['barcode'] . "</h3>";
    $accountID = $result['accountID'];
    $statsQueryReader = "SELECT r1.readerFirstName, r1.readerNumber, r1.readerID, IFNULL((SELECT COUNT(r2rL.bookTitle) AS totalBooks FROM r2rLog r2rL WHERE r2rL.readerID = r1.readerID), 0) AS totalBooks, IFNULL((SELECT SUM(r2rA.booksAwarded) AS toddler FROM r2rAward r2rA WHERE r2rA.readerID = r1.readerID AND r2rA.awardType = 'toddler' GROUP BY r2rA.awardType), 0) AS toddler, IFNULL((SELECT SUM(r2rA.booksAwarded) AS picture FROM r2rAward r2rA WHERE r2rA.readerID = r1.readerID AND r2rA.awardType = 'picture' GROUP BY r2rA.awardType), 0) AS picture, IFNULL((SELECT SUM(r2rA.booksAwarded) AS easyReader FROM r2rAward r2rA WHERE r2rA.readerID = r1.readerID AND r2rA.awardType = 'easyReader' GROUP BY r2rA.awardType), 0) AS easyReader, IFNULL((SELECT SUM(r2rA.booksAwarded) AS teacher FROM r2rAward r2rA WHERE r2rA.readerID = r1.readerID AND r2rA.awardType = 'teacher' GROUP BY r2rA.awardType), 0) AS teacher FROM reader r1 WHERE r1.accountID = $accountID";
    $classResults = $connection->query($statsQueryReader);
    foreach($classResults as $classResult){
      echo "<br>" . $classResult['readerFirstName'] . "- " . $classResult['readerNumber'] . " Students<br>";
      echo $classResult['totalBooks'] . " Books read<br>";
      ?>
  <table width="200" border="0">
  <tbody>
    <tr>
      <form action="" method="post">
      <td>Toddler</td>
      <input type="hidden" name="readerID" id="readerID" value="<?php echo $classResult['readerID']; ?>">
      <input type="hidden" name="bookType" id="bookType" value="toddler">
      <td><input type="text" name="numberOfBooks" id="numberOfBooks" size="2" maxlength="2"></td>
      <td><input type="image" style="vertical-align: middle" width="25px" value="submit" src="../../assets/add.png" alt="submit Button"></td>
      <td><?php echo "Total Given: " . $classResult['toddler']; ?></td>
      </form>
    </tr>
    <tr>
      <form action="" method="post">
      <td>Picture</td>
      <input type="hidden" name="readerID" id="readerID" value="<?php echo $classResult['readerID']; ?>">
      <input type="hidden" name="bookType" id="bookType" value="picture">
      <td><input type="text" name="numberOfBooks" id="numberOfBooks" size="2" maxlength="2"></td>
      <td><input type="image" style="vertical-align: middle" width="25px" value="submit" src="../../assets/add.png" alt="submit Button"></td>
      <td><?php echo "Total Given: " . $classResult['picture']; ?></td>
      </form>
    </tr>
        <tr>
      <form action="" method="post">
      <td>Easy Reader</td>
      <input type="hidden" name="readerID" id="readerID" value="<?php echo $classResult['readerID']; ?>">
      <input type="hidden" name="bookType" id="bookType" value="easyReader">
      <td><input type="text" name="numberOfBooks" id="numberOfBooks" size="2" maxlength="2"></td>
      <td><input type="image" style="vertical-align: middle" width="25px" value="submit" src="../../assets/add.png" alt="submit Button"></td>
      <td><?php echo "Total Given: " . $classResult['easyReader']; ?></td>
      </form>
    </tr>
     <tr>
      <form action="" method="post">
      <td>Teacher</td>
      <input type="hidden" name="readerID" id="readerID" value="<?php echo $classResult['readerID']; ?>">
      <input type="hidden" name="bookType" id="bookType" value="teacher">
      <td><input type="text" name="numberOfBooks" id="numberOfBooks" size="2" maxlength="2"></td>
      <td><input type="image" style="vertical-align: middle" width="25px" value="submit" src="../../assets/add.png" alt="submit Button"></td>
      <td><?php echo "Total Given: " . $classResult['teacher']; ?></td>
      </form>
    </tr>
  </tbody>
</table>

      <?php
      $bookTotals['toddler'] += $classResult['toddler'];
      $bookTotals['picture'] += $classResult['picture'];
      $bookTotals['easyReader'] += $classResult['easyReader'];
      $bookTotals['teacher'] += $classResult['teacher'];
    }
  }
  $stmt->close();
  ?>
  <h2>Grand Totals</h2>
<table>
  <tbody>
        <tr>
      <td>Toddler</td>
      <td><?php echo "Total Given: " . $bookTotals['toddler']; ?></td>
    </tr>
    <tr>
      <td>Picture</td>
      <td><?php echo "Total Given: " . $bookTotals['picture']; ?></td>
    </tr>
    <tr>
      <td>Easy Reader</td>
      <td><?php echo "Total Given: " . $bookTotals['easyReader']; ?></td>
    </tr>
     <tr>
      <td>Teacher</td>
      <td><?php echo "Total Given: " . $bookTotals['teacher']; ?></td>
      </form>
    </tr>
  </tbody>
</table>
</body>
</html>