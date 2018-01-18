<?php
include('assets/scripts.php'); //File with connection information and functions
session_start();


//Save information
if(($_POST['formType'] == "main")){
  unset($_POST['formType']);
  $phone = $_POST['phone'];
  unset($_POST['phone']);
  $email = $_POST['email'];
  unset($_POST['email']);
  $branch = $_POST['branch'];
  unset($_POST['branch']);
  $accountID = $_SESSION['accountID'];
  
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


if(isset($_SESSION['accountID'])){
  
  $accountID = $_SESSION['accountID'];
  $accountQuery = "SELECT phoneNumber, emailAddress, branch FROM account WHERE accountID = ?";
  
  if( $accountInfo = $connection->prepare($accountQuery)){
		$accountInfo->bind_param("i", $accountID);
		$accountInfo->execute();
		
		$accountInfo->bind_result($phoneNumber, $emailAddress, $branch);
		while( $accountInfo->fetch() ){
        
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Edit Information</title>
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
<div class="editInfo" style="text-align: right">
    <a href="log.php">Back to Log</a>
  </div>
<div class="register">
	<h1>Summer Reading Information</h1>
	<p>Below is the information we have on file for you.</p>
	<form method="post" action="">
    <input type="hidden" name="formType" id="formType" value="main">
		<input class="forminput" type="tel" name="phone" id="phone" placeholder="Phone Number" onChange="checkPhone(this)" onKeyPress="return isNumberKey(event)" value="<?php echo $phoneNumber ?>"><br>
    <input class="forminput" type="text" name="email" id="email" placeholder="Email Address" value="<?php echo $emailAddress ?>"><br>
    <select class="forminput" type="text" name="branch" id="branch">
      <option value="Covington" <?php if($branch == "Covington"){echo "Selected";} ?>>Covington</option>
      <option value="Durr" <?php if($branch == "Durr"){echo "Selected";} ?>>William E. Durr</option>
      <option value="Erlanger" <?php if($branch == "Erlanger"){echo "Selected";} ?>>Erlanger</option>
    </select>
		<?php
    }
    ?>
		<div>
			<div id="container">
        
        <div id="container">
          
    <?php
    $readerQuery = "SELECT readerID, readerFirstName, readerLastName, YEAR(readerBirthDate), MONTH(readerBirthDate), DAY(readerBirthDate), readerCategory, readerSchool, readerGrade FROM reader WHERE accountID = ?";
    if( $readerInfo = $connection->prepare($readerQuery)){
		$readerInfo->bind_param("i", $accountID);
		$readerInfo->execute();
		
		$readerInfo->bind_result($readerID, $readerFirstName, $readerLastName, $readerBirthYear, $readerBirthMonth, $readerBirthDay, $readerCategory, $readerSchool, $readerGrade);
		while( $readerInfo->fetch() ){
    ?>
          <br><br><br><br>
			<div class="child" id="<?php echo $readerID; ?>">
        <h1 style="display: inline;"> <?php echo $readerFirstName . " " . $readerLastName ?></h1><br>
        <input placeholder="First Name" class="chfirstName forminput" type="text" name="<?php echo $readerID; ?>[firstName]" id="<?php echo $readerID; ?>[firstName]" value="<?php echo $readerFirstName ?>"><br>
        <input placeholder="Last Name" class="chlastName forminput" type="text" name="<?php echo $readerID; ?>[lastName]" id="<?php echo $readerID; ?>[lastName]"  value="<?php echo $readerLastName ?>"><br>
        <font class="label" id="Label<?php echo $readerID; ?>[birthday]">Birthday: 
          <input class="birthday chbirthMonth forminput" type="text" name="<?php echo $readerID; ?>[birthMonth]" id="<?php echo $readerID; ?>[birthMonth]" placeholder="MM" size="2" maxlength="2" onkeyup="autoTab(this)" onkeypress="return isNumberKey(event)" value="<? echo $readerBirthMonth; ?>">
          <input class="birthday chbirthDay forminput" type="text" name="<?php echo $readerID; ?>[birthDay]" id="<?php echo $readerID; ?>[birthDay]" placeholder="DD" size="2" maxlength="2" onkeyup="autoTab(this)" onkeypress="return isNumberKey(event)" value="<? echo $readerBirthDay; ?>">
          <input class="birthday chbirthYear forminput" type="text" name="<?php echo $readerID; ?>[birthYear]" id="<?php echo $readerID; ?>[birthYear]" placeholder="YYYY" size="4" maxlength="4" onkeyup="autoTab(this)" onkeypress="return isNumberKey(event)" value="<? echo $readerBirthYear; ?>">
        </font><br>
        <font class="label" id="Label<?php echo $readerID; ?>[grade]">Last Grade Completed: 
          <select name="<?php echo $readerID; ?>[grade]" id="<?php echo $readerID; ?>[grade]">
            <option value="4 Year Old" <?php if($readerGrade == "4 Year Old"){echo "Selected";} ?>>4 Year Old</option>
            <option value="5 Year Old" <?php if($readerGrade == "5 Year Old"){echo "Selected";} ?>>5 Year Old</option>
            <option value="Kindergarten" <?php if($readerGrade == "Kindergarten"){echo "Selected";} ?>>Kindergarten</option>
            <option value="1st Grade" <?php if($readerGrade == "1st Grade"){echo "Selected";} ?>>1st Grade</option>
            <option value="2nd Grade" <?php if($readerGrade == "2nd Grade"){echo "Selected";} ?>>2nd Grade</option>
            <option value="3rd Grade <?php if($readerGrade == "3rd Grade"){echo "Selected";} ?>">3rd Grade</option>
            <option value="4th Grade" <?php if($readerGrade == "4th Grade"){echo "Selected";} ?>>4th Grade</option>
            <option value="5th Grade" <?php if($readerGrade == "5th Grade"){echo "Selected";} ?>>5th Grade</option>
            <option value="6th Grade" <?php if($readerGrade == "6th Grade"){echo "Selected";} ?>>6th Grade</option>
            <option value="7th Grade" <?php if($readerGrade == "7th Grade"){echo "Selected";} ?>>7th Grade</option>
            <option value="8th Grade" <?php if($readerGrade == "8th Grade"){echo "Selected";} ?>>8th Grade</option>
            <option value="9th Grade" <?php if($readerGrade == "9th Grade"){echo "Selected";} ?>>9th Grade</option>
            <option value="10th Grade" <?php if($readerGrade == "10th Grade"){echo "Selected";} ?>>10th Grade</option>
            <option value="11th Grade" <?php if($readerGrade == "11th Grade"){echo "Selected";} ?>>11th Grade</option>
            <option value="12th Grade" <?php if($readerGrade == "12th Grade"){echo "Selected";} ?>>12th Grade</option>
          </select>
        </font><br><br><font class="label" id="Label<?php echo $readerID; ?>[category]">Reading Program: 
        <select name="<?php echo $readerID; ?>[category]" id="<?php echo $readerID; ?>[category]">
          <option selected="" disabled="">Select a Program</option>
          <option value="olderChild" <?php if($readerCategory == "olderChild"){echo "Selected";} ?>>Track Time(Older Child)
          </option><option value="youngChild"  <?php if($readerCategory == "youngChild"){echo "Selected";} ?>>Track Books(Younger Child)</option>
          <option value="teen"  <?php if($readerCategory == "teen"){echo "Selected";} ?>>Teen</option>
        </select>
        </font><br><br><br>
        <input placeholder="School" class="chschool forminput" type="text" name="<?php echo $readerID; ?>[school]" id="<?php echo $readerID; ?>[school]" value="<?php echo $readerSchool ?>"><br>
          </div>
          <?php } }?>
        </div>
			</div>
			<button id="addchild" class="label" type="button">Add Child</button><br>
    		<button id="submit" class="label submit" type="submit">Save</button>
		</div>
	</form>
</div>
</body>
</html>

<?php
  }
}
?>