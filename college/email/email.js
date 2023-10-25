import { getJSONFromURL, postJSONFromURL } from "../scripts/utils.js";

// initialize jquery
$(document).ready(function () {
  $("#daterange").daterangepicker();
});

updatePostDataTable();

$('a[href="#make-post"]').on("click", function () {
  setTimeout(function () {
    // remove the loading screen
    $(".loading-row").addClass("hidden");
    updatePostDataTable();
  }, 1000);
});

function updatePostDataTable() {
  // remove the loading screen

  $("#postTable").DataTable({
    ajax: {
      url: "./make-post/apiPosts.php?all=true",
      dataSrc: "",
    },
    paging: true,
    ordering: true,
    info: false,
    lengthChange: false,
    searching: true,
    pageLength: 10,
    columns: [
      { data: "caption", width: "25%" },
      { data: "like_count" },
      { data: "comment_count" },
      { data: "timestamp" },
      {
        data: null,
        render: function (data, type, row) {
          // Define the buttons for the Actions column
          return `
                        <label for="archive-modal" class="daisy-btn" data-id="${row.postID}">Archive</label>
                        <label for="view-modal" class="daisy-btn" data-id="${row.postID}">View</label>
                    `;
        },
      },
    ],
  });
}
