import {
  postJSONFromURL,
  getJSONFromURL,
  animateOpactityTransitionOnContainer,
} from "../scripts/utils.js";

$(document).ready(function () {
  const GET_URL_LINK = "./job-opportunities/apiJobs.php";
  updateDataTable();
  updateUnverifiedJobs();
  // there is a timeout since the table is not yet loaded. render the table first before binding the handlers
  setTimeout(() => {
    bindHandlers();
  }, 100);

  $('a[href="#job-opportunities"]').on("click", function () {
    setTimeout(function () {
      // remove the loading screen
      $(".loading-row").addClass("hidden");
      updateDataTable();
      updateUnverifiedJobs();
      bindHandlers();
    }, 300);
  });

  function bindHandlers() {
    console.log("bindHandlers()");

    // Create new job
    $("#create-new-job-form").on("submit", async function (e) {
      e.preventDefault();

      const formData = new FormData(this);

      console.log(formData);

      // add confirmation on the user using sweet alert
      const confirmation = await Swal.fire({
        title: "Confirmation",
        text: "You are about to add this job post.",

        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",

        confirmButtonText: "Yes, add it!",
      });

      console.log(confirmation);
      if (!confirmation.isConfirmed) {
        return;
      }

      // post to the server
      const response = await postJSONFromURL(
        "./job-opportunities/apiJobs.php",
        formData
      );

      console.log(response);
      if (response.status === true) {
        // show success message
        console.log(response);
        Swal.fire(
          "Success!",
          "Job Post has been added successfully.",
          "success"
        ).then(() => {
          $("#jobPostingBack").addClass("hidden");
          animateOpactityTransitionOnContainer(
            $("#content-container"),
            $("#read-job-container"),
            $("#add-job-container")
          );

          // reload the table
          $("#jobTable").DataTable().ajax.reload();
          $("#unverified-job-table").DataTable().ajax.reload();
        });
      } else {
        // show error message
        Swal.fire("Error!", "Something went wrong.", "error");
      }

      // clear the form
    });

    $("#jobPostingBack").on("click", function () {
      $("#jobPostingBack").addClass("hidden");
      animateOpactityTransitionOnContainer(
        $("#content-container"),
        $("#read-job-container"),
        $("#add-job-container")
      );
    });
    // Add handler when the user clicks the add new job button
    $("#addNewBtn").on("click", function () {
      console.log("clicked");
      $("#jobPostingBack").removeClass("hidden");

      animateOpactityTransitionOnContainer(
        $("#content-container"),
        $("#add-job-container"),

        $("#read-job-container")
      );
    });

    $("#unverified-job-table ").on("click", ".approve-job-btn", function () {
      console.log("run this");
      // get the data-id
      const id = $(this).attr("data-id");
      console.log(id);
      // make confimation using sweet alert
      Swal.fire({
        title: "Confirmation",
        text: "You are about to approve this job post.",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",

        confirmButtonText: "Yes, approve it!",
      }).then((result) => {
        if (result.isConfirmed) {
          // Make form data
          const formData = new FormData();
          formData.append("action", "approve");
          formData.append("careerID", id);

          // approve the job
          postJSONFromURL("./job-opportunities/apiJobs.php", formData).then(
            (response) => {
              if (response.status === true) {
                // show success message
                Swal.fire(
                  "Success!",
                  "The job has been approved.",
                  "success"
                ).then(() => {
                  // reload the table
                  $("#unverified-job-table").DataTable().ajax.reload();
                  $("#jobTable").DataTable().ajax.reload();
                });
              } else {
                // show error message
                Swal.fire("Error!", "Something went wrong.", "error");
              }
            }
          );
        }
      });
    });

    $("table").on("click", "label", function () {
      // get the data-id
      const id = $(this).attr("data-id");
      const formData = new FormData();
      formData.append("action", "view");
      formData.append("careerID", id);

      // send a get request to the api
      postJSONFromURL(GET_URL_LINK, formData).then((response) => {
        if (response.status === true) {
          // get the data
          const data = response.data;
          console.log(data);

          // set the values
          $("#viewJobColText").text(data.jobTitle);
          $("#viewJobColCompany").text(data.companyName);
          console.log($("#viewJobColCompany").text());

          $("#jobOverview").text(data.jobDescript);

          $("#jobQualification").text(data.jobqualification);
          $("#skillSets").text(data.jobSkills);
          $("#jobDatePosted").text(data.date_posted);
          $("#jobAuthor").text(data.author);
          $("#locationJobModal").text(data.author);
          // salary
          $("#minSalary").text(data.minSalary);
          $("#maxSalary").text(data.maxSalary);
          $("#locationJobModal").text(data.location);

          // $("#author").text(data.author);
          // $("#companyLogo").attr("src", "data:image/jpeg;base64," +data.companyLogo);

          // show the modal
          // $("#view-modal").removeClass("hidden");
        } else {
          // show error message
          Swal.fire("Error!", "Something went wrong.", "error");
        }
      });
    });

    // binders for reject job
    $("#unverified-job-table ").on("click", ".deny-job-btn", function () {
      // get the data-id
      const id = $(this).attr("data-id");

      // make confimation using sweet alert
      Swal.fire({
        title: "Are you sure?",
        text: "You are about to reject this job post.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",

        confirmButtonText: "Yes, reject it!",
      }).then((result) => {
        if (result.isConfirmed) {
          // Make form data
          const formData = new FormData();
          formData.append("action", "reject");
          formData.append("careerID", id);

          // approve the job
          postJSONFromURL("./job-opportunities/apiJobs.php", formData).then(
            (response) => {
              if (response.status === true) {
                // show success message
                Swal.fire(
                  "Success!",
                  "The job post has been rejected.",
                  "success"
                ).then(() => {
                  // reload the table
                  $("#unverified-job-table").DataTable().ajax.reload();
                });
              } else {
                // show error message
                Swal.fire("Error!", "Something went wrong.", "error");
              }
            }
          );
        }
      });
    });
  }

  async function updateUnverifiedJobs() {
    $.fn.dataTable.moment("MMMM D, YYYY");

    $("#unverified-job-table").DataTable({
      ajax: {
        url: `${GET_URL_LINK}?status=unverified`,
        dataSrc: "data",
      },
      paging: true,
      ordering: true,
      info: false,
      lengthChange: false,
      searching: true,
      pageLength: 5,
      columns: [
        {
          data: "null",
          render: function (data, type, row) {
            return `
                <div class="grid grid-cols-2  gap-4 w-full max-w-md">
                    <label for="view-details-modal" data-id="${row.careerID}" class="cursor-pointer hover:text-accent col-span-2 flex flex-col justify-start items-start ">
                        <p class="font-bold text-left">${row.jobTitle}</p>
                        <p class="text-gray-500 font-light">${row.companyName}</p>
                    </label>
                </div>
          `;
          },
        },
        { data: "author" },
        { data: "date_posted" },
        {
          data: null,
          render: function (data, type, row) {
            // Define the buttons for the Actions column
            return `
                        <button  class="daisy-btn daisy-btn-xs daisy-btn-success approve-job-btn" data-id="${row.careerID}">Approve</button>
                        <button  class="daisy-btn daisy-btn-xs daisy-btn-warning deny-job-btn" data-id="${row.careerID}">Reject</button>
                    `;
          },
        },
      ],
      columnDefs: [
        {
          targets: [2],
          render: function (data, type, row) {
            return moment(data).format("MMMM D, YYYY");
          },
        },
      ],
    });

    $("#unverified-job-table").css("width", "");
  }

  // get all the verfied jobs
  async function updateDataTable() {
    $.fn.dataTable.moment("MMMM D, YYYY");
    $("#jobTable").DataTable({
      ajax: {
        url: `${GET_URL_LINK}?status=all`,
        dataSrc: "data",
      },
      paging: true,
      ordering: true,
      info: false,
      lengthChange: false,
      searching: true,
      pageLength: 10,
      columns: [
        { data: "companyName" },
        { data: "jobTitle" },
        { data: "author" },
        { data: "date_posted" },
        { data: "status" },
        {
          data: null,
          render: function (data, type, row) {
            // Define the buttons for the Actions column
            return `
                        <label for="view-details-modal" class="daisy-btn" data-id="${row.careerID}">View</label>
                    `;
          },
        },
      ],

      columnDefs: [
        {
          targets: [3],
          render: function (data, type, row) {
            return moment(data).format("MMMM D, YYYY");
          },
        },
      ],
    });
    $("#jobTable").css("width", "");
  }
});

