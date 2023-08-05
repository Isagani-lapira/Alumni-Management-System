
$(document).ready(function () {

  const imgFormat = 'data:image/jpeg;base64,';
  const colCode = $('#colCode').html();
  $('#tabs').tabs();
  // Initialize the tabs - FEED BTN
  $("#tabs-feed-btns").tabs();

  $("#job-offer-tabs").tabs();


  // Drop down PROFILE AND LOGOUT
  $('#dropdown-btn').click(function () {
    $('#dropdown-content').toggle();
    $(this).toggleClass('active');
  });

  //MODAL
  // When either of the buttons is clicked, show the modal
  $('#writeBtn, #postButton').click(function () {
    $('#modal').removeClass('hidden');
  });

  // When the cancel button or the modal overlay is clicked, hide the modal
  $('.cancel, #modal').click(function () {
    $('#modal').addClass('hidden');
  });

  // Prevent closing the modal when clicking inside it
  $('.modal-container').click(function (e) {
    e.stopPropagation();
  });

  $("#yearbookButton").click(function () {
    $("#tabs-yrbook").show();
    $("#tabs-1").hide();
  });

  $("#feedLink").click(function () {
    $("#tabs-yrbook").hide();
    $("#tabs-1").show();
    $("#tabs-college").show();

  });

  function getCurrentDate() {
    var today = new Date();
    var year = today.getFullYear();
    var month = String(today.getMonth() + 1).padStart(2, '0');
    var day = String(today.getDate()).padStart(2, '0');
    return year + '-' + month + '-' + day;
  }

  function formatDate(date) {
    var year = date.getFullYear();
    var month = String(date.getMonth() + 1).padStart(2, '0');
    var day = String(date.getDate()).padStart(2, '0');
    return year + '-' + month + '-' + day;
  }

  function getLastTwoWeeksFromStringDate(dateString) {
    var currentDate = new Date(dateString); // Convert the string date to a Date object
    var dates = "";

    for (var i = 0; i < 14; i++) {
      var date = new Date(currentDate.getTime() - i * 24 * 60 * 60 * 1000);
      dates = formatDate(date);
    }

    return dates;
  }

  let endDate = getCurrentDate();
  let startDate = getLastTwoWeeksFromStringDate(endDate)

  let action = {
    action: 'readWithCol',
    colCode: colCode,
  }

  $('#JobHuntText').on('click', function () {
    getWork(action, startDate, endDate) //load a list of work
  })


  $('#closeReportModal').on('click', function () {
    $('#reportModal').addClass('hidden')
  })

  function getWork(action, startDate, endDate) {
    let formData = new FormData();
    formData.append('action', JSON.stringify(action));
    formData.append('startDate', startDate)
    formData.append('endDate', endDate)
    $.ajax({
      method: 'POST',
      url: '../PHP_process/jobTable.php',
      data: formData,
      processData: false,
      contentType: false,
      success: (response) => {
        if (response != 'none') {
          const parsedResponse = JSON.parse(response);
          //if there's a value
          if (parsedResponse.result == 'Success') {
            const length = parsedResponse.author.length; //total length of all data that has been retrieved
            for (let i = 0; i < length; i++) {
              //data to be use
              const careerID = parsedResponse.careerID[i];
              const companyLogo = imgFormat + parsedResponse.companyLogo[i];
              const jobTitle = parsedResponse.jobTitle[i];
              const company = parsedResponse.companyName[i];
              const author = parsedResponse.author[i];
              const skill = parsedResponse.skills[i];
              const location = parsedResponse.location[i];

              //display job with design
              listOfJobDisplay(jobTitle, company, author, skill, companyLogo, careerID, location)
            }

          }
        }
        else $('#noJobMsg').removeClass('hidden');
      },
      error: (error) => { console.log(error) }
    })
  }

  function listOfJobDisplay(jobTitle, company, author, skills, companyLogo, careerID, location) {

    //creating elements
    let containerJob = $('<div>').addClass('rounded-md px-2 py-3 text-sm center-shadow flex gap-2 text-gray-500 cursor-pointer')
    let companyImg = $('<img>').addClass('h-12 w-12 rounded-full').attr('src', companyLogo)
    let jobDescription = $('<div>').addClass('flex-grow flex flex-col text-dirtyWhite gap-1')
    let jobTitleElement = $('<p>').text(jobTitle).addClass('text-bold font-semibold text-black')
    let companyName = $('<p>').text(company).addClass('text-sm')

    // location
    let locationIcon = '<iconify-icon icon="fluent:location-16-filled"></iconify-icon>';
    let locationElement = $('<span>')
      .addClass('flex gap-1 items-center text-xs')
      .append(locationIcon, location)

    let postedCont = $('<div>').addClass('flex text-xs justify-end text-lightDirtyWhite mt-2')
    let postedText = $('<p>').text('Posted by:')
    let postedByElement = $('<p>').text(author)
    let skillContainer = $('<div>').addClass('text-xs flex gap-1 flex-wrap')

    //bookmark
    let isSave = false;
    let outlineBookmark = '<iconify-icon id="bookmark" icon="iconamoon:bookmark-light" style="color: gray;" width="24" height="24"></iconify-icon>'
    let solidBookmark = '<iconify-icon icon="iconamoon:bookmark-fill" style="color: #991b1b;" width="24" height="24"></iconify-icon>'
    let bookmarkCont = $('<span>').html(outlineBookmark)
      .on('click', function () {
        //remove the saved career
        if (isSave) {
          $(this).html(outlineBookmark)
          removeBookmark(careerID)
        }
        else {
          //save the career
          $(this).html(solidBookmark)
          saveCareer(careerID);
        }
        isSave = !isSave
      })

    //check if the career is bookedmark
    checkCareerMarked(careerID)
      .then((result) => {
        if (result === 'exist') {
          bookmarkCont.html(solidBookmark) //change the bookmark from outline to solid
          isSave = true;
        }
      })

    //retrieve all the skill and display in on a div to be included on the container
    skills.forEach(skill => {
      let bulletIcon = '<iconify-icon icon="fluent-mdl2:radio-bullet" style="color: #6c6c6c;"></iconify-icon>';
      let skillElement = $('<span>').html(skill)
      skillContainer.append(bulletIcon, skillElement)
    })

    //put the element to their corresponding container
    postedCont.append(postedText, postedByElement);
    jobDescription.append(jobTitleElement, companyName, locationElement, skillContainer, postedCont)
    containerJob.append(companyImg, jobDescription, bookmarkCont)
    let list = $('<li>').append(containerJob);
    $('#listOfJob').append(list) // add to the list


    containerJob.on('click', function () {

      //remove the the last one has been selected
      $('.selectedJob').each((index, element) => {
        $(element).removeClass('selectedJob')
      })
      containerJob.addClass('selectedJob');//set the container that has been clicked as selected container
      //viewing of particular job
      viewOfCareer(careerID);
    })

    // Automatically select the first job listing and trigger the click event
    if ($('#listOfJob li').length === 1) {
      containerJob.trigger('click');
    }
  }

  //saving career to bookmark
  function saveCareer(careerID) {
    //data to be send
    let formData = new FormData();
    formData.append('action', 'saveCareer');
    formData.append('careerID', careerID);

    //process insertion
    $.ajax({
      method: 'POST',
      url: '../PHP_process/bookmark.php',
      data: formData,
      processData: false,
      contentType: false,
      success: (response) => { console.log(response) },
    })
  }

  //remove career in bookmark
  function removeBookmark(careerID) {
    //data to be send
    let formData = new FormData();
    formData.append('action', 'removeBookmark');
    formData.append('careerID', careerID);

    //process removal
    $.ajax({
      method: 'POST',
      url: '../PHP_process/bookmark.php',
      data: formData,
      processData: false,
      contentType: false,
      success: (response) => { console.log(response) },
    })
  }

  //read all the bookmark
  function readBookMark() {
    formData = new FormData();
    formData.append('action', 'readBookmark');

    $.ajax({
      method: 'POST',
      url: '../PHP_process/bookmark.php',
      data: formData,
      processData: false,
      contentType: false,
      success: (response) => {
        const parsedResponse = JSON.parse(response);
        const length = parsedResponse.length;

        if (length > 0) {
          //display data that has been retrived
          parsedResponse.forEach((value) => {
            const careerID = value.careerID;
            const companyLogo = imgFormat + value.companyLogo;
            const jobTitle = value.jobTitle;
            const company = value.companyName;
            const author = value.author;
            const skill = value.skills;
            const location = value.location;

            //display job with design
            listOfJobDisplay(jobTitle, company, author, skill, companyLogo, careerID, location)
          })
        }
        else $('#noJobMsg').removeClass('hidden');
      },
      error: (error) => { console.log(error) }
    })
  }

  //checking if the career is already in saved
  function checkCareerMarked(careerID) {
    return new Promise((resolve, reject) => {
      // data to be sent
      let formData = new FormData();
      formData.append('action', 'checkBookmark');
      formData.append('careerID', careerID);

      // process checking
      $.ajax({
        method: 'POST',
        url: '../PHP_process/bookmark.php',
        data: formData,
        processData: false,
        contentType: false,
        success: (response) => {
          if (response === 'Exist') {
            resolve('exist'); // Resolve the promise with the value 'exist'
          } else {
            resolve('not exist'); // Resolve the promise with the value 'not exist'
          }
        },
        error: (error) => {
          console.log(error);
          reject(error); // Reject the promise if an error occurs
        },
      });
    });
  }

  function getCurrentDate() {
    var today = new Date();
    var year = today.getFullYear();
    var month = String(today.getMonth() + 1).padStart(2, '0');
    var day = String(today.getDate()).padStart(2, '0');
    return year + '-' + month + '-' + day;
  }

  //display the badge if there's a notification haven't read yet
  badgeNotification()
  function badgeNotification() {
    let action = {
      action: 'readUnreadNotif'
    }
    let formatData = new FormData()
    formatData.append('action', JSON.stringify(action));

    $.ajax({
      url: '../PHP_process/notificationData.php',
      method: 'POST',
      data: formatData,
      processData: false,
      contentType: false,
      success: (success) => {
        //display a notification badge
        if (success != 'none') $('#notifBadge').removeClass('hidden').html(success);
        else $('#notifBadge').addClass('hidden')
      },
      error: (error) => { console.log(error) }
    })
  }

  //change list to admin
  $('#jobSelection').on('change', function () {
    let jobVal = $(this).val();

    //restart everything
    endDate = getCurrentDate()
    startDate = getLastTwoWeeksFromStringDate(endDate)
    $('#listOfJob').empty()
    if (jobVal == 'Admin') {
      let actionAdmin = {
        action: 'readWithAuthor',
        colCode: colCode
      }
      //change list into admin's list
      getWork(actionAdmin, startDate, endDate) //list of work
    }
    else if (jobVal == 'Saved') {
      readBookMark()
    }
    //back to all
    else if (jobVal == 'all') {
      getWork(action, startDate, endDate)
    }
  })

  //retrieve new data of post
  $('#listOfJob').on('scroll', function () {
    if ($('#listOfJob').scrollTop() + $('#listOfJob').innerHeight() >= $('#listOfJob')[0].scrollHeight) {
      endDate = startDate
      startDate = getLastTwoWeeksFromStringDate(endDate)
      getWork(action, startDate, endDate) //list of work
    }
  })


  function displaySelectedCareer(jobTitle, companyName, author, datePosted, companyLogo,
    description, skills, qualification, location) {
    let logo = imgFormat + companyLogo;

    //remove the past display
    $('#skillsContainer').empty();

    //displaying a particular job data
    $('#viewJobLogo').attr('src', logo)
    $('#viewJobTitle').text(jobTitle)
    $('#viewJobCompany').text(companyName)
    $('#viewJobAuthor').text(author)
    $('#viewJobDatePosted').text(datePosted)
    $('#jobDescript').text(description)
    $('#viewJobQuali').text(qualification)
    $('#locationContainer').text(location)
    //get all the skills that a particular career has
    skills.forEach(skill => {
      let bulletIcon = '<iconify-icon icon="fluent-mdl2:radio-bullet" style="color: #6c6c6c;"></iconify-icon>';
      skillVal = $('<p>').text(skill);

      //add it on the container
      $('#skillsContainer').append(bulletIcon, skillVal);
    })
  }

  function viewOfCareer(careerID) {
    const actionCareer = {
      action: 'readWithCareerID',
      careerID: careerID, //to be change
    }

    let formData = new FormData();
    formData.append('action', JSON.stringify(actionCareer));

    $.ajax({
      url: '../PHP_process/jobTable.php',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: (result) => {
        //if the process is successful
        const parsedResponse = JSON.parse(result);
        if (parsedResponse.result == 'Success') {
          const companyName = parsedResponse.companyName[0];
          const jobTitle = parsedResponse.jobTitle[0];
          const companyLogo = parsedResponse.companyLogo[0];
          const author = parsedResponse.author[0];
          const datePosted = parsedResponse.date_posted[0];
          const description = parsedResponse.jobDescript[0];
          const skills = parsedResponse.skills[0];
          const qualification = parsedResponse.jobQuali[0];
          const location = parsedResponse.location[0];

          displaySelectedCareer(jobTitle, companyName, author, datePosted,
            companyLogo, description, skills, qualification, location)
        }
      },
      error: (error) => { console.log(error) }
    })
  }

  //logout the user
  $('#logout').on('click', function () {
    //perform php process for signing out
    $.ajax({
      method: 'POST',
      url: "../PHP_process/signout.php",
      success: () => { window.location.href = "login.php" }, //go back to login page
    })
  })


  //searching for job
  $('#searchJob').on('input', function () {
    let search = $(this).val();

    //data to be send to the php
    let action = {
      action: 'searching',
      jobTitle: search
    }
    let formData = new FormData();
    formData.append('action', JSON.stringify(action))

    $.ajax({
      url: '../PHP_process/jobTable.php',
      method: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: (response) => {
        $('#listOfJob').empty() //remove the past suggestion to show new suggestion
        const parsedResponse = JSON.parse(response);
        //if there's a value
        if (parsedResponse.result == 'Success') {
          const length = parsedResponse.author.length; //total length of all data that has been retrieved
          for (let i = 0; i < length; i++) {
            //data to be use
            const careerID = parsedResponse.careerID[i];
            const companyLogo = imgFormat + parsedResponse.companyLogo[i];
            const jobTitle = parsedResponse.jobTitle[i];
            const company = parsedResponse.companyName[i];
            const author = parsedResponse.author[i];
            const skill = parsedResponse.skills[i];

            //display job with design
            listOfJobDisplay(jobTitle, company, author, skill, companyLogo, careerID)
          }

        }
        else $('#noJobMsg').removeClass('hidden');
      },
      error: (error) => { console.log(error) }
    })

  })

  retreiveUpcomingEvent()
  //retrieve the upcoming event
  function retreiveUpcomingEvent() {
    let currentDate = getCurrentDate();
    let action = 'readUpcomingEvent'
    let formData = new FormData();
    //data to be send
    formData.append('action', action)
    formData.append('currentDate', currentDate)

    //process retrieval of upcoming event
    $.ajax({
      method: 'POST',
      url: '../PHP_process/event.php',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: response => {
        if (response.result == 'Success') {

          // retrieve data 
          const length = response.eventName.length
          for (let i = 0; i < length; i++) {
            const eventID = response.eventID[i]
            const eventName = response.eventName[i]
            const eventDate = response.eventDate[i]

            //display the upcoming event
            let eventWrapper = $('<div>').addClass('flex flex-wrap gap-2 items-center')
            let bulletIcon = '<iconify-icon icon="fluent-mdl2:radio-bullet" style="color: #6c6c6c;"></iconify-icon>';
            let eventNameElement = $('<p>')
              .addClass('font-bold italic text-greyish_black text-sm')
              .text(eventName)
            let eventDateElement = $('<p>')
              .addClass('text-xs text-gray-500')
              .text(eventDate)

            let viewDetails = $('<button>')
              .addClass('text-blue-500 text-xs')
              .text('View Details')
              .on('click', function () {
                // open the modal
                $('#eventModal').removeClass('hidden')
                displayEventModal(eventID, eventName, eventDate)
              })

            let eventDetailWrapper = $('<div>').append(eventNameElement, eventDateElement, viewDetails)
            eventWrapper.append(bulletIcon, eventDetailWrapper);
            $('#upcomingEventroot').append(eventWrapper);
          }

        }
      },
      error: error => { console.log(error) }
    })
  }

  function displayEventModal(eventID, eventTitle, eventDateModal) {
    $('#eventTitleModal').text(eventTitle)
    $('#eventDateModal').text(eventDateModal)

    let action = "retrieveSpecificEvent"
    const formatData = new FormData();
    formatData.append('action', action)
    formatData.append('eventID', eventID);

    // get other details
    $.ajax({
      method: 'POST',
      url: "../PHP_process/event.php",
      data: formatData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: response => {
        //data that has been retrieved
        const aboutEvent = response.about_event
        const images = response.images
        const eventPlace = response.eventPlace
        const eventStartTime = response.eventStartTime
        const expectation = response.expectation

        //display the data
        $('#eventDescript').text(aboutEvent)
        $('#eventPlaceModal').text(eventPlace)
        $('#eventTimeModal').text(eventStartTime)

        $('#eventImgWrapper').empty(); //remove the previously display images
        //show images
        images.forEach(value => {
          const imgSrc = imgFormat + value //convert into base64 format
          const imgElement = $('<img>')
            .addClass('rounded-md w-40')
            .attr('src', imgSrc)

          $('#eventImgWrapper').append(imgElement);
        })

        $('#expectationList').empty() //remove the previously display list of expectation
        // show expectation
        const expectationData = expectation.expectation
        expectationData.forEach(value => {
          const wrapper = $('<div>')
            .addClass('flex gap-2 items-center text-gray-500')

          const bulletIcon = '<iconify-icon icon="fluent-mdl2:radio-bullet" style="color: #6c6c6c;"></iconify-icon>';
          const expectationElement = $('<p>')
            .addClass('text-sm')
            .text(value)
          wrapper.append(bulletIcon, expectationElement)

          $('#expectationList').append(wrapper)
        })
      },
      error: error => { console.log(error) }
    })
  }

});



