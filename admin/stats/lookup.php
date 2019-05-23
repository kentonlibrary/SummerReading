<?php 
include('../../assets/scripts.php'); //File with connection information and functions

$query = "SELECT reader.readerID, account.barcode, account.phoneNumber, account.emailAddress, account.branch, account.lastLogin, reader.readerFirstName, reader.readerLastName, reader.readerBirthDate, reader.readerCategory, reader.readerAgeRange, reader.readerSchool, reader.readerGrade, reader.readerNumber FROM reader, account WHERE reader.accountID = account.accountID ORDER BY reader.readerLastName, reader.readerFirstName";

$rows = mysqli_query($connection, $query);


?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>All Patrons</title>
</head>

<body>
	<table border="1" style="white-space: nowrap;">
  <tbody>
    <tr>
      <th scope="col"></th>
      <th scope="col">Last Name</th>
      <th scope="col">First Name</th>
      <th scope="col">Barcode</th>
      <th scope="col">Phone Number</th>
      <th scope="col">Email Address</th>
      <th scope="col">Branch</th>
      <th scope="col">Birth Date</th>
      <th scope="col">Category</th>
      <th scope="col">Age Range</th>
      <th scope="col">School</th>
      <th scope="col">Grade</th>
      <th scope="col">Number</th>
      <th scope="col">Last Login</th>
    </tr>
		
		<?php
		while ($row = mysqli_fetch_assoc($rows)){
		?>
    <tr>
      <th scope="row"><a href="editPatron.php?ID=<?php echo $row['readerID']; ?>">Edit</a></th>
      <td><?php echo $row['readerLastName']; ?></td>
      <td><?php echo $row['readerFirstName']; ?></td>
      <td><?php echo $row['barcode']; ?></td>
      <td><?php echo $row['phoneNumber']; ?></td>
      <td><?php echo $row['emailAddress']; ?></td>
      <td><?php echo $row['branch']; ?></td>
      <td><?php echo $row['readerBirthDate']; ?></td>
      <td><?php echo $row['readerCategory']; ?></td>
      <td><?php echo $row['readerAgeRange']; ?></td>
      <td><?php echo $row['readerSchool']; ?></td>
      <td><?php echo $row['readerGrade']; ?></td>
      <td><?php echo $row['readerNumber']; ?></td>
      <td><?php echo $row['lastLogin']; ?></td>
    </tr>
	<?php
		}
	?>
  </tbody>
</table>
</body>
</html>