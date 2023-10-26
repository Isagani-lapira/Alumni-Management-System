$(document).ready(function () {
  // bind handlers when the anchor link is clicked
  // Add onchange whenever the dashboard is clicked
  $('a[href="#settings"]').on("click", function () {
    setTimeout(function () {
      bindHandlers();
    }, 500);
  });

  bindHandlers();

  function bindHandlers() {
    // preview the image after changing the input

    console.log("binded");
    $("#cover-img").on("change", function () {
      console.log("i rannn");
      let reader = new FileReader();
      reader.onload = (e) => {
        $("#cover-img-preview").attr("src", e.target.result);
        console.log("i ran");
      };
      reader.readAsDataURL(this.files[0]);
    });
    $("#personal-img-pic").on("change", function () {
      console.log("i rannn");
      let reader = new FileReader();
      reader.onload = (e) => {
        $("#personal-img-preview").attr("src", e.target.result);
        console.log("i ran");
      };
      reader.readAsDataURL(this.files[0]);
    });

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
  }
});
