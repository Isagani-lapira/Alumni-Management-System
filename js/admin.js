const redAccent = "#991B1B";
const blueAccent = "#2E59C6";
const skillDiv = "skillDiv";
const holderSkill = "Add skill/s that needed";

const reqDiv = "reqDiv";
const holderReq = "Add skill/s that needed";
const imgFormat = "data:image/jpeg;base64,";
const defaultProfileSrc = "../assets/default_profile.png";

//get today's date to set starting date and end for our date picker
const datePicker = new Date();
const thisyear = datePicker.getFullYear();
const thismonth = datePicker.getMonth() + 1;
const thisday = datePicker.getDate();
let defaultStart = thismonth + "/" + thisday + "/" + thisyear;
let defaultEnd = thismonth + 1 + "/" + thisday + "/" + thisyear;

$(document).ready(function () {
  $("#tabs").tabs();

  let validExtension = ["jpeg", "jpg", "png"]; //only allowed extension
  let fileExtension;

  //change the tab appearance when active and not
  $(".tabs li").click(function () {
    $(".tabs li").removeClass("ui-tabs-active");
    $(this).addClass("ui-tabs-active");
  });

  $('#profileTabAdmin').on('click', function () {
    $('#profile-tab').removeClass('hidden')
  })

  $('#aoyLi').on('click', function () {
    $('#alumnYear-tab').removeClass('hidden')
  })

  $('#aomLi').on('click', function () {
    $('#alumnMonth-tab').removeClass('hidden')
  })

  $('#communityLi').on('click', function () {
    $('#community-tab').removeClass('hidden')
  })
  $('#jobLI').on('click', function () {
    $('#jobOpportunities-tab').removeClass('hidden')
  })

  //go to creating college page
  $("#btnNewCol").click(function () {
    window.location.href = "../admin/NewCollege.php";
  });

  $('collegeLi').on('click', function () {
    $('#colleges-tab').removeClass('hidden')
  })
  $(".back-icon").click(() => {
    $(".individual-col").addClass("hidden");
    $(".college-content").removeClass("hidden");
  });

  $("#btnDelPost").click(function () {
    prompt("#modalDelete", true);
  });
  $(".cancelDel").click(function () {
    prompt("#modalDelete", false);
  });

  $(".viewProfile").click(function () {
    prompt("#viewProfile", true);
  });

  $(".closeProfile").click(function () {
    prompt("#viewProfile", false);
  });

  //show the prompt modal
  function prompt(id, openIt) {
    openIt == true ? $(id).removeClass("hidden") : $(id).addClass("hidden");
  }

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
    const modal = $('#viewingJobModal')
    const target = e.target
    if (!modal.is(target) && modal.has(target).length === 0)
      $("#viewJob").addClass("hidden");
  });



  $(function () {
    $('input[name="aomdaterange"]').daterangepicker(
      {
        opens: "left",
        startDate: defaultStart,
        endDate: defaultEnd,
      },
      function (start, end, label) {
        console.log(
          "A new date selection was made: " +
          start.format("YYYY-MM-DD") +
          " to " +
          end.format("YYYY-MM-DD")
        );
      }
    );
  });

  $("#jobMyPost").on("click", () => {
    $("#jobList").hide();
    $("#jobPosting").hide();
    $("#adminJobPost").show();
    $(".jobPostingBack").show();
  });

  var data = {
    action: "read",
  };

  var formData = new FormData();
  formData.append("data", JSON.stringify(data));

  //get total number of colleges available in the database
  $.ajax({
    url: "../PHP_process/collegeDB.php",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      $("#totalCol").html(response);
      $("#totalColNo").html(response);
    },
    error: function (error) {
      $("#totalCol").html(error);
    },
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
      url: "../PHP_process/emailDB.php",
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
          let length = data.fullname.length;
          //show all the suggested email
          for (let i = 0; i < length; i++) {
            const fullname = data.fullname[i];
            const email = data.email[i];

            fullnameElement = $('<p>')
              .text(fullname)
            emailElement = $('<p>')
              .addClass('text-xs font-bold italic text-blue-400')
              .text(email)
            suggestionWrapper = $("<div>")
              .addClass("hover:bg-gray-200 cursor-pointer border-b border-gray-300 py-1")
              .append(fullnameElement, emailElement)
              .on("click", function () {
                //add the selected name to the input box
                $("#searchEmail").val(email);
                $("#suggestionContainer").hide();
              });
            $('#suggestionContainer').removeClass('hidden')
            $("#suggestionContainer").append(suggestionWrapper).addClass("bg-white");
          }
        }
      },
      error: (error) => {
        console.log(error);
      },
    });
  });

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



  //sign out
  $("#signout").on("click", function () {
    $("#signOutPrompt").removeClass("hidden");

    let cancelBtn = $("#cancelSignout");
    let signOutBtn = $("#signoutBtn");

    cancelBtn.on("click", function () {
      $("#signOutPrompt").addClass("hidden");
    });

    signOutBtn.on("click", function () {
      $.ajax({
        url: "../PHP_process/signout.php",
        type: "GET",
        success: () => {
          window.location.href = "loginAdmin.php";
        },
        error: (error) => {
          console.log(error);
        },
      });
    });
  });

  //displaying image
  let imgProfileVal = $(".profilePicVal").html();
  let profilePic = imgFormat + imgProfileVal;

  //set all the profile with a user picture
  $(".profilePic").each(function () {
    var imgElement = $(this);
    imgElement.attr("src", profilePic);
  });

  //edit admin details
  $("#editProf").on("click", function () {
    $("#profileModal").removeClass("hidden");
  });
  //profile
  let profileLbl = "";
  let profileBtn = "";
  let currentProfile = $('#profileImgEdit').attr('src')
  let profileImg = $("#profileImgEdit");

  //change the current profile displayed to new selected profile
  $("#profileFile").on("change", function () {
    let selectedFile = $(this).prop("files")[0];
    profileLbl = $("#profileLbl");
    profileBtn = $("#profileBtn");

    if (selectedFile) {
      var reader = new FileReader();
      reader.onload = (e) => {
        profileImg.attr("src", e.target.result);
        profileBtn.removeClass("hidden");
        profileLbl.addClass("hidden");
      };
      reader.readAsDataURL(selectedFile);
    }
  });

  $("#saveProfile").on("click", function () {
    let newProfile = $("#profileFile").prop("files")[0];

    let action = {
      action: "updateProfile",
      dataUpdate: "profilepicture",
    };
    let profileBtn = $("#profileBtn");
    let label = $("#profileLbl");
    processImgUpdate(action, newProfile, profileBtn, label);
  });

  // bring back the current profile
  $('#cancelProfileImg').on('click', function () {
    profileImg.attr('src', currentProfile)
    profileBtn.addClass("hidden");
    profileLbl.removeClass("hidden");
  })

  function processImgUpdate(action, img, confirmationCont, editIcon) {
    let formData = new FormData();
    formData.append("action", JSON.stringify(action));
    formData.append("imgSrc", img);

    $.ajax({
      url: "../PHP_process/person.php",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: (response) => {
        if (response == "success") {
          confirmationCont.addClass("hidden");
          editIcon.removeClass("hidden");
        }
      },
      error: (error) => {
        console.log(error);
      },
    });
  }

  let currentLocation = $('#editAddress').val()
  //edit location
  $("#editAddLabel").on("click", function () {
    $("#editAddress")
      .removeAttr("disabled") //allows to be edit
      .addClass("border-b border-gray-400");

    $("#locBtn").removeClass("hidden"); //show the save button
    $("#editAddLabel").addClass("hidden"); //remove the edit label
  });

  $('#cancelLocation').on('click', function () {
    $('#editAddress')
      .attr("disabled", true) //disable editting
      .removeClass("border-b border-gray-400")
      .val(currentLocation)

    $("#locBtn").addClass("hidden"); //hide the save button
    $("#editAddLabel").removeClass("hidden"); //display the edit label
  })

  $("#saveLocation").on("click", function () {
    let newAddress = $("#editAddress").val();

    let action = {
      action: "updatePersonDetails",
      dataUpdate: "address",
    };
    let btnCont = $("#locBtn");
    let label = $("#editAddLabel");
    processPersonalInfo(action, newAddress, btnCont, label);
  });

  //edit email address
  let currentEmail = $('#editEmail').val();
  $("#editEmailLbl").on("click", function () {
    $("#editEmail")
      .removeAttr("disabled") //allows to be edit
      .addClass("border-b border-gray-400");

    $("#emailBtn").removeClass("hidden"); //show the save button
    $("#editEmailLbl").addClass("hidden"); //remove the edit label
  });

  $('#cancelEmail').on('click', function () {
    $("#editEmail")
      .attr("disabled", true) //allows to be edit
      .removeClass("border-b border-gray-400")
      .val(currentEmail)

    $("#emailBtn").addClass("hidden");
    $("#editEmailLbl").removeClass("hidden");
  })

  // save changes
  $("#saveEmail").on("click", function () {
    let newEmail = $("#editEmail").val();

    let action = {
      action: "updatePersonDetails",
      dataUpdate: "personal_email",
    };
    let btnCont = $("#emailBtn");
    let label = $("#editEmailLbl");
    processPersonalInfo(action, newEmail, btnCont, label);
  });


  //edit contact Number
  let currentContact = $('#editContact').val()
  $("#editContactLbl").on("click", function () {
    $("#editContact")
      .removeAttr("disabled") //allows to be edit
      .addClass("border-b border-gray-400");

    $("#contactBtn").removeClass("hidden"); //show the save button
    $("#editContactLbl").addClass("hidden"); //remove the edit label
  });

  // cancel editting
  $('#cancelContact').on('click', function () {
    $("#editContact")
      .attr("disabled", true) //allows to be edit
      .removeClass("border-b border-gray-400")
      .val(currentContact)

    $("#contactBtn").addClass("hidden"); //show the save button
    $("#editContactLbl").removeClass("hidden"); //remove the edit label
  })

  $("#saveContact").on("click", function () {
    let newEmail = $("#editContact").val();

    let action = {
      action: "updatePersonDetails",
      dataUpdate: "contactNo",
    };
    let btnCont = $("#contactBtn");
    let label = $("#editContactLbl");
    processPersonalInfo(action, newEmail, btnCont, label);
  });

  function processPersonalInfo(action, value, confirmationCont, editIcon) {
    let formData = new FormData();
    formData.append("action", JSON.stringify(action));
    formData.append("value", value);

    $.ajax({
      url: "../PHP_process/person.php",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: (response) => {
        if (response == "success") {
          confirmationCont.addClass("hidden");
          editIcon.removeClass("hidden");
        }
      },
      error: (error) => {
        console.log(error);
      },
    });
  }
  //close the modal
  $("#profileModal").on("click", function (event) {
    const target = event.target;
    const formUpdate = $(".formUpdate");

    //clicked outside the edit modal
    if (!formUpdate.is(target) && !formUpdate.has(target).length) {
      $(this).addClass("hidden");
    }
  });

  let isClose = false
  $('#burgerBtn').on('click', function () {
    if (!isClose) {
      $('#listOfPanels').find('span').addClass('hidden')
      $('#listOfPanels').addClass('w-max').removeClass('w-3/12 ')
    }
    else {
      $('#listOfPanels').find('span').removeClass('hidden')
      $('#listOfPanels').removeClass('w-max').addClass('w-3/12 ')
    }
    isClose = !isClose
  })

  $('#tracerbtn').on('click', function () {
    isClose = false
    $('#burgerBtn').click()
  })

  $('#formLi').on('click', function () {
    $('#formReport').removeClass('hidden'); //as default graph is presented
    $('#TracerWrapper').addClass('hidden');
    $('#categoryWrapper').addClass('hidden');
  })


  $('#closeQuestionModal').on('click', function () {
    $('#newQuestionModal').addClass('hidden')
    $('#newQuestionInputName, .choicesVal').val('')
  })


  retrievedLast5YearResponse()

  // retrieve dashboard response
  function retrievedLast5YearResponse() {
    const action = "retrieveRespondent";
    const formData = new FormData();
    formData.append('action', action);

    $.ajax({
      url: '../PHP_process/deploymentTracer.php',
      method: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      dataType: 'json',
      success: response => {
        let labels = [];
        let dataCount = [];
        if (response.response == 'Success') {
          const data = response
          let length = data.year.length;
          let lastYear = 0;
          for (let i = 0; i < length; i++) {
            let year = data.year[i];
            let respondent = data.respondent[i];

            lastYear = year
            labels.push(year);
            dataCount.push(respondent);
          }

          const maxPrevYear = 4;
          const defaultRespondentCount = 0
          // if the data doesnt have 5 year previous set it as default zero
          if (length != maxPrevYear) {
            while (length <= maxPrevYear) {
              lastYear-- //decreasing year from the last year retrieve
              labels.push(lastYear);
              dataCount.push(defaultRespondentCount);
              length++
            }
          }

          // update the graph
          objResponse.data.labels = labels;
          objResponse.data.datasets[0].data = dataCount;
          objResponse.update()
        }

      }
    })
  }

  const responseByYear = $('#responseByYear')[0].getContext('2d');
  const objResponse = new Chart(responseByYear, {
    type: 'line',
    data: {
      datasets: [{
        label: '# of Response',
        borderWidth: 1,
        borderColor: redAccent, // Set the line color
        backgroundColor: '#991b1b',
        borderWidth: 1,
        tension: 0.1,
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1, // Specify the step size for Y-axis values
          }
        }
      }
    }
  })

});

