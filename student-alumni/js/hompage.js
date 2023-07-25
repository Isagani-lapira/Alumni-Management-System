
$(document).ready(function () {

  const swiper = new Swiper('.swiper', {
    // If we need pagination
    pagination: {
      el: '.swiper-pagination',
    },

    // Navigation arrows
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  });

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
  const action = {
    action: 'readWithCol',
    colCode: colCode, //to be change
  }

  getWork(action) //list of work
  function getWork(action) {
    let formData = new FormData();
    formData.append('action', JSON.stringify(action));

    $.ajax({
      method: 'POST',
      url: '../PHP_process/jobTable.php',
      data: formData,
      processData: false,
      contentType: false,
      success: (response) => {
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
  }

  function listOfJobDisplay(jobTitle, company, author, skills, companyLogo, careerID) {

    //creating elements
    let containerJob = $('<div>').addClass('rounded-md px-2 py-3 text-sm center-shadow flex gap-2 text-gray-500 cursor-pointer')
    let companyImg = $('<img>').addClass('h-12 w-12 rounded-full').attr('src', companyLogo)
    let jobDescription = $('<div>').addClass('flex-grow flex flex-col')
    let jobTitleElement = $('<p>').text(jobTitle).addClass('text-accent font-bold')
    let companyName = $('<p>').text(company).addClass('text-xs')
    let postedCont = $('<div>').addClass('flex gap-1 text-xs')
    let postedText = $('<p>').text('Posted by:')
    let postedByElement = $('<p>').text(author).addClass('text-green-400 font-bold')
    let skillContainer = $('<div>').addClass('text-xs flex py-2')
    let bookmark = $('<span>').addClass("far fa-bookmark text-accent text-xl bookmark-icon")
    //retrieve all the skill and display in on a div to be included on the container
    skills.forEach(skill => {
      let skillElement = $('<span>').html('&#x2022; ' + skill)
      skillContainer.append(skillElement)
    })

    //put the element to their corresponding container
    postedCont.append(postedText, postedByElement);
    jobDescription.append(jobTitleElement, companyName, postedCont, skillContainer)
    containerJob.append(companyImg, jobDescription, bookmark)
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
  }

  function displaySelectedCareer(jobTitle, companyName, author, datePosted, companyLogo,
    description, skills, qualification, requirements) {
    let logo = imgFormat + companyLogo;

    //remove the past display
    $('#skillsContainer').empty();
    $('#requirements').empty();

    //displaying a particular job data
    $('#viewJobLogo').attr('src', logo)
    $('#viewJobTitle').text(jobTitle)
    $('#viewJobCompany').text(companyName)
    $('#viewJobAuthor').text(author)
    $('#viewJobDatePosted').text(datePosted)
    $('#jobDescript').text(description)
    $('#viewJobQuali').text(qualification)

    //get all the skills that a particular career has
    skills.forEach(skill => {
      skillVal = $('<p>').text(skill);

      //add it on the container
      $('#skillsContainer').append(skillVal);
    })

    //get all the requirements of this job
    requirements.forEach(requirement => {
      let list = $('<li>').text(requirement)
      $('#requirements').append(list);
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
          const requirements = parsedResponse.requirements[0];
          displaySelectedCareer(jobTitle, companyName, author, datePosted,
            companyLogo, description, skills, qualification, requirements)
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
  function getCurrentDate() {
    var today = new Date();
    var year = today.getFullYear();
    var month = String(today.getMonth() + 1).padStart(2, '0');
    var day = String(today.getDate()).padStart(2, '0');
    return year + '-' + month + '-' + day;
  }

  function getPreviousDate(daysToSubtract) {
    var today = new Date();
    today.setDate(today.getDate() - daysToSubtract);
    var year = today.getFullYear();
    var month = String(today.getMonth() + 1).padStart(2, '0');
    var day = String(today.getDate()).padStart(2, '0');
    return year + '-' + month + '-' + day;
  }

  var retrievalDate = getCurrentDate(); //to be change getCurrentDate()
  var noOfDaySubtract = 1;
  var maxRetrieve = 10;
  let dataRetrieved = 0;
  var stoppingPostRetrieval = 0;
  getPost()
  //retrieve post data
  function getPost() {
    let action = {
      action: 'readColPost',
      retrievalDate: retrievalDate, // to be change
      maxRetrieve: maxRetrieve
    }
    const formData = new FormData();
    formData.append('action', JSON.stringify(action));

    $.ajax({
      url: '../PHP_process/postDB.php',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: (response) => {
        $('#noAvailablePostMsg').addClass('hidden')
        //no data available for the day
        if (response == "none" && maxRetrieve != 0 && stoppingPostRetrieval != 30) {
          retrievalDate = getPreviousDate(noOfDaySubtract);
          getPost()
          noOfDaySubtract++ //if no more the day will be increasing to get the previous date
          stoppingPostRetrieval++
          console.log(retrievalDate)
        }
        else if (response != 'none') {
          console.log(response)
          const parsedResponse = JSON.parse(response); //parsed the json data

          //check for response
          if (parsedResponse.response == "Success") {

            const length = parsedResponse.username.length;
            for (let i = 0; i < length; i++) {
              //store data that retrieve
              const imgProfile = parsedResponse.profilePic[i];
              const fullname = parsedResponse.fullname[i];
              const username = parsedResponse.username[i];
              const images = parsedResponse.images[i];
              const caption = parsedResponse.caption[i];
              let date = parsedResponse.date[i];
              const likes = parsedResponse.likes[i];
              const comments = parsedResponse.comments[i];
              date = getFormattedDate(date) //formatted date for easy viewing of date

              displayPost(imgProfile, username, fullname, caption, images, date, likes, comments); //display the post on the container
            }

            dataRetrieved = length; // get how many data has been retrieve for that day
            maxRetrieve = maxRetrieve - dataRetrieved;
            if (maxRetrieve != 0) {
              retrievalDate = getPreviousDate(noOfDaySubtract);
              stoppingPostRetrieval = 0;
              getPost()
            } else maxRetrieve = 10;
          }
        }
        else $('#noAvailablePostMsg').removeClass('hidden');

      },
      error: (error) => { console.log(error) }
    })


  }


  function insertPrevPost(date, timestamp) {
    let action = {
      action: 'insertPrevPost',
      date: date,
      timestamp: timestamp,
    }

    const formData = new FormData();
    formData.append('action', JSON.stringify(action));

    $.ajax({
      url: '../PHP_process/postDB.php',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: (response) => { console.log(response) },
      erro: (error) => { console.log(error) },
    })
  }

  function getFormattedDate(date) {
    //parts out the date
    let year = date.substring(0, 4);
    let dateMonth = parseInt(date.substring(5, 7));
    let day = date.substring(8, 10);

    const listOfMonths = ['', 'January', 'February', 'March', 'April', 'May',
      'June', 'July', 'August', 'September', 'October', 'November', 'December']
    let month = listOfMonths[dateMonth];

    return month + ' ' + day + ', ' + year
  }

  function displayPost(imgProfile, username, fullname, caption, images, date, likes, comments) {
    //creating a markup for post
    let postWrapper = $('<div>').addClass("postWrapper center-shadow w-10/12 p-4 rounded-md mx-auto")
    let header = $('<div>')
    let headerWrapper = $('<div>').addClass("flex gap-2 items-center")

    let img = imgFormat + imgProfile
    let userProfile = $('<img>').addClass("h-10 w-10 rounded-full").attr('src', img);
    let authorDetails = $('<div>').addClass("flex-1")
    let fullnameElement = $('<p>').addClass("font-bold text-greyish_black").text(fullname)
    let usernameElement = $('<p>').addClass("text-gray-400 text-xs").text(username)


    // header content
    authorDetails.append(fullnameElement, usernameElement);
    headerWrapper.append(userProfile, authorDetails)
    header.append(headerWrapper)

    //markup for body
    let description = $('<p>').addClass('text-sm text-gray-500 my-2').text(caption)
    let swiperContainer = $('<div>').addClass("swiper h-80 bg-black rounded-md")
    let swiperWrapper = $('<div>').addClass("swiper-wrapper")
    //add images
    images.forEach(image => {
      let postImg = imgFormat + image;

      //create slides for the image
      let slide = $('<div>').addClass("swiper-slide relative flex justify-center items-center")
      let imageContainer = $('<img>').addClass('object-contain h-full')
        .attr('src', postImg);

      slide.append(imageContainer);
      swiperWrapper.append(slide)
    })
    //navigation buttons
    let pagination = $('<div>').addClass("swiper-pagination")
    let prevBtn = $('<div>').addClass("swiper-button-prev")
    let nextBtn = $('<div>').addClass("swiper-button-next")

    swiperContainer.append(swiperWrapper, pagination, prevBtn, nextBtn);
    date_posted = $('<p>').addClass('text-xs text-gray-500 my-2').text(date);

    //interaction buttons
    let isLiked = false;
    let interactionContainer = $('<div>').addClass('border-t border-gray-400 p-2 flex items-center justify-between')
    let heartIcon = $('<span>').html('<iconify-icon icon="mdi:heart-outline" style="color: #626262;" width="20" height="20"></iconify-icon>')
      .addClass('cursor-pointer flex items-center')
      .on('click', function () {
        //toggle like button
        if (isLiked)
          heartIcon.html('<iconify-icon icon="mdi:heart-outline" style="color: #626262;" width="20" height="20"></iconify-icon>');
        else
          heartIcon.html('<iconify-icon icon="mdi:heart" style="color: #ed1d24;" width="20" height="20"></iconify-icon>');

        isLiked = !isLiked;
      });

    let commentIcon = $('<span>').html('<iconify-icon icon="uil:comment" style="color: #626262;" width="20" height="20"></iconify-icon>')
      .addClass('cursor-pointer flex items-center comment')
    let likesElement = $('<p>').addClass('text-xs text-gray-500').text(likes)
    let commentElement = $('<p>').addClass('text-xs text-gray-500 comment').text(comments)
    let leftContainer = $('<div>').addClass('flex gap-2 items-center').append(heartIcon, likesElement, commentIcon, commentElement)


    let reportElement = $('<p>').addClass('text-xs text-red-400 cursor-pointer ').text('report');
    interactionContainer.append(leftContainer, reportElement)
    //set up the details of the post
    postWrapper.append(header, description, swiperContainer, date_posted, interactionContainer)
    $('#feedContainer').append(postWrapper);

    new Swiper('.swiper', {
      effect: 'cards',
      // If we need pagination
      pagination: {
        el: '.swiper-pagination',
      },

      // Navigation arrows
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });

  }

  //add retrieve new data
  $('#feedContainer').on('scroll', function () {
    const containerHeight = $(this).height();
    const contentHeight = $(this)[0].scrollHeight;
    const scrollOffset = $(this).scrollTop();

    if (scrollOffset + containerHeight >= contentHeight) {
      console.log('rarr');
      //get another sets of post
    }
  })

});


// NOTIFICATIONS  
function toggleNotifications() {
  const notificationsTab = document.getElementById("notification-tab");
  notificationsTab.classList.toggle("hidden");
}

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
  //$("#mainFeed").removeClass("hidden");
  //$("#jobPostFeed").addClass("hidden");

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

// MODAL RELATED FUCNTIONS

// Open the modal
function openModal() {
  var modal = document.getElementById("postModal");
  modal.style.display = "block";
}

// Close the modal
function closeModal() {
  var modal = document.getElementById("postModal");
  modal.style.display = "none";
}

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

//RIGHT DIV - Carousel
let currentSlide = 0;
const slides = document.getElementsByClassName('carousel-slide');

function showSlide(index) {
  if (index < 0) {
    currentSlide = slides.length - 1;
  } else if (index >= slides.length) {
    currentSlide = 0;
  }
  for (let i = 0; i < slides.length; i++) {
    slides[i].classList.remove('opacity-100');
  }
  slides[currentSlide].classList.add('opacity-100');
}

function prevSlide() {
  showSlide(currentSlide - 1);
}

function nextSlide() {
  showSlide(currentSlide + 1);
}

showSlide(currentSlide); // Show the first slide initially


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