function buttonColor() {
  var targetDiv = document.getElementById("target-div");
  targetDiv.classList.toggle("red-color");

  var icon = document.querySelector("#notif-btn .fa");
  var text = document.querySelector("#notif-btn .text-greyish_black");

  if (targetDiv.classList.contains("red-color")) {
    icon.style.color = "white";
    text.style.color = "white";
    targetDiv.classList.add("hover:bg-red-900");
  } else {
    icon.style.color = "";
    text.style.color = "";
    targetDiv.classList.remove("hover:bg-red-900");
  }
}

//Verification Job Post
function toggleColorJob() {
  var targetDiv = document.getElementById("target-div-job");
  targetDiv.classList.toggle("red-color");

  var icon = document.querySelector("#verif-btn .fa");
  var text = document.querySelector("#verif-btn .text-greyish_black");

  if (targetDiv.classList.contains("red-color")) {
    icon.style.color = "white";
    text.style.color = "white";
    targetDiv.classList.add("hover:bg-red-900");
  } else {
    icon.style.color = "";
    text.style.color = "";
    targetDiv.classList.remove("hover:bg-red-900");
  }
}

function toggleJobPost() {
  $("#mainFeed").toggleClass("hidden");
  $("#jobPostFeed").toggleClass("hidden");
}

