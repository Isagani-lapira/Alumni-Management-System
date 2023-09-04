$(document).ready(async function () {
  // Set the image preview

  $("#event-img").on("change", function () {
    console.log("did it ?");

    const file = $(this)[0].files[0];
    if (file) {
      console.log("did it work?");
      $("#aboutImgPreview").attr("src", URL.createObjectURL(file));
    }
  });

  // Form
  $("#crud-event-form").on("submit", async function (e) {
    console.log("submitting form");

    e.preventDefault();

    // get the form data
    const formData = new FormData(this);
    // send the data to the server using fetch await
    const API_URL = "./event/addEvent.php";
    const response = await fetch(API_URL, {
      method: "POST",
      body: formData,
    });
    const result = await response.json();
    console.log(result);
    if (result.response === "Successful") {
      console.log("success");
      // remove the form data
      $("#crud-event-form")[0].reset();
      // show the success message
    } else {
      console.log("failed");
    }
  });

  const API_URL = "./event/getEvent.php";
  let offset = 0;
  //   get the event details
  const results = await getPartialEventDetails(0);

  appendContent(results.result);

  //   get the event details
  async function getPartialEventDetails(offset) {
    const response = await fetch(API_URL + "?offset=" + offset, {
      headers: {
        method: "GET",
        "Content-Type": "application/json",
        cache: "no-cache",
      },
    });

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
                    }" class="border border-gray-400 rounded-lg p-2">
                    <!-- image -->
                    <div class="grid grid-cols-3 ">
                        <img src="data:image/jpg;base64,${
                          event.aboutImg
                        }" alt="event image" class="w-32 object-cover" loading="lazy">
                        <div>
                            <h3 class="font-bold text-lg text-gray-800 "> ${
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
                            <button class="btn-tertiary">Edit Details</button>
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
    toggleDisplay("#event-record-list-section", "#crud-event");
  });
  $(".cancel").on("click", function () {
    toggleDisplay("#crud-event", "#event-record-list-section");
  });

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

  // fetch the data from the database
  function getEventDetails(id) {
    return dummyData;
  }

  // handles the click to show more details
  function handlePreviewClick() {
    const eventID = $(this).data("event-id");
    console.log(eventID);
    // get the event details
    const eventDetails = getEventDetails(eventID);
    console.log(eventDetails);
    // replace the contents of the event.php with the event details
    replaceContentWithEventDetail(eventDetails);
  }

  $(".td-eventID-holder").on("click", handlePreviewClick);

  // Handlers for edit and archive button
});
