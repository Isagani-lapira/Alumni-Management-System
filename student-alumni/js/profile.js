function toggleLike() {
  var likeIcon = document.getElementById('likeIcon');
  likeIcon.classList.toggle('fas'); // Toggle the 'fas' class (solid heart)
  likeIcon.classList.toggle('far'); // Toggle the 'far' class (regular heart)
  likeIcon.classList.toggle('text-red-600'); // Toggle the color to red
}

function toggleComment() {
  var commentIcon = document.getElementById('commentIcon');
  commentIcon.classList.toggle('fas'); // Toggle the 'fas' class (solid comment)
  commentIcon.classList.toggle('far'); // Toggle the 'far' class (regular comment)
  commentIcon.classList.toggle('text-blue-600'); // Toggle the color to blue
}

// Get DOM elements
const modalOpenBtn = document.getElementById('modal-openBtn');
const modalCancelBtn = document.getElementById('modal-cancelBtn');
const modalSaveBtn = document.getElementById('modal-saveBtn');
const profileModal = document.getElementById('profileModal');

// Add event listeners
modalOpenBtn.addEventListener('click', () => {
  profileModal.classList.remove('hidden');
});

modalCancelBtn.addEventListener('click', () => {
  profileModal.classList.add('hidden');
});

modalSaveBtn.addEventListener('click', () => {
  // Add logic here to save changes and close the modal
  profileModal.classList.add('hidden');
});


// Function to handle the click event for "Edit Profile Picture" button
document.getElementById('editProfilePicBtn').addEventListener('click', function () {
  document.getElementById('profilePicInput').click(); // Trigger file input click event
});

// Function to handle the click event for "Edit Cover Photo" button
document.getElementById('editCoverPhotoBtn').addEventListener('click', function () {
  document.getElementById('coverPhotoInput').click(); // Trigger file input click event
});

// Function to handle the change event for profile picture file input
document.getElementById('profilePicInput').addEventListener('change', function (event) {
  const file = event.target.files[0];
  if (file) {
    // Handle the selected profile picture file (e.g., display preview, upload, etc.)
    const reader = new FileReader();
    reader.onload = function () {
      document.getElementById('profilePic').src = reader.result;
    };
    reader.readAsDataURL(file);
  }
});

// Function to handle the change event for cover photo file input
document.getElementById('coverPhotoInput').addEventListener('change', function (event) {
  const file = event.target.files[0];
  if (file) {
    // Handle the selected cover photo file (e.g., display preview, upload, etc.)
    const reader = new FileReader();
    reader.onload = function () {
      document.getElementById('coverPhoto').src = reader.result;
    };
    reader.readAsDataURL(file);
  }
});

// Function to switch to edit mode
function switchToEditMode(textElement, inputElement) {
  textElement.classList.add('hidden');
  inputElement.classList.remove('hidden');
  inputElement.value = textElement.textContent.trim();
  inputElement.focus();
}

// Function to switch to view mode
function switchToViewMode(textElement, inputElement) {
  textElement.classList.remove('hidden');
  inputElement.classList.add('hidden');
  textElement.textContent = inputElement.value.trim();
}

// Edit Location Button
const editLocationBtn = document.getElementById('editLocationBtn');
const locationText = document.getElementById('locationText');
const locationInput = document.getElementById('locationInput');

editLocationBtn.addEventListener('click', function () {
  switchToEditMode(locationText, locationInput);
});

locationInput.addEventListener('blur', function () {
  switchToViewMode(locationText, locationInput);
});

// Edit Email Button
const editEmailBtn = document.getElementById('editEmailBtn');
const emailText = document.getElementById('emailText');
const emailInput = document.getElementById('emailInput');

editEmailBtn.addEventListener('click', function () {
  switchToEditMode(emailText, emailInput);
});

emailInput.addEventListener('blur', function () {
  switchToViewMode(emailText, emailInput);
});

// Edit Contact Button
const editContactBtn = document.getElementById('editContactBtn');
const contactText = document.getElementById('contactText');
const contactInput = document.getElementById('contactInput');

editContactBtn.addEventListener('click', function () {
  switchToEditMode(contactText, contactInput);
});

contactInput.addEventListener('blur', function () {
  switchToViewMode(contactText, contactInput);
});


// Function to toggle between displaying text and input field
function toggleEdit(sectionId) {
  const textElement = document.getElementById(sectionId + "Text");
  const inputElement = document.getElementById(sectionId + "Input");
  if (textElement.style.display === "none") {
    textElement.style.display = "inline";
    inputElement.style.display = "none";
  } else {
    textElement.style.display = "none";
    inputElement.style.display = "inline";
    inputElement.value = textElement.textContent;
    inputElement.focus();
  }
}

