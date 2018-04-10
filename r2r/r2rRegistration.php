<?php
include('../assets/scripts.php'); //File with connection information and functions
if(isset($_POST['formType'])){
  if($_POST['formType'] == "register"){
    $barcode = $_POST['barcode'];
    unset($_POST['barcode']);
    $centerName = $_POST['centerName'];
    unset($_POST['centerName']);
    $branch = $_POST['branch'];
    unset($_POST['branch']);
    unset($_POST['formType']);

    $accountQuery = $connection->prepare("INSERT INTO account (barcode, branch) VALUES (?, ?)");
    if ( $accountQuery->bind_param("ss", $barcode, $branch) ){}
     else{
      print_r( $accountQuery->error );
    }

    $accountQuery->execute();
    $accountID = $accountQuery->insert_id;


    foreach($_POST as $key => $value){
      $firstName = $value['firstName'];
      $category = $value['category'];
      $ageRange = $value['ageRange'];
      $readerNumber = $value['readerNumber'];

      $query = $connection->prepare("INSERT INTO reader (accountID, readerFirstName, readerLastName, readerCategory, readerAgeRange, readerNumber) VALUES (?, ?, ?, ?, ?, ?)");

      $query->bind_param("isssss", $accountID, $firstName, $centerName, $category, $ageRange, $readerNumber);

      $query->execute();
      $query->close();
    }

    session_start();
    $_SESSION['accountID'] = $accountID;
    $accountQuery->close();
    echo "Saved";
  }

  //Save information
  if((isset($_POST['formType']) == "update")){
    unset($_POST['formType']);
    $phone = $_POST['phone'];
    unset($_POST['phone']);
    $email = $_POST['email'];
    unset($_POST['email']);
    $branch = $_POST['branch'];
    unset($_POST['branch']);
    $accountID = $_SESSION['accountID'];
    unset($_POST['formType']);

    foreach($_POST as $key => $value){
      if( 'ch' == substr($key, 0, 2)){
        $firstName = $value['firstName'];
        $lastName = $value['lastName'];
        $birthdate = $value['birthYear'] . "-" . $value['birthMonth'] . "-" . $value['birthDay'];
        $grade = $value['grade'];
        $school = $value['school'];
        $category = $value['category'];

        $query = $connection->prepare("INSERT INTO reader (accountID, readerFirstName, readerLastName, readerBirthDate, readerCategory, readerSchool, readerGrade) VALUES (?, ?, ?, ?, ?, ?, ?)");

        $query->bind_param("issssss", $accountID, $firstName, $lastName, $birthdate, $category, $school, $grade);

        $query->execute();
        $query->close();
      }
      else{
        $firstName = $value['firstName'];
        $lastName = $value['lastName'];
        $birthdate = $value['birthYear'] . "-" . $value['birthMonth'] . "-" . $value['birthDay'];
        $grade = $value['grade'];
        $school = $value['school'];
        $category = $value['category'];

        $query = $connection->prepare("UPDATE reader SET readerFirstName = ?, readerLastName = ?, readerBirthDate = ?, readerCategory = ?, readerSchool = ?, readerGrade = ? WHERE readerID = ?");

        $query->bind_param("ssssssi", $firstName, $lastName, $birthdate, $category, $school, $grade, $key);

        $query->execute();
        $query->close();
      }
    }

    $accountSave = $connection->prepare("UPDATE account SET phoneNumber = ?, emailAddress = ?, branch = ? WHERE accountID = ?");
    if ( $accountSave->bind_param("sssi", $phone, $email, $branch, $accountID) ){}
     else{
      print_r( $accountSave->error );
    }

    $accountSave->execute();

  }
}

if(isset($_SESSION['accountID'])){
  $accountID = $_SESSION['accountID'];
  $accountQuery = "SELECT phoneNumber, emailAddress, branch, barcode FROM account WHERE accountID = ?";
    
  if( $accountInfo = $connection->prepare($accountQuery)){
		$accountInfo->bind_param("i", $accountID);
		$accountInfo->execute();
		
		$accountInfo->bind_result($phoneNumber, $emailAddress, $branch, $barcode);
  }
  $formType = "update";
}
else{
  $formType = "register";
}

