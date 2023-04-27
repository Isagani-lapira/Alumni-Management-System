
$(document).ready(function() {
    const additionalClass = "font-extrabold text-accent text-10xs";
    $('.sidebar span').hover(function() {
      $(this).addClass(additionalClass);
    }, function() {
      $('.sidebar span').removeClass(additionalClass);
    });
});