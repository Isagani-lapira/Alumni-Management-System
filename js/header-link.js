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
});
