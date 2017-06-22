$(document).ready(function(){
  $('.expand').click(function(){
      $(this).next('.toggle_area').slideToggle();
       return false;
  });
});
