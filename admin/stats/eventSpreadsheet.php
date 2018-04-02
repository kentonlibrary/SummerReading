<?php
include('../../assets/scripts.php'); //File with connection information and functions

// output headers so that the file is downloaded rather than displayed
header('Content-type: text/csv');
$filename = "AS-SRC " . date("Y-m-d h:ma");
header('Content-Disposition: attachment; filename="' . $filename . '"');
 
// do not cache the file
header('Pragma: no-cache');
header('Expires: 0');
 
// create a file pointer connected to the output stream
$file = fopen('php://output', 'w');
 
// save the column headers
fputcsv($file, array('First Name', 'Last Name', 'Age', 'PatronBranch', 'Phone Number', 'Email', 'PatronRating', 'EventName', 'EventBranch', 'EventTime', 'SRC-Program'));


$query = "SELECT reader.readerFirstName, reader.readerLastName, reader.readerAgeRange, account.branch as PatronBranch, account.phoneNumber, account.emailAddress, eventRating.rating, event.eventName, event.branch as EventBranch, event.Timestamp, reader.readerCategory FROM reader, account, event, eventRating WHERE reader.accountID = account.accountID AND reader.readerID = eventRating.readerID AND eventRating.eventID = event.eventID";

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