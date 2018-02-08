<?php
include('../../assets/scripts.php');

session_start();

if(isset($_POST['eventName'])){
  $eventQuery = $connection->prepare("INSERT INTO event (eventName, branch) VALUES (?, ?)");
	if ( $eventQuery->bind_param("ss", $_POST['eventName'], $_POST['branch']) ){}
   else{
    print_r( $eventQuery->error );
  }
	
	$eventQuery->execute();
	$eventID = $eventQuery->insert_id;
  $_SESSION['event'] = $eventID;
}

if(!isset($_SESSION['event'])){
  ?>
  <!DOCTYPE html>
<html>
  <body>
    <form method="post" action="">
    Event Name<input type="text" name="eventName" id="eventName">
    Branch<select name="branch" id="branch">
          <option value="Covington">Covington</option>
          <option value="Durr">William E. Durr</option>
          <option value="Erlanger">Erlanger</option>
        </select>
    <input type="submit">
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
  

</style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body style="">
<div style="text-align: center; padding-top: 20%; ">
<h2>Scan Library Card</h2>

<!-- Trigger/Open The Modal -->
  <form action="javascript:openModal()" method="post" name="card" id="card">
      <input type="text" name="cardNumber" id="cardNumber" placeholder="Library Card" class="cardScan" maxlength="14" autofocus>
    </form>

<!-- The Modal -->
<div id="myModal" class="modal">

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
}

// When the user clicks on <span> (x), close the modal
//span.onclick = function() {
//    modal.style.display = "none";
//}

$(document).ready(function() { //Keeps the cardNumber field focused for easy scanning
    $("#cardNumber").focus().bind('blur', function() {
        $(this).focus(); 
      $('#cardNumber').select();
      document.getElementById('card').reset();
    }); 
    
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

</script>

</body>
</html>

<?php } ?>