//Yearbook
function toggleYearbook() {
  $("#mainFeedContainer").toggleClass("hidden");
  $("#yearbookContainer").toggleClass("hidden");
}

// TOGGLE THE FEED AGAIN 
function toggleFeed() {
  $("#mainFeedContainer").removeClass("hidden");
  $("#yearbookContainer").addClass("hidden");

}

const jobOffers = document.querySelectorAll('.job-offer');
jobOffers.forEach(offer => {
  offer.addEventListener('click', () => {
    // Remove the selected class from all job offers
    jobOffers.forEach(offer => offer.classList.remove('selected'));
    // Add the selected class to the clicked job offer
    offer.classList.add('selected');
  });
});


//BOOKMARK ICON ON JOB HUNT
jobOffers.forEach((offer) => {
  const bookmarkIcon = offer.querySelector('.bookmark-icon');
  bookmarkIcon.addEventListener('click', function () {
    bookmarkIcon.classList.toggle('fas');
  });
});

//FEED DYAMIC ARRANGEMENT OF DIVS
// Calculate and set the width of the center div dynamically
function setCenterDivWidth() {
  const leftDiv = document.querySelector('.left-div');
  const rightDiv = document.querySelector('.right-div');
  const centerDiv = document.getElementById('centerDiv');

  const leftDivWidth = leftDiv.offsetWidth;
  const rightDivWidth = rightDiv.offsetWidth;
  const centerDivWidth = window.innerWidth - leftDivWidth - rightDivWidth;

  centerDiv.style.width = `${centerDivWidth}px`;
}