// Add event listeners for the "Edit" buttons
document.getElementById("editFacebookBtn").addEventListener("click", function () {
  toggleEdit("facebook");
});

document.getElementById("editInstagramBtn").addEventListener("click", function () {
  toggleEdit("instagram");
});

document.getElementById("editTwitterBtn").addEventListener("click", function () {
  toggleEdit("twitter");
});

// Add event listeners for saving the edited text
document.getElementById("facebookInput").addEventListener("blur", function () {
  const textElement = document.getElementById("facebookText");
  const inputElement = document.getElementById("facebookInput");
  textElement.textContent = inputElement.value;
  toggleEdit("facebook");
});

document.getElementById("instagramInput").addEventListener("blur", function () {
  const textElement = document.getElementById("instagramText");
  const inputElement = document.getElementById("instagramInput");
  textElement.textContent = inputElement.value;
  toggleEdit("instagram");
});

document.getElementById("twitterInput").addEventListener("blur", function () {
  const textElement = document.getElementById("twitterText");
  const inputElement = document.getElementById("twitterInput");
  textElement.textContent = inputElement.value;
  toggleEdit("twitter");
});

const notificationsLink = document.getElementById('notificationsLink');
const notificationsDropdown = document.getElementById('notificationsDropdown');

notificationsLink.addEventListener('click', function (event) {
  event.preventDefault();
  notificationsDropdown.classList.toggle('hidden');
});

