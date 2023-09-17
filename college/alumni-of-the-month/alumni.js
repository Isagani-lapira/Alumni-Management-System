$(document).ready(function () {
  // add event handler to the cancel button
  $(".cancelModal").click(function () {
    hideDisplay("#modalAlumni");
  });

  $("#addAlumniBtn").on("click", function () {
    showDisplay("#modalAlumni");
  });

  /*****
   *
   */
  const API_URL = "./event/getEvent.php";
  let offset = 0;
  refreshList();
  // refresh the list
  async function refreshList(category = "all") {
    //   get the event details
    const results = await getPartialEventDetails(0, category);

    $("#event-list").empty();
    appendContent($("#event-list"), results.result);
  }
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
  function appendContent(selectorContainer, jsonData) {
    const container = selectorContainer;
    jsonData.forEach((event) => {
      // moment js parsing
      const parsedDatePosted = moment(event.date_posted).format("MMMM Do YYYY");
      const parsedEventDateTime = moment(
        event.eventDate + " " + event.eventStartTime
      ).format("MMMM Do YYYY [|] h:mm a");

      //  append the data to the table
      container.append(`
      <tr class="h-14">
      <td class="student-num__val text-start font-bold">${jsonData.studentNo}</td>
      <td>
          <div class="flex items-center justify-start">
              <div class="w-10 h-10 rounded-full border border-accent"></div>
              <span class="ml-2">${jsonData.fullname}</span>
          </div>
      </td>

      <td class="text-center text-blue-400 font-light hover:cursor-pointer hover:text-accentBlue hover:font-semibold">VIEW PROFILE</td>
  </tr>
            `);
    });
  }

  // end
});

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