// Call the function initially and on window resize
setCenterDivWidth();
window.addEventListener('resize', setCenterDivWidth);


//IMAGE PICKER

function openFileExplorer() {
  document.getElementById("fileInput").click();
}

function handleFileSelection(event) {
  const selectedFiles = event.target.files;
  const selectedImagesContainer = document.getElementById("selectedImagesContainer");

  for (let i = 0; i < selectedFiles.length; i++) {
    const selectedFile = selectedFiles[i];

    if (selectedFile.type.startsWith("image/")) {
      const reader = new FileReader();

      reader.onload = function (e) {
        const imageContainer = document.createElement("div");
        imageContainer.className = "relative max-w-xs h-auto mr-2 mt-2 rounded-md";

        const image = document.createElement("img");
        image.src = e.target.result;

        const deleteButton = document.createElement("button");
        deleteButton.innerHTML = "&times;";
        deleteButton.className = "text-accent text-3xl p-1 focus:outline-none absolute top-0 right-0";
        deleteButton.onclick = function () {
          deleteImage(imageContainer);
        };

        imageContainer.appendChild(image);
        imageContainer.appendChild(deleteButton);
        selectedImagesContainer.appendChild(imageContainer);
      };

      reader.readAsDataURL(selectedFile);
    }
  }
}

