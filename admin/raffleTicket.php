<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Raffle Ticket</title>
</head>

<body>
<?php
  include('../assets/scripts.php');
  $readerID = $_GET['readerID'];
  $results = $connection->query("SELECT reader.readerFirstName, reader.readerLastName, reader.readerBirthDate, reader.readerSchool, reader.readerGrade, account.phoneNumber FROM reader, account WHERE account.accountID = reader.accountID AND reader.readerID = '$readerID'");
  
  
  foreach($results as $result){
    echo "<h1>" . $_GET['type'] . " Ticket</h1>";
    echo "Name: " . $result['readerFirstName'] . " " . $result['readerLastName'] . "\n<br>";
    echo "Phone Number: " . $result['phoneNumber'] . "\n<br>";
    echo "Age: " . getAge($result['readerBirthDate']) . "\n<br>";
    echo "Grade: " . $result['readerGrade'] . "\n<br>";
    echo "School: " . $result['readerSchool'] . "\n<br>";
  }
?>
  
<script>
   // set portrait orientation
   jsPrintSetup.setOption('orientation', jsPrintSetup.kPortraitOrientation);
   // set top margins in millimeters
   jsPrintSetup.setOption('marginTop', 5);
   jsPrintSetup.setOption('marginBottom', 5);
   jsPrintSetup.setOption('marginLeft', 5);
   jsPrintSetup.setOption('marginRight', 5);
   // set page header
   jsPrintSetup.setOption('headerStrLeft', '');
   jsPrintSetup.setOption('headerStrCenter', '');
   jsPrintSetup.setOption('headerStrRight', 's');
   // set empty page footer
   jsPrintSetup.setOption('footerStrLeft', '');
   jsPrintSetup.setOption('footerStrCenter', '');
   jsPrintSetup.setOption('footerStrRight', '');
   // clears user preferences always silent print value
   // to enable using 'printSilent' option
   jsPrintSetup.clearSilentPrint();
   // Suppress print dialog (for this context only)
   jsPrintSetup.setOption('printSilent', 1);
   // Do Print 
   // When print is submitted it is executed asynchronous and
   // script flow continues after print independently of completetion of print process! 
   jsPrintSetup.print();
   // next commands
  
	window.print();
  window.close();
	
</script> 
</body>
</html>