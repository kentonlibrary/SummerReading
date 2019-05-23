<?php
include('../../assets/scripts.php'); //File with connection information and functions

if(isset($_POST['CenterSave'])){
	$accountQuery = $connection->prepare("UPDATE account SET barcode = ? WHERE accountID = ?");
    if ( $accountQuery->bind_param("si", $_POST['lookupID'], $_POST['accountID']) ){}
     else{
      print_r( $accountQuery->error );
    }

    $accountQuery->execute();
	
	
		$accountQuery2 = $connection->prepare("UPDATE reader SET readerLastName = ? WHERE accountID = ?");
    if ( $accountQuery2->bind_param("si", $_POST['centerName'], $_POST['accountID']) ){}
     else{
      print_r( $accountQuery2->error );
    }

    $accountQuery2->execute();
	?>

<!doctype html>
	<html>
	<head>
	<meta charset="UTF-8">
	<title>Edit Center</title>
	</head>
	<script type="application/javascript">
		window.opener.location.reload(false);
		window.close();
	</script>
	</html

<?php
}

if(isset($_POST['ClassSave'])){
	$accountQuery = $connection->prepare("UPDATE reader SET readerFirstName = ?, readerAgeRange = ?, readerNumber = ? WHERE readerID = ?");
    if ( $accountQuery->bind_param("sssi", $_POST['className'], $_POST['ageRange'], $_POST['students'], $_POST['accountID']) ){}
     else{
      print_r( $accountQuery->error );
    }

    $accountQuery->execute();
	?>

<!doctype html>
	<html>
	<head>
	<meta charset="UTF-8">
	<title>Edit Center</title>
	</head>
	<script type="application/javascript">
		window.opener.location.reload(false);
		window.close();
	</script>
	</html

<?php
}

if(isset($_GET['account'])){

	$accountID = $_GET['accountID'];

	$statsQuery = "SELECT account.barcode, account.accountID, reader.readerLastName FROM account, (SELECT DISTINCT reader.readerLastName, reader.accountID FROM reader) AS reader WHERE account.branch = 'r2r' AND account.accountID = reader.accountID AND account.accountID = '$accountID' ORDER BY reader.readerLastName";

	$stmt = $connection->query($statsQuery);
	$results = $stmt->fetch_array(MYSQLI_ASSOC);
	?>
	<!doctype html>
	<html>
	<head>
	<meta charset="UTF-8">
	<title>Edit Center</title>
	</head>

	<body>
	<form action="" method="post">
	Lookup ID: <input type="text" name="lookupID" id="lookupID" value="<?php echo $results['barcode']; ?>"><br>
	Center Name: <input type="text" name="centerName" id="centerName" value="<?php echo $results['readerLastName']; ?>">
	<input type="hidden" name="accountID" id="accountID" value="<?php echo $results['accountID']; ?>">
	<input type="submit" name="CenterSave" value="Save">
	</form>

	</body>
	</html>
<?php }

if(isset($_GET['class'])){

	$accountID = $_GET['accountID'];

	$statsQuery = "SELECT readerID, readerFirstName, readerAgeRange, readerNumber FROM reader WHERE readerID = '$accountID'";

	$stmt = $connection->query($statsQuery);
	$results = $stmt->fetch_array(MYSQLI_ASSOC);
	?>
	<!doctype html>
	<html>
	<head>
	<meta charset="UTF-8">
	<title>Edit Center</title>
	</head>

	<body>
	<form action="" method="post">
	Class Name: <input type="text" name="className" id="className" value="<?php echo $results['readerFirstName']; ?>"><br>
	Age Range: <input type="text" name="ageRange" id="ageRange" value="<?php echo $results['readerAgeRange']; ?>"><br>
	Number of Students: <input type="text" name="students" id="students" value="<?php echo $results['readerNumber']; ?>"><br>
	<input type="hidden" name="accountID" id="accountID" value="<?php echo $results['readerID']; ?>">
	<input type="submit" name="ClassSave" value="Save">
	</form>

	</body>
	</html>
<?php } ?>