?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Enter Summer Reading Contest</title>
	<link href="../assets/main.css" rel="stylesheet" type="text/css">
	<link href="../assets/mobile.css" rel="stylesheet" type="text/css" media="screen and (max-device-width: 500px)">
	<link href="../assets/desktop.css" rel="stylesheet" type="text/css" media="screen and (min-device-width:501px)">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		var submitReadyChild = false;
		var childCount = 1;
		
		 //This funtion adds the child boxes to the form.
    (function($){
        $.fn.addChildForms = function(){
            var myform = "<div class='child' id='ch" + childCount + "'>" +
                "<h3 style='display: inline;'>Class #" + childCount + "</h3><button id='removech" + childCount + "' class='removeButton' type='button' onClick='removeit(ch" + childCount + ")'>Remove</button><br>" +
                "<input placeholder='Class Name' class='chfirstName forminput' type='text' name='ch" + childCount + "[firstName]' id='ch" + childCount + "[firstName]'><br>"+
                "<input placeholder='Age Range' class='chlastName forminput' type='text' name='ch" + childCount + "[ageRange]' id='ch" + childCount + "[ageRange]' value='" + "' maxlength='6'></font><br>"+
                "<input placeholder='Number of Students' class='chlastName forminput' type='text' name='ch" + childCount + "[readerNumber]' id='ch" + childCount + "[readerNumber]' value='" + "' maxlength='3'></font>"+
                "<input type='hidden' name='ch" + childCount + "[category]' id='ch" + childCount + "[category]' value='r2r'>" + 
                "</font>"+
                "</div>"
 
            $("button", $(myform)).click(function(){ $(this).parent().remove();});
             
            $(this).append(myform);
            childCount++;
        };
    })(jQuery);
     
    $(function(){
        $("#addchild").bind("click", function(){
            $("#container").addChildForms();   
        });
    });
		
		//This function autotabs birthday fields
    function autoTab (obj){
        if (obj.value.length == obj.maxLength) {
            $(obj).next('.birthday').focus();
        }
    };
     
//This function removes dynamically added div containers
    function removeit(divID) {
        var removeID = $(divID);
        removeID.remove();   
    }
    
//Number Only Field
    function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
    
//Phone validation
    function checkPhone (obj) {
  str = obj.value.replace(/[^0-9]+?/g, '');
  switch (str.length) {
   case 0:
     obj.select();
     return;
   case 10:
     str = "("+str.substr(0,3)+")"+str.substr(3,3)+"-"+str.substr(6,4);
     break;
   default:
     obj.select();
     return;
  }
  obj.value = str;
 }
    