function old() {
  const imgFormat = "data:image/jpeg;base64,";

  let offset = 0;

  let tempOffsetJob = 0;
  let countNext = 0;
  //job table listing
  $("#jobLI").on("click", function () {
    offset = 0;
    $("#jobTBContent").find("tr").remove();
    jobList(offset);
  });
  // TODO revise this later
  jobList(0);

  function jobList(offset) {
    let jobAction = {
      action: "read", //read the data
    };
    const jobData = new FormData();
    jobData.append("action", JSON.stringify(jobAction));
    jobData.append("offset", offset);

    $.ajax({
      url: "./php/jobTable.php",
      type: "POST",
      data: jobData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        //check if there's a value
        if (response.result === "Success") {
          $("#jobNavigation").find("button").remove();
          $(".jobErrorMsg").addClass("hidden"); //hide the message
          let data = response;
          let jobTitles = data.jobTitle; //job title is a property that is an array, all data is an array that we can use it as reference to get the lengh

          for (let i = 0; i < jobTitles.length; i++) {
            //fetch all the data
            let jobTitle = jobTitles[i];
            let author = data.author[i];
            let companyName = data.companyName[i];
            let jobDescript = data.jobDescript[i];
            let jobQuali = data.jobQuali[i];
            let college = data.colCode[i];
            let datePosted = data.date_posted[i];
            let companyLogo = data.companyLogo[i];
            let skills = data.skills[i];
            let logo = imgFormat + companyLogo;

            //add data to a table data
            let row = $("<tr>").addClass("text-xs");
            let tdTitle = $("<td>").text(jobTitle);
            let tdAuthor = $("<td>").text(author);
            let tdCollege = $("<td>").text(college);
            let tdDatePosted = $("<td>").text(datePosted);
            let tdLogo = $("<td>").append(
              $("<img>")
                .attr("src", logo)
                .addClass("w-16 h-16 mx-auto rounded-full")
            );

            //set up the value if th button view was clicked to view the details of the job
            let btnView = $("<td>").append(
              $("<button>")
                .text("View")
                .addClass(
                  "py-2 px-4 bg-postButton rounded-lg text-white hover:bg-postHoverButton"
                )
                .on("click", function () {
                  //remove the recent added skill and requirements
                  $("#skillSets").empty();

                  //set value to the view modal
                  $("#viewJob").removeClass("hidden");
                  $("#jobCompanyLogo").attr("src", logo);
                  $("#viewJobColText").text(jobTitle);
                  $("#viewJobAuthor").text(author);
                  $("#viewJobColCompany").text(companyName);
                  $("#viewPostedDate").text(datePosted);
                  $("#jobOverview").text(jobDescript);
                  $("#jobQualification").text(jobQuali);

                  //retrieve the skills
                  skills.forEach((skill) => {
                    //create a span and append it in the div
                    spSkill = $("<span>").html("&#x2022; " + skill);
                    $("#skillSets").append(spSkill);
                  });
                })
            );

            //display every data inside the table
            row.append(
              tdLogo,
              tdTitle,
              tdAuthor,
              tdCollege,
              tdDatePosted,
              btnView
            );
            $("#jobTBContent").append(row);
          }
          offset += jobTitles.length;
          tempOffsetJob = jobTitles.length;
          const nextBtn = $("<button>")
            .addClass(
              "bg-accent hover:bg-darkAccent text-white px-5 py-1 rounded-md"
            )
            .text("Next")
            .on("click", function () {
              $("#jobTBContent").find("tr").remove();
              jobList(offset);
              countNext += tempOffsetJob;
            });
          const prevBtn = $("<button>")
            .addClass(
              "border border-accent hover:bg-accent hover:text-white px-3 py-1 rounded-md"
            )
            .text("Previous")
            .on("click", function () {
              countNext -= tempOffsetJob;

              console.log(countNext);
              //check if there are still to be back
              if (countNext >= 0) {
                $("#jobTBContent").find("tr").remove();
                jobList(countNext);
              } else prevBtn.addClass("hidden");
            });

          $("#jobNavigation").append(prevBtn, nextBtn);
        } else {
          $(".jobErrorMsg").removeClass("hidden"); //add message to the user
          $("#nextJob").attr("disabled", true).addClass("hidden");
        }
      },
      error: function (xhr, status, error) {
        console.log("AJAX request error:", error);
      },
    });
  }

  //admin job list post
  $("#jobMyPost").on("click", function () {
    console.log("napindot");
  });
  //retrieve all the skills have been written
  function skillArray() {
    var skills = [];
    $(".skillInput").each(function () {
      skills.push($(this).val());
    });

    return skills;
  }

  //check if the forms in the job field is all answered
  function jobField() {
    var allFieldCompleted = true;
    $(".jobField").each(function () {
      if (!$(this).val()) {
        $(this).removeClass("border-gray-400").addClass("border-accent");
        allFieldCompleted = false;
      } else $(this).addClass("border-grayish").removeClass("border-accent");
    });
    return allFieldCompleted;
  }

  $(".college").on("click", function () {
    var colName = $(this).data("colname");
    var data = {
      action: "read",
      query: true,
    };

    var formData = new FormData();
    formData.append("data", JSON.stringify(data));
    formData.append("college", colName);

    $.ajax({
      url: "./php/collegeDB.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: (response) => {
        if (response.result == "Success") {
          $(".individual-col").removeClass("hidden");
          $(".college-content").addClass("hidden");

          //fetch the data that has been retrieve
          let colData = response;
          let colCode = colData["colCode"];
          let colName = colData["colName"];
          let colEmailAdd = colData["colEmailAdd"];
          let colContactNo = colData["colContactNo"];
          let colWebLink = colData["colWebLink"];
          let colLogo = colData["colLogo"];
          let colDean = colData["colDean"];
          let colDeanImg = colData["colDeanImg"];
          let colAdmin = colData["colAdminName"];
          let colAdminImg = colData["colAdminImg"];

          //add the images
          let logo = imgFormat + colLogo;
          let deanImgFormat = imgFormat + colDeanImg;
          let adminImgFormat = imgFormat + colAdminImg;
          let deanImg = colDeanImg == "" ? defaultProfileSrc : deanImgFormat; //check if still no value
          let adminImg = colAdminImg == "" ? defaultProfileSrc : adminImgFormat;

          //display the data
          $("#colLogo").attr("src", logo);
          $("#deanImg").attr("src", deanImg);
          $("#adminImg").attr("src", adminImg);

          $("#colName").text(colName + "(" + colCode + ")");
          $("#collegeCode").text(colCode);
          $("#colContact").text(colContactNo);
          $("#colEmail").text(colEmailAdd);
          $("#colWebLink").attr("href", colWebLink).text(colWebLink);
          colDean = colDean == null ? "No inserted dean yet" : "MR. " + colDean;
          $("#colDean").text(colDean);
          $("#colAdminName").text("MR. " + colAdmin);
        } else console.log("ayaw na");
      },
      error: (error) => {
        console.log(error);
      },
    });
  });

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
      url: "./php/emailDB.php",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: (response) => {
        let data = response;
        $("#suggestionContainer").show();
        $("#suggestionContainer").empty(); //remove the data first
        if (data.response == "success") {
          let length = data.suggestions.length;
          //show all the suggested email
          for (let i = 0; i < length; i++) {
            suggestedEmail = data.suggestions[i];
            email = $("<p>")
              .text(suggestedEmail)
              .addClass(
                "hover:bg-gray-200 cursor-pointer border-b border-gray-300 py-1"
              )
              .on("click", function () {
                let emailVal = $(this).text();
                $("#searchEmail").val(emailVal);
                $("#suggestionContainer").hide();
              });

            $("#suggestionContainer").append(email).addClass("bg-white");
          }
        }
      },
      error: (error) => {
        console.log(error);
      },
    });
  });

  //show new posting of job
  $("#addNewbtn").click(function () {
    $("#jobPosting").show();
    $("#jobList").hide();
    $(".jobPostingBack").show();
  });

  //show the default job posting content
  $(".jobPostingBack").click(function () {
    $("#jobPosting").hide();
    $("#jobList").show();
    $(".jobPostingBack").hide();
    $("#adminJobPost").hide();
  });

  $(".inputSkill").on("input", function () {
    addNewField(skillDiv, holderSkill, true);
  });

  //error handling for logo
  $("#jobLogoInput").change(function () {
    const fileInput = $(this);
    const file = fileInput[0].files[0];

    fileExtension = file.name.split(".").pop().toLowerCase(); //getting the extension of the selected file
    let fileName = "";
    if (validExtension.includes(fileExtension)) {
      fileName = file.name;
    } else fileName = "Wrong file";

    //set the text as the name of the selected file
    fileName == "Wrong file"
      ? $("#jobFileName").addClass("text-accent")
      : $("#jobFileName").removeClass("text-accent");
    $("#jobFileName").html(fileName);
  });

  //open and close the view job modal
  $(".viewJobModal").on("click", function () {
    $("#viewJob").removeClass("hidden");
  });

  //allows modal to be close when Click else where
  $("#viewJob").on("click", function (e) {
    console.log("test this");
    if (!e.target.closest("#viewJob").length) $("#viewJob").addClass("hidden");
  });
}
