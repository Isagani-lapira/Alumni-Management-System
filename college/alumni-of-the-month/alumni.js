import { getJSONFromURL, postJSONFromURL } from "../scripts/utils.js";

$(document).ready(function () {
  // Constants
  const GET_API_URL = "./alumni-of-the-month/getAlumni.php";
  const PROFILE_PICTURE_URL = "../media/search.php?media=profile_pic&personID=";
  const AOM_COVER_IMAGE_URL = "../media/search.php?media=aom&AOMID=";
  const API_URL_SEARCH = "php/searchAlumni.php?search=true";
  const API_POST_URL = "./alumni-of-the-month/addAlumni.php";
  const AVATAR_PLACEHOLDER = "../assets/default_profile.png";

  let offset = 0;
  const handleSearchList = _.debounce(searchAlumniListener, 500);
  const handleEditSearchList = _.debounce(editSearchAlumniListener, 500);

  // Initial load
  // refreshList();
  setHandlers();
  updateDataTable();
  checkIfAlumniExists();

  // Binds the section link in order to reload the list whenever the section is clicked
  $('a[data-link="alumni-of-the-month"]').on("click", function () {
    // refreshList();
    setTimeout(function () {
      updateDataTable();
      setHandlers();
      checkIfAlumniExists();

      console.log('refreshed the handlers of "alumni-of-the-month"');
    }, 1000);
  });

  function addEmptyAchievement(container) {
    const newAchievement = $(
      ".form-component-container .achievement-box:first"
    ).clone();
    newAchievement.find("input").val(""); // Clear input values
    newAchievement.find("input[type=date]").val(""); // Clear date field
    newAchievement.removeClass("hidden"); // Show cloned element
    newAchievement.appendTo(container).slideDown();
    console.log(newAchievement);
  }

  function addFilledAchievement(container, data) {
    console.log(data);
    const newAchievement = $(
      ".form-component-container .filled-achievement-box:first"
    ).clone();
    newAchievement.find("input").val(""); // Clear input values
    newAchievement.find("input[type=date]").val(""); // Clear date field
    newAchievement.removeClass("hidden"); // Show cloned element
    newAchievement.appendTo(container).slideDown();

    // fill the input values
    newAchievement.find("input[name='filled-title']").val(data.achievement);
    newAchievement
      .find("textarea[name='filled-description']")
      .text(data.description);
    newAchievement.find("input[name='filled-date']").val(data.date);
    newAchievement.find("input[name='filled-id']").val(data.achievementID);
    newAchievement
      .find(".filled-edit-achievement-btn")
      .attr("data-achievement-id", data.achievementID);
    newAchievement
      .find(".filled-delete-achievement-btn")
      .attr("data-achievement-id", data.achievementID);

    newAchievement
      .find("input[name='filled-achievementID")
      .val(data.achievementID);

    console.log(newAchievement);
  }

  function addEmptyTestimony(container) {
    const newContainer = $(
      ".form-component-container .testimony-box:first"
    ).clone();
    newContainer.find("input").val(""); // Clear input values
    newContainer.find("input[type=date]").val(""); // Clear date field
    newContainer.removeClass("hidden"); // Show cloned element
    newContainer.appendTo(container).slideDown();
    console.log(newContainer);
  }

  function addFilledTestimony(container, data) {
    /**
         *     "testimonialID": 0,
    "AOMID": 22,
    "message": "He is pretty good",
    "person_name": "Jayson",
    "position": "Intern",
    "relationship": "Employer",
    "emailAddress": "jayson@gmail.com",
    "companyName": "Accenture",
    "date": "2023-11-06",
          */

    const newContainer = $(
      ".form-component-container .filled-testimony-box:first"
    ).clone();
    newContainer.find("input").val(""); // Clear input values
    newContainer.find("input[type=date]").val(""); // Clear date field

    console.log(newContainer);

    // fill the input values
    newContainer
      .find("input[name='filled-testimony-id']")
      .val(data.testimonialID);
    newContainer.find("textarea[name='filled-message']").val(data.message);
    newContainer.find("input[name='filled-person_name']").val(data.person_name);
    newContainer.find("input[name='filled-position']").val(data.position);
    newContainer
      .find("input[name='filled-relationship']")
      .val(data.relationship);
    newContainer
      .find("input[name='filled-emailAddress']")
      .val(data.emailAddress);
    newContainer.find("input[name='filled-companyName']").val(data.companyName);
    newContainer.find("input[name='filled-date']").val(data.date);
    newContainer.find("input[name='filled-id']").val(data.testimonialID);
    newContainer
      .find(".filled-t-edit")
      .attr("data-testimonial-id", data.testimonialID);
    newContainer
      .find(".filled-t-remove")
      .attr("data-testimonial-id", data.testimonialID);

    newContainer.removeClass("hidden"); // Show cloned element
    newContainer.appendTo(container).slideDown();
    console.log(newContainer);
  }

  function setEditAOMHandlers() {
    // set handler for editing the alumni of the month
    /**
     * Triggered when the edit button is clicked
     */
    $("#alumni-of-the-month-container").on(
      "click",
      ".edit-aotm-btn",
      async function () {
        // get the id
        const id = $(this).data("aotm-id");
        console.log("fetching the details of the edit aotm", id);

        const result = await getJSONFromURL(
          GET_API_URL + "?action=getAOTMById&aomID=" + id
        );

        // populate the edit modal
        console.log("result", result);
        populateEditAOTMModal(result.data[0]);

        const testimonialsRes = await getJSONFromURL(
          GET_API_URL + "?action=getTestimonial&aomID=" + id
        );

        const achievementsRes = await getJSONFromURL(
          GET_API_URL + "?action=getAchievement&aomID=" + id
        );

        console.log("testimonials", testimonialsRes);
        // populate the testimonials

        console.log("achievements", achievementsRes);

        // if there is achievement
        if (achievementsRes.data.length > 0) {
          // populate the achievement
          achievementsRes.data.forEach((achievement) => {
            addFilledAchievement($("#edit-achievementFields"), achievement);
          });
        }

        /**
         *     "testimonialID": 0,
    "AOMID": 22,
    "message": "He is pretty good",
    "person_name": "Jayson",
    "position": "Intern",
    "relationship": "Employer",
    "emailAddress": "jayson@gmail.com",
    "companyName": "Accenture",
    "date": "2023-11-06",
         */
        // if there is testimonial
        if (testimonialsRes.data.length > 0) {
          // populate the testimonial
          testimonialsRes.data.forEach((testimonial) => {
            addFilledTestimony($("#edit-testimonyFields"), testimonial);
          });
        }

        // fetch the testimonials
        // fetch the achievements

        // fetch the alumni of the month

        // populate the modal

        try {
          // get contents of the alumni of the month
        } catch (error) {}
      }
    );
    $("#edit-achievementFields").on("click", ".a-remove", function () {
      $(this)
        .closest(".achievement-box")
        .slideUp(function () {
          $(this).remove();
        });
    });

    // Edit a single achievement

    $("#edit-achievementFields").on(
      "click",
      ".filled-delete-achievement-btn",
      function () {
        const container = $(this).closest(".filled-achievement-box ");
        // get the input field value
        const achievementID = container
          .find(".filled-delete-achievement-btn")
          .attr("data-achievement-id");

        // add the values to the formData
        const formData = new FormData();

        formData.append("achievementID", achievementID);

        formData.append("action", "delete-one-achievement");

        // add some sweet alert dialog
        Swal.fire({
          title: "Confirm?",
          text: "Are you sure to delete this achievement?",
          icon: "info",
          showCancelButton: true,
          confirmButtonText: "Yes, Delete it!",
        }).then(async (result) => {
          if (result.isConfirmed) {
            const isSuccessful = await postJSONFromURL(API_POST_URL, formData);
            console.log(isSuccessful);
            if (isSuccessful.response === "Successful") {
              // show the success message
              Swal.fire(
                "Success!",
                "The achievement has been deleted.",
                "success"
              );
              container.slideUp(function () {
                $(this).remove();
              });
            } else {
              Swal.fire(
                "Error",
                "Achievement is not edited due to error.",
                "info"
              );
            }
          }
        });
      }
    );

    $("#edit-achievementFields").on("click", ".a-edit", function () {
      const container = $(this).closest(".filled-achievement-box ");
      // get the input field value
      const title = container.find("input[name='filled-title']").val();
      console.log("title", title);

      const description = container
        .find("textarea[name='filled-description']")
        .text();
      const date = container.find("input[name='filled-date']").val();
      const achievementID = container
        .find(".filled-edit-achievement-btn")
        .attr("data-achievement-id");

      // add the values to the formData
      const formData = new FormData();
      formData.append("title", title);
      formData.append("achievement", title);
      formData.append("description", description);
      formData.append("date", date);
      formData.append("achievementID", achievementID);

      formData.append("action", "edit-one-achievement");

      // add some sweet alert dialog
      Swal.fire({
        title: "Confirm?",
        text: "Are you sure to edit this achievement?",
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Yes, Edit it!",
      }).then(async (result) => {
        if (result.isConfirmed) {
          const isSuccessful = await postJSONFromURL(API_POST_URL, formData);
          console.log(isSuccessful);
          if (isSuccessful.response === "Successful") {
            // show the success message
            Swal.fire(
              "Success!",
              "The achievement has been edited.",
              "success"
            );
          } else {
            Swal.fire(
              "Error",
              "Achievement is not edited due to error.",
              "info"
            );
          }
        }
      });
    });

    $("#edit-aotm-form").on("click", "#edit-add-testimony-btn", function () {
      addEmptyTestimony("#edit-testimonyFields");
    });

    $("#edit-testimonyFields").on("click", ".filled-t-edit", function () {
      const container = $(this).closest(".filled-testimony-box");

      // get all the input and textarea in the container
      const formData = new FormData();

      const inputElements = container.find("input");

      inputElements.each(function (index) {
        console.log("Element " + (index + 1) + ": " + $(this).val());
        // get the name of the input

        formData.append($(this).attr("name"), $(this).val());
      });

      const textAreaElements = container.find("textarea");

      textAreaElements.each(function (index) {
        console.log("Element " + (index + 1) + ": " + $(this).val());
        formData.append($(this).attr("name"), $(this).val());
      });

      // get the file input
      const fileInput = container.find("input[type=file]")[0];
      // append it
      formData.append("filled-profile_img", fileInput.files[0]);

      console.log(formData);
      // add the values to the formData

      formData.append("action", "edit-one-testimonial");

      // add some sweet alert dialog
      Swal.fire({
        title: "Confirm?",
        text: "Are you sure to edit this testimonial?",
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Yes, Edit it!",
      }).then(async (result) => {
        if (result.isConfirmed) {
          const isSuccessful = await postJSONFromURL(API_POST_URL, formData);
          console.log(isSuccessful);
          if (isSuccessful.response === "Successful") {
            // show the success message
            Swal.fire(
              "Success!",
              "The testimonial has been edited.",
              "success"
            );
          } else {
            Swal.fire(
              "Error",
              "testimonial is not edited due to error.",
              "info"
            );
          }
        }
      });
    });

    // $("#edit-testimonyFields").on("click", ".filled-t-edit", function () {
    //   $(this)
    //     .closest(".testimony-box")
    //     .slideUp(function () {
    //       $(this).remove();
    //     });
    // });

    $("#edit-testimonyFields").on("click", ".filled-t-remove", function () {
      const container = $(this).closest(".filled-testimony-box ");
      // get the input field value
      const testimonialID = container.find("input[name='filled-testimony-id']");

      // add the values to the formData
      const formData = new FormData();

      formData.append("testimonialID", testimonialID);

      formData.append("action", "delete-one-testimonial");

      // add some sweet alert dialog
      Swal.fire({
        title: "Confirm?",
        text: "Are you sure to delete this Testimonial?",
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Yes, Delete it!",
      }).then(async (result) => {
        if (result.isConfirmed) {
          const isSuccessful = await postJSONFromURL(API_POST_URL, formData);
          console.log(isSuccessful);
          if (isSuccessful.response === "Successful") {
            // show the success message
            Swal.fire(
              "Success!",
              "The Testimonial has been deleted.",
              "success"
            );
            container.slideUp(function () {
              $(this).remove();
            });
          } else {
            Swal.fire(
              "Error",
              "Testimonial is not deleted due to error.",
              "info"
            );
          }
        }
      });
    });

    // edit form
    // on form submit
    $("#edit-aotm-form").submit(async function (e) {
      e.preventDefault();
      // add some sweet alert dialog
      // if #edit-description is empty

      const textareaValue = CKEDITOR.instances["edit-description"].getData();
      if (textareaValue.trim().length === 0) {
        Swal.fire("Error", "Description is empty.", "info");
        return;
      }

      const confirmation = await Swal.fire({
        title: "Confirm?",
        text: "Are you sure to edit alumni of the month with these details?",
        icon: "info",
        showCancelButton: true,
        // confirmButtonColor: CONFIRM_COLOR,
        // cancelButtonColor: CANCEL_COLOR,
        confirmButtonText: "Yes, Edit it!",
      });
      if (confirmation.isConfirmed) {
        console.log("submitting edit alumni form");

        // get the form data
        const formData = new FormData(this);
        // add the id
        formData.append("id", $("#edit-aotm-id").val());
        // add action
        formData.append("action", "edit");
        // add description
        formData.append("description", textareaValue);

        console.log(formData);
        const isSuccessful = await postJSONFromURL(API_POST_URL, formData);
        console.log(isSuccessful);
        if (isSuccessful.response === "Successful") {
          // show the success message
          Swal.fire(
            "Success!",
            "The Alumni of the Month has been edited.",
            "success"
          );
          // remove the form data
          $("#edit-aotm-form")[0].reset();
          $("#edit-cover-image-preview").attr("src", "");
          // $("#profile-image-preview").attr("src", "");
          $("#edit-aotm").prop("checked", false);

          // refresh the datatable
          // clear the datatable
          $("#alumni-month-table").DataTable().clear().draw();
          $("#alumni-month-table").DataTable().ajax.reload();
          // refreshList();
        } else {
          Swal.fire("Error", "Alumni is not edited due to error.", "info");
        }
      } else {
        Swal.fire("Cancelled", "Edit alumni cancelled.", "info");
      }
    });
  }

  /**
   * Adds all the event listeners for the page
   */

  function setHandlers() {
    // set ckeditor
    CKEDITOR.replace("description");
    CKEDITOR.replace("edit-description");

    setEditAOMHandlers();

    // Achievement handlers
    $("#add-aotm-form").on("click", "#add-achievement-btn", function () {
      addEmptyAchievement("#achievementFields");
    });

    // Achievement handlers
    $("#edit-aotm-form").on("click", "#edit-add-achievement-btn", function () {
      addEmptyAchievement("#edit-achievementFields");
    });

    // // Achievement handlers
    // $("#edit-aotm-form").on("click", "#edit-add-achievement-btn", function () {
    //   addEmptyAchievement("#edit-achievementFields");
    // });

    $("#achievementFields").on("click", ".a-remove", function () {
      $(this)
        .closest(".achievement-box")
        .slideUp(function () {
          $(this).remove();
        });
    });

    // * Testimonial handlers
    $("#add-aotm-form").on("click", "#add-testimony-btn", function () {
      addEmptyTestimony("#testimonyFields");
    });

    $("#testimonyFields").on("click", ".t-remove", function () {
      $(this)
        .closest(".testimony-box")
        .slideUp(function () {
          $(this).remove();
        });
    });

    // Date picker
    $("#aoydaterange").daterangepicker();

    // set handler for deleting the alumni of the month
    $("#alumni-of-the-month-container").on(
      "click",
      ".delete-aotm",
      function () {
        // get the id
        const id = $(this).data("aotm-id");
        console.log("remove", id);
        // add some sweet alert dialog
        Swal.fire({
          title: "Confirm?",
          text: "Are you sure to delete this alumni of the month?",
          icon: "warning",
          showCancelButton: true,
          // confirmButtonColor: CONFIRM_COLOR,
          // cancelButtonColor: CANCEL_COLOR,
          confirmButtonText: "Yes, delete it!",
        }).then((result) => {
          if (result.isConfirmed) {
            const formData = new FormData();
            formData.append("action", "delete");
            formData.append("aotm-id", id);

            postJSONFromURL(API_POST_URL, formData).then((result) => {
              console.log(result);
              if (result.response === "Successful") {
                Swal.fire(
                  "Deleted!",
                  "Alumni of the month has been deleted.",
                  "success"
                );
                // clear the datatable and reload it
                $("#alumni-month-table").DataTable().clear().draw();
                $("#alumni-month-table").DataTable().ajax.reload();
                // hide the aotm card
                $("#aotm-card").addClass("hidden");
                // show no-alumni
                $("#no-alumni").removeClass("hidden");
                // show the add alumni button
                $("#add-alumni-modal").attr("disabled", false);
                $("#add-alumni-label").removeClass("daisy-btn-disabled");
              }
            });
          }
        });
      }
    );

    // preview the image after changing the input
    $("#edit-cover-image").on("change", function () {
      let reader = new FileReader();
      reader.onload = (e) => {
        $("#edit-cover-img-preview").attr("src", e.target.result);
      };
      reader.readAsDataURL(this.files[0]);
    });

    // preview the image after changing the input
    $("#cover-image").on("change", function () {
      let reader = new FileReader();
      reader.onload = (e) => {
        $("#cover-img-preview").attr("src", e.target.result);
      };
      reader.readAsDataURL(this.files[0]);
    });
    // TODO get the details and show the details.

    // * Reset Button Handlers

    // $("#edit-reset-aotm").on("click", function () {
    //   $("#edit-aotm-form")[0].reset();
    //   $("#edit-cover-img-preview").attr("src", "");
    //   // hide alumni details
    //   $("#edit-alumni-details").addClass("hidden");
    // });

    $("#reset-aotm").on("click", function () {
      $("#add-aotm-form")[0].reset();
      $("#cover-img-preview").attr("src", "");
      // hide alumni details
      $("#alumni-details").addClass("hidden");

      // $("#profile-image-preview").attr("src", "");
      // $("#add-alumni-modal").prop("checked", false);
    });

    // on form submit
    $("#add-aotm-form").submit(async function (e) {
      e.preventDefault();
      const textareaValue = CKEDITOR.instances.description.getData();
      if (textareaValue.trim().length === 0) {
        Swal.fire("Error", "Description is empty.", "info");
        return;
      }

      // add some sweet alert dialog
      const confirmation = await Swal.fire({
        title: "Confirm?",
        text: "Are you sure to add alumni with these details?",
        icon: "info",
        showCancelButton: true,
        // confirmButtonColor: CONFIRM_COLOR,
        // cancelButtonColor: CANCEL_COLOR,
        confirmButtonText: "Yes, Add it!",
      });
      if (confirmation.isConfirmed) {
        console.log("submitting add alumni form");

        // get the form data
        const formData = new FormData(this);

        // add the description
        formData.append("description", textareaValue);

        const isSuccessful = await postNewAlumni(formData);
        console.log(isSuccessful);
        if (isSuccessful.response === "Successful") {
          // show the success message
          Swal.fire("Success!", "Alumni has been added.", "success");
          checkIfAlumniExists();

          // remove the form data
          $("#add-aotm-form")[0].reset();
          $("#cover-image-preview").attr("src", "");
          // $("#profile-image-preview").attr("src", "");
          $("#add-alumni-modal").prop("checked", false);
          // empty and reload the data table
          $("#alumni-month-table").DataTable().clear().draw();
          $("#alumni-month-table").DataTable().ajax.reload();
        } else {
          Swal.fire("Error", "Alumni is not added due to error.", "info");
        }
      } else {
        Swal.fire("Cancelled", "Add alumni cancelled.", "info");
      }
    });

    // add event handler to the cancel button
    $(".cancelModal").click(function () {
      hideDisplay("#modalAlumni");
    });

    $("#addAlumniBtn").on("click", function () {
      showDisplay("#modalAlumni");
    });

    // Event handler for the search bar
    $("#searchQuery").on("keyup", async function () {
      const search = $(this).val();
      await handleSearchList(search);
    });

    // Event handler for the search bar
    $("#edit-searchQuery").on("keyup", async function () {
      const search = $(this).val();
      await handleEditSearchList(search);
    });

    // Search bar remove suggestions when clicked outside except on search suggestions
    $(document).on("click", function (e) {
      if (!$(e.target).closest("#searchList").length) {
        $("#searchList").addClass("hidden");
        $("#searchList").empty();
      }

      if (!$(e.target).closest("#edit-searchList").length) {
        $("#edit-searchList").addClass("hidden");
        $("#edit-searchList").empty();
      }
    });

    // Event handler for clicking list item
    $("#edit-searchList").on("click", "li", async function () {
      console.log("clicked");
      const id = $(this).data("personid");

      const result = await getJSONFromURL(
        GET_API_URL + "?action=searchPerson" + "&personId=" + id
      );
      console.log(result);

      try {
        if (result.data.length > 0) {
          const data = result.data[0];
          console.log("search result data: ", data);
          const fullName = data.fname + " " + data.lname;
          $("#edit-detail-fullname").text(fullName);
          // $("#detail-student-id").text(data.studNo);
          $("#edit-detail-personal-email").text(data.personal_email);
          $("#edit-detail-profile-picture").attr("src", data.profileImage);
          $("#edit-searchList").addClass("hidden");
          $("#edit-searchList").empty();
          $("#edit-alumni-details").removeClass("hidden");
          $("#edit-studentId").val(data.studNo);
          $("#edit-personId").val(data.personID);

          $("#edit-searchQuery").val(fullName);
        }
        $("#searchList").addClass("hidden");
        $("#searchList").empty();
      } catch (error) {
        console.log(error);
      }
    });

    // Event handler for clicking list item
    $("#searchList").on("click", "li", async function () {
      console.log("clicked");
      const id = $(this).data("personid");
      const result = await getJSONFromURL(
        GET_API_URL + "?action=searchPerson" + "&personId=" + id
      );
      console.log(result);

      try {
        if (result.data.length > 0) {
          const data = result.data[0];
          console.log("data", data);
          $("#detail-fullname").text(data.fname + " " + data.lname);
          // $("#detail-student-id").text(data.studNo);
          $("#detail-personal-email").text(data.personal_email);
          let profileImage = AVATAR_PLACEHOLDER;
          if (data.profileImage) {
            profileImage = data.profileImage;
          }
          $("#detail-profile-img").attr("src", profileImage);
          $("#searchList").addClass("hidden");
          $("#searchList").empty();
          $("#alumni-details").removeClass("hidden");
          $("#studentId").val(data.studNo);
          $("#personId").val(data.personID);

          // set the search query the fullname
          $("#searchQuery").val(data.fname + " " + data.lname);
        }
        $("#searchList").addClass("hidden");
        $("#searchList").empty();
      } catch (error) {
        console.log(error);
      }
    });

    //  add event handler to the refresh button
    $("#refreshRecord").on("click", async function () {
      refreshList();
    });

    // Make the button be disabled when there's a record of this month's alumni
  }

  async function checkIfAlumniExists() {
    try {
      const result = await getJSONFromURL(GET_API_URL + "?action=getThisMonth");
      console.log(result);
      console.log("this month's aotm", result.data.length);

      // there is no alumni of the month yet
      if (result.data.length === 0) {
        // hide the alumni of the month card

        $("#aotm-card").addClass("hidden");
        // remove the card loading
        $("#card-loading").addClass("hidden");
        $("#no-alumni").removeClass("hidden");

        console.log("not disabled");

        // remove disabled attribute
        $("#add-alumni-modal").attr("disabled", false);
        $("#add-alumni-label").removeClass("daisy-btn-disabled");
      } else {
        // show the alumni card
        $("#no-alumni").addClass("hidden");
        // remove the card loading
        $("#card-loading").addClass("hidden");

        $("#aotm-card").removeClass("hidden");

        console.log("disabled");
        $("#add-alumni-modal").attr("disabled", true);
        $("#add-alumni-label").addClass(
          "daisy-btn-disabled disabled cursor-not-allowed"
        );

        // populate the alumni of the month card
        populateAlumniCard(result.data[0]);
        populateEditAOTMModal(result.data[0]);
      }
    } catch (error) {
      console.error(error);
    }
  }

  function populateAlumniCard(data) {
    let profileImage = AVATAR_PLACEHOLDER;
    if (data.profilepicture) {
      profileImage = "data:image/jpeg;base64," + data.profilepicture;
    }
    $("#card-avatar").attr("src", profileImage);
    $("#card-fullname").text(data.fullname);
    $("#card-job").text(data.job);
    $("#card-course").text(data.course);
    $("#card-company").text(data.company);
    $("#card-batch").text(data.batchYr);
    $("#card-edit").attr("data-id", data.personID);
    $("#card-edit").attr("data-aotm-id", data.AOMID);
    $("#card-delete").attr("data-id", data.personID);
    $("#card-delete").attr("data-aotm-id", data.AOMID);
  }

  function populateEditAOTMModal(data) {
    // clear the edit-achievementFields container
    $("#edit-achievementFields").empty();
    $("#edit-testimonyFields").empty();

    $("#edit-cover-img-preview").attr(
      "src",
      "data:image/jpeg;base64," + data.cover_img
    );

    let profileImage = AVATAR_PLACEHOLDER;
    if (data.profilepicture) {
      profileImage = "data:image/jpeg;base64," + data.profilepicture;
    }

    $("#edit-detail-profile-img").attr("src", profileImage);
    $("#edit-detail-fullname").text(data.fullname);
    $("#edit-searchQuery").text(data.fullname);
    $("#edit-detail-personal-email").text(data.personal_email);
    $("#edit-detail-yearGraduated").text("Batch " + data.batchYr);

    $("#edit-quote").val(data.quote);
    // hidden input
    $("#edit-studentId").val(data.studentNo);
    $("#edit-personId").val(data.personID);
    $("#edit-aotm-record").val(data.AOMID);
    $("#edit-aotm-id").val(data.AOMID);

    // container
    $("#edit-alumni-details").removeClass("hidden");

    $("#edit-description").val(data.description);
  }

  async function postNewAlumni(
    form,
    url = "./alumni-of-the-month/addAlumni.php"
  ) {
    // // get the form data
    // const response = await fetch(url, {
    //   method: "POST",
    //   body: formData,
    // });
    // return response.json();
    const res = await postJSONFromURL(url, form);
    return res;
  }

  async function editSearchAlumniListener(searchStr) {
    if (searchStr.trim().length === 0) {
      $("#edit-searchList").addClass("hidden").empty();
      return;
    }
    try {
      const result = await getJSONFromURL(
        API_URL_SEARCH + "&qName=" + searchStr
      );
      console.log("completed", result);
      // Add the list to the search suggestion
      if (result.data.length > 0) {
        $("#edit-searchList").removeClass("hidden");
        $("#edit-searchList").empty();
        const list = result.data.map((item) => {
          // check if item has a profile image
          let profileImage = "../assets/icons/person.png";
          if (item.profileImage) {
            profileImage = item.profileImage;
          }
          return `<li class="searchList__item p-3 flex items-center gap-2 hover:bg-gray-200 hover:text-gray-900 text-gray-500 cursor-pointer" data-id="${item.personID}" data-personId="${item.personID}"">
            <img class="rounded-full h-10 w-10 border border-accent" src="${profileImage}">
            <div class="flex flex-col text-sm">
              <p class="font-bold">${item.fullname}</p>
              <p class="text-xs">${item.studNo}</p>
                   
                </li>`;
        });
        $("#edit-searchList").append(list);
      } else {
        console.log("no results found");
        $("#edit-searchList")
          .removeClass("hidden")
          .empty()
          .append(
            `<li class="searchList__item p-3 flex items-center gap-2 hover:bg-gray-200 hover:text-gray-900 text-gray-500">
            <p>No results found.</p>
          </li>`
          );
      }
    } catch (error) {
      console.log(error);
    }
  }

  async function searchAlumniListener(searchStr) {
    if (searchStr.trim().length === 0) {
      $("#searchList").addClass("hidden");
      $("#searchList").empty();
      return;
    }
    try {
      const result = await getJSONFromURL(
        API_URL_SEARCH + "&qName=" + searchStr
      );
      console.log("completed", result);
      // Add the list to the search suggestion
      if (result.data.length > 0) {
        $("#searchList").removeClass("hidden");
        $("#searchList").empty();
        const list = result.data.map((item) => {
          // check if item has a profile image
          let profileImage = "../assets/icons/person.png";
          if (item.profileImage) {
            profileImage = item.profileImage;
          }
          return `<li class="searchList__item p-3 flex items-center gap-2 hover:bg-gray-200 hover:text-gray-900 text-gray-500 cursor-pointer" data-id="${item.personID}" data-personId="${item.personID}"">
            <img class="rounded-full h-10 w-10 border border-accent" src="${profileImage}">
            <div class="flex flex-col text-sm">
              <p class="font-bold">${item.fullname}</p>
              <p class="text-xs">${item.studNo}</p>
                   
                </li>`;
        });
        $("#searchList").append(list);
      } else {
        console.log("no results found");
        $("#searchList").removeClass("hidden");
        $("#searchList").empty();
        $("#searchList").append(
          `<li class="searchList__item p-3 flex items-center gap-2 hover:bg-gray-200 hover:text-gray-900 text-gray-500">
            <p>No results found.</p>
          </li>`
        );
      }
    } catch (error) {
      console.log(error);
    }
  }

  // // refresh the list
  // async function refreshList(category = "all") {
  //   //   get the event details
  //   const result = await getJSONFromURL(
  //     GET_API_URL + "partial=true&offset=" + offset
  //   );

  //   $("#tBodyRecord").empty();
  //   console.log("first-result", result);
  //   appendContent($("#tBodyRecord"), result.data);
  // }

  //   set the data into the table
  function appendContent(selectorContainer, jsonData) {
    console.log(jsonData);
    const container = selectorContainer;
    jsonData.forEach((item) => {
      //  append the data to the table
      console.log("item", item);

      container.append(`
      <tr class="h-14">
      <td class="student-num__val text-start font-bold">${item.studentNo}</td>
      <td>
          <div class="flex items-center justify-start">
              <div class="w-10 h-10 rounded-full border border-accent"></div>
              <span class="ml-2">${item.fullname}</span>
          </div>
      </td>

      <td class="text-center text-blue-400 font-light hover:cursor-pointer hover:text-accentBlue hover:font-semibold">VIEW PROFILE</td>
  </tr>
            `);
    });
  }

  function hideDisplay(hide = "") {
    $(hide)
      .css({
        opacity: "1.0",
      })
      .addClass("hidden")
      .delay(50)
      .animate(
        {
          opacity: "0.0",
        },
        300
      );
  }

  function showDisplay(show = "") {
    // add transition
    $(show)
      .css({
        opacity: "0.0",
      })
      .removeClass("hidden")
      .delay(50)
      .animate(
        {
          opacity: "1.0",
        },
        300
      );
  }

  function updateDataTable() {
    // remove the loading screen
    $("#alumni-month-table").DataTable({
      ajax: {
        url: GET_API_URL + "?action=getAllAOTM",
        dataSrc: "data",
      },
      paging: true,
      ordering: true,
      info: false,
      lengthChange: false,
      searching: true,
      pageLength: 10,
      columns: [
        { data: "date_assigned", width: "25%" },
        { data: "studentNo" },
        { data: "fullname" },
        {
          data: null,
          render: function (data, type, row) {
            // Define the buttons for the Actions column
            return `
                        <label for="edit-aotm" class="edit-aotm-btn daisy-btn daisy-btn-sm daisy-btn-info daisy-btn-outline " data-id=${row.personID} data-aotm-id="${row.AOMID}">Edit</label>
                        <button  class="delete-aotm daisy-btn daisy-btn-warning daisy-btn-sm daisy-btn-outline " data-id=${row.personID} data-aotm-id="${row.AOMID}">Remove</button>
                    `;
          },
        },
      ],
      columnDefs: [
        {
          targets: [0],
          render: function (data, type, row) {
            return moment(data).format("MMMM YYYY");
          },
        },
      ],
    });
  }
});
