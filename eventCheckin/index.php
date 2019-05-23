<?php
include('../assets/scripts.php');

session_start();

if(isset($_POST['eventName'])){
  $eventQuery = $connection->prepare("INSERT INTO event (eventName, branch, eventYoungChild, eventOlderChild, eventTeen, eventAdult) VALUES (?, ?, ?, ?, ?, ?)"); //, eventYoungChild, eventOlderChild, eventReen, eventAdult
	if ( $eventQuery->bind_param("ssssss", $_POST['eventName'], $_POST['branch'], $_POST['youngChild'], $_POST['olderChild'], $_POST['teen'], $_POST['adult'])){} //, $_POST['youngChild'], $_POST['olderChild'], $_POST['teen'], $_POST['adult']
   else{
    print_r( $eventQuery->error );
  }
	
	$eventQuery->execute();
	$eventID = $eventQuery->insert_id;
  $_SESSION['event'] = $eventID;
  $_SESSION['programCatagories'] = array();
  if($_POST['youngChild'] == "1"){ $_SESSION['programCatagories']['youngChild'] = "youngChild"; }
  if($_POST['olderChild'] == "1"){ $_SESSION['programCatagories']['olderChild'] = "olderChild"; }
  if($_POST['teen'] == "1"){ $_SESSION['programCatagories']['teen'] = "teen"; }
  if($_POST['adult'] == "1"){ $_SESSION['programCatagories']['adult'] = "adult"; }
  

}

if(!isset($_SESSION['event'])){
  ?>
  <!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" src="/assets/onscreenkeyboard/css/bootstrap.min.css">
    <link rel="stylesheet" src="/assets/onscreenkeyboard/css/bootstrap-responsive.min.css">
    <link rel="stylesheet" src="/assets/onscreenkeyboard/css/main.css">
    <link rel="stylesheet" href="/assets/onscreenkeyboard/css/jsKeyboard.css" type="text/css" media="screen"/>
    <script type="text/javascript" src="/assets/onscreenkeyboard/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
		<style>
			input[type='checkbox']{
				    -webkit-appearance:none;
    width:30px;
    height:30px;
    background:white;
    border-radius:5px;
    border:2px solid #555;
			}
			input[type='checkbox']:checked {
    background: #abd;
}
			
			label{
				font-size: 24px;
			}
		</style>
  </head>
  <body style="padding: 30px;">
		<form method="post" action="">
    Event Name<input type="text" name="eventName" id="eventName" class="input">
    Branch<select name="branch" id="branch">
          <option value="Covington">Covington</option>
          <option value="Durr">William E. Durr</option>
          <option value="Erlanger">Erlanger</option>
        </select><br>
    Who can check in?<br>
    <input type="hidden" name="youngChild" id="youngChild" value="0">
    <Label><input type="checkbox" name="youngChild" id="youngChild" value="1">Young Child</Label><br>
    <input type="hidden" name="olderChild" id="olderChild" value="0">
      <label><input type="checkbox" name="olderChild" id="olderChild" value="1">Older Child<br></label>
    <input type="hidden" name="teen" id="teen" value="0">
      <label><input type="checkbox" name="teen" id="teen" value="1">Teen<br></label>
    <input type="hidden" name="adult" id="adult" value="0">
      <label><input type="checkbox" name="adult" id="adult" value="1">Adult<br></label>
    <input type="submit" style="width: 150px;; height:75px;">
      <div id="virtualKeyboard"></div>
      
      <script src="/assets/onscreenkeyboard/js/vendor/jquery-1.9.1.min.js"></script>
      <script src="/assets/onscreenkeyboard/js/vendor/bootstrap.min.js"></script>
      <script type="text/javascript" src="/assets/onscreenkeyboard/js/jsKeyboard.js"></script>
      <script src="/assets/onscreenkeyboard/js/main.js"></script>
  </body>
</html>
<?php
}
else{
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="../assets/keyboard/keyboard.css" />
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
  
.personButton {
  background-color: rgba(0,65,255,1.00);
  border: none;
  color: white;
  padding: 16px 32px;
  text-decoration: none;
  margin: 4px 2px;
  cursor: pointer;
  font-size: large;
}
  
  #attendee{
    display: none;
  }
  
  .cardScan{
    height: 42px;
    font-size: 40px;
    background: #B16FAE;
  }

