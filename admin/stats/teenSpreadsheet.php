<?php
include('../../assets/scripts.php'); //File with connection information and functions
include('../../assets/settings.php');
// output headers so that the file is downloaded rather than displayed
header('Content-type: text/csv');
$filename = "TEEN-SRC " . date("Y-m-d h:ma");
header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
 
// do not cache the file
header('Pragma: no-cache');
header('Expires: 0');
 
// create a file pointer connected to the output stream
$file = fopen('php://output', 'w');
 
// save the column headers
fputcsv($file, array('First Name', 'Last Name', 'Birthday', 'Grade', 'School', 'Branch', 'Phone Number', 'Email', 'Book Read', 'Rating', 'Timestamp'));


$query = "SELECT reader.readerFirstName, reader.readerLastName, reader.readerbirthDate, reader.readerGrade, reader.readerSchool, account.branch, account.phoneNumber, account.emailAddress, teenLog.title, teenLog.rating, teenLog.timeStamp FROM reader, account, teenLog WHERE reader.accountID = account.accountID AND reader.readerID = teenLog.readerID AND reader.readerCategory = 'teen' AND DATE(teenLog.timestamp) >= '$startDate'";

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
