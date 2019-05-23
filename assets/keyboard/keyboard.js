// JavaScript Document
$(function(){
    var write = document.getElementById('cardNumber');    
    $('#keyboard li').click(function(){
        var $this = $(this),
            character = $this.html(); // If it's a lowercase letter, nothing happens to this variable
        // Delete
        if ($this.hasClass('delete')) {             
            write.value = (write.value.substr(0, write.value.length - 1));
            return false;
         }
          if ($this.hasClass('return')) {             
            return false;
         }
         
        // Add the character
        write.value = write.value + character;
    });
});