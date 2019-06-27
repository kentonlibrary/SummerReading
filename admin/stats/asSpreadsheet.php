<?php
include('../../assets/scripts.php'); //File with connection information and functions
include('../../assets/settings.php');
// output headers so that the file is downloaded rather than displayed
header('Content-type: text/csv');
$filename = "AS-SRC " . date("Y-m-d h:ma");
header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
 
// do not cache the file
header('Pragma: no-cache');
header('Expires: 0');
 
// create a file pointer connected to the output stream
$file = fopen('php://output', 'w');
 
// save the column headers
fputcsv($file, array('First Name', 'Last Name', 'Age', 'Branch', 'Phone Number', 'Email', 'Book Read'));

if(isset($_POST['adultstartDate'])){
	$startDateEntry = $_POST['adultstartDate'];
	$endDateEntry = $_POST['adultstopDate'];
	$branch = $_POST['branch'];
	if($branch == 'All'){
		$query = "SELECT reader.readerFirstName, reader.readerLastName, reader.readerAgeRange, account.branch, account.phoneNumber, account.emailAddress, adultLog.title , adultLog.timestamp FROM reader, account, adultLog WHERE reader.accountID = account.accountID AND reader.readerID = adultLog.readerID AND reader.readerCategory = 'adult' AND DATE(adultLog.timestamp) >= '$startDateEntry' AND DATE(adultLog.timestamp) <= '$endDateEntry'";
	}
	else if($branch == 'Covington' || $branch == 'Durr' || $branch == 'Erlanger'){
		$query = "SELECT reader.readerFirstName, reader.readerLastName, reader.readerAgeRange, account.branch, account.phoneNumber, account.emailAddress, adultLog.title , adultLog.timestamp FROM reader, account, adultLog WHERE reader.accountID = account.accountID AND reader.readerID = adultLog.readerID AND reader.readerCategory = 'adult' AND DATE(adultLog.timestamp) >= '$startDateEntry' AND DATE(adultLog.timestamp) <= '$endDateEntry' AND account.branch = '$branch'";
	}
}
else{
	$query = "SELECT reader.readerFirstName, reader.readerLastName, reader.readerAgeRange, account.branch, account.phoneNumber, account.emailAddress, adultLog.title , adultLog.timestamp FROM reader, account, adultLog WHERE reader.accountID = account.accountID AND reader.readerID = adultLog.readerID AND reader.readerCategory = 'adult' AND DATE(adultLog.timestamp) >= '$startDate'";
}
if($rows = mysqli_query($connection, $query)){
  //loop over the rows, outputting them
  while ($row = mysqli_fetch_assoc($rows)){
    fputcsv($file, $row);
  }
}
mysqli_close($connection);

// save each row of the data
//foreach ($data as $row)
//{
//fputcsv($file, $row);
//}
 
exit();
?>
