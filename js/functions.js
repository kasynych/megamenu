$(document).ready(function(){
   $('.msg .close').click(function(){
       $(this).parent().slideUp();
   });

   $('.table .radio').click(function(){
       $('#parent_id').val($(this).val());
   });

   $('#jump_to_button').click(function(){
        document.location.href="?page="+$('#jump_to_text').val();
   });

   $('.pagging .current').click(function(){
      $(this).preventDefault();
       return false;
   });
});