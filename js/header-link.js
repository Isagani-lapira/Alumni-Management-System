/**
 *
 * Logic to change the link color of the header links
 *
 */

// onload
$(function () {
  $("nav a").each(function () {
    $(this).addClass("text-slate-500");
    if ($(this).html() === document.title) {
      $(this).removeClass("text-slate-500");
      $(this).addClass("text-accent");
    }
  });

  $(window).scroll(function () {
    $("section").each(function () {
      if ($(window).scrollTop() >= $(this).offset().top) {
        var id = $(this).attr("id");
        $("header a").removeClass("text-accent font-bold active");
        $('header a[href="#' + id + '"]').addClass(
          "text-accent font-bold active"
        );
      }
    });
  });

  var navbar = document.getElementById("navbar");
  var scrolled = false;

  window.addEventListener("scroll", function () {
    if (window.scrollY > 50) {
      if (!scrolled) {
        navbar.style.backgroundColor =
          "rgba(255, 255, 255, 0.9)"; /* Change to the desired background color */
        navbar.classList.add("visible");
        scrolled = true;
      }
    } else {
      navbar.style.backgroundColor = "rgba(255, 255, 255, 0)";
      navbar.classList.remove("visible");
      scrolled = false;
    }
  });

  // var navbar = document.getElementById("navbar");
  // var scrolled = false;

  // window.addEventListener("scroll", function () {
  //   if (window.scrollY > 50) {
  //     if (!scrolled) {
  //       navbar.style.backgroundColor =
  //         "rgba(255, 255, 255, 0.9)"; /* Change to the desired background color */
  //       scrolled = true;
  //     }
  //   } else {
  //     //
  //     navbar.style.backgroundColor = "rgba(255, 255, 255, 0)";
  //     scrolled = false;
  //   }
  // });
});
