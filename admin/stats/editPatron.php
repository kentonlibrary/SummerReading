<?php 

include('../../assets/scripts.php'); //File with connection information and functions

if(isset($_POST['submit'])){
	$readerID = $_POST['readerID'];
	$accountID = $_POST['accountID'];
	$barcode = $_POST['barcode'];
	$phoneNumber = $_POST['phoneNumber'];
	$emailAddress = $_POST['emailAddress'];
	$branch = $_POST['branch'];
	$firstName = $_POST['readerFirstName'];
	$lastName = $_POST['readerLastName'];
	$birthDate = $_POST['readerBirthDate'];
	$category = $_POST['readerCategory'];
	$ageRange = $_POST['readerAgeRange'];
	$school = $_POST['readerSchool'];
	$grade = $_POST['readerGrade'];
	$number = $_POST['readerNumber'];
	
	$accountUpdate = "UPDATE account SET barcode = '$barcode', phoneNumber = '$phoneNumber', emailAddress = '$emailAddress', branch = '$branch' WHERE accountID = $accountID";
	
	$readerUpdate = "UPDATE reader SET readerFirstName = '$firstName', readerLastName = '$lastName', readerBirthDate = '$birthDate', readerCategory = '$category', readerAgeRange = '$ageRange', readerSchool = '$school', readerGrade = '$grade', readerNumber = '$number' WHERE readerID = $readerID";

	
	mysqli_query($connection, $accountUpdate);
	mysqli_query($connection, $readerUpdate);
	
	
	
	header("Location:lookup.php");
}




$readerID = $_GET['ID'];

$query = "SELECT reader.readerID, account.accountID, account.barcode, account.phoneNumber, account.emailAddress, account.branch, account.lastLogin, reader.readerFirstName, reader.readerLastName, reader.readerBirthDate, reader.readerCategory, reader.readerAgeRange, reader.readerSchool, reader.readerGrade, reader.readerNumber FROM reader, account WHERE reader.accountID = account.accountID AND reader.readerID = $readerID ORDER BY reader.readerLastName, reader.readerFirstName";

$rows = mysqli_query($connection, $query);


?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>All Patrons</title>
</head>

<body>
<form method="post" action="">
<?php
	while ($row = mysqli_fetch_assoc($rows)){ 
		foreach($row as $key => $value){
			if($key == 'readerID' || $key == 'accountID'){
				?><input type="hidden" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $value; ?>">
				<input type="hidden" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $value; ?>">

	<?php	}
			else{
		?>
		<?php echo $key . " "; ?><input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $value; ?>"><br>	
	<?php  } } } ?>	
	<input type="submit" name="submit" id="submit" value="Save">
</body>
</html>