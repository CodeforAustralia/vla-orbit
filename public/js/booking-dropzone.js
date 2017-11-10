$(document).ready(function(){
  $('.booking-form input').change(function () {
    $('.booking-form p').text(this.files.length + " file(s) selected");
  });
});