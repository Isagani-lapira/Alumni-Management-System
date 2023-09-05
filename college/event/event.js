$(document).ready(async function () {
  const CONFIRM_COLOR = "#991B1B";
  const CANCEL_COLOR = "#6A6A6A";

  const API_URL = "./event/getEvent.php";
  let offset = 0;
  refreshList();

  // Handles the individual event detail

  // make a dummy data first, then make a function that will get the data from the database
  const dummyData = {
    eventName: "Event Name",
    eventDate: "2021-05-20",
    eventStartTime: "12:00",
    eventEndTime: "13:00",
    eventLocation: "Event Location",
    eventDescription: "Event Description",
    eventImg: "https://picsum.photos/200/300",
    eventHeaderPhrase: "Event Header Phrase",
    eventAboutImg: "https://picsum.photos/200/300",
    eventAbout: "Event About",
    eventAboutHeader: "Event About Header",
    eventAboutSubHeader: "Event About Sub Header",
  };

  // Set the image preview

  $("#event-img").on("change", function () {
    const file = $(this)[0].files[0];
    if (file) {
      $("#aboutImgPreview").attr("src", URL.createObjectURL(file));
    }
  });

  // Form
  $("#crud-event-form").on("submit", async function (e) {
    e.preventDefault();

    // check if it will be edit or not
    const isEdit = $("#postBtn").data("post-type") === "edit" ? true : false;
    let api_url = "";

    if (isEdit) {
      // will edit the event
      api_url = "./event/editEvent.php";
      const confirmation = await Swal.fire({
        title: "Confirm?",
        text: "Are you sure to edit event with these details?",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: CONFIRM_COLOR,
        cancelButtonColor: CANCEL_COLOR,
        confirmButtonText: "Yes, Add it!",
      });

      if (confirmation.isConfirmed) {
        console.log("submitting edit form");

        const isSuccessful = await setEditEvent(this, api_url);
        console.log(isSuccessful);
        if (isSuccessful.response === "Successful") {
          // show the success message
          Swal.fire("Success!", "Your event has been edited.", "success");
          // remove the form data
          $("#crud-event-form")[0].reset();
          $("#aboutImgPreview").attr("src", "");
          // hide the form
          toggleDisplay("#crud-event", "#event-record-list-section");
          // refresh the list
          refreshList();
        } else {
          Swal.fire("Cancelled", "Edit form cancelled.", "info");
        }
      }
    } else {
      // will add new event
      api_url = "./event/addEvent.php";

      const confirmation = await Swal.fire({
        title: "Confirm?",
        text: "Are you sure with the details?",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: CONFIRM_COLOR,
        cancelButtonColor: CANCEL_COLOR,
        confirmButtonText: "Yes, Add it!",
      });

      if (confirmation.isConfirmed) {
        console.log("submitting form");
        const isSuccessful = await setNewEvent(this, api_url);
        console.log(isSuccessful);
        if (isSuccessful.response === "Successful") {
          // show the success message
          Swal.fire("Success!", "Your event has been added.", "success");
          // remove the form data
          $("#crud-event-form")[0].reset();
          $("#aboutImgPreview").attr("src", "");
          // hide the form
          toggleDisplay("#crud-event", "#event-record-list-section");
          // refresh the list
          refreshList();
        } else {
          Swal.fire("Cancelled", "Form submit cancelled.", "info");
        }
      }
    }
  });

  // set new event
  async function setNewEvent(form, url) {
    // get the form data
    const formData = new FormData(form);
    // send the data to the server using fetch await

    const response = await fetch(url, {
      method: "POST",
      body: formData,
    });
    return response.json();
  }

  // set edit event
  async function setEditEvent(form, url) {
    // get the form data
    const formData = new FormData(form);
    console.log(formData.get("eventID"));
    console.log(formData);
    // send the data to the server using fetch await

    const response = await fetch(url, {
      method: "POST",
      body: formData,
    });
    return response.json();
  }

  // refresh the list
  async function refreshList() {
    //   get the event details
    const results = await getPartialEventDetails(0);

    $("#event-list").empty();
    appendContent(results.result);
  }

  //   get the event details
  async function getPartialEventDetails(offset) {
    const response = await fetch(
      API_URL + "?offset=" + offset + "&partial=true",
      {
        headers: {
          method: "GET",
          "Content-Type": "application/json",
          cache: "no-cache",
        },
      }
    );

    const result = await response.json();

    console.log(result);

    return result;
  }

  function limit(string, length, end = "...") {
    return string.length < length ? string : string.substring(0, length) + end;
  }

  //   set the data into the table
  function appendContent(jsonData) {
    const eventList = $("#event-list");
    jsonData.forEach((event) => {
      // moment js parsing
      const parsedDatePosted = moment(event.date_posted).format("MMMM Do YYYY");
      const parsedEventDateTime = moment(
        event.eventDate + " " + event.eventStartTime
      ).format("MMMM Do YYYY [|] h:mm a");

      //  append the data to the table
      eventList.append(`

                    <li  data-event-id="${
                      event.eventID
                    }" class="event-list-item border border-gray-400 rounded-lg p-2">
                    <!-- image -->
                    <div class="grid grid-cols-3 ">
                      
                        <div class="img-container h-56 w-full p-4 rounded hover:scale-110">
                          <img src="data:image/jpg;base64,${
                            event.aboutImg
                          }" alt="event image" class="  object-cover rounded  object-center max-w-full h-auto max-h-full w-full" loading="lazy">
                        </div>
                        <div>
                            <h3 class="event-list-item__name font-bold text-lg text-gray-800 "> ${
                              event.eventName
                            }</h3>
                            <p class="font-normal  text-gray-400">${
                              event.headerPhrase
                            }</p>
                            <p class="text-gray-400 text-sm ">${limit(
                              event.about_event,
                              60
                            )}</p>
                        </div>
                        <div>
                            <p class="text-sm  uppercase tracking-wider text-gray-800 opacity-50 ">Event Date and Time</p>
                            <p class="font-medium text-lg text-gray-800">${parsedEventDateTime}</p>
                            <p class="text-sm  uppercase tracking-wider text-gray-800 opacity-50 ">Date Posted</p>
                            <p class=" font-medium text-lg text-gray-800">${parsedDatePosted}</p>
                            
                            <div class="">
                            <button class="btn-tertiary event-list-item__edit-btn" data-event-id="${
                              event.eventID
                            }">Edit Details</button>
                            <i class="fa-solid fa-ellipsis fa-xl"></i>
                        </div>
                        </div>
                  
                    </div>
    
                </li>
            `);
    });
  }

  //   event handlers
  $("#nextPostsBtn").on("click", async function () {
    $("#prevPostsbtn").attr("disabled", false);

    const eventDOMContent = $("#event-list");
    offset += 5;
    eventDOMContent.empty();
    const results = await getPartialEventDetails(offset);
    if (results.response !== "Successful") {
      // show no more posts
      eventDOMContent.empty();
      eventDOMContent.append(`
          <tr>
              <td colspan="6" class="text-center">No more posts</td>
          </tr>
          `);

      $("#nextPostsBtn").attr("disabled", true);
      return;
    } else {
      appendContent(results.result);
    }
  });

  //   event handlers
  $("#prevPostsBtn").on("click", async function () {
    $("#nextPostsBtn").attr("disabled", false);

    console.log(offset, "offset");
    if (offset === 0) {
      $("#prevPostsbtn").attr("disabled", true);
      return;
    }

    const eventDOMContent = $("#event-list");
    eventDOMContent.empty();
    offset -= 5;

    const results = await getPartialEventDetails(offset);
    appendContent(results.result);
  });

  function toggleDisplay(hide = "", show = "") {
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

  //  Handles button clicks
  $("#addNewEventBtn").on("click", function () {
    $("#postBtn").data("post-type", "create");
    toggleDisplay("#event-record-list-section", "#crud-event");
    $("#event-img").attr("required", true);
    replaceFormForEdit("Add New Event", "");
  });

  $(".cancel").on("click", function () {
    toggleDisplay("#crud-event", "#event-record-list-section");
    // remove the form data
    $("#crud-event-form")[0].reset();
    $("#aboutImgPreview").attr("src", "");
    $("#postBtn").data("post-type", "");
    $("#event-img").attr("required", true);
    $("#eventID").val("");
  });

  // fetch the data from the database
  async function getEventDetails(id) {
    const API_URL = "./event/getEvent.php" + "?eventID=" + id;
    const response = await fetch(API_URL, {
      headers: {
        method: "GET",
        "Content-Type": "application/json",
        cache: "no-cache",
      },
    });

    return response.json();
  }

  // Handlers for edit and archive button
  $("#event-list").on("click", ".event-list-item__edit-btn", async function () {
    const eventID = $(this).data("event-id");
    $("#postBtn").data("post-type", "edit");
    $("#postBtn").text("Edit Event");

    console.log(eventID);
    // get the event details
    const eventDetails = await getEventDetails(eventID);
    console.log(eventDetails);
    // replace the contents of the form with the event details
    replaceFormWithEventDetail(eventDetails, eventID);
    toggleDisplay("#event-record-list-section", "#crud-event");
    replaceFormForEdit(`Edit '${eventDetails.eventName}' Event`);
  });

  function replaceFormWithEventDetail(eventDetails, eventID) {
    console.log(eventDetails.event_category);
    $("#eventName").val(eventDetails.eventName);
    $("#headerPhrase").val(eventDetails.headerPhrase);
    $("#category").val(eventDetails.event_category).prop("selected", true);
    $("#eventDate").val(eventDetails.eventDate);
    $("#contactLink").val(eventDetails.contactLink);
    $("#eventStartTime").val(eventDetails.eventStartTime);
    $("#eventEndTime").val(eventDetails.eventEndTime);
    $("#eventPlace").val(eventDetails.eventPlace);
    $("#about_event").val(eventDetails.about_event);
    $("#aboutImgPreview").attr(
      "src",
      "data:image/jpg;base64," + eventDetails.aboutImg
    );
    $("#event-img").attr("required", false);
    // change the eventID
    $("#eventID").val(eventID);

    // change the url when the form is submitted
  }

  // replaces some of the content in the form for edit content
  function replaceFormForEdit(name) {
    $("#event-title").text(name);
  }
});
