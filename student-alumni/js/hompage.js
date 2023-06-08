$(document).ready(function() {
  var navbar = $('.navbar');
  var content = $('.content');

  $(window).scroll(function() {
    if ($(this).scrollTop() > navbar.height()) {
      navbar.addClass('navbar-fixed');
      content.css('margin-top', navbar.height());
    } else {
      navbar.removeClass('navbar-fixed');
      content.css('margin-top', 0);
    }
  });
});