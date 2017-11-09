<?php
include('assets/scripts.php'); //File with connection information and functions

if(isset($_POST['barcode'])){
	$barcode = $_POST['barcode'];
	unset($_POST['barcode']);
	$phone = $_POST['phone'];
	unset($_POST['phone']);
	
	$accountQuery = $connection->prepare("INSERT INTO account (barcode, phoneNumber) VALUES (?, ?)");
	$accountQuery->bind_param("ss", $barcode, $phone);
	
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
		print_r( $query );
		$query->close();
	}
	
	session_start();
	$_SESSION['accountID'] = $accountID;
	$accountQuery->close();
	header("Location: log.php");
	
}

?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Register</title>
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
                "<option value='4 Year Old'>4 Year Old</option>" +
                "<option value='5 Year Old'>5 Year Old</option>" +
                "<option value='Kindergarten'>Kindergarten</option>" +
                "<option value='1st Grade'>1st Grade</option>" +
                "<option value='2nd Grade'>2nd Grade</option>" +
                "<option value='3rd Grade'>3rd Grade</option>" +
                "<option value='4th Grade'>4th Grade</option>" +
                "<option value='5th Grade'>5th Grade</option>" +
                "</select>" +
                "</font><br><br>"+
				 "<font class='label' id='Labelch" + childCount + "[category]'><input type='radio' name='ch" + childCount + "[category]' id='ch" + childCount + "[category]' value='olderChild'>Track Time(Older Child) <input type='radio' name='ch" + childCount + "[category]' id='ch" + childCount + "[category]' value='youngChild'>Track Books(Younger Child) " + 
                "</select>" +
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
            submitReadyChild = true;    
            if( submitReadyPickup && submitReadyChild ){
                    document.getElementById("submit").className="label";
            }
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
     
	</script>
</head>

<body>
	
<div class="register">
	<h1>Register for KCPL Summer Reading</h1>
	<p>Fill out the following information so you don't have to fill it out again</p>
	<form method="post" action="">
		<input type="hidden" name="barcode" id="barcode" value="<?php echo $_GET['barcode'];?>">
		<input class="forminput" type="tel" name="phone" id="phone" placeholder="Phone Number"><br>
		
		<div>
			<div id="container">
				
			</div>
			<button id="addchild" class="label" type="button">Add Child</button><br>
    		<button id="submit" class="label submit" type="submit">Register</button>
		</div>
	</form>
</div>
</body>
</html>