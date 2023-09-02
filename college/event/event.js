$(document).ready(async function () {
  // Form
  $("#crud-event-form").on("submit", function (e) {
    // e.preventDefault();
    //  todo later
  });
  const API_URL = "./event/getEvent.php";
  let offset = 0;
  //   get the event details
  const results = await getPartialEventDetails(0);

  appendTable(results.result);

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

  //   set the data into the table
  function appendTable(jsonData) {
    const eventRecordTBody = $("#event-record-tbody");
    jsonData.forEach((event) => {
      // moment js parsing
      const parsedDatePosted = moment(event.date_posted).format("MMMM Do YYYY");
      const parsedEventDateTime = moment(
        event.eventDate + " " + event.eventStartTime
      ).format("MMMM Do YYYY [at] h:mm a");

      //  append the data to the table
      eventRecordTBody.append(`
                <tr>
                        <td class="w-48 border "><img loading="lazy" 
                        class="object-cover block" src="data:image/jpeg;base64,${event.aboutImg}" alt=""></td>
                        <td class="td-eventID-holder font-bold text-gray-600 hover:text-blue-500 cursor-pointer" data-event-id="${event.eventID}">
                          ${event.eventName}
                        </td>
                        <td class="font-medium ">${event.headerPhrase}</td>
                        <td class="max-w-prose">${parsedEventDateTime}</td>
                        <td>${parsedDatePosted}</td>
                        <td class="text-sm inline-flex flex-nowrap items-center align-middle"><button class="btn-tertiary">Edit</button>
                        <button class="btn-tertiary">Archive</button></td>
                    </tr>
            `);
    });
  }

  //   event handlers
  $("#nextPostsBtn").on("click", async function () {
    $("#prevPostsbtn").attr("disabled", false);

    const eventRecordTBody = $("#event-record-tbody");
    offset += 5;
    eventRecordTBody.empty();
    const results = await getPartialEventDetails(offset);
    if (results.response !== "Successful") {
      // show no more posts
      eventRecordTBody.empty();
      eventRecordTBody.append(`
          <tr>
              <td colspan="6" class="text-center">No more posts</td>
          </tr>
          `);

      $("#nextPostsBtn").attr("disabled", true);
      return;
    } else {
      appendTable(results.result);
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

    const eventRecordTBody = $("#event-record-tbody");
    eventRecordTBody.empty();
    offset -= 5;

    const results = await getPartialEventDetails(offset);
    appendTable(results.result);
  });

  //   end

  //   Handles the Modal
  //close the announcement modal
  $("#announcementModal").on("click", function (e) {
    const target = e.target;
    let container = $("#announcementContainer");

    //check if the clicked is outside the container
    if (!container.is(target) && !container.has(target).length) {
      $("#announcementModal").addClass("hidden");
    }
  });

  //  Handles button clicks
  $("#addNewEventBtn").on("click", function () {
    $("#crud-event-modal").removeClass("hidden");
  });
  $(".cancel").on("click", function () {
    $("#crud-event-modal").addClass("hidden");
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
