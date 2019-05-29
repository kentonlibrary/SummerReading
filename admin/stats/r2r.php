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

$bookTotals['toddler1'] = 0;
$bookTotals['picture1'] = 0;
$bookTotals['easyReader1'] = 0;
$bookTotals['teacher1'] = 0;
$bookTotals['toddler2'] = 0;
$bookTotals['picture2'] = 0;
$bookTotals['easyReader2'] = 0;
$bookTotals['teacher2'] = 0;

$titlePrint = 1;



$statsQuery = "SELECT account.barcode, account.accountID, reader.readerLastName FROM account, (SELECT DISTINCT reader.readerLastName, reader.accountID FROM reader) AS reader WHERE account.branch = 'r2r' AND account.accountID = reader.accountID ORDER BY reader.readerLastName";
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
    $titlePrint = 1;
    
    $accountID = $result['accountID'];
    $statsQueryReader = "SELECT r1.readerFirstName, r1.readerLastName, r1.readerNumber, r1.readerID, IFNULL((SELECT COUNT(r2rL.bookTitle) AS totalBooks FROM r2rLog r2rL WHERE r2rL.readerID = r1.readerID AND DATE(r2rL.timestamp > $startDate)), 0) AS totalBooks, IFNULL((SELECT SUM(r2rA.booksAwarded) AS toddler FROM r2rAward r2rA WHERE r2rA.readerID = r1.readerID AND r2rA.awardType = 'toddler1' AND DATE(r2rA.timestamp > $startDate) GROUP BY r2rA.awardType), 0) AS toddler1, IFNULL((SELECT SUM(r2rA.booksAwarded) AS picture FROM r2rAward r2rA WHERE r2rA.readerID = r1.readerID AND r2rA.awardType = 'picture1'  AND DATE(r2rA.timestamp > $startDate)GROUP BY r2rA.awardType), 0) AS picture1, IFNULL((SELECT SUM(r2rA.booksAwarded) AS easyReader FROM r2rAward r2rA WHERE r2rA.readerID = r1.readerID AND r2rA.awardType = 'easyReader1' AND DATE(r2rA.timestamp > $startDate) GROUP BY r2rA.awardType), 0) AS easyReader1, IFNULL((SELECT SUM(r2rA.booksAwarded) AS teacher FROM r2rAward r2rA WHERE r2rA.readerID = r1.readerID AND r2rA.awardType = 'teacher1' AND DATE(r2rA.timestamp > $startDate) GROUP BY r2rA.awardType), 0) AS teacher1, IFNULL((SELECT SUM(r2rA.booksAwarded) AS toddler FROM r2rAward r2rA WHERE r2rA.readerID = r1.readerID AND r2rA.awardType = 'toddler2' AND DATE(r2rA.timestamp > $startDate) GROUP BY r2rA.awardType), 0) AS toddler2, IFNULL((SELECT SUM(r2rA.booksAwarded) AS picture FROM r2rAward r2rA WHERE r2rA.readerID = r1.readerID AND r2rA.awardType = 'picture2' AND DATE(r2rA.timestamp > $startDate) GROUP BY r2rA.awardType), 0) AS picture2, IFNULL((SELECT SUM(r2rA.booksAwarded) AS easyReader FROM r2rAward r2rA WHERE r2rA.readerID = r1.readerID AND r2rA.awardType = 'easyReader2' AND DATE(r2rA.timestamp > $startDate) GROUP BY r2rA.awardType), 0) AS easyReader2, IFNULL((SELECT SUM(r2rA.booksAwarded) AS teacher FROM r2rAward r2rA WHERE r2rA.readerID = r1.readerID AND r2rA.awardType = 'teacher2' AND DATE(r2rA.timestamp > $startDate) GROUP BY r2rA.awardType), 0) AS teacher2 FROM reader r1 WHERE r1.accountID = $accountID ORDER BY r1.readerFirstName;";
    $classResults = $connection->query($statsQueryReader);
    foreach($classResults as $classResult){
      if($titlePrint == 1){
        echo "<h3 id='" . strtolower($classResult['readerLastName'][0]) . "'>" . $classResult['readerLastName'] . " - LookupID: "  . $result['barcode'] . " <a href='r2rEdit.php?accountID=" . $accountID . "' onclick=\"window.open('r2rEdit.php?account&accountID=" . $accountID . "', 'newwindow', 'width=300,height=250'); return false;\" >Edit</a></h3>";
        $titlePrint = 0;
      }
      echo "<br>" . $classResult['readerFirstName'] . "- " . $classResult['readerNumber'] . " Students <a href='r2rEdit.php?accountID=" . $accountID . "' onclick=\"window.open('r2rEdit.php?class&accountID=" . $classResult['readerID'] . "', 'newwindow', 'width=300,height=250'); return false;\" >Edit</a><br>";
      echo $classResult['totalBooks'] . " Books read<br>";
      ?>
  <table border="0">

    <tr>
      <form action="" method="post">
      <td>Toddler Round 1</td>
      <input type="hidden" name="readerID" id="readerID" value="<?php echo $classResult['readerID']; ?>">
      <input type="hidden" name="bookType" id="bookType" value="toddler1">
      <td><input type="text" name="numberOfBooks" id="numberOfBooks" size="2" maxlength="2"></td>
      <td><input type="image" style="vertical-align: middle" width="25px" value="submit" src="../../assets/add.png" alt="submit Button"></td>
      <td><?php echo "Total Given: " . $classResult['toddler1']; ?></td>
      </form>
      <form action="" method="post">
      <td width="15"></td>
      <td width="15" style="border-left: 5px solid black"></td>
      <td>Toddler Round 2</td>
      <input type="hidden" name="readerID" id="readerID" value="<?php echo $classResult['readerID']; ?>">
      <input type="hidden" name="bookType" id="bookType" value="toddler2">
      <td><input type="text" name="numberOfBooks" id="numberOfBooks" size="2" maxlength="2"></td>
      <td><input type="image" style="vertical-align: middle" width="25px" value="submit" src="../../assets/add.png" alt="submit Button"></td>
      <td><?php echo "Total Given: " . $classResult['toddler2']; ?></td>
      </form>
    </tr>
    <tr>
      <form action="" method="post">
      <td>Picture Round 1</td>
      <input type="hidden" name="readerID" id="readerID" value="<?php echo $classResult['readerID']; ?>">
      <input type="hidden" name="bookType" id="bookType" value="picture1">
      <td><input type="text" name="numberOfBooks" id="numberOfBooks" size="2" maxlength="2"></td>
      <td><input type="image" style="vertical-align: middle" width="25px" value="submit" src="../../assets/add.png" alt="submit Button"></td>
      <td><?php echo "Total Given: " . $classResult['picture1']; ?></td>
      </form>
      <td width="15"></td>
      <td width="15" style="border-left: 5px solid black"></td>
         <form action="" method="post">
      <td>Picture Round 2</td>
      <input type="hidden" name="readerID" id="readerID" value="<?php echo $classResult['readerID']; ?>">
      <input type="hidden" name="bookType" id="bookType" value="picture2">
      <td><input type="text" name="numberOfBooks" id="numberOfBooks" size="2" maxlength="2"></td>
      <td><input type="image" style="vertical-align: middle" width="25px" value="submit" src="../../assets/add.png" alt="submit Button"></td>
      <td><?php echo "Total Given: " . $classResult['picture2']; ?></td>
      </form>
    </tr>
        <tr>
      <form action="" method="post">
      <td>Easy Reader Round 1</td>
      <input type="hidden" name="readerID" id="readerID" value="<?php echo $classResult['readerID']; ?>">
      <input type="hidden" name="bookType" id="bookType" value="easyReader1">
      <td><input type="text" name="numberOfBooks" id="numberOfBooks" size="2" maxlength="2"></td>
      <td><input type="image" style="vertical-align: middle" width="25px" value="submit" src="../../assets/add.png" alt="submit Button"></td>
      <td><?php echo "Total Given: " . $classResult['easyReader1']; ?></td>
      </form>
      <td width="15"></td>
      <td width="15" style="border-left: 5px solid black"></td>
      <form action="" method="post">
      <td>Easy Reader Round 2</td>
      <input type="hidden" name="readerID" id="readerID" value="<?php echo $classResult['readerID']; ?>">
      <input type="hidden" name="bookType" id="bookType" value="easyReader2">
      <td><input type="text" name="numberOfBooks" id="numberOfBooks" size="2" maxlength="2"></td>
      <td><input type="image" style="vertical-align: middle" width="25px" value="submit" src="../../assets/add.png" alt="submit Button"></td>
      <td><?php echo "Total Given: " . $classResult['easyReader2']; ?></td>
      </form>
    </tr>
     <tr>
      <form action="" method="post">
      <td>Teacher Round 1</td>
      <input type="hidden" name="readerID" id="readerID" value="<?php echo $classResult['readerID']; ?>">
      <input type="hidden" name="bookType" id="bookType" value="teacher1">
      <td><input type="text" name="numberOfBooks" id="numberOfBooks" size="2" maxlength="2"></td>
      <td><input type="image" style="vertical-align: middle" width="25px" value="submit" src="../../assets/add.png" alt="submit Button"></td>
      <td><?php echo "Total Given: " . $classResult['teacher1']; ?></td>
      </form>
      <td width="15"></td>
      <td width="15" style="border-left: 5px solid black"></td>
      <form action="" method="post">
      <td>Teacher Round 2</td>
      <input type="hidden" name="readerID" id="readerID" value="<?php echo $classResult['readerID']; ?>">
      <input type="hidden" name="bookType" id="bookType" value="teacher2">
      <td><input type="text" name="numberOfBooks" id="numberOfBooks" size="2" maxlength="2"></td>
      <td><input type="image" style="vertical-align: middle" width="25px" value="submit" src="../../assets/add.png" alt="submit Button"></td>
      <td><?php echo "Total Given: " . $classResult['teacher2']; ?></td>
      </form>
    </tr>
</table>

      <?php
      $bookTotals['toddler1'] += $classResult['toddler1'];
      $bookTotals['picture1'] += $classResult['picture1'];
      $bookTotals['easyReader1'] += $classResult['easyReader1'];
      $bookTotals['teacher1'] += $classResult['teacher1'];
      $bookTotals['toddler2'] += $classResult['toddler2'];
      $bookTotals['picture2'] += $classResult['picture2'];
      $bookTotals['easyReader2'] += $classResult['easyReader2'];
      $bookTotals['teacher2'] += $classResult['teacher2'];
    }
  }
  $stmt->close();
  ?>
  <h2>Grand Totals</h2>
