import { getJSONFromURL, postJSONFromURL } from "../scripts/utils.js";

// initialize jquery
$(document).ready(function () {
  $("#daterange").daterangepicker();
});

updatePostDataTable();

$('a[href="#email"]').on("click", function () {
  setTimeout(function () {
    // remove the loading screen
    $(".loading-row").addClass("hidden");
    updatePostDataTable();
  }, 1000);
});

function updatePostDataTable() {
  // remove the loading screen

  $("#emailTable").DataTable({
    ajax: {
      url: "./email/apiEmail.php?all=true",
      dataSrc: "",
    },
    paging: true,
    ordering: true,
    info: false,
    lengthChange: false,
    searching: true,
    pageLength: 10,
    columns: [
      { data: "recipient", width: "25%" },
      { data: "dateSent" },
      {
        data: null,
        render: function (data, type, row) {
          // Define the buttons for the Actions column
          return `
                        <label for="view-modal" class="daisy-btn" data-id="${row.personID}">View</label>
                    `;
        },
      },
    ],
    columnDefs: [
      {
        targets: [1],
        render: function (data, type, row) {
          return moment(data).format("MMMM DD, YYYY");
        },
      },
    ],
  });
}
