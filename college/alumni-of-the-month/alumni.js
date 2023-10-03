import { getJSONFromURL } from "../scripts/utils.js";

$(document).ready(function () {
  // Constants
  const API_URL = "./alumni-of-the-month/getAlumni.php?";
  const API_URL_SEARCH = "php/searchAlumni.php?search=true";
  let offset = 0;
  // Initial load
  refreshList();
  // Binds the section link in order to reload the list whenever the section is clicked
  $('a[data-link="alumni-of-the-month"]').on("click", function () {
    refreshList();

    console.log("i ran");
  });

  // TODO get the details and show the details.

  // Date picker
  $("#aoydaterange").daterangepicker();

  // preview the image after changing the input
  $("#cover-image").change(function () {
    let reader = new FileReader();
    reader.onload = (e) => {
      $("#cover-img-preview").attr("src", e.target.result);
    };
    reader.readAsDataURL(this.files[0]);
  });

  // on form submit
  $("#add-aotm-form").submit(async function (e) {
    e.preventDefault();
    let formData = new FormData(this);
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

      const isSuccessful = await postNewAlumni(this);
      console.log(isSuccessful);
      if (isSuccessful.response === "Successful") {
        // show the success message
        Swal.fire("Success!", "Alumni has been added.", "success");
        // remove the form data
        $("#add-aotm-form")[0].reset();
        $("#cover-image-preview").attr("src", "");
        // $("#profile-image-preview").attr("src", "");
        $("#add-alumni-modal").prop("checked", false);
      } else {
        Swal.fire("Error", "Alumni is not added due to error.", "info");
      }
    } else {
      Swal.fire("Cancelled", "Add alumni cancelled.", "info");
    }
  });

  async function postNewAlumni(
    form,
    url = "./alumni-of-the-month/addAlumni.php"
  ) {
    // get the form data
    const formData = new FormData(form);
    const response = await fetch(url, {
      method: "POST",
      body: formData,
    });
    return response.json();
  }

  // add event handler to the cancel button
  $(".cancelModal").click(function () {
    hideDisplay("#modalAlumni");
  });

  $("#addAlumniBtn").on("click", function () {
    showDisplay("#modalAlumni");
  });

  const handleSearchList = _.debounce(searchAlumniListener, 500);

  // Event handler for the search bar
  $("#searchQuery").on("keyup", async function () {
    const search = $(this).val();
    await handleSearchList(search);
  });

  // Search bar remove suggestions when clicked outside except on search suggestions
  $(document).on("click", function (e) {
    if (!$(e.target).closest("#searchList").length) {
      $("#searchList").addClass("hidden");
      $("#searchList").empty();
    }
  });

  // Event handler for clicking list item
  $("#searchList").on("click", "li", async function () {
    console.log("clicked");
    const id = $(this).data("personid");
    const result = await getJSONFromURL(
      API_URL + "&getPersonId=1" + "&personId=" + id
    );
    console.log(result);

    try {
      if (result.data.length > 0) {
        const data = result.data[0];
        console.log("data", data);
        $("#detail-fullname").text(data.fname + " " + data.lname);
        // $("#detail-student-id").text(data.studNo);
        $("#detail-personal-email").text(data.personal_email);
        $("#detail-profile-picture").attr("src", data.profileImage);
        $("#searchList").addClass("hidden");
        $("#searchList").empty();
        $("#alumni-details").removeClass("hidden");
        $("#studentId").val(data.studNo);
        $("#personId").val(data.personID);
      }
      $("#searchList").addClass("hidden");
      $("#searchList").empty();
    } catch (error) {
      console.log(error);
    }
  });

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

  // refreshList();

  //  add event handler to the refresh button
  $("#refreshRecord").on("click", async function () {
    refreshList();
  });

  // refresh the list
  async function refreshList(category = "all") {
    //   get the event details
    const result = await getJSONFromURL(
      API_URL + "partial=true&offset=" + offset
    );

    $("#tBodyRecord").empty();
    console.log("first-result", result);
    appendContent($("#tBodyRecord"), result.data);
  }

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

  // end
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
});