<table>
	  <tr>
      <td>Toddler Round 1</td>
      <td><?php echo "Total Given: " . $bookTotals['toddler1']; ?></td>
			<td width="15"></td>
      <td width="15" style="border-left: 5px solid black"></td>
      <td>Toddler Round 2</td>
      <td><?php echo "Total Given: " . $bookTotals['toddler2']; ?></td>
    </tr>
    <tr>
      <td>Picture Round 1</td>
      <td><?php echo "Total Given: " . $bookTotals['picture1']; ?></td>
			<td width="15"></td>
      <td width="15" style="border-left: 5px solid black"></td>
      <td>Picture Round 2</td>
      <td><?php echo "Total Given: " . $bookTotals['picture2']; ?></td>
    </tr>
     <tr>
      <td>Easy Reader Round 1</td>
      <td><?php echo "Total Given: " . $bookTotals['easyReader1']; ?></td>
			 <td width="15"></td>
      <td width="15" style="border-left: 5px solid black"></td>
      <td>Easy Reader Round 2</td>
      <td><?php echo "Total Given: " . $bookTotals['easyReader2']; ?></td>
    </tr>
     <tr>
		  <td>Teacher Round 1</td>
      <td><?php echo "Total Given: " . $bookTotals['teacher1']; ?></td>
			 <td width="15"></td>
      <td width="15" style="border-left: 5px solid black"></td>
      <td>Teacher Round 2</td>
      <td><?php echo "Total Given: " . $bookTotals['teacher2']; ?></td>
    </tr>
</table>
</body>
</html>
