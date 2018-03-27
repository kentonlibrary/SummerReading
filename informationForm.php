<?php
include('assets/scripts.php'); //File with connection information and functions
if(isset($_POST['formType'])){
  if($_POST['formType'] == "register"){
    $barcode = $_POST['barcode'];
    unset($_POST['barcode']);
    $phone = $_POST['phone'];
    unset($_POST['phone']);
    $branch = $_POST['branch'];
    unset($_POST['branch']);
    $email = $_POST['email'];
    unset($_POST['email']);
    unset($_POST['formType']);

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

  //Save information
  if(($_POST['formType'] == "update")){
    unset($_POST['formType']);
    unset($_POST['barcode']);
    $phone = $_POST['phone'];
    unset($_POST['phone']);
    $email = $_POST['email'];
    unset($_POST['email']);
    $branch = $_POST['branch'];
    unset($_POST['branch']);
    $accountID = $_SESSION['accountID'];
    unset($_POST['formType']);

    foreach($_POST as $key => $value){
      if( $value['readerSave'] == 'new'){
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
      elseif( $value['readerSave'] == 'existing'){
        $firstName = $value['firstName'];
        $lastName = $value['lastName'];
        $birthdate = $value['birthYear'] . "-" . $value['birthMonth'] . "-" . $value['birthDay'];
        $grade = $value['grade'];
        $school = $value['school'];
        $category = $value['category'];
        if(isset($value['ageRange'])){
          $ageRange = $value['ageRange'];
        }
        else $ageRange = NULL;

        $query = $connection->prepare("UPDATE reader SET readerFirstName = ?, readerLastName = ?, readerBirthDate = ?, readerCategory = ?, readerSchool = ?, readerGrade = ?, readerAgeRange = ? WHERE readerID = ?");

        $query->bind_param("sssssssi", $firstName, $lastName, $birthdate, $category, $school, $grade, $ageRange, $key);

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
  $barcode = $_GET['barcode'];
  $formType = "register";
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
	<script type="text/javascript" src="assets/registration.js"></script>

</head>

<body>
<?php echo $headerNav; ?>
<div class="register">
	<h1>Register for KCPL Summer Reading</h1>
	<p>Fill out the following information so you don't have to fill it out again</p>
  <p id="phoneEmail">Please fill out either phone number or email or both</p>
	<form method="post" action="" onSubmit="return validateForm()" name="register" id="register">
   <?php
    if($formType == "register"){
      ?>
    <input type="hidden" name="barcode" id="barcode" value="<?php echo $barcode;?>">
    <input type="hidden" name="formType" id="formType" value="<?php echo $formType;?>">
		<input class="forminput" type="tel" name="phone" id="phone" placeholder="Phone Number" onChange="checkPhone(this)" onKeyPress="return isNumberKey(event)"><br>
    <input class="forminput" type="text" name="email" id="email" placeholder="Email Address"><br>
    <select class="forminput" type="text" name="branch" id="branch">
      <option disabled selected value="">Select a Branch</option>
      <option value="Covington">Covington</option>
      <option value="Durr">William E. Durr</option>
      <option value="Erlanger">Erlanger</option>
    </select>
    <br><br>
    <?php
    }
    
    if($formType == "update"){
      while( $accountInfo->fetch() ){
  ?>
		<input type="hidden" name="barcode" id="barcode" value="<?php echo $barcode;?>">
    <input type="hidden" name="formType" id="formType" value="<?php echo $formType;?>">
		<input class="forminput" type="tel" name="phone" id="phone" placeholder="Phone Number" onChange="checkPhone(this)" onKeyPress="return isNumberKey(event)" value="<?php echo $phoneNumber; ?>"><br>
    <input class="forminput" type="text" name="email" id="email" placeholder="Email Address" value="<?php echo $emailAddress; ?>"><br>
    <select class="forminput" type="text" name="branch" id="branch">
      <option disabled selected value="">Select a Branch</option>
      <option value="Covington" <?php if($branch == "Covington"){echo "Selected";} ?>>Covington</option>
      <option value="Durr" <?php if($branch == "Durr"){echo "Selected";} ?>>William E. Durr</option>
      <option value="Erlanger" <?php if($branch == "Erlanger"){echo "Selected";} ?>>Erlanger</option>
    </select>
		<?php } ?>
		<div>
			<div id="container2">

    <div id="container">
          
    <?php
    $readerQuery = "SELECT readerID, readerFirstName, readerLastName, YEAR(readerBirthDate), MONTH(readerBirthDate), DAY(readerBirthDate), readerAgeRange, readerCategory, readerSchool, readerGrade FROM reader WHERE accountID = ?";
    if( $readerInfo = $connection->prepare($readerQuery)){
		$readerInfo->bind_param("i", $accountID);
		$readerInfo->execute();
		
		$readerInfo->bind_result($readerID, $readerFirstName, $readerLastName, $readerBirthYear, $readerBirthMonth, $readerBirthDay, $readerAgeRange, $readerCategory, $readerSchool, $readerGrade);
		while( $readerInfo->fetch() ){
    ?>
          <br><br><br><br>
			<h3 style='display: inline;'><?php echo $readerFirstName . " " . $readerLastName; ?></h3><br>
                <input type='hidden' name='<?php echo $readerID; ?>[readerSave]' id='<?php echo $readerID; ?>[readerSave]' value="existing">
                <input placeholder='First Name' class='chfirstName forminput' type='text' name='<?php echo $readerID; ?>[firstName]' id='<?php echo $readerID; ?>[firstName]' value="<?php echo $readerFirstName ?>"><br>
                <input placeholder='Last Name' class='chlastName forminput' type='text' name='<?php echo $readerID; ?>[lastName]' id='<?php echo $readerID; ?>[lastName]' value='<?php echo $readerLastName ?>'></font><br>
                <font class='label' id='Labelch<?php echo $readerID; ?>[category]'>Reading Program: <select name='<?php echo $readerID; ?>[category]' id='<?php echo $readerID; ?>[category]' onChange='readingProgram(this)'>
                <option value ='' selected disabled>Select a Program</option>
                <option value='olderChild' <?php if($readerCategory == "olderChild"){echo "Selected";} ?>>Track Time(Older Child)</option>
                <option value='youngChild' <?php if($readerCategory == "youngChild"){echo "Selected";} ?>>Track Books(Younger Child)</option>
                <option value='teen' <?php if($readerCategory == "teen"){echo "Selected";} ?>>Teen</option>
                <option value='adult' <?php if($readerCategory == "adult"){echo "Selected";} ?>>Adult</option>
                </select>
                </font><br><br>
                </font><br>
                <font class='label birthday' id='Labelch<?php echo $readerID; ?>[birthday]'>Birthday: <input class='birthday chbirthMonth forminput' type='text' name='<?php echo $readerID; ?>[birthMonth]' id='<?php echo $readerID; ?>[birthMonth]' placeholder='MM' size='2' maxlength='2' onKeyUp='autoTab(this)' onKeyPress='return isNumberKey(event)' value="<?php echo $readerBirthMonth; ?>"><input class='birthday chbirthDay forminput' type='text' name='<?php echo $readerID; ?>[birthDay]' id='<?php echo $readerID; ?>[birthDay]' placeholder='DD' size='2' maxlength='2' onKeyUp='autoTab(this)' onKeyPress='return isNumberKey(event)'  value="<?php echo $readerBirthDay; ?>"><input class='birthday chbirthYear forminput' type='text' name='<?php echo $readerID; ?>[birthYear]' id='<?php echo $readerID; ?>[birthYear]' placeholder='YYYY' size='4' maxlength='4' onKeyUp='autoTab(this)' onKeyPress='return isNumberKey(event)' value="<?php echo $readerBirthYear; ?>"></font><br class='birthday' id='br<?php echo $readerID; ?>[birthday]'>
				 <font class='label grade' id='Labelch<?php echo $readerID; ?>[grade]'>Last Grade Completed: <select name='<?php echo $readerID; ?>[grade]' id='<?php echo $readerID; ?>[grade]' class='grade'>
                <option value='' selected disabled>Select a Grade</option>
                <option value='2 Year Old' <?php if($readerGrade == "2 Year Old"){echo "Selected";} ?>>2 Year Old</option>
                <option value='3 Year Old' <?php if($readerGrade == "3 Year Old"){echo "Selected";} ?>>3 Year Old</option>
                <option value='4 Year Old' <?php if($readerGrade == "4 Year Old"){echo "Selected";} ?>>4 Year Old</option>
                <option value='5 Year Old' <?php if($readerGrade == "5 Year Old"){echo "Selected";} ?>>5 Year Old</option>
                <option value='Kindergarten' <?php if($readerGrade == "Kindergarten"){echo "Selected";} ?>>Kindergarten</option>
                <option value='1st Grade' <?php if($readerGrade == "1st Grade"){echo "Selected";} ?>>1st Grade</option>
                <option value='2nd Grade' <?php if($readerGrade == "2nd Grade"){echo "Selected";} ?>>2nd Grade</option>
                <option value='3rd Grade' <?php if($readerGrade == "3rd Grade"){echo "Selected";} ?>>3rd Grade</option>
                <option value='4th Grade' <?php if($readerGrade == "4th Grade"){echo "Selected";} ?>>4th Grade</option>
                <option value='5th Grade' <?php if($readerGrade == "5th Grade"){echo "Selected";} ?>>5th Grade</option>
                <option value='6th Grade' <?php if($readerGrade == "6th Grade"){echo "Selected";} ?>>6th Grade</option>
                <option value='7th Grade' <?php if($readerGrade == "7th Grade"){echo "Selected";} ?>>7th Grade</option>
                <option value='8th Grade' <?php if($readerGrade == "8th Grade"){echo "Selected";} ?>>8th Grade</option>
                <option value='9th Grade' <?php if($readerGrade == "9th Grade"){echo "Selected";} ?>>9th Grade</option>
                <option value='10th Grade' <?php if($readerGrade == "10th Grade"){echo "Selected";} ?>>10th Grade</option>
                <option value='11th Grade' <?php if($readerGrade == "11th Grade"){echo "Selected";} ?>>11th Grade</option>
                <option value='12th Grade' <?php if($readerGrade == "12th Grade"){echo "Selected";} ?>>12th Grade</option>
                </select>
                </font><br class='grade' id='br<?php echo $readerID; ?>[grade1]'><br class='grade' id='br<?php echo $readerID; ?>[grade2]'>
				        <input placeholder='School' class='chschool forminput' type='text' name='<?php echo $readerID; ?>[school]' id='<?php echo $readerID; ?>[school]' value="<?php echo $readerSchool ?>"><br class='school' id='br<?php echo $readerID; ?>[school]'>
                      
                <font class='label ageRangeLabel' id='Label<?php echo $readerID; ?>[ageRange]'>Age Range</font><br>
                <input type='radio' class='ageRange' name='<?php echo $readerID; ?>[ageRange]' id='<?php echo $readerID; ?>[ageRange1]' value='18-30' <?php if($readerAgeRange == "18-30"){echo "checked";} ?>><font class='label ageRange' id='Label<?php echo $readerID; ?>[ageRange1]'>18-30</font><br>
                <input type='radio' class='ageRange' name='<?php echo $readerID; ?>[ageRange]' id='<?php echo $readerID; ?>[ageRange2]' value='31-40' <?php if($readerAgeRange == "31-40"){echo "checked";} ?>><font class='label ageRange' id='Label<?php echo $readerID; ?>[ageRange2]'>31-40</font><br>
                <input type='radio' class='ageRange' name='<?php echo $readerID; ?>[ageRange]' id='<?php echo $readerID; ?>[ageRange3]' value='41-50' <?php if($readerAgeRange == "41-50"){echo "checked";} ?>><font class='label ageRange' id='Label<?php echo $readerID; ?>[ageRange3]'>41-50</font><br>
                <input type='radio' class='ageRange' name='<?php echo $readerID; ?>[ageRange]' id='<?php echo $readerID; ?>[ageRange4]' value='51-60' <?php if($readerAgeRange == "51-60"){echo "checked";} ?>><font class='label ageRange' id='Label<?php echo $readerID; ?>[ageRange4]'>51-60</font><br>
                <input type='radio' class='ageRange' name='<?php echo $readerID; ?>[ageRange]' id='<?php echo $readerID; ?>[ageRange5]' value='61+' <?php if($readerAgeRange == "61+"){echo "checked";} ?>><font class='label ageRange' id='Label<?php echo $readerID; ?>[ageRange5]'>61+</font><br>
                <input type='radio' class='ageRange' name='<?php echo $readerID; ?>[ageRange]' id='<?php echo $readerID; ?>[ageRange6]' value='N/A' <?php if($readerAgeRange == "N/A"){echo "checked";} ?>><font class='label ageRange' id='Label<?php echo $readerID; ?>[ageRange6]'>Perfer not to say</font>
                <br><br><br>
                <script>
                    selectObject = document.getElementById("<?php echo $readerID; ?>[category]");
                    window.onload = readingProgramExisting(selectObject);  
                </script>
          <?php } } }?>
          <div id="readerSection">
      
          </div>
          <div id="container3">
				
			<button id="addchild" class="label" type="button" onclick="addChildForms();">Add Reader</button><br>
    		<button id="submit" class="label submit" type="submit" <?php if($formType == 'register'){?>style="display: none;"<?php } ?>>Finish</button>
		</div>
    </div>
	</form>
</div>
  </div>
  
  </body>
</html>