</style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body style="cursor: none;">
<div style="text-align: center; padding-top: 20px; ">
<h2 style="color: #B16FAE; font-size: 56px;">Scan Library Card or Enter below</h2><br>

<!-- Trigger/Open The Modal -->
  <form action="javascript:openModal()" method="post" name="card" id="card">
      <input type="text" name="cardNumber" id="cardNumber" placeholder="Library Card" class="cardScan" maxlength="14" autofocus>
    </form>
    <?php include_once('../assets/keyboard/keyboard.php'); ?>

<!-- The Modal -->
<div id="myModal" class="modal">
  <h1 onClick="closeModal()" style="color: red">X</h1>
  <!-- Modal content -->
  <div class="modal-content close" id="popup">
    <p>Some text in the Modal..</p>
  </div>

</div>
</div>

<script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
function openModal() {
    modal.style.opacity = 1;
    modal.style.display = "block";
    
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("popup").innerHTML = this.responseText;
    }
  };
  var cardNumber = $( '#cardNumber' ).val();
  xhttp.open("GET", "signin.php?cardNumber=" + cardNumber, true);
  xhttp.send();
  $('#cardNumber').select();
  
  var write = document.getElementById('cardNumber');
  write.value = '';
}

// When the user clicks on <span> (x), close the modal
//span.onclick = function() {
//    modal.style.display = "none";
//}

$(document).ready(function() { //Keeps the cardNumber field focused for easy scanning
    //$("#cardNumber").focus().bind('blur', function() {
        //$(this).focus(); 
      //$('#cardNumber').select();
      //document.getElementById('card').reset();
    //}); 
    
    //disable tabindex on elements
    $("input").attr("tabindex", "-1");

    $("html").click(function() {
        $("#cardNumber").val($("#cardNumber").val()).focus();
      $('#cardNumber').select();
    });        
});
  
 function selectEmotion( ele ) {
  var emotion = document.getElementById(ele.id);
  var oldClass = document.getElementsByClassName("selectedChoice");
  var readerID = ele.id.substr(0, ele.id.length - 8);
  var rating = ele.id.charAt(ele.id.length - 2);
  var cardNumber = cardNumber = $( '#cardNumber' ).val();
  [].forEach.call(oldClass, function(el){
    el.className = el.className.replace(/\bselectedChoice\b/, "");
  })
  emotion.className = "selectedChoice";
  
  //Update Database
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      //document.getElementById("popup").innerHTML =
      //this.responseText;
    }
  };
  var cardNumber = $( '#cardNumber' ).val();
  xhttp.open("GET", "signin.php?readerID=" + readerID + "&rating=" + rating + "&cardNumber=" + cardNumber, true);
  xhttp.send();
   
   var op = 1;  // initial opacity
    var timer = setInterval(function () {
        if (op <= 0.1){
            clearInterval(timer);
            modal.style.display = 'none';
        }
        modal.style.opacity = op;
        modal.style.filter = 'alpha(opacity=' + op * 100 + ")";
        op -= op * 0.1;
    }, 50);
  //modal.style.display = "none";
  
}
  
  function openReader( readerID ){
    var reader = document.getElementById(readerID);
    var attendeeBlock = document.getElementById("attendee");
    var namesBlock = document.getElementById("names");
    attendeeBlock.style.display = "block";
    reader.style.display = "block";
    namesBlock.style.display = "none";
  }
  
  function closeModal(){
    modal.style.display = "none";
  }

</script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="../assets/keyboard/keyboard.js"></script>
</body>
</html>

<?php } ?>