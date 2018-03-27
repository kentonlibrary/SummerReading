(function () {
   'use strict';
   // this function is strict...
}());	
var submitReadyChild = false;
		var childCount = 1;
		
		 //This funtion adds the child boxes to the form.
        function addChildForms(){
            var myform = "<div class='child' id='ch" + childCount + "'>" +
                "<h3 style='display: inline;'>Reader #" + childCount + "</h3><button id='removech" + childCount + "' class='removeButton' type='button' onClick='removeit(ch" + childCount + ")'>Remove</button><br>" +
                "<input type='hidden' name='" + childCount + "[readerSave]' id='" + childCount + "[readerSave]' value='new'>"+
                "<input placeholder='First Name' class='chfirstName forminput' type='text' name='" + childCount + "[firstName]' id='" + childCount + "[firstName]'><br>"+
                "<input placeholder='Last Name' class='chlastName forminput' type='text' name='" + childCount + "[lastName]' id='" + childCount + "[lastName]' value='" + "'></font><br>"+
                "<font class='label' id='Labelch" + childCount + "[category]'>Reading Program: <select name='" + childCount + "[category]' id='" + childCount + "[category]' onChange='readingProgram(this)'>" + 
                "<option value ='' selected disabled>Select a Program</option>" +
                "<option value='olderChild'>Track Time(Older Child)</option>" +
                "<option value='youngChild'>Track Books(Younger Child)</option>" +
                "<option value='teen'>Teen</option>" +
                "<option value='adult'>Adult</option>" +
                "</select>" +
                "</font><br><br>"+
                "</font><br>"+
                "<font class='label birthday' id='Labelch" + childCount + "[birthday]'>Birthday: <input class='birthday chbirthMonth forminput' type='text' name='" + childCount + "[birthMonth]' id='" + childCount + "[birthMonth]' placeholder='MM' size='2' maxlength='2' onKeyUp='autoTab(this)' onKeyPress='return isNumberKey(event)'><input class='birthday chbirthDay forminput' type='text' name='" + childCount + "[birthDay]' id='" + childCount + "[birthDay]' placeholder='DD' size='2' maxlength='2' onKeyUp='autoTab(this)' onKeyPress='return isNumberKey(event)'><input class='birthday chbirthYear forminput' type='text' name='" + childCount + "[birthYear]' id='" + childCount + "[birthYear]' placeholder='YYYY' size='4' maxlength='4' onKeyUp='autoTab(this)' onKeyPress='return isNumberKey(event)'></font><br class='birthday' id='br" + childCount + "[birthday]'>"+
				 "<font class='label grade' id='Labelch" + childCount + "[grade]'>Last Grade Completed: <select name='" + childCount + "[grade]' id='" + childCount + "[grade]' class='grade'>" + 
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
				        "<input placeholder='School' class='chschool forminput' type='text' name='" + childCount + "[school]' id='" + childCount + "[school]'><br class='school' id='br" + childCount + "[school]'>"+
                
                "<font class='label ageRangeLabel' id='Label" + childCount + "[ageRange]'>Age Range</font><br>"+
                "<input type='radio' class='ageRange' name='" + childCount + "[ageRange]' id='" + childCount + "[ageRange1]' value='18-30'><font class='label ageRange' id='Label" + childCount + "[ageRange1]'>18-30</font><br>"+
                "<input type='radio' class='ageRange' name='" + childCount + "[ageRange]' id='" + childCount + "[ageRange2]' value='31-40'><font class='label ageRange' id='Label" + childCount + "[ageRange2]'>31-40</font><br>"+
                "<input type='radio' class='ageRange' name='" + childCount + "[ageRange]' id='" + childCount + "[ageRange3]' value='41-50'><font class='label ageRange' id='Label" + childCount + "[ageRange3]'>41-50</font><br>"+
                "<input type='radio' class='ageRange' name='" + childCount + "[ageRange]' id='" + childCount + "[ageRange4]' value='51-60'><font class='label ageRange' id='Label" + childCount + "[ageRange4]'>51-60</font><br>"+
                "<input type='radio' class='ageRange' name='" + childCount + "[ageRange]' id='" + childCount + "[ageRange5]' value='61+'><font class='label ageRange' id='Label" + childCount + "[ageRange5]'>61+</font><br>"+ 
                "<input type='radio' class='ageRange' name='" + childCount + "[ageRange]' id='" + childCount + "[ageRange6]' value='N/A'><font class='label ageRange' id='Label" + childCount + "[ageRange6]'>Perfer not to say</font><br><br><br>"+ 
                "</div>";
 
            var container = document.getElementById('readerSection');
            var submitButton = document.getElementById('submit');
            submitButton.style.display = "inline-block";  
            $("#readerSection").append(myform);
            childCount++;
        }
     
    function addChild(){
            addChildForms();   
    }
		
		//This function autotabs birthday fields
    function autoTab (obj){
      "use strict";
        if (obj.value.length === obj.maxLength) {
            $(obj).next('.birthday').focus();
        }
    }
     
//This function removes dynamically added div containers
    function removeit(divID) {
      "use strict";
        var removeID = $(divID);
        removeID.remove();   
    }
    
//Number Only Field
    function isNumberKey(evt){
      "use strict";
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)){
        return false;
    }
    return true;
}
    
