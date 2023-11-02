import { getJSONFromURL, postJSONFromURL } from "../scripts/utils.js";

// initialize jquery
$(document).ready(function () {
  updatePostDataTable();
  bindHandlers();

  $('a[href="#make-post"]').on("click", function () {
    setTimeout(function () {
      // remove the loading screen
      $(".loading-row").addClass("hidden");
      updatePostDataTable();
      bindHandlers();
    }, 1000);
  });

  function bindHandlers() {
    // for table view button
    $("#postTable").on("click", "label[for='view-modal']", async function () {
      // get the id of the row
      const postID = $(this).attr("data-id");
      // get the post data
      const response = await getJSONFromURL(
        "./make-post/apiPosts.php?action=id&postID=" + postID
      );
      console.log(response);
      if (response.status == true) {
        const data = response.data;
        /**
         * {
  "postID": "6542f47691c3c-1816",
  "username": "JaysonBatoonBulSU-CICT",
  "colCode": "CICT",
  "caption": "afsdfasdf",
  "date": "2023-11-02",
  "timestamp": "2023-11-02 01:59:34",
  "status": "available"
}

         */
        $("#statusDescript").html(data.caption);
        $("#statusDate").html(moment(data.date).format("MMMM Do, YYYY"));
        $("#postFullName").html(data.fullname);
        $("#postUN").html(data.username);
        $("#profileStatusImg").prop(
          "src",
          "data:image/jpg;base64," + data.profilePicture
        );

        $("#statusLikes").html();
        $("#statusComment").html();
      }
    });

    $("#postTable").on("click", "button.archive", async function () {
      // get the id of the row
      const postID = $(this).attr("data-id");

      // make confirmation with sweet alert
      const result = await Swal.fire({
        title: "Confirmation",
        text: "You are about to archive this announcement. Are you sure?",
        icon: "info",
        showCancelButton: true,
      });

      if (result.isConfirmed) {
        // get the post data
        const formData = new FormData();
        formData.append("action", "archivePost");
        formData.append("postID", postID);

        const response = await postJSONFromURL(
          "./make-post/apiPosts.php",
          formData
        );
        console.log(response);
        if (response.status == true) {
          // reload the #postTable datatable
          $("#postTable").DataTable().ajax.reload();
          // reduce the number of post in #totalPost
          $("#totalPost").html(parseInt($("#totalPost").html()) - 1);

          // add success alert
          Swal.fire({
            title: "Success",
            text: "Your Post is Successfully Archived!",
            icon: "success",
          });
        }
      }
    });

    $("#fileGallery").change(() => {
      $("#errorMsg").addClass("hidden"); //always set the error message as hidden when changing the file
      $("#TxtAreaAnnouncement").addClass("h-5/6").removeClass("h-3/6");

      //file input
      var fileInput = $("#fileGallery");
      var file = fileInput[0].files[0]; //get the first file that being select

      fileExtension = file.name.split(".").pop().toLowerCase(); //getting the extension of the selected file
      //checking if the file is based on the extension we looking for
      if (validExtension.includes(fileExtension)) {
        var reader = new FileReader();
        selectedFiles.push(file); // Store the selected file in the array
        //new image element to be place on the  image container div
        const imageElement = document.createElement("img");

        const imgPlaceHolder = document.createElement("div");
        imgPlaceHolder.className = "relative";

        //for button x
        const xBtn = document.createElement("button");
        xBtn.innerHTML = "X";
        xBtn.className =
          "xBtn absolute h-5 w-5 top-0 text-center right-0 cursor-pointer rounded-full hover:bg-accent hover:text-white hover:font-bold";
        //remove the image
        xBtn.addEventListener("click", function (e) {
          var parent = e.target.parentNode;
          var index = Array.from(parent.parentNode.children).indexOf(parent); //get a specific index which picture has been remove
          selectedFiles.splice(index, 1); // Remove the file from the selectedFiles array
          parent.parentNode.removeChild(parent);
        });

        // img element
        imageElement.className = "flex-shrink-0 h-20 w-20 rounded-md m-2";
        imageElement.setAttribute("id", "reservedPicture" + imageSequence); //to make sure every id is unique

        //add to its corresponding container
        imgPlaceHolder.appendChild(imageElement);
        imgPlaceHolder.appendChild(xBtn);
        imgContPost.appendChild(imgPlaceHolder);

        //assign the image path to the img element
        reader.onload = function (e) {
          $("#reservedPicture" + imageSequence).attr("src", e.target.result);
          $("#imgContPost").removeClass("hidden");
          $("#TxtAreaAnnouncement").addClass("h-3/6").removeClass("h-5/6"); //make the text area smaller in height
          imageSequence++;
        };

        reader.readAsDataURL(file);
      } else {
        $("#errorMsg").removeClass("hidden"); //if the file is not based on the img extension we looking for
      }
    });

    // on submit of make-post-form
    $("#make-post-form").on("submit", async function (e) {
      e.preventDefault();
      // get the form data
      const formData = new FormData(this);
      // formData.append("files", selectedFiles);
      formData.append("action", "createPost");

      const fileGallery = $("#fileGallery");

      console.log(formData);

      // const response = await
      // make confirmation with sweet alert
      const result = await Swal.fire({
        title: "Confirmation",
        text: "You are about to post this announcement. Are you sure?",
        icon: "info",
        showCancelButton: true,
      });

      if (result.isConfirmed) {
        // post the form data
        postJSONFromURL("./make-post/apiPosts.php", formData).then((data) => {
          console.log(data);
          if (data.status == true) {
            // reset the form
            $("#make-post-form")[0].reset();
            // remove the image container
            $("#imgContPost").addClass("hidden");
            // remove the selected files
            // selectedFiles = [];
            // update the table
            // updatePostDataTable();
            // reload the #postTable datatable
            $("#postTable").DataTable().ajax.reload();
            // add success alert
            Swal.fire({
              title: "Success",
              text: "Your Post is Successfully Posted!",
              icon: "success",
            });
            // toggle newPostModal checkbox
            $("#newPostModal").prop("checked", false);
            // add the number of post in #totalPost
            $("#totalPost").html(parseInt($("#totalPost").html()) + 1);
          } else {
            Swal.fire({
              title: "Error",
              text: "Your Post is not Successfully Posted!",
              icon: "error",
            });
          }
        });
      }
    });
  }

  function updatePostDataTable() {
    // remove the loading screen

    // initialize the moment js plugin to datatable
    $.fn.dataTable.moment("MMMM Do, YYYY h:mm a");

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
                        <div class="flex flex-wrap gap-4 items-center">
                          <button for="archive-modal" class="archive text-accent daisy-link daisy-link-hover" data-id="${row.postID}">Archive</button>
                          <label for="view-modal" class="daisy-btn daisy-btn-sm daisy-btn-info text-white" data-id="${row.postID}">View</label>
                        </div>
                    `;
          },
        },
      ],
      columnDefs: [
        {
          targets: [3],
          render: function (data, type, row) {
            return moment(data).format("MMMM Do, YYYY h:mm a");
          },
        },
      ],
    });
  }
});
