$(document).ready(async function () {
  // Form
  $("#createNewEventForm").on("submit", function (e) {
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
      eventRecordTBody.append(`
                <tr>
                        <td class="w-48 border "><img loading="lazy" 
                        class="object-cover block" src="data:image/jpeg;base64,${event.aboutImg}" alt=""></td>
                        <td class="font-bold text-gray-600 hover:text-blue-500 cursor-pointer">${event.eventName}</td>
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
    $("#createNewPostModal").removeClass("hidden");
  });
  $(".cancel").on("click", function () {
    $("#createNewPostModal").addClass("hidden");
  });
});