//Phone validation
    function checkPhone (obj) {
      "use strict";
  var str = obj.value.replace(/[^0-9]+?/g, '');
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
  "use strict";
  switch (obj.value) {
    case 'olderChild':
      var entrantID = obj.name.substring(0, obj.name.length - 10);
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
      document.getElementById("Label" + entrantID + "[ageRange6]").style.display = "none";
      document.getElementById(entrantID + "[ageRange6]").style.display = "none";
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
      document.getElementById("Label" + entrantID + "[ageRange6]").style.display = "none";
      document.getElementById(entrantID + "[ageRange6]").style.display = "none";
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
      document.getElementById("Label" + entrantID + "[ageRange6]").style.display = "none";
      document.getElementById(entrantID + "[ageRange6]").style.display = "none";
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
      document.getElementById("Label" + entrantID + "[ageRange6]").style.display = "inline";
      document.getElementById(entrantID + "[ageRange6]").style.display = "inline";
      break;
  }
}
    
    
function readingProgramExisting(obj){
  "use strict";
  switch (obj.value) {
    case 'olderChild':
      var entrantID = obj.name.substring(0, obj.name.length - 10);
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
  "use strict";
  var phoneNumber, email, branch, phoneExp, emailExp, shouldReturn;
  phoneNumber = document.forms["register"]["phone"].value;
  email = document.forms["register"]["email"].value;
  branch = document.forms["register"]["branch"].value;
  
  phoneExp = /^\([0-9]{3}\)[0-9]{3}-[0-9]{4}$/;
  emailExp = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
  shouldReturn = true;
  //phoneExp = /^\d{10}$/
  if( (phoneExp.test(phoneNumber)) || ( emailExp.test(email)) ){}
  else{
    document.getElementById('email').style.borderBottom = "5px solid red";
    document.getElementById('phone').style.borderBottom = "5px solid red";
    document.getElementById('phoneEmail').style.color = "red";
    document.getElementById('phoneEmail').style.fontWeight = "bold";
    shouldReturn = false;
  }
  if(branch === ''){
    document.getElementById('branch').style.borderBottom = "5px solid red";
    shouldReturn = false;
  }
  for (var i = 1, len = childCount; i < len; i++){
    var firstName = '', lastName = '', readingProgram = '', birthDay = '', birthMonth = '', birthYear = '', grade = '', school = '', ageRange = '', ageRange_Value = '';
    firstName = document.getElementById("ch" + i + "[firstName]").value;
    if(firstName === ''){
      document.getElementById("ch" + i + "[firstName]").style.borderBottom = "5px solid red";
      shouldReturn = false;
    }
    lastName = document.getElementById("ch" + i + "[lastName]").value;
    if(lastName === ''){
      document.getElementById("ch" + i + "[lastName]").style.borderBottom = "5px solid red";
      shouldReturn = false;
    }
    readingProgram = document.getElementById("ch" + i + "[category]").value;
    if(readingProgram === ''){
      document.getElementById("ch" + i + "[category]").style.borderBottom = "5px solid red";
      shouldReturn = false;
    }
    if(readingProgram === "olderChild" || readingProgram === "youngChild" || readingProgram === "teen"){
      birthDay = document.getElementById("ch" + i + "[birthDay]").value;
      if(birthDay > 31 || birthDay < 1 || birthDay === ''){
        document.getElementById("ch" + i + "[birthDay]").style.borderBottom = "5px solid red";
        shouldReturn = false;
      }
      birthMonth = document.getElementById("ch" + i + "[birthMonth]").value;
      if(birthMonth > 12 || birthMonth < 1 || birthMonth === ''){
        document.getElementById("ch" + i + "[birthMonth]").style.borderBottom = "5px solid red";
        shouldReturn = false;
      }
      birthYear = document.getElementById("ch" + i + "[birthYear]").value;
      if(birthYear >= 2018 || birthYear <= 1800 || birthYear === ''){
        document.getElementById("ch" + i + "[birthYear]").style.borderBottom = "5px solid red";
        shouldReturn = false;
      }
      grade = document.getElementById("ch" + i + "[grade]").value;
      if(grade === ''){
        document.getElementById("ch" + i + "[grade]").style.borderBottom = "5px solid red";
        shouldReturn = false;
      }
      school = document.getElementById("ch" + i + "[school]").value;
      if(school === ''){
        document.getElementById("ch" + i + "[school]").style.borderBottom = "5px solid red";
        shouldReturn = false;
      }
      //alert("Entrant " + i + "\n" + "Name: " + firstName + " " + lastName + "\nBirthday: " + birthMonth + "/" + birthDay + "/" + birthYear + "\nGrade: " + grade + "\nSchool: " + school);
    }
    if(readingProgram === "adult"){
      ageRange = document.getElementsByName('ch' + i + '[ageRange]');
      
      for(var x = 0; x < ageRange.length; x++){
        if(ageRange[x].checked){
          ageRange_Value = ageRange[x].value;
        }
      }
      if(ageRange_Value === ''){
        document.getElementById("Label" + i + "[ageRange]").style.color = "red";
        document.getElementById("Label" + i + "[ageRange]").style.fontWeight = "bold";
        shouldReturn = false;
      }
      //alert("Entrant " + i + "\n" + "Name: " + firstName + " " + lastName + "\nAge Range: " + ageRange_Value);
    }
  }
  return shouldReturn;
}