<?php
include('../../assets/scripts.php');


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
</style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<h2>Scan Library Card</h2>

<!-- Trigger/Open The Modal -->
  <form action="javascript:openModal()" method="post">
      <input type="text" name="cardNumber" id="cardNumber" placeholder="Library Card" class="cardScan" maxlength="14" autofocus>
    </form>

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content close" id="popup">
    <p>Some text in the Modal..</p>
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
    modal.style.display = "block";
    var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("popup").innerHTML =
      this.responseText;
    }
  };
  var cardNumber = $( '#cardNumber' ).val();
  xhttp.open("GET", "signin.php?cardNumber=" + cardNumber, true);
  xhttp.send();
  $('#cardNumber').select();
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

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

</script>

</body>
</html>