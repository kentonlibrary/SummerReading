<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Statistics</title>
<style>
/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
</style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <link rel="stylesheet" href="../../assets/Datepicker.css">
  <link rel="stylesheet" href="../../assets/jquery-ui.css">
  <script>
  $( function() {
    $( "#startDate" ).datepicker();
    $( "#startDate" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
    $( "#stopDate" ).datepicker();
    $( "#stopDate" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
  } );
  </script>
</head>
  
<?php

  include('../../assets/scripts.php');
  
  
  ///////////////////////////////////
  //                               //
  //          Covington            //
  //                               //
  ///////////////////////////////////
  //Older Child Logs
  $covingtonOlderChildrensLogs = $connection->query("SELECT CEIL(SUM(timeRead)/300) as logs FROM olderChildLog, reader, account WHERE olderChildLog.readerID = reader.readerID AND account.accountID = reader.accountID AND account.branch = 'Covington' AND DATE(olderChildLog.timestamp) >= '$startDate' GROUP BY olderChildLog.readerID"); 
  $covingtonChildLogsTotal = 0;
  foreach( $covingtonOlderChildrensLogs as $individualOlderLogs ){
    $covingtonChildLogsTotal +=$individualOlderLogs['logs'];
  }
  
  //Younger Child Logs
  $covingtonYoungChildrensLogs = $connection->query("SELECT CEIL(COUNT(*)/10) as logs FROM youngChildLog, reader, account WHERE youngChildLog.readerID = reader.readerID AND account.accountID = reader.accountID AND account.branch = 'Covington' AND DATE(youngChildLog.timestamp) >= '$startDate' GROUP BY youngChildLog.readerID"); 
  foreach( $covingtonYoungChildrensLogs as $individualYoungLogs ){
    $covingtonChildLogsTotal +=$individualYoungLogs['logs'];
  }
  
    //Total Patrons Signed up
  $covingtonPatrons = $connection->query("SELECT COUNT(*) as count FROM reader, account WHERE reader.accountID = account.accountID and account.branch = 'Covington' AND DATE(account.lastLogin) >= '$startDate'");
  $covingtonPatronsTotal = 0;
  foreach( $covingtonPatrons as $covingtonPatron ){
    $covingtonPatronsTotal +=$covingtonPatron['count'];
  }
	
	   //Total Child Patrons Signed up
  $covingtonChildPatrons = $connection->query("SELECT COUNT(*) as count FROM reader, account WHERE reader.accountID = account.accountID and account.branch = 'Covington' AND DATE(account.lastLogin) >= '$startDate' AND (reader.readerCategory = 'olderChild' OR reader.readerCategory = 'youngChild')");
  $covingtonChildPatronsTotal = 0;
  foreach( $covingtonChildPatrons as $covingtonChildPatron ){
    $covingtonChildPatronsTotal +=$covingtonChildPatron['count'];
  }
	
	//Total Patrons completed program
	$ProgramCompleted = $connection->query("SELECT (SELECT COUNT(*) as youngCount FROM youngChildAward, account, reader WHERE account.accountID = reader.accountID AND youngChildAward.readerID = reader.readerID AND account.branch = 'Covington' AND youngChildAward.awardType = 'book'  AND DATE(youngChlildAward.timestamp) >= '$startDate') + (SELECT COUNT(*) as olderCount FROM olderChildAward, account, reader WHERE account.accountID = reader.accountID AND olderChildAward.readerID = reader.readerID AND account.branch = 'Covington' AND olderChildAward.awardType = 'book' AND DATE(olderChildAward.timestamp) >= '$startDate') as covingtonCount, (SELECT COUNT(*) as youngCount FROM youngChildAward, account, reader WHERE account.accountID = reader.accountID AND youngChildAward.readerID = reader.readerID AND account.branch = 'Erlanger' AND youngChildAward.awardType = 'book' AND DATE(youngChildAward.timestamp) >= '$startDate') + (SELECT COUNT(*) as olderCount FROM olderChildAward, account, reader WHERE account.accountID = reader.accountID AND olderChildAward.readerID = reader.readerID AND account.branch = 'Erlanger' AND olderChildAward.awardType = 'book' AND DATE(olderChildAward.timestamp) >= '$startDate') as erlangerCount, (SELECT COUNT(*) as youngCount FROM youngChildAward, account, reader WHERE account.accountID = reader.accountID AND youngChildAward.readerID = reader.readerID AND account.branch = 'Durr' AND youngChildAward.awardType = 'book' AND DATE(youngChildAward.timestamp) >= '$startDate') + (SELECT COUNT(*) as olderCount FROM olderChildAward, account, reader WHERE account.accountID = reader.accountID AND olderChildAward.readerID = reader.readerID AND account.branch = 'Durr' AND olderChildAward.awardType = 'book' AND DATE(olderChildAward.timestamp) >= '$startDate') as durrCount");
	
	
  
  ////////////////////////////////////
  //                                //
  //          Erlanger              //
  //                                //
  ////////////////////////////////////
    //Older Child Logs
  $covingtonOlderChildrensLogs = $connection->query("SELECT CEIL(SUM(timeRead)/300) as logs FROM olderChildLog, reader, account WHERE olderChildLog.readerID = reader.readerID AND account.accountID = reader.accountID AND account.branch = 'Erlanger' AND DATE(olderChildLog.timestamp) >= '$startDate' GROUP BY olderChildLog.readerID"); 
  $erlangerChildLogsTotal = 0;
  foreach( $covingtonOlderChildrensLogs as $individualOlderLogs ){
    $erlangerChildLogsTotal +=$individualOlderLogs['logs'];
  }
  
  //Younger Child Logs
  $covingtonYoungChildrensLogs = $connection->query("SELECT CEIL(COUNT(*)/10) as logs FROM youngChildLog, reader, account WHERE youngChildLog.readerID = reader.readerID AND account.accountID = reader.accountID AND account.branch = 'Erlanger' AND DATE(yongChildLog.timestamp) >= '$startDate' GROUP BY youngChildLog.readerID"); 
  foreach( $covingtonYoungChildrensLogs as $individualYoungLogs ){
    $erlangerChildLogsTotal +=$individualYoungLogs['logs'];
  }
  
  //Total Patrons Signed up
  $erlangerPatrons = $connection->query("SELECT COUNT(*) as count FROM reader, account WHERE reader.accountID = account.accountID and account.branch = 'Erlanger' AND DATE(account.lastLogin) >= '$startDate'"); 
  $erlangerPatronsTotal = 0;
  foreach( $erlangerPatrons as $erlangerPatron ){
    $erlangerPatronsTotal +=$erlangerPatron['count'];
  }
	
		
	   //Total Child Patrons Signed up
  $erlangerChildPatrons = $connection->query("SELECT COUNT(*) as count FROM reader, account WHERE reader.accountID = account.accountID and account.branch = 'Erlanger' AND DATE(account.lastLogin) >= '$startDate' AND (reader.readerCategory = 'olderChild' OR reader.readerCategory = 'youngChild')");
  $erlangerChildPatronsTotal = 0;
  foreach( $erlangerChildPatrons as $erlangerChildPatron ){
    $erlangerChildPatronsTotal +=$erlangerChildPatron['count'];
  }
  ////////////////////////////////////
  //                                //
  //          Durr                  //
  //                                //
  ////////////////////////////////////
    //Older Child Logs
  $covingtonOlderChildrensLogs = $connection->query("SELECT CEIL(SUM(timeRead)/300) as logs FROM olderChildLog, reader, account WHERE olderChildLog.readerID = reader.readerID AND account.accountID = reader.accountID AND account.branch = 'Durr' AND DATE(olderChildLog.timestamp) >= '$startDate' GROUP BY olderChildLog.readerID"); 
  foreach( $covingtonOlderChildrensLogs as $individualOlderLogs ){
    $durrChildLogsTotal +=$individualOlderLogs['logs'];
  }
  
  //Younger Child Logs
  $covingtonYoungChildrensLogs = $connection->query("SELECT CEIL(COUNT(*)/10) as logs FROM youngChildLog, reader, account WHERE youngChildLog.readerID = reader.readerID AND account.accountID = reader.accountID AND account.branch = 'Durr' AND DATE(youngChildLog.timestamp) >= '$startDate' GROUP BY youngChildLog.readerID"); 
  $durrChildLogsTotal = 0;
  foreach( $covingtonYoungChildrensLogs as $individualYoungLogs ){
    $durrChildLogsTotal +=$individualYoungLogs['logs'];
  }
  
  //Total Patrons Signed up
  $durrPatrons = $connection->query("SELECT COUNT(*) as count FROM reader, account WHERE reader.accountID = account.accountID and account.branch = 'Durr' AND DATE(account.lastLogin) >= '$startDate'");
  $durrPatronsTotal = 0;
  foreach( $durrPatrons as $durrPatron ){
    $durrPatronsTotal +=$durrPatron['count'];
  }
	
	   //Total Child Patrons Signed up
  $durrChildPatrons = $connection->query("SELECT COUNT(*) as count FROM reader, account WHERE reader.accountID = account.accountID and account.branch = 'Durr' AND DATE(account.lastLogin) >= '$startDate' AND (reader.readerCategory = 'olderChild' OR reader.readerCategory = 'youngChild')");
  $durrChildPatronsTotal = 0;
  foreach( $durrChildPatrons as $durrChildPatron ){
    $durrChildPatronsTotal +=$durrChildPatron['count'];
  }
  
  
  //Awards Given.
  $youngAwardsGiven = $connection->query("SELECT branch, COUNT(*) as awarded FROM youngChildAward AND DATE(youngChildAward.timestamp) >= '$startDate' GROUP BY branch");
  
  foreach( $youngAwardsGiven as $awardCount){
    $key = $awardCount['branch'];
    $awards[$key] = $awardCount['awarded'];
  }
  
   $olderAwardsGiven = $connection->query("SELECT branch, COUNT(*) as awarded FROM olderChildAward AND DATE(olderChildAward.timestamp) >= '$startDate' GROUP BY branch");
  
  foreach( $olderAwardsGiven as $awardCount){
    $key = $awardCount['branch'];
    $awards[$key] = $awardCount['awarded'];
  }
    
?>
<body>
  <h1>Statistics for Summer Reading</h1>
  <h2>Childrens</h2>
  <div id="LogsGiven">
    <h3>Total Logs Given Out: <?php echo $covingtonChildLogsTotal + $erlangerChildLogsTotal + $durrChildLogsTotal;?></h3>
    <p>Covington: <?php echo $covingtonChildLogsTotal;?></p>
    <p>Erlanger: <?php echo $erlangerChildLogsTotal;?></p>
    <p>Durr: <?php echo $durrChildLogsTotal;?></p>
  <div>
    <div id="AwardsGiven">
      <h3>Total Awards Given Out: <?php echo $awards['Covington'] + $awards['Durr'] + $awards['Erlanger'];?></h3>
      <p>Covington: <?php echo $awards['Covington'];?></p>
      <p>Erlanger: <?php echo $awards['Erlanger'];?></p>
      <p>Durr: <?php echo $awards['Durr'];?></p>
  <div>
	<div id="totalPatrons">
      <h3>Total Patrons Signed Up: <?php echo $covingtonChildPatronsTotal + $durrChildPatronsTotal + $erlangerChildPatronsTotal;?></h3>
      <p>Covington: <?php echo $covingtonChildPatronsTotal;?></p>
      <p>Erlanger: <?php echo $erlangerChildPatronsTotal;?></p>
      <p>Durr: <?php echo $durrChildPatronsTotal;?></p>
  <div>
		<?php
		foreach($ProgramCompleted as $programComplete){
			
		?>
	<div id="firstAward">
      <h3>Total Patrons Completed Program (First Prize): <?php echo $programComplete['covingtonCount'] + $programComplete['durrCount'] + $programComplete['erlangerCount'];?></h3>
      <p>Covington: <?php echo $programComplete['covingtonCount'];?></p>
      <p>Erlanger: <?php echo $programComplete['erlangerCount'];?></p>
      <p>Durr: <?php echo $programComplete['durrCount'];?></p>
  <div>
	<?php } ?>
    
<h2>Teen Stats</h2>
  <div>
    <h3>Drawing</h3>
    <form action="javascript:openModal()" method="POST">
      Start Date:<input type="datetime" name="startDate" id="startDate">
      Stop Date:<input type="datetime" name="stopDate" id="stopDate">
      <select name="branch" id="branch">
          <option value="Covington">Covington</option>
          <option value="Durr">William E. Durr</option>
          <option value="Erlanger">Erlanger</option>
        </select>
      <input type="submit">
    </form>
  </div>
    
    <!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content close" id="popup">
    <p>Some text in the Modal..</p>
  </div>

</div>

    
<h2>All Stats</h2>
<div id="totalPatrons">
    <h3>Total Patrons: <?php echo $covingtonPatronsTotal + $erlangerPatronsTotal + $durrPatronsTotal?></h3>
    <p>Covington: <?php echo $covingtonPatronsTotal;?></p>
    <p>Erlanger: <?php echo $erlangerPatronsTotal;?></p>
    <p>Durr: <?php echo $durrPatronsTotal;?></p>
  <div>
    <a href="r2r.php">Racing to Read Stats</a><br>
    <a href="asSpreadsheet.php">Adult Services Spreadsheet</a><br>
    <a href="eventSpreadsheet.php">Event Spreadsheet</a><br>
    <a href="teenSpreadsheet.php">Teen Spreadsheet</a>
<script>
  var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
function openModal() {
    modal.style.display = "block";
    var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("popup").innerHTML =
      this.responseText;
    }
  };
  var startDate = $( '#startDate' ).val();
  var stopDate = $( '#stopDate' ).val();
  var branch = $( '#branch' ).val();
  xhttp.open("GET", "raffle.php?startDate=" + startDate + "&stopDate=" + stopDate + "&branch=" + branch, true);
  xhttp.send();
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}
    </script>
    
</body>
</html>