$(document).ready(function () {

  const imgFormat = 'data:image/jpeg;base64,'
  function getCurrentDate() {
    var today = new Date();
    var year = today.getFullYear();
    var month = String(today.getMonth() + 1).padStart(2, '0');
    var day = String(today.getDate()).padStart(2, '0');
    return year + '-' + month + '-' + day;
  }

  //for retrieving recent date
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
  let stoppingPoint = 0;
  let maxRetrieve = 10;
  let dataRetrieved = 0;
  getPost()
  function getPost() {
    let action = {
      action: 'readUserPost',
      retrievalDate: retrievalDate, // to be change
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
        if (response == "none" && stoppingPoint <= 20 && maxRetrieve != 0) {
          retrievalDate = getPreviousDate(noOfDaySubtract); //get the previous dates
          console.log(retrievalDate)
          getPost();
          noOfDaySubtract++;
          stoppingPoint++;
        }
        else if (response != "none") {
          const parsedResponse = JSON.parse(response); //parsed the json data
          //check for response
          if (parsedResponse.response == "Success") {
            const length = parsedResponse.username.length;
            for (let i = 0; i < length; i++) {
              //store data that retrieve
              const imgProfile = parsedResponse.profilePic[i];
              const postID = parsedResponse.postID[i];
              const fullname = parsedResponse.fullname[i];
              const username = parsedResponse.username[i];
              const images = parsedResponse.images[i];
              const caption = parsedResponse.caption[i];
              let date = parsedResponse.date[i];
              const likes = parsedResponse.likes[i];
              const comments = parsedResponse.comments[i];
              date = getFormattedDate(date) //formatted date for easy viewing of date

              displayPost(imgProfile, username, fullname, caption, images, date, likes, comments, postID); //display the post on the container
            }

            dataRetrieved += length; // get how many data has been retrieve for that day
            console.log(dataRetrieved)
            if (dataRetrieved <= 10) {
              retrievalDate = getPreviousDate(noOfDaySubtract);
              stoppingPostRetrieval = 0;
              getPost()
            }
            //if it doesnt reach 10 post for a specific date it will get another set of
          }
        }
        else console.log('stopping point');
      },
      error: (error) => { console.log(error) },
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


  function displayPost(imgProfile, username, fullname, caption, images, date, likes, comments, postID) {
    let postWrapper = $('<div>').addClass("postWrapper center-shadow w-full p-4 rounded-md mx-auto");

    let header = $('<div>');
    let headerWrapper = $('<div>').addClass("flex gap-2 items-center");
    let img = imgFormat + imgProfile;
    let userProfile = $('<img>').addClass("h-10 w-10 rounded-full").attr('src', img);
    let authorDetails = $('<div>').addClass("flex-1");
    let fullnameElement = $('<p>').addClass("font-bold text-greyish_black").text(fullname);
    let usernameElement = $('<p>').addClass("text-gray-400 text-xs").text(username);

    // Header content
    authorDetails.append(fullnameElement, usernameElement);
    headerWrapper.append(userProfile, authorDetails);
    header.append(headerWrapper);

    // Markup for body
    let description = $('<p>').addClass('text-sm text-gray-500 my-2').text(caption);
    let swiperContainer = null;

    // Check if there are images to display
    if (images.length > 0) {
      swiperContainer = $('<div>').addClass("swiper h-80 bg-black rounded-md cursor-pointer");
      let swiperWrapper = $('<div>').addClass("swiper-wrapper");

      // Add images
      images.forEach(image => {
        let postImg = imgFormat + image;

        // Create slides for the image
        let slide = $('<div>').addClass("swiper-slide relative flex justify-center items-center");
        let imageContainer = $('<img>').addClass('object-contain h-full w-full').attr('src', postImg);

        slide.append(imageContainer);
        swiperWrapper.append(slide);
      });

      // Navigation buttons
      let pagination = $('<div>').addClass("swiper-pagination");
      let prevBtn = $('<div>').addClass("swiper-button-prev");
      let nextBtn = $('<div>').addClass("swiper-button-next");

      swiperContainer.append(swiperWrapper, pagination, prevBtn, nextBtn);

      new Swiper('.swiper', {
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

      swiperContainer.on('click', function () {
        console.log('may problem')
        $('#viewingPost').removeClass("hidden");
        viewingOfPost(postID, fullname, username, caption, images, likes, img)
      })
    } else { postWrapper.css('min-height', '100px') }

    date_posted = $('<p>').addClass('text-xs text-gray-500 my-2').text(date);

    let newlyAddedLike = parseInt(likes);
    //interaction buttons
    let isLiked = false;
    let interactionContainer = $('<div>').addClass('border-t border-gray-400 p-2 flex items-center justify-between')
    let heartIcon = $('<span>').html('<iconify-icon icon="mdi:heart-outline" style="color: #626262;" width="20" height="20"></iconify-icon>')
      .addClass('cursor-pointer flex items-center')
      .on('click', function () {
        //toggle like button
        if (isLiked) {
          //decrease the current total number of likes by 1
          newlyAddedLike -= 1
          console.log(newlyAddedLike)
          likesElement.text(newlyAddedLike)
          heartIcon.html('<iconify-icon icon="mdi:heart-outline" style="color: #626262;" width="20" height="20"></iconify-icon>');
          removeLike(postID)
        }
        else {
          //increase the current total number of likes by 1
          newlyAddedLike += 1
          likesElement.text(newlyAddedLike)
          heartIcon.html('<iconify-icon icon="mdi:heart" style="color: #ed1d24;" width="20" height="20"></iconify-icon>');
          addLikes(postID)
        }

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

  }

  //add the likes to a post
  function addLikes(postID) {
    let action = {
      action: 'addLike',
    }

    const formData = new FormData();
    formData.append('action', JSON.stringify(action))
    formData.append('postID', postID);

    //process the adding of like
    $.ajax({
      url: '../PHP_process/likesData.php',
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: (response) => { console.log(response) },
      error: (error) => { console.log(error) }
    })
  }

  //add the likes to a post
  function removeLike(postID) {
    let action = {
      action: 'removeLike',
    }

    const formData = new FormData();
    formData.append('action', JSON.stringify(action))
    formData.append('postID', postID);

    //process the removal of like
    $.ajax({
      url: '../PHP_process/likesData.php',
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: (response) => { console.log(response) },
      error: (error) => { console.log(error) }
    })
  }

  //add retrieve new data
  $('#feedContainer').on('scroll', function () {
    const containerHeight = $(this).height();
    const contentHeight = $(this)[0].scrollHeight;
    const scrollOffset = $(this).scrollTop();

    //once the bottom ends, it will reach another sets of data (post)
    if (scrollOffset + containerHeight >= contentHeight) {
      console.log('umabot na')
    }
  })


  //close the post modal view
  $('#closePostModal').on('click', function () {
    $('#viewingPost').addClass('hidden')
    $('#carousel-wrapper').empty()
    $("#carousel-indicators").empty();
  })

  function viewingOfPost(postID, name, accUN, description, images, likes, imgProfile) {
    $('#profilePic').attr('src', imgProfile);
    $('#postFullName').text(name);
    $('#postUN').text(accUN);
    $('#noOfLikes').text(likes);
    $('#postDescript').text(description).addClass('text-sm my-2 text-gray-400');

    const carouselWrapper = $('#carousel-wrapper');
    const carouselIndicators = $('#carousel-indicators');

    let totalImgNo = images.length;

    //remove the navigation button when it is only 1
    if (totalImgNo === 1) {
      $('#btnPrev, #btnNext').addClass('hidden');
    } else {
      $('#btnPrev, #btnNext').removeClass('hidden');
    }

    //add image/s to the carousel
    images.forEach((image, index) => {
      let imageName = 'item-' + index;
      const item = $('<div>')
        .addClass('relative duration-700 ease-in-out h-full')
        .attr('data-carousel-item', '')
        .attr('id', imageName);

      const format = imgFormat + image;
      const img = $('<img>')
        .addClass('absolute inset-0 left-0 right-0 top-0 bottom-0 m-auto object-contain')
        .attr('src', format)
        .attr('alt', 'Carousel Image');

      if (index === 0) {
        item.removeClass('hidden'); // Show the first image
      } else {
        item.addClass('hidden'); // Hide the rest of the images
      }

      item.append(img);
      carouselWrapper.append(item);

      const indicator = $('<button>')
        .attr('type', 'button')
        .addClass('w-3 h-3 rounded-full')
        .attr('aria-current', index === 0 ? 'true' : 'false')
        .attr('aria-label', 'Slide ' + (index + 1))
        .attr('data-carousel-slide-to', index.toString());

      carouselIndicators.append(indicator);
    });

    //controller how to next image
    let currentImageDisplay = 0;
    $('#btnNext').on('click', function () {
      $('#item-' + currentImageDisplay).addClass('hidden'); // Hide the current image
      currentImageDisplay = (currentImageDisplay + 1) % totalImgNo; // Move to the next image
      $('#item-' + currentImageDisplay).removeClass('hidden'); // Show the next image
    });

    //controller how to previous image
    $('#btnPrev').on('click', function () {
      $('#item-' + currentImageDisplay).addClass('hidden'); // Hide the current image
      currentImageDisplay = (currentImageDisplay - 1 + totalImgNo) % totalImgNo; // Move to the previous image
      $('#item-' + currentImageDisplay).removeClass('hidden'); // Show the previous image
    });

    getComment(postID); //retrieve the comment if available

    //display all the person who likes a specific post
    $('#noOfLikes').hover(
      function () {
        //show the name of the one who likes the post
        getLikes(postID)
        $('#namesOfUser').show()
      },
      function () {
        $('#namesOfUser').hide().empty() //remove so that it the first one that added will not duplicate
      }
    )
  }


  //retrieving the comments
  function getComment(postID) {
    const action = {
      action: 'read',
      postID: postID
    }

    let formData = new FormData();
    formData.append('action', JSON.stringify(action));

    $.ajax({
      url: '../PHP_process/commentData.php',
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: (response) => {
        const parsedResponse = JSON.parse(response);
        $('#commentContainer').empty(); //remove the comment of the firstly view
        if (parsedResponse.result == 'Success') {
          const length = parsedResponse.commentID.length;
          $('#noOfComment').text(length) //set the number of comments
          $('#commentMsg').addClass('hidden')
          //display every comments
          for (let i = 0; i < length; i++) {
            const commentID = parsedResponse.commentID[i];
            const fullname = parsedResponse.fullname[i];
            const comment = parsedResponse.comment[i];
            const img = imgFormat + parsedResponse.profile[i];

            let commentContainer = $('<div>').addClass("flex gap-2 my-2")
            let imgProfile = $('<img>').addClass("h-8 w-8 rounded-full").attr('src', img);
            let commentDescript = $('<div>').addClass("bg-gray-300 rounded-md p-3 flex-grow text-sm flex flex-col gap-1 text-greyish_black");
            let commentor = $('<p>').text(fullname)
            let postComment = $('<p>').text(comment).addClass('text-xs text-gray-500');

            commentDescript.append(commentor, postComment);
            commentContainer.append(imgProfile, commentDescript)

            $('#commentContainer').append(commentContainer);
          }
        } else {
          let noCommentMsg = $('<p>').addClass('text-gray-500').text('No available comment')
          $('#commentContainer').append(noCommentMsg) //show no comment
        }
      },
      error: (error) => { console.log(error) }
    })
  }


  function getLikes(postID) {
    let action = {
      action: 'readLikes',
      postID: postID,
    }

    let formData = new FormData();
    formData.append('action', JSON.stringify(action))

    //process the data
    $.ajax({
      url: '../PHP_process/likesData.php',
      method: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: (response) => {
        const parsedResponse = JSON.parse(response);
        if (parsedResponse.result == 'Success') {
          let length = parsedResponse.fullname.length;
          //retrieve all the names of the people who likes a post
          for (let i = 0; i < length; i++) {
            let fullname = parsedResponse.fullname
            let p = $('<p>').text(fullname);
            $('#namesOfUser').append(p)
          }
        }
      },
      error: (error) => { console.log(error) }
    })
  }

})