function readingProgram(obj){
  switch (obj.value) {
    case 'olderChild':
      entrantID = obj.name.substring(2, obj.name.length - 10);
      document.getElementById("Labelch" + entrantID + "[birthday]").style.display = "inline";
      document.getElementById("ch" + entrantID + "[birthMonth]").style.display = "inline";
      document.getElementById("ch" + entrantID + "[birthDay]").style.display = "inline";
      document.getElementById("ch" + entrantID + "[birthYear]").style.display = "inline";
      document.getElementById("Labelch" + entrantID + "[grade]").style.display = "inline";
      document.getElementById("ch" + entrantID + "[grade]").style.display = "inline";
      document.getElementById("ch" + entrantID + "[school]").style.display = "inline";
      document.getElementById("br" + entrantID + "[grade1]").style.display = "inline";
      document.getElementById("br" + entrantID + "[grade2]").style.display = "inline";
      document.getElementById("br" + entrantID + "[birthday]").style.display = "inline";
      
      document.getElementById("Label" + entrantID + "[ageRange]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange1]").style.display = "none";
      document.getElementById("ch" + entrantID + "[ageRange1]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange2]").style.display = "none";
      document.getElementById("ch" + entrantID + "[ageRange2]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange3]").style.display = "none";
      document.getElementById("ch" + entrantID + "[ageRange3]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange4]").style.display = "none";
      document.getElementById("ch" + entrantID + "[ageRange4]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange5]").style.display = "none";
      document.getElementById("ch" + entrantID + "[ageRange5]").style.display = "none";
      break;
    case 'youngChild':
      entrantID = obj.name.substring(2, obj.name.length - 10);
      document.getElementById("Labelch" + entrantID + "[birthday]").style.display = "inline";
      document.getElementById("ch" + entrantID + "[birthMonth]").style.display = "inline";
      document.getElementById("ch" + entrantID + "[birthDay]").style.display = "inline";
      document.getElementById("ch" + entrantID + "[birthYear]").style.display = "inline";
      document.getElementById("Labelch" + entrantID + "[grade]").style.display = "inline";
      document.getElementById("ch" + entrantID + "[grade]").style.display = "inline";
      document.getElementById("ch" + entrantID + "[school]").style.display = "inline";
      document.getElementById("br" + entrantID + "[grade1]").style.display = "inline";
      document.getElementById("br" + entrantID + "[grade2]").style.display = "inline";
      document.getElementById("br" + entrantID + "[birthday]").style.display = "inline";
      
      document.getElementById("Label" + entrantID + "[ageRange]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange1]").style.display = "none";
      document.getElementById("ch" + entrantID + "[ageRange1]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange2]").style.display = "none";
      document.getElementById("ch" + entrantID + "[ageRange2]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange3]").style.display = "none";
      document.getElementById("ch" + entrantID + "[ageRange3]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange4]").style.display = "none";
      document.getElementById("ch" + entrantID + "[ageRange4]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange5]").style.display = "none";
      document.getElementById("ch" + entrantID + "[ageRange5]").style.display = "none";
      break;
    case 'teen':
      entrantID = obj.name.substring(2, obj.name.length - 10);
      document.getElementById("Labelch" + entrantID + "[birthday]").style.display = "inline";
      document.getElementById("ch" + entrantID + "[birthMonth]").style.display = "inline";
      document.getElementById("ch" + entrantID + "[birthDay]").style.display = "inline";
      document.getElementById("ch" + entrantID + "[birthYear]").style.display = "inline";
      document.getElementById("Labelch" + entrantID + "[grade]").style.display = "inline";
      document.getElementById("ch" + entrantID + "[grade]").style.display = "inline";
      document.getElementById("ch" + entrantID + "[school]").style.display = "inline";
      document.getElementById("br" + entrantID + "[grade1]").style.display = "inline";
      document.getElementById("br" + entrantID + "[grade2]").style.display = "inline";
      document.getElementById("br" + entrantID + "[birthday]").style.display = "inline";
      
      document.getElementById("Label" + entrantID + "[ageRange]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange1]").style.display = "none";
      document.getElementById("ch" + entrantID + "[ageRange1]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange2]").style.display = "none";
      document.getElementById("ch" + entrantID + "[ageRange2]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange3]").style.display = "none";
      document.getElementById("ch" + entrantID + "[ageRange3]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange4]").style.display = "none";
      document.getElementById("ch" + entrantID + "[ageRange4]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange5]").style.display = "none";
      document.getElementById("ch" + entrantID + "[ageRange5]").style.display = "none";
      break;
    case 'adult':
      entrantID = obj.name.substring(2, obj.name.length - 10);
      document.getElementById("Labelch" + entrantID + "[birthday]").style.display = "none";
      document.getElementById("ch" + entrantID + "[birthMonth]").style.display = "none";
      document.getElementById("ch" + entrantID + "[birthDay]").style.display = "none";
      document.getElementById("ch" + entrantID + "[birthYear]").style.display = "none";
      document.getElementById("Labelch" + entrantID + "[grade]").style.display = "none";
      document.getElementById("ch" + entrantID + "[grade]").style.display = "none";
      document.getElementById("ch" + entrantID + "[school]").style.display = "none";
      document.getElementById("br" + entrantID + "[grade1]").style.display = "none";
      document.getElementById("br" + entrantID + "[grade2]").style.display = "none";
      document.getElementById("br" + entrantID + "[birthday]").style.display = "none";
      
      document.getElementById("Label" + entrantID + "[ageRange]").style.display = "inline";
      document.getElementById("Label" + entrantID + "[ageRange1]").style.display = "inline";
      document.getElementById("ch" + entrantID + "[ageRange1]").style.display = "inline";
      document.getElementById("Label" + entrantID + "[ageRange2]").style.display = "inline";
      document.getElementById("ch" + entrantID + "[ageRange2]").style.display = "inline";
      document.getElementById("Label" + entrantID + "[ageRange3]").style.display = "inline";
      document.getElementById("ch" + entrantID + "[ageRange3]").style.display = "inline";
      document.getElementById("Label" + entrantID + "[ageRange4]").style.display = "inline";
      document.getElementById("ch" + entrantID + "[ageRange4]").style.display = "inline";
      document.getElementById("Label" + entrantID + "[ageRange5]").style.display = "inline";
      document.getElementById("ch" + entrantID + "[ageRange5]").style.display = "inline";
      break;
  }
}
    
    
function readingProgramExisting(obj){
  switch (obj.value) {
    case 'olderChild':
      entrantID = obj.name.substring(0, obj.name.length - 10);
      document.getElementById("Labelch" + entrantID + "[birthday]").style.display = "inline";
      document.getElementById(entrantID + "[birthMonth]").style.display = "inline";
      document.getElementById(entrantID + "[birthDay]").style.display = "inline";
      document.getElementById(entrantID + "[birthYear]").style.display = "inline";
      document.getElementById("Labelch" + entrantID + "[grade]").style.display = "inline";
      document.getElementById(entrantID + "[grade]").style.display = "inline";
      document.getElementById(entrantID + "[school]").style.display = "inline";
      document.getElementById("br" + entrantID + "[grade1]").style.display = "inline";
      document.getElementById("br" + entrantID + "[grade2]").style.display = "inline";
      document.getElementById("br" + entrantID + "[birthday]").style.display = "inline";
      
      document.getElementById("Label" + entrantID + "[ageRange]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange1]").style.display = "none";
      document.getElementById(entrantID + "[ageRange1]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange2]").style.display = "none";
      document.getElementById(entrantID + "[ageRange2]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange3]").style.display = "none";
      document.getElementById(entrantID + "[ageRange3]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange4]").style.display = "none";
      document.getElementById(entrantID + "[ageRange4]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange5]").style.display = "none";
      document.getElementById(entrantID + "[ageRange5]").style.display = "none";
      break;
    case 'youngChild':
      entrantID = obj.name.substring(0, obj.name.length - 10);
      document.getElementById("Labelch" + entrantID + "[birthday]").style.display = "inline";
      document.getElementById(entrantID + "[birthMonth]").style.display = "inline";
      document.getElementById(entrantID + "[birthDay]").style.display = "inline";
      document.getElementById(entrantID + "[birthYear]").style.display = "inline";
      document.getElementById("Labelch" + entrantID + "[grade]").style.display = "inline";
      document.getElementById(entrantID + "[grade]").style.display = "inline";
      document.getElementById(entrantID + "[school]").style.display = "inline";
      document.getElementById("br" + entrantID + "[grade1]").style.display = "inline";
      document.getElementById("br" + entrantID + "[grade2]").style.display = "inline";
      document.getElementById("br" + entrantID + "[birthday]").style.display = "inline";
      
      document.getElementById("Label" + entrantID + "[ageRange]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange1]").style.display = "none";
      document.getElementById(entrantID + "[ageRange1]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange2]").style.display = "none";
      document.getElementById(entrantID + "[ageRange2]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange3]").style.display = "none";
      document.getElementById(entrantID + "[ageRange3]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange4]").style.display = "none";
      document.getElementById(entrantID + "[ageRange4]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange5]").style.display = "none";
      document.getElementById(entrantID + "[ageRange5]").style.display = "none";
      break;
    case 'teen':
      entrantID = obj.name.substring(0, obj.name.length - 10);
      document.getElementById("Labelch" + entrantID + "[birthday]").style.display = "inline";
      document.getElementById(entrantID + "[birthMonth]").style.display = "inline";
      document.getElementById(entrantID + "[birthDay]").style.display = "inline";
      document.getElementById(entrantID + "[birthYear]").style.display = "inline";
      document.getElementById("Labelch" + entrantID + "[grade]").style.display = "inline";
      document.getElementById(entrantID + "[grade]").style.display = "inline";
      document.getElementById(entrantID + "[school]").style.display = "inline";
      document.getElementById("br" + entrantID + "[grade1]").style.display = "inline";
      document.getElementById("br" + entrantID + "[grade2]").style.display = "inline";
      document.getElementById("br" + entrantID + "[birthday]").style.display = "inline";
      
      document.getElementById("Label" + entrantID + "[ageRange]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange1]").style.display = "none";
      document.getElementById(entrantID + "[ageRange1]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange2]").style.display = "none";
      document.getElementById(entrantID + "[ageRange2]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange3]").style.display = "none";
      document.getElementById(entrantID + "[ageRange3]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange4]").style.display = "none";
      document.getElementById(entrantID + "[ageRange4]").style.display = "none";
      document.getElementById("Label" + entrantID + "[ageRange5]").style.display = "none";
      document.getElementById(entrantID + "[ageRange5]").style.display = "none";
      break;
    case 'adult':
      entrantID = obj.name.substring(0, obj.name.length - 10);
      document.getElementById("Labelch" + entrantID + "[birthday]").style.display = "none";
      document.getElementById(entrantID + "[birthMonth]").style.display = "none";
      document.getElementById(entrantID + "[birthDay]").style.display = "none";
      document.getElementById(entrantID + "[birthYear]").style.display = "none";
      document.getElementById("Labelch" + entrantID + "[grade]").style.display = "none";
      document.getElementById(entrantID + "[grade]").style.display = "none";
      document.getElementById(entrantID + "[school]").style.display = "none";
      document.getElementById("br" + entrantID + "[grade1]").style.display = "none";
      document.getElementById("br" + entrantID + "[grade2]").style.display = "none";
      document.getElementById("br" + entrantID + "[birthday]").style.display = "none";
      
      document.getElementById("Label" + entrantID + "[ageRange]").style.display = "inline";
      document.getElementById("Label" + entrantID + "[ageRange1]").style.display = "inline";
      document.getElementById(entrantID + "[ageRange1]").style.display = "inline";
      document.getElementById("Label" + entrantID + "[ageRange2]").style.display = "inline";
      document.getElementById(entrantID + "[ageRange2]").style.display = "inline";
      document.getElementById("Label" + entrantID + "[ageRange3]").style.display = "inline";
      document.getElementById(entrantID + "[ageRange3]").style.display = "inline";
      document.getElementById("Label" + entrantID + "[ageRange4]").style.display = "inline";
      document.getElementById(entrantID + "[ageRange4]").style.display = "inline";
      document.getElementById("Label" + entrantID + "[ageRange5]").style.display = "inline";
      document.getElementById(entrantID + "[ageRange5]").style.display = "inline";
      break;
  }
}
    
