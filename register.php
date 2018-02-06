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
		
		$query = $connection->prepare("INSERT INTO reader (accountID, readerFirstName, readerLastName, readerBirthDate, readerCategory, readerSchool, readerGrade) VALUES (?, ?, ?, ?, ?, ?, ?)");
		
		$query->bind_param("issssss", $accountID, $firstName, $lastName, $birthdate, $category, $school, $grade);
		
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
                "<h3 style='display: inline;'>Child #" + childCount + "</h3><button id='removech" + childCount + "' class='removeButton' type='button' onClick='removeit(ch" + childCount + ")'>Remove</button><br>" +
                "<input placeholder='First Name' class='chfirstName forminput' type='text' name='ch" + childCount + "[firstName]' id='ch" + childCount + "[firstName]'><br>"+
                "<input placeholder='Last Name' class='chlastName forminput' type='text' name='ch" + childCount + "[lastName]' id='ch" + childCount + "[lastName]' value='" + "'></font><br>"+
                "<font class='label' id='Labelch" + childCount + "[birthday]'>Birthday: <input class='birthday chbirthMonth forminput' type='text' name='ch" + childCount + "[birthMonth]' id='ch" + childCount + "[birthMonth]' placeholder='MM' size='2' maxlength='2' onKeyUp='autoTab(this)' onKeyPress='return isNumberKey(event)'><input class='birthday chbirthDay forminput' type='text' name='ch" + childCount + "[birthDay]' id='ch" + childCount + "[birthDay]' placeholder='DD' size='2' maxlength='2' onKeyUp='autoTab(this)' onKeyPress='return isNumberKey(event)'><input class='birthday chbirthYear forminput' type='text' name='ch" + childCount + "[birthYear]' id='ch" + childCount + "[birthYear]' placeholder='YYYY' size='4' maxlength='4' onKeyUp='autoTab(this)' onKeyPress='return isNumberKey(event)'></font><br>"+
				 "<font class='label' id='Labelch" + childCount + "[grade]'>Last Grade Completed: <select name='ch" + childCount + "[grade]' id='ch" + childCount + "[grade]'>" + 
                "<option selected disabled>Select a Grade</option>" +
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
                "</font><br><br>"+
				"<font class='label' id='Labelch" + childCount + "[category]'>Reading Program: <select name='ch" + childCount + "[category]' id='ch" + childCount + "[category]'>" + 
                "<option selected disabled>Select a Program</option>" +
                "<option value='olderChild'>Track Time(Older Child)</option>" +
                "<option value='youngChild'>Track Books(Younger Child)</option>" +
                "<option value='teen'>Teen</option>" +
                "</select>" +
                "</font><br><br>"+
                "</font><br>"+
				"<input placeholder='School' class='chschool forminput' type='text' name='ch" + childCount + "[school]' id='ch" + childCount + "[school]'><br>"+
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
     
	</script>
</head>

<body>
	
<div class="register">
	<h1>Register for KCPL Summer Reading</h1>
	<p>Fill out the following information so you don't have to fill it out again</p>
	<form method="post" action="">
		<input type="hidden" name="barcode" id="barcode" value="<?php echo $_GET['barcode'];?>">
		<input class="forminput" type="tel" name="phone" id="phone" placeholder="Phone Number" onChange="checkPhone(this)" onKeyPress="return isNumberKey(event)"><br>
    <input class="forminput" type="text" name="email" id="email" placeholder="Email Address"><br>
    <select class="forminput" type="text" name="branch" id="branch">
      <option disabled selected>Select a Branch</option>
      <option value="Covington">Covington</option>
      <option value="Durr">William E. Durr</option>
      <option value="Erlanger">Erlanger</option>
    </select>
		
		<div>
			<div id="container">
				
			</div>
			<button id="addchild" class="label" type="button">Add Entrant</button><br>
    		<button id="submit" class="label submit" type="submit">Register</button>
		</div>
	</form>
</div>
</body>
</html>