import { getJSONFromURL, postJSONFromURL } from "../scripts/utils.js";

$(document).ready(async function () {
  const CONFIRM_COLOR = "#991B1B";
  const CANCEL_COLOR = "#6A6A6A";

  // Add onchange whenever the link is clicked
  $('a[href="#event"]').on("click", function () {
    setTimeout(function () {
      refreshList();
      setHandlers();
    }, 500);
  });

  const API_URL = "./event/getEvent.php";
  let offset = 0;

  // Initial Load
  refreshList();
  setHandlers();

  // Set the image preview

  // Handler function
  function setHandlers() {
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
    // Handlers for edit and archive button
    $("#event-list").on(
      "click",
      ".event-list-item__edit-btn",
      async function () {
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
      }
    );
    //   handles the delete button
    $("#event-list").on(
      "click",
      ".event-list-item__delete-btn",
      async function () {
        // get the name of the event
        const eventName = $(this).data("event-name");
        console.log(eventName);
        const confirmation = await Swal.fire({
          title: "Confirm?",
          text: `Are you sure to delete '${eventName}' event?`,
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: CONFIRM_COLOR,
          cancelButtonColor: CANCEL_COLOR,
          confirmButtonText: "Yes, delete it!",
        });

        if (confirmation.isConfirmed) {
          const eventID = $(this).data("event-id");
          console.log(eventID);
          const formData = new FormData();
          formData.append("eventID", eventID);

          // send the data to the server using fetch await
          const response = await fetch("./event/deleteEvent.php", {
            method: "POST",
            body: formData,
          });

          const result = await response.json();

          if (result.response === "Successful") {
            await Swal.fire(
              "Deleted!",
              "Your event has been deleted.",
              "success"
            );
            // refresh the list
            offset = 0;
            refreshList();
            // Reduce the total posts by 1
            const totalPosts = parseInt($("#totalPosts").text());
            $("#totalPosts").text(totalPosts - 1);
          }
        } else {
          Swal.fire("Cancelled", "Delete cancelled.", "info");
        }
      }
    );

    // handles the preview clicks
    $("#event-list").on("click", ".img-container", async function () {
      const eventID = $(this).closest("li").data("event-id");
      console.log(eventID);
      const eventDetails = await getEventDetails(eventID);
      console.log(eventDetails);
      // replace the contents of the form with the event details
      $("#preview-eventName").text(eventDetails.eventName);
      $("#preview-headerPhrase").text(eventDetails.headerPhrase);
      $("#preview-category").text(
        eventDetails.event_category === "col_event"
          ? "College Event"
          : "Alumni Event"
      );
      $("#preview-contactLink").text(eventDetails.contactLink);
      $("#preview-eventPlace").text(eventDetails.eventPlace);
      $("#preview-about_event").text(eventDetails.about_event);
      $("#preview-aboutImg").attr(
        "src",
        "data:image/jpg;base64," + eventDetails.aboutImg
      );
      const parsedDatePosted = moment(eventDetails.date_posted).format(
        "MMMM Do YYYY"
      );
      const parsedEventDateTime = moment(
        eventDetails.eventDate + " " + eventDetails.eventStartTime
      ).format("MMMM Do YYYY [|] h:mm a");
      $("#preview-eventDate").text(parsedEventDateTime);
      $("#preview-datePosted").text(
        moment(eventDetails.date_posted).format("MMMM Do YYYY")
      );
      // toggle the view with preview event details

      toggleDisplay("#event-record-list-section", "#preview-event");
    });

    // handles the return button
    $("#return-event-btn").on("click", function () {
      toggleDisplay("#preview-event", "#event-record-list-section");
    });

    // Category filter
    $("#filterByCategory").on("change", async function () {
      const category = $(this).val();
      console.log(category);
      offset = 0;
      refreshList(category);
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
  }

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
  async function refreshList(category = "all") {
    //   get the event details
    const results = await getPartialEventDetails(0, category);

    $("#event-list").empty();
    appendContent(results.result);
  }

  //   get the event details
  async function getPartialEventDetails(offset, category = "all") {
    console.log(category);

    const response = await fetch(
      API_URL + "?offset=" + offset + "&partial=true" + "&category=" + category,
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
                    }" class="event-list-item shadow-md border border-gray-200  rounded-lg">
                    <div class="grid grid-cols-2">
                      
                        <div class="img-container h-60 w-full rounded-sm hover:scale-95 hover:opacity-70 transition-all cursor-pointer">
                          <img src="data:image/jpg;base64,${
                            event.aboutImg
                          }" alt="event image" class="object-cover rounded-sm  object-center max-w-full h-auto max-h-full w-full" loading="lazy">
                        </div>
                        <div class="flex justify-between flex-col  p-2 pl-4 ">
                            <div>
                              <h3 class="event-list-item__name font-bold text-xl text-gray-800 cursor-pointer  hover:text-accent transition-colors "> ${
                                event.eventName
                              }</h3>
                              <p class=" text-gray-400">${parsedDatePosted}</p>
                              <!-- <p class="font-medium text-lg text-gray-800">${parsedEventDateTime}</p> -->
                              <p class=" ">${limit(event.about_event, 60)}</p>
                            </div>
                            
                            <div class="text-end">
                            <button class="btn-tertiary event-list-item__edit-btn" data-event-id="${
                              event.eventID
                            }">Edit</button>
                            <button class="btn-tertiary bg-transparent border-none  event-list-item__delete-btn" data-event-id="${
                              event.eventID
                            }" data-event-name="${
        event.eventName
      }">Delete</button>
                        </div>
                        </div>
     
                  
                    </div>
    
                </li>
            `);
    });
  }

  /**
   * Adds transition when toggling the display of elements
   */
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

  // deletes the event entry to the database
  // TODO no read yet
  async function deleteEventFromDB(id) {
    const API_URL = "./event/deleteEvent.php" + "?eventID=" + id;

    // send the data to the server using fetch await
    const response = await fetch(url, {
      headers: {
        method: "DELETE",
      },
    });
    return response.json();
  }

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