function validateForm(){
  var shouldReturn;
  branch = document.forms["register"]["branch"].value;
  
  phoneExp = /^\([0-9]{3}\)[0-9]{3}-[0-9]{4}$/;
  emailExp = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
  shouldReturn = true
  //phoneExp = /^\d{10}$/
  if( (phoneExp.test(phoneNumber)) || ( emailExp.test(email)) ){}
  else{
    document.getElementById('email').style.borderBottom = "5px solid red";
    document.getElementById('phone').style.borderBottom = "5px solid red";
    document.getElementById('phoneEmail').style.color = "red";
    document.getElementById('phoneEmail').style.fontWeight = "bold";
    shouldReturn = false;
  }
  if(branch == ''){
    document.getElementById('branch').style.borderBottom = "5px solid red";
    shouldReturn = false;
  }
  for (i = 1, len = childCount, text = ""; i < len; i++){
    var firstName = '', lastName = '', readingProgram = '', birthDay = '', birthMonth = '', birthYear = '', grade = '', school = '', ageRange = '', ageRange_Value = '';
    firstName = document.getElementById("ch" + i + "[firstName]").value;
    if(firstName == ''){
      document.getElementById("ch" + i + "[firstName]").style.borderBottom = "5px solid red";
      shouldReturn = false;
    }
    lastName = document.getElementById("ch" + i + "[lastName]").value;
    if(lastName == ''){
      document.getElementById("ch" + i + "[lastName]").style.borderBottom = "5px solid red";
      shouldReturn = false;
    }
    readingProgram = document.getElementById("ch" + i + "[category]").value;
    if(readingProgram == ''){
      document.getElementById("ch" + i + "[category]").style.borderBottom = "5px solid red";
      shouldReturn = false;
    }
    if(readingProgram == "olderChild" || readingProgram == "youngChild" || readingProgram == "teen"){
      birthDay = document.getElementById("ch" + i + "[birthDay]").value;
      if(birthDay > 31 || birthDay < 1 || birthDay == ''){
        document.getElementById("ch" + i + "[birthDay]").style.borderBottom = "5px solid red";
        shouldReturn = false;
      }
      birthMonth = document.getElementById("ch" + i + "[birthMonth]").value;
      if(birthMonth > 12 || birthMonth < 1 || birthMonth == ''){
        document.getElementById("ch" + i + "[birthMonth]").style.borderBottom = "5px solid red";
        shouldReturn = false;
      }
      birthYear = document.getElementById("ch" + i + "[birthYear]").value;
      if(birthYear >= 2018 || birthYear <= 1800 || birthYear == ''){
        document.getElementById("ch" + i + "[birthYear]").style.borderBottom = "5px solid red";
        shouldReturn = false;
      }
      grade = document.getElementById("ch" + i + "[grade]").value;
      if(grade == ''){
        document.getElementById("ch" + i + "[grade]").style.borderBottom = "5px solid red";
        shouldReturn = false;
      }
      school = document.getElementById("ch" + i + "[school]").value;
      if(school == ''){
        document.getElementById("ch" + i + "[school]").style.borderBottom = "5px solid red";
        shouldReturn = false;
      }
      //alert("Entrant " + i + "\n" + "Name: " + firstName + " " + lastName + "\nBirthday: " + birthMonth + "/" + birthDay + "/" + birthYear + "\nGrade: " + grade + "\nSchool: " + school);
    }
    if(readingProgram == "adult"){
      ageRange = document.getElementsByName('ch' + i + '[ageRange]');
      
      for(var x = 0; x < ageRange.length; x++){
        if(ageRange[x].checked){
          ageRange_Value = ageRange[x].value;
        }
      }
      if(ageRange_Value == ''){
        document.getElementById("Label" + i + "[ageRange]").style.color = "red";
        document.getElementById("Label" + i + "[ageRange]").style.fontWeight = "bold";
        shouldReturn = false;
      }
      //alert("Entrant " + i + "\n" + "Name: " + firstName + " " + lastName + "\nAge Range: " + ageRange_Value);
    }
  }
  return shouldReturn;
}
     
	</script>
</head>

<body>
<div class="register">
  <a href="../admin/stats/r2r.php">Back</a>
	<h1>Register for KCPL Summer Reading</h1>
	<p>Fill out the following information so you don't have to fill it out again</p>
  <p id="phoneEmail">Please fill out either phone number or email or both</p>
	<form method="post" action="" name="register" id="register">
   <?php
    if($formType == "register"){
      ?>
    <input type="hidden" name="formType" id="formType" value="register">
    <input class="forminput" type="text" name="barcode" id="barcode" placeholder="Lookup ID"><br>
    <input class="forminput" type="text" name="centerName" id="centerName" placeholder="Center Name"><br>
    <input class="forminput" type="hidden" name="branch" id="branch" value="r2r">
    <br><br>
    <?php
    }
?>
      	<div>
			<div id="container2">

    <div id="container">
      
        </div>
				
			<button id="addchild" class="label" type="button">Add Class</button><br>
    		<button id="submit" class="label submit" type="submit">Finish</button>
		</div>
	</form>
</div>
</body>
</html>