function deleteImage(imageContainer) {
  imageContainer.parentNode.removeChild(imageContainer);
}


// Dropdown report for post

// Toggle dropdown menu
function toggleDropdown() {
  const dropdownMenu = document.getElementById('dropdownMenu');
  dropdownMenu.classList.toggle('hidden');
}


//Function for Zoom Image (POST)
function openImageModal(imageSrc) {
  const modal = document.getElementById('imageModal');
  const modalImage = document.getElementById('modalImage');

  modalImage.src = imageSrc;
  modal.classList.remove('hidden');
}

function closeModalPost() {
  const modal = document.getElementById('imageModal');
  const modalImage = document.getElementById('modalImage');

  modalImage.src = '';
  modal.classList.add('hidden');
}

//For Like and Comment (POST)
function toggleIcon(iconId) {
  const icon = document.getElementById(iconId);
  icon.classList.toggle('fa-solid');
  icon.classList.toggle('fa-regular');
}

//For Like and Comment (IMAGEMODAL POST)
function toggleIcon1(iconId) {
  const icon = document.getElementById(iconId);
  icon.classList.toggle('fa-solid');
  icon.classList.toggle('fa-regular');
}

//Dropdown for Image Modal (POST)
function toggleDropdownPostModal(dropdownId) {
  const dropdown = document.getElementById(dropdownId);
  dropdown.classList.toggle('hidden');
}


//Comment Modal
function openCommentModal() {
  // Get the post image source
  //const postImageSrc = document.getElementById('postImage').src;

  // Set the post image source for the comment modal
  //document.getElementById('modalPostImage').src = postImageSrc;

  // Open the comment modal
  document.getElementById('commentModal').classList.remove('hidden');
}

function closeCommentModal() {
  document.getElementById('commentModal').classList.add('hidden');
}


//Truncate sentence from carousel RIGHT DIV
document.addEventListener("DOMContentLoaded", function () {
  const paragraphs = document.querySelectorAll(".carousel-slide p");
  const limit = 120;

  paragraphs.forEach((paragraph, index) => {
    const text = paragraph.textContent;
    if (text.length > limit) {
      paragraph.textContent = text.substring(0, limit) + "...";
    }
  });
});

