<?php
include('assets/scripts.php'); //File with connection information and functions

if(isset($_POST['barcode'])){
	$barcode = $_POST['barcode'];
	unset($_POST['barcode']);
	$phone = $_POST['phone'];
	unset($_POST['phone']);
  $branch = $_POST['branch'];
  unset($_POST['branch']);
  $email = $_POST['email'];
  unset($_POST['email']);
	
	$accountQuery = $connection->prepare("INSERT INTO account (barcode, phoneNumber, branch, emailAddress) VALUES (?, ?, ?, ?)");
	if ( $accountQuery->bind_param("ssss", $barcode, $phone, $branch, $email) ){}
   else{
    print_r( $accountQuery->error );
  }
	
	$accountQuery->execute();
	$accountID = $accountQuery->insert_id;
	
	
	foreach($_POST as $key => $value){
		$firstName = $value['firstName'];
		$lastName = $value['lastName'];
		$birthdate = $value['birthYear'] . "-" . $value['birthMonth'] . "-" . $value['birthDay'];
		$grade = $value['grade'];
		$school = $value['school'];
		$category = $value['category'];
    $ageRange = $value['ageRange'];
		
		$query = $connection->prepare("INSERT INTO reader (accountID, readerFirstName, readerLastName, readerBirthDate, readerCategory, readerSchool, readerGrade, readerAgeRange) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
		
		$query->bind_param("isssssss", $accountID, $firstName, $lastName, $birthdate, $category, $school, $grade, $ageRange);
		
		$query->execute();
		$query->close();
	}
	
	session_start();
	$_SESSION['accountID'] = $accountID;
	$accountQuery->close();
	header('Location: log.php');
	
}

?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Enter Summer Reading Contest</title>
	<link href="assets/main.css" rel="stylesheet" type="text/css">
	<link href="assets/mobile.css" rel="stylesheet" type="text/css" media="screen and (max-device-width: 500px)">
	<link href="assets/desktop.css" rel="stylesheet" type="text/css" media="screen and (min-device-width:501px)">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		var submitReadyChild = false;
		var childCount = 1;
		
		 //This funtion adds the child boxes to the form.
    (function($){
        $.fn.addChildForms = function(){
            var myform = "<div class='child' id='ch" + childCount + "'>" +
                "<h3 style='display: inline;'>Human #" + childCount + "</h3><button id='removech" + childCount + "' class='removeButton' type='button' onClick='removeit(ch" + childCount + ")'>Remove</button><br>" +
                "<input placeholder='First Name' class='chfirstName forminput' type='text' name='ch" + childCount + "[firstName]' id='ch" + childCount + "[firstName]'><br>"+
                "<input placeholder='Last Name' class='chlastName forminput' type='text' name='ch" + childCount + "[lastName]' id='ch" + childCount + "[lastName]' value='" + "'></font><br>"+
                "<font class='label' id='Labelch" + childCount + "[category]'>Reading Program: <select name='ch" + childCount + "[category]' id='ch" + childCount + "[category]' onChange='readingProgram(this)'>" + 
                "<option value ='' selected disabled>Select a Program</option>" +
                "<option value='olderChild'>Track Time(Older Child)</option>" +
                "<option value='youngChild'>Track Books(Younger Child)</option>" +
                "<option value='teen'>Teen</option>" +
                "<option value='adult'>Adult</option>" +
                "</select>" +
                "</font><br><br>"+
                "</font><br>"+
                "<font class='label birthday' id='Labelch" + childCount + "[birthday]'>Birthday: <input class='birthday chbirthMonth forminput' type='text' name='ch" + childCount + "[birthMonth]' id='ch" + childCount + "[birthMonth]' placeholder='MM' size='2' maxlength='2' onKeyUp='autoTab(this)' onKeyPress='return isNumberKey(event)'><input class='birthday chbirthDay forminput' type='text' name='ch" + childCount + "[birthDay]' id='ch" + childCount + "[birthDay]' placeholder='DD' size='2' maxlength='2' onKeyUp='autoTab(this)' onKeyPress='return isNumberKey(event)'><input class='birthday chbirthYear forminput' type='text' name='ch" + childCount + "[birthYear]' id='ch" + childCount + "[birthYear]' placeholder='YYYY' size='4' maxlength='4' onKeyUp='autoTab(this)' onKeyPress='return isNumberKey(event)'></font><br class='birthday' id='br" + childCount + "[birthday]'>"+
				 "<font class='label grade' id='Labelch" + childCount + "[grade]'>Last Grade Completed: <select name='ch" + childCount + "[grade]' id='ch" + childCount + "[grade]' class='grade'>" + 
                "<option value='' selected disabled>Select a Grade</option>" +
                "<option value='2 Year Old'>2 Year Old</option>" +
                "<option value='3 Year Old'>3 Year Old</option>" +
                "<option value='4 Year Old'>4 Year Old</option>" +
                "<option value='5 Year Old'>5 Year Old</option>" +
                "<option value='Kindergarten'>Kindergarten</option>" +
                "<option value='1st Grade'>1st Grade</option>" +
                "<option value='2nd Grade'>2nd Grade</option>" +
                "<option value='3rd Grade'>3rd Grade</option>" +
                "<option value='4th Grade'>4th Grade</option>" +
                "<option value='5th Grade'>5th Grade</option>" +
                "<option value='6th Grade'>6th Grade</option>" +
                "<option value='7th Grade'>7th Grade</option>" +
                "<option value='8th Grade'>8th Grade</option>" +
                "<option value='9th Grade'>9th Grade</option>" +
                "<option value='10th Grade'>10th Grade</option>" +
                "<option value='11th Grade'>11th Grade</option>" +
                "<option value='12th Grade'>12th Grade</option>" +
                "</select>" +
                "</font><br class='grade' id='br" + childCount + "[grade1]'><br class='grade' id='br" + childCount + "[grade2]'>"+
				        "<input placeholder='School' class='chschool forminput' type='text' name='ch" + childCount + "[school]' id='ch" + childCount + "[school]'><br class='school' id='br" + childCount + "[school]'>"+
                
                "<font class='label ageRangeLabel' id='Label" + childCount + "[ageRange]'>Age Range</font><br>"+
                "<input type='radio' class='ageRange' name='ch" + childCount + "[ageRange]' id='ch" + childCount + "[ageRange1]' value='18-30'><font class='label ageRange' id='Label" + childCount + "[ageRange1]'>18-30</font><br>"+
                "<input type='radio' class='ageRange' name='ch" + childCount + "[ageRange]' id='ch" + childCount + "[ageRange2]' value='31-40'><font class='label ageRange' id='Label" + childCount + "[ageRange2]'>31-40</font><br>"+
                "<input type='radio' class='ageRange' name='ch" + childCount + "[ageRange]' id='ch" + childCount + "[ageRange3]' value='41-50'><font class='label ageRange' id='Label" + childCount + "[ageRange3]'>41-50</font><br>"+
                "<input type='radio' class='ageRange' name='ch" + childCount + "[ageRange]' id='ch" + childCount + "[ageRange3]' value='51-60'><font class='label ageRange' id='Label" + childCount + "[ageRange3]'>51-60</font><br>"+
                "<input type='radio' class='ageRange' name='ch" + childCount + "[ageRange]' id='ch" + childCount + "[ageRange5]' value='61+'><font class='label ageRange' id='Label" + childCount + "[ageRange5]'>61+</font><br><br><br>"+ 
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
    
function validateForm(){
  var phoneNumber, email, branch, phoneExp, emailExp, shouldReturn;
  phoneNumber = document.forms["register"]["phone"].value;
  email = document.forms["register"]["email"].value;
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
	<h1>Register for KCPL Summer Reading</h1>
	<p>Fill out the following information so you don't have to fill it out again</p>
  <p id="phoneEmail">Please fill out either phone number or email or both</p>
	<form method="post" action="" onSubmit="return validateForm()" name="register" id="register">
		<input type="hidden" name="barcode" id="barcode" value="<?php echo $_GET['barcode'];?>">
		<input class="forminput" type="tel" name="phone" id="phone" placeholder="Phone Number" onChange="checkPhone(this)" onKeyPress="return isNumberKey(event)"><br>
    <input class="forminput" type="text" name="email" id="email" placeholder="Email Address"><br>
    <select class="forminput" type="text" name="branch" id="branch">
      <option disabled selected value="">Select a Branch</option>
      <option value="Covington">Covington</option>
      <option value="Durr">William E. Durr</option>
      <option value="Erlanger">Erlanger</option>
    </select>
		
		<div>
			<div id="container">
				
			</div>
			<button id="addchild" class="label" type="button">Add Human</button><br>
    		<button id="submit" class="label submit" type="submit">Register</button>
		</div>
	</form>
</div>
</body>
</html>