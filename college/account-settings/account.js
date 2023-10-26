$(document).ready(function () {
  // Transistion the section appearance
  function transistionSectionAppearance(container, htmlBody) {
    container
      .css({
        opacity: "0.0",
      })
      .html(htmlBody)
      .delay(50)
      .animate(
        {
          opacity: "1.0",
        },
        300
      );
  }

  $("#setting-tab-container .daisy-tab").click(function () {
    // get the value of the href
    const url = $(this).attr("href");
    const container = $("#content-container");

    container.css({
      opacity: "0.0",
    });

    console.log(url);
    // remove all the active classes
    $("#setting-tab-container .daisy-tab").removeClass("daisy-tab-active");
    $(this).addClass("daisy-tab-active");

    // get the linked element
    const elem = $(url);
    // hide all the other elements from the container
    elem.siblings().addClass("hidden");
    //  remove the hide class
    elem.removeClass("hidden");

    // animate the container to show the new element
    container.delay(50).animate(
      {
        opacity: "1.0",
      },
      300
    );
  });
});
