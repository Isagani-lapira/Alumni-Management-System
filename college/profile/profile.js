import { getJSONFromURL, postJSONFromURL } from "../scripts/utils.js";

$(document).ready(function () {
  const URL_LINK = "./profile/apiProfile.php";

  bindHandlers();
  $('a[href="#profile"]').on("click", function () {
    setTimeout(function () {
      bindHandlers();
    }, 500);
  });

  function bindHandlers() {
    console.log("profile events is binded");
    // * used for reseting the image preview into the original image
    const temp_dean_img = $("#deanImgPreview").attr("src");
    const temp_col_logo = $("#colLogoPreview").attr("src");

    // $("#submitUpdateProfileBtn").click(async function (e) {});

    // $add-course-form
    $("#add-course-form").on("submit", function (e) {
      e.preventDefault();
      console.log("submit btn clicked");
      const formData = new FormData(this);
      formData.append("action", "add-course");
      console.log(formData);
      // confirm using sweet alert
      Swal.fire({
        icon: "info",
        title: "Are you sure?",
        text: "Do you want to add this course?",
        showCancelButton: true,
        confirmButtonText: "Yes",
        cancelButtonText: "No",
      }).then((result) => {
        if (result.isConfirmed) {
          // post the data
          postJSONFromURL(URL_LINK, formData).then((response) => {
            console.log(response);
            if (response.status == true) {
              Swal.fire({
                icon: "success",
                title: "Success",
                text: "Course added successfully",
              }).then((result) => {
                if (result.isConfirmed) {
                  // location.reload();
                  const container = $("#add-course-container");

                  // reset the form
                  $("#add-course-form")[0].reset();

                  container.css({
                    opacity: "0.0",
                  });
                  $("#add-course").addClass("hidden");
                  $("#view-courses").removeClass("hidden");

                  // animate the container to show the new element
                  container.delay(50).animate(
                    {
                      opacity: "1.0",
                    },
                    300
                  );
                }
              });
            } else {
              Swal.fire({
                icon: "error",
                title: "Error",
                text: "Something went wrong",
              });
            }
          });
        }
      });
    });

    // for manage courses
    $("#manage-courses-btn").on("click", function () {
      console.log("manage courses btn clicked");
      // get the colcode value
      const colCode = $("#colCode").val();
      // make a post request to get the courses
      getJSONFromURL(URL_LINK + "?action=get-courses&colCode=" + colCode).then(
        (response) => {
          console.log(response);
          if (response.status == true) {
            // show the modal
            // clear the table
            $("#manage-courses-tbody").empty();
            // add the courses to the table
            [...response.data].forEach((course) => {
              console.log("hello");
              console.log($("#manage-courses-tbody").html());
              $("#manage-courses-tbody").append(`
              <tr>
                <td>${course.courseID}</td>
                <td>${course.courseCode}</td>
                <td>${course.courseName}</td>
                <td>
                  <button class="btn btn-sm daisy-btn daisy-btn-sm daisy-btn-warning" data-course-id="${course.course_id}">Edit</button>
                </td>
              </tr>
            `);
            });
          }
        }
      );
    });

    // reset the file upload of logo and dean image
    $("#reset-logo").on("click", function () {
      $("#colLogoInput").val("");
      $("#colLogoPreview").attr("src", temp_col_logo);
    });

    $("#reset-dean").on("click", function () {
      $("#deanImgInput").val("");
      $("#deanImgPreview").attr("src", temp_dean_img);
    });

    // form update-college-form
    $("#update-college-form").on("submit", function (e) {
      e.preventDefault();
      console.log("submit btn clicked");
      const formData = new FormData(this);
      console.log(formData);
      // confirm using sweet alert
      Swal.fire({
        icon: "info",
        title: "Are you sure?",
        text: "Do you want to update your profile?",
        showCancelButton: true,
        confirmButtonText: "Yes",
        cancelButtonText: "No",
      }).then((result) => {
        if (result.isConfirmed) {
          // post the data
          postJSONFromURL(URL_LINK, formData).then((response) => {
            console.log(response);
            if (response.status == true) {
              Swal.fire({
                icon: "success",
                title: "Success",
                text: "Profile updated successfully",
              }).then((result) => {
                if (result.isConfirmed) {
                  // location.reload();
                  const container = $("#college-profile-container");

                  // reset the form
                  $("#update-college-form")[0].reset();

                  container.css({
                    opacity: "0.0",
                  });
                  $("#edit-college-profile").addClass("hidden");
                  $("#view-college-profile").removeClass("hidden");

                  // animate the container to show the new element
                  container.delay(50).animate(
                    {
                      opacity: "1.0",
                    },
                    300
                  );
                }
              });
            } else {
              Swal.fire({
                icon: "error",
                title: "Error",
                text: "Something went wrong",
              });
            }
          });
        }
      });
    });

    // Show the edit college form when edit-college-profile-btn is clicked
    $("#edit-college-profile-btn").on("click", function () {
      console.log("edit college profile btn clicked");
      const container = $("#college-profile-container");
      container.css({
        opacity: "0.0",
      });
      $("#edit-college-profile").removeClass("hidden");
      $("#view-college-profile").addClass("hidden");
      // animate the container to show the new element
      container.delay(50).animate(
        {
          opacity: "1.0",
        },
        300
      );
    });

    // account-profile-container

    // remove the edit college form when cancel-edit-college-profile-btn is clicked
    $("#cancel-edit-college-profile-btn").on("click", function () {
      const container = $("#college-profile-container");

      // reset the form
      $("#update-college-form")[0].reset();

      container.css({
        opacity: "0.0",
      });
      $("#edit-college-profile").addClass("hidden");
      $("#view-college-profile").removeClass("hidden");

      // animate the container to show the new element
      container.delay(50).animate(
        {
          opacity: "1.0",
        },
        300
      );
    });

    // // Submit College Profile
    // $("#submit-update-college-form").click(async function (e) {
    //   e.preventDefault();
    // });

    // Show the preview of the image after changing the input

    $("#deanImgInput").on("change", function () {
      let reader = new FileReader();
      reader.onload = (e) => {
        $("#deanImgPreview").attr("src", e.target.result);
        console.log("changed the dean image");
      };
      reader.readAsDataURL(this.files[0]);
    });

    $("#colLogoInput").on("change", function () {
      console.log("i rannn");
      let reader = new FileReader();
      reader.onload = (e) => {
        $("#colLogoPreview").attr("src", e.target.result);
        console.log("changed the college logo");
      };
      reader.readAsDataURL(this.files[0]);
    });

    // Change the active tab when clicked
    $("#profile-tab-container a.daisy-menu-item").click(function () {
      // get the value of the href
      const url = $(this).attr("href");
      const container = $("#content-container");

      container.css({
        opacity: "0.0",
      });

      console.log(url);
      // remove all the active classes
      $("#profile-tab-container .daisy-menu-item").removeClass("daisy-active");
      $(this).addClass("daisy-active");

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
