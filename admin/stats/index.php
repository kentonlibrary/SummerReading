<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Statistics</title>
<link href="../assets/main.css" rel="stylesheet" type="text/css">
<link href="../assets/mobile.css" rel="stylesheet" type="text/css" media="screen and (max-device-width: 500px)">
<link href="../assets/desktop.css" rel="stylesheet" type="text/css" media="screen and (min-device-width:501px)">
</head>
  
<?php

  include('../../assets/scripts.php');
  
  
  ////////////////////////////////////
  //                                //
  //          Covington            //
  //                               //
  //////////////////////////////////
  //Older Child Logs
  $covingtonOlderChildrensLogs = $connection->query("SELECT CEIL(SUM(timeRead)/150) as logs FROM olderChildLog, reader, account WHERE olderChildLog.readerID = reader.readerID AND account.accountID = reader.accountID AND account.branch = 'Covington' GROUP BY olderChildLog.readerID"); 
  $covingtonChildLogsTotal = 0;
  foreach( $covingtonOlderChildrensLogs as $individualOlderLogs ){
    $covingtonChildLogsTotal +=$individualOlderLogs['logs'];
  }
  
  //Younger Child Logs
  $covingtonYoungChildrensLogs = $connection->query("SELECT CEIL(COUNT(*)/5) as logs FROM youngChildLog, reader, account WHERE youngChildLog.readerID = reader.readerID AND account.accountID = reader.accountID AND account.branch = 'Covington' GROUP BY youngChildLog.readerID"); 
  foreach( $covingtonYoungChildrensLogs as $individualYoungLogs ){
    $covingtonChildLogsTotal +=$individualYoungLogs['logs'];
  }
  
  ////////////////////////////////////
  //                                //
  //          Erlanger              //
  //                               //
  //////////////////////////////////
    //Older Child Logs
  $covingtonOlderChildrensLogs = $connection->query("SELECT CEIL(SUM(timeRead)/150) as logs FROM olderChildLog, reader, account WHERE olderChildLog.readerID = reader.readerID AND account.accountID = reader.accountID AND account.branch = 'Erlanger' GROUP BY olderChildLog.readerID"); 
  $erlangerChildLogsTotal = 0;
  foreach( $covingtonOlderChildrensLogs as $individualOlderLogs ){
    $erlangerChildLogsTotal +=$individualOlderLogs['logs'];
  }
  
  //Younger Child Logs
  $covingtonYoungChildrensLogs = $connection->query("SELECT CEIL(COUNT(*)/5) as logs FROM youngChildLog, reader, account WHERE youngChildLog.readerID = reader.readerID AND account.accountID = reader.accountID AND account.branch = 'Erlanger' GROUP BY youngChildLog.readerID"); 
  foreach( $covingtonYoungChildrensLogs as $individualYoungLogs ){
    $erlangerChildLogsTotal +=$individualYoungLogs['logs'];
  }
  
  ////////////////////////////////////
  //                                //
  //          Durr                  //
  //                               //
  //////////////////////////////////
    //Older Child Logs
  $covingtonOlderChildrensLogs = $connection->query("SELECT CEIL(SUM(timeRead)/150) as logs FROM olderChildLog, reader, account WHERE olderChildLog.readerID = reader.readerID AND account.accountID = reader.accountID AND account.branch = 'Durr' GROUP BY olderChildLog.readerID"); 
  foreach( $covingtonOlderChildrensLogs as $individualOlderLogs ){
    $durrChildLogsTotal +=$individualOlderLogs['logs'];
  }
  
  //Younger Child Logs
  $covingtonYoungChildrensLogs = $connection->query("SELECT CEIL(COUNT(*)/5) as logs FROM youngChildLog, reader, account WHERE youngChildLog.readerID = reader.readerID AND account.accountID = reader.accountID AND account.branch = 'Durr' GROUP BY youngChildLog.readerID"); 
  $durrChildLogsTotal = 0;
  foreach( $covingtonYoungChildrensLogs as $individualYoungLogs ){
    $durrChildLogsTotal +=$individualYoungLogs['logs'];
  }
  
  
  //Awards Given.
  $youngAwardsGiven = $connection->query("SELECT branch, COUNT(*) as awarded FROM youngChildAward GROUP BY branch");
  
  foreach( $youngAwardsGiven as $awardCount){
    $key = $awardCount['branch'];
    $awards[$key] = $awardCount['awarded'];
  }
  
   $olderAwardsGiven = $connection->query("SELECT branch, COUNT(*) as awarded FROM olderChildAward GROUP BY branch");
  
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
    
</body>
</html>