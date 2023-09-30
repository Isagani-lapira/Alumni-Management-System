$(document).ready(function () {
  // add event handler to the cancel button
  $(".cancelModal").click(function () {
    hideDisplay("#modalAlumni");
  });

  $("#addAlumniBtn").on("click", function () {
    showDisplay("#modalAlumni");
  });

  $("#searchQuery").on("keyup", function () {
    const search = $("#searchQuery").val();
    console.log(search);
    debouncedSearchAlumni(search);
  });

  /*****
   *
   */
  const API_URL = "./alumni-of-the-month/getAlumni.php?partial=true";
  const API_URL_SEARCH = "php/searchAlumni.php?search=true";

  let offset = 0;
  refreshList();
  //  add event handler to the refresh button
  $("#refreshRecord").on("click", async function () {
    refreshList();
  });

  // refresh the list
  async function refreshList(category = "all") {
    //   get the event details
    const results = await getPartialEventDetails(0, category);
    $("#tBodyRecord").empty();
    appendContent($("#tBodyRecord"), results.result);
  }

  //   get the event details
  async function getPartialEventDetails(offset, category) {
    console.log("getPartialEventDetails");
    const response = await fetch(API_URL + "&offset=" + offset, {
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

  async function getSearchAlumni(search) {
    console.log("working...");
    const response = await fetch(API_URL_SEARCH + "&qName=" + search, {
      headers: {
        method: "GET",
        "Content-Type": "application/json",
        cache: "no-cache",
      },
    });

    const result = await response.json();
    return result.result;
  }

  const debouncedSearchAlumni = _.debounce(searchAlumni, 500);

  async function searchAlumni() {
    const search = $("#searchQuery").val();
    console.log(search);
    if (search.length > 0) {
      const result = await getSearchAlumni(search);
      console.log(result);
    } else {
    }
  }

  //   set the data into the table
  function appendContent(selectorContainer, jsonData) {
    console.log(jsonData);
    const container = selectorContainer;
    jsonData.forEach((item) => {
      //  append the data to the table
      console.log(item);

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
