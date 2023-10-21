import { getJSONFromURL, postJSONFromURL } from "../scripts/utils.js";

// initialize jquery
$(document).ready(function () {
  $('a[href="#make-post"]').on("click", function () {
    console.log("make-post clicked");
    $("#postTable").DataTable({
      ajax: "./make-post/apiPosts.php?all=true",
      dataSrc: "",
      paging: true,
      //   ordering: true,
      info: false,
      lengthChange: true,
      searching: true,
      pageLength: 10,
      processing: true,
      serverSide: true,
    });
    console.log("finished");
  });

  async function getPost() {
    let post = await getJSONFromURL("./make-post/apiPosts.php?all=true");
    return post;
  }

  $("#postTable").DataTable({
    ajax: {
      url: "./make-post/apiPosts.php?all=true",
      dataSrc: "",
    },
    paging: true,
    ordering: true,
    info: false,
    lengthChange: true,
    searching: true,
    pageLength: 10,
    columns: [
      { data: "caption" },
      { data: "like_count" },
      { data: "comment_count" },
      { data: "timestamp" },
      {
        data: null,
        render: function (data, type, row) {
          // Define the buttons for the Actions column
          return `
                        <button class="edit-button" data-id="${row.id}">Edit</button>
                        <button class="delete-button" data-id="${row.id}">Delete</button>
                    `;
        },
      },
    ],
    // order: [
    //   [0, "desc"],
    //   [1, "desc"],
    // ],
  });
});
