<?
include('../assets/scripts.php');
if(isset($_COOKIE['Branch']) == false){
  header('Location: index.php');
}
?>
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
  <title>Awards <?php echo $_COOKIE['Branch']; ?></title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="../assets/main.css" rel="stylesheet" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>

  <div class="main">
    <form action="javascript:loadDoc()" method="post">
      <input type="text" name="cardNumber" id="cardNumber" placeholder="Library Card" class="cardScan" maxlength="14" autofocus>
    </form>
    <div class="children" id="awards"> <!-- complete.php loads here using AJAX when a barcode is entered into the cardNumber field -->
    </div>
  </div>
<script>
$(document).ready(function() { //Keeps the cardNumber field focused for easy scanning
    $("#cardNumber").focus().bind('blur', function() {
        $(this).focus(); 
      $('#cardNumber').select();
    }); 
    
    //disable tabindex on elements
    $("input").attr("tabindex", "-1");

    $("html").click(function() {
        $("#cardNumber").val($("#cardNumber").val()).focus();
      $('#cardNumber').select();
    });        
});
  
function loadDoc() { //loads complete.php into awards div
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("awards").innerHTML =
      this.responseText;
    }
  };
  var cardNumber = $( '#cardNumber' ).val();
  xhttp.open("GET", "complete.php?cardNumber=" + cardNumber, true);
  xhttp.send();
  $('#cardNumber').select();
}

//fuctions to be performed when images are clicked
  
function level1( readerID, readerType ){
  alert("Please allow child to select a book");
  var xhttp = new XMLHttpRequest();
  xhttp.open("GET", "markAward.php?readerID=" + readerID + "&type=book" + '&reader=' + readerType, true);
  xhttp.send();
  
}
  
function level2( readerID, readerType ){
  var winfeatures="width=800,height=510,scrollbars=1,resizable=1,toolbar=1,location=1,menubar=1,status=1,directories=0";
  alert("Please allow child to pick out a T-Shirt or Tote Bag");
  window.open('raffleTicket.php?readerID=' + readerID + '&type=Raffle', "", winfeatures);
  var xhttp = new XMLHttpRequest();
  xhttp.open("GET", "markAward.php?readerID=" + readerID + "&type=shirt" + '&reader=' + readerType, true);
  xhttp.send();
}
  
function level3( readerID, readerType ){
  var winfeatures="width=800,height=510,scrollbars=1,resizable=1,toolbar=1,location=1,menubar=1,status=1,directories=0";
  alert("Please place raffle ticket in correct basket");
  window.open('raffleTicket.php?readerID=' + readerID + '&type=Challenge', "", winfeatures);
  var xhttp = new XMLHttpRequest();
  xhttp.open("GET", "markAward.php?readerID=" + readerID + "&type=challenge" + '&reader=' + readerType, true);
  xhttp.send();
}


</script>
</body>
</html>