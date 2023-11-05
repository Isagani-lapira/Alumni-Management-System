$(document).ready(function () {
  bindHandlers();
  $('a[href="#email"]').on("click", function () {
    setTimeout(function () {
      // remove the loading screen
      $(".loading-row").addClass("hidden");
      bindHandlers();
    }, 300);
  });

  function bindHandlers() {
    let emailOffset = 0;

    const actionDefault = "retrieveEmails";
    //get today's date to set starting date and end for our date picker
    const datePicker = new Date();
    const thisyear = datePicker.getFullYear();
    const thismonth = datePicker.getMonth() + 1;
    const thisday = datePicker.getDate();
    let defaultStart = thismonth + "/" + thisday + "/" + thisyear;
    let defaultEnd = thismonth + 1 + "/" + thisday + "/" + thisyear;

    const imgConEmail = document.getElementById("imgContEmail");

    let validExtension = ["jpeg", "jpg", "png"]; //only allowed extension
    let fileExtension;

    getEmailSent(actionDefault);
    //show or close the prompt modal
    function prompt(id, openIt) {
      openIt == true ? $(id).removeClass("hidden") : $(id).addClass("hidden");
    }

    CKEDITOR.replace("TxtAreaEmail");

    // Search Email Functionality
    // adding email suggestions
    $("#searchEmail").on("input", function () {
      let action = {
        action: "suggestionEmail",
      };
      let email = $(this).val(); //email that has been typed
      let formData = new FormData();
      formData.append("email", email);
      formData.append("action", JSON.stringify(action));
      $.ajax({
        url: "./email/emailDB.php",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: (response) => {
          let data = response;
          $("#suggestionContainer").removeClass("hidden");
          $("#suggestionContainer").empty(); //remove the data first
          if (data.response == "success") {
            let length = data.fullname.length;
            //show all the suggested email
            for (let i = 0; i < length; i++) {
              const fullname = data.fullname[i];
              const email = data.email[i];

              const fullnameElement = $("<p>").text(fullname);
              const emailElement = $("<p>")
                .addClass("text-xs font-bold italic text-blue-400")
                .text(email);
              const suggestionWrapper = $("<div>")
                .addClass(
                  "hover:bg-gray-200 cursor-pointer border-b border-gray-300 py-1"
                )
                .append(fullnameElement, emailElement)
                .on("click", function () {
                  //add the selected name to the input box
                  $("#searchEmail").val(email);
                  $("#suggestionContainer").addClass("hidden");
                });
              $("#suggestionContainer").removeClass("hidden");
              $("#suggestionContainer")
                .append(suggestionWrapper)
                .addClass("bg-white");
            }
          } else {
            $("#suggestionContainer").addClass("hidden");
          }
        },
        error: (error) => {
          console.log(error);
        },
      });
    });

    // Binds the Group Email
    //checked the type of recipient
    $('input[name="recipient"').on("change", function () {
      let recipient = $('input[name="recipient"]:checked').val();

      if (recipient == "individualEmail") {
        $("#groupEmail").hide();
        $("#individualEmail").removeClass("hidden");
      } else {
        $("#groupEmail").show();
        $("#individualEmail").addClass("hidden");
      }
    });

    //close modal
    $(".cancel").click(function () {
      prompt("#modal", false);

      //remove the images
      while (imgConEmail.firstChild) {
        imgConEmail.removeChild(imgConEmail.firstChild);
        selectedImgEM = [];
      }
      $("#TxtAreaAnnouncement").val("");
    });

    let imageSequenceEM = 1;
    let selectedImgEM = [];
    let selectedFileEM = [];

    //add image to the modal
    $("#imageSelection").change(() => {
      $("#errorMsgEM").addClass("hidden"); //always set the error message as hidden when changing the file

      //file input
      var fileInput = $("#imageSelection");
      var file = fileInput[0].files[0]; //get the first file that being select
      var filename = file.name;
      fileExtension = file.name.split(".").pop().toLowerCase(); //getting the extension of the selected file
      //checking if the file is based on the extension we looking for
      if (validExtension.includes(fileExtension)) {
        if (file.size <= 1024 * 1024) {
          //check file size if the image is 1mb
          var reader = new FileReader();
          selectedImgEM.push(file); // Store the selected file in the array
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
            selectedImgEM.splice(index, 1); // Remove the file from the selectedImgEM array
            parent.parentNode.removeChild(parent);
          });

          // img element
          imageElement.className = "flex-shrink-0 h-20 w-20 rounded-md m-2";
          imageElement.setAttribute("id", "reservedPicture" + imageSequenceEM); //to make sure every id is unique

          //add to its corresponding container
          imgPlaceHolder.appendChild(imageElement);
          imgPlaceHolder.appendChild(xBtn);
          imgConEmail.appendChild(imgPlaceHolder);

          //assign the image path to the img element
          reader.onload = function (e) {
            $("#reservedPicture" + imageSequenceEM).attr(
              "src",
              e.target.result
            );
            $("#imgContEmail").removeClass("hidden");
            $("#TxtAreaEmail").addClass("h-3/6").removeClass("h-5/6"); //make the text area smaller in height
            imageSequenceEM++;
          };

          reader.readAsDataURL(file);
        } else
          $("#errorMsgEM")
            .removeClass("hidden")
            .text(filename + " file size greater than 1mb");
      } else
        $("#errorMsgEM")
          .removeClass("hidden")
          .text(
            "Sorry we only allow images that has file extension of jpg, jpeg, png"
          ); //if the file is not based on the img extension we looking for
    });

    $("#fileSelection").on("change", function () {
      //file input
      var fileInput = $("#fileSelection");
      var file = fileInput[0].files[0]; //get the first file that being select
      var nameOfFile = file.name;
      if (file.size <= 5 * 1024 * 1024) {
        $("#errorMsgEM").addClass("hidden"); // hide the message
        selectedFileEM.push(file);
        //preview of the file
        let fileContainerPrev = $("<div>").addClass(
          "flex justify-evenly item-center"
        );
        let fileName = $("<p>").addClass("p-1 w-full text-xs").text(nameOfFile);
        let xBtn = $("<span>")
          .text("x")
          .addClass("cursor-pointer")
          .on("click", function (e) {
            var parent = e.target.parentNode;
            var index = Array.from(parent.parentNode.children).indexOf(parent); //get a specific index which picture has been remove
            selectedFileEM.splice(index, 1); // Remove the file from the selectedImgEM array
            parent.parentNode.removeChild(parent);
          });

        fileContainerPrev.append(fileName, xBtn);
        $("#fileContEmail").show().append(fileContainerPrev);
      } else
        $("#errorMsgEM")
          .removeClass("hidden")
          .text(nameOfFile + " file size greater than 5mb");
    });

    //clicked the gallery icon
    $("#galleryIcon").on("click", function () {
      $("#imageSelection").click();
    });

    //clicked the file icon
    $("#fileIcon").on("click", function () {
      $("#fileSelection").click();
    });

    //send email
    $("#emailForm").on("submit", (e) => {
      e.preventDefault();
      //check the type of recipient
      let recipient = $('input[name="recipient"]:checked').val();
      let emailSubj = $("#emailSubj").val();
      let formSend = new FormData();

      if (recipient == "individualEmail") {
        let searchEmail = $("#searchEmail").val();
        formSend.append("searchEmail", searchEmail);
      } else {
        let user = $('input[name="selectedUser"]:checked').val();
        let college = $("#college-hidden").val();
        formSend.append("college", college);
        formSend.append("user", user);

        if (college === null) {
          $(".selectColWrapper")
            .removeClass("border-gray-400")
            .addClass("border-accent");
          return; //for avoiding unselected college
        } else
          $(".selectColWrapper")
            .addClass("border-gray-400")
            .removeClass("border-accent");
      }

      // let message = $("#TxtAreaEmail").val();
      // get the message in the editor
      message = CKEDITOR.instances["TxtAreaEmail"].getData();
      //

      formSend.append("recipient", recipient);
      formSend.append("subject", emailSubj);
      formSend.append("message", message);

      // Append each file individually to the FormData object
      for (let i = 0; i < selectedImgEM.length; i++) {
        formSend.append("images[]", selectedImgEM[i]);
      }
      for (let i = 0; i < selectedFileEM.length; i++) {
        formSend.append("files[]", selectedFileEM[i]);
      }

      // submit email
      if (checkerInput(emailSubj, message)) {
        $("#loadingScreen").removeClass("hidden"); //open loading animation
        console.log(formSend);
        $.ajax({
          url: "./email/sendEmail.php",
          type: "POST",
          data: formSend,
          processData: false,
          contentType: false,
          // while loading
          beforeSend: () => {
            $("#loadingScreen").removeClass("hidden");
            // toggle the daisy-email-modal checkbox
            $("#daisy-email-modal").prop("checked", false);
          },
          success: (response) => {
            $("#loadingScreen").addClass("hidden");
            if (response == "user is not existing") $("#userNotExist").show();
            else {
              $("#promptMsg").removeClass("hidden");
              //retrieve emails
              table.clear().draw();
              emailOffset = 0;
              getEmailSent(actionDefault);

              //success sending
              $("#message").text("Email sent!");
              $("#userNotExist").hide();
              $("#modalEmail").addClass("hidden");
              setTimeout(() => {
                $("#promptMsg").addClass("hidden");
              }, 4000);
            }
          },
          error: (error) => console.log(error),
        });
      }
    });

    $("#btnEmail").on("click", function () {
      $("#emailForm")[0].reset(); // restart everything
      $("#modalEmail").removeClass("hidden");
    });

    $("#emailLi").on("click", function () {
      emailOffset = 0;
      //retrieve emails
      $("#newsAndUpdate-tab").removeClass("hidden");
      getEmailSent(actionDefault);
    });

    // back to default button
    const defaultButton = $(
      '<button class="hover:bg-accent rounded-md hover:text-white px-2">Default</button>'
    ).on("click", function () {
      startDate = "";
      endDate = "";
      $("#emDateRange").val("Select date");
      filterTable();
    });

    $("#emDateRange").parent().append(defaultButton);

    let college = "";
    let startDate = "";
    let endDate = "";
    const table = $("#emailTable").DataTable({
      pageLength: 10,
      paging: true,
      info: false,
      lengthChange: false,
      ordering: false,
    });
    $("#emailTable").removeClass("dataTable").addClass("rounded-md");

    $("#emCol").on("change", function () {
      college = $(this).val();
      filterTable();
    });

    $("#emailTable").on("click", ".view-button", function () {
      let recipient = $(this).attr("data-recipient");
      let colcode = $(this).attr("data-colcode");
      let subject = $(this).attr("data-subject");
      let message = $(this).attr("data-message");
      let date = $(this).attr("data-date");

      //set up details for email modal
      $(".subject").text(subject);
      $(".to").text(recipient + " ( " + colcode + " )");
      $(".messageEmail").text(message);
      $(".dateData").text(date);

      // open modal for email details
      $(".emailDetailModal").removeClass("hidden");
    });

    // close email sent modal
    $(".closeEmailModal").on("click", function () {
      $(".emailDetailModal").addClass("hidden");

      // remove details in email
      $(".emailDetailModal h2 span").text("");
      $(".dateData").text("");
    });

    // filtering date
    $(function () {
      $('input[name="emDateRange"]').daterangepicker(
        {
          opens: "left",
          startDate: defaultStart,
          endDate: defaultEnd,
        },
        function (start, end, label) {
          startDate = start.format("MMMM DD, YYYY");
          endDate = end.format("MMMM DD, YYYY");

          filterTable();
        }
      );
    });

    //close modal email
    $(".cancelEmail").click(function () {
      $("#modalEmail").addClass("hidden");
    });
    function filterTable() {
      if (college !== "" && startDate === "" && endDate === "") {
        // college filtering only
        table.column(1).search(college);
        table.column(2).search("");
      } else if (startDate !== "" && endDate !== "" && college === "") {
        // Date filtering
        table
          .columns(2)
          .search(startDate + "|" + endDate, true, false, true)
          .draw();
      } else if (college !== "" && startDate !== "" && endDate !== "") {
        // college and range
        table.column(1).search(college);
        table.column(2).search(startDate + "|" + endDate, true, false, true);
      } else table.column(1).search("").column(2).search(""); // back to default

      table.draw(); //display the filtered table
    }

    // format the date into easy to read date
    function getFormattedDate(date) {
      //parts out the date
      let year = date.substring(0, 4);
      let dateMonth = parseInt(date.substring(5, 7));
      let day = date.substring(8, 10);

      const listOfMonths = [
        "",
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ];
      let month = listOfMonths[dateMonth];

      return month + " " + day + ", " + year;
    }

    function checkerInput(emailSubj, message) {
      let isComplete = true;
      // check first if the fields are complete
      if (emailSubj === "") {
        $("#emailSubj")
          .removeClass("border-gray-400")
          .addClass("border-accent");
        isComplete = false;
      } else $("#emailSubj").addClass("border-gray-400").removeClass("border-accent");

      if (message === "") {
        $(".modal-descript")
          .removeClass("border-gray-400")
          .addClass("border-accent");
        isComplete = false;
      } else $(".modal-descript").addClass("border-gray-400").removeClass("border-accent"); // back to normal, remove error red indicator

      return isComplete;
    }

    function getEmailSent(actionData, colCode = "") {
      //perform ajax operation
      const action = { action: actionData };
      const formData = new FormData();
      formData.append("action", JSON.stringify(action));
      formData.append("offset", emailOffset);
      formData.append("colCode", colCode);

      $.ajax({
        url: "./email/emailDB.php",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "JSON",
        success: (response) => {
          //check for the data retrieved
          if (response.result == "Success") {
            const length = response.recipient.length;

            for (let i = 0; i < length; i++) {
              const recipient = response.recipient[i];
              const colCode = response.colCode[i];
              const dateSent = getFormattedDate(response.dateSent[i]);
              const subject = response.subject[i];
              const message = response.message[i];

              //row data
              let row = [
                recipient,
                colCode,
                dateSent,
                `<button class="rounded-md px-3 py-2 text-white bg-postButton hover:bg-postHoverButton view-button"
                            data-recipient="${recipient}"
                            data-colcode="${colCode}"
                            data-subject="${subject}"
                            data-message="${message}"
                            data-date="${dateSent}"
                            >View</buton>`,
              ];
              table.row.add(row);
            }

            table.draw(); //display the newly retrieved email data
            emailOffset += length;
            $("#totalEmailed").text(emailOffset);
            // retrieve another data
            if (length === 10) getEmailSent(actionDefault);
          }
        },
        error: (error) => {
          console.log(error);
        },
      });
    }
  }
});
