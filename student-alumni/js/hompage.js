
$(document).ready(function () {
  $('#tabs').tabs();

  // Initialize the tabs - FEED BTN
  $("#tabs-feed-btns").tabs();


  $("#job-offer-tabs").tabs();
  
  // When hovering at JOB HUNT IT WILL CHANGE COLOR AND IMG
  $('#jobHuntLink').hover(
    function () {
      $(this).find('img').attr('src', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAXElEQVR4nGNgoBFoY2Bg+MnAwPAfikHsFlIM+MnAwCCExAexf5Bi438CGMNFP9FsJAQwXPSfBM1Y9fynpgH/cRiILo5hwH8y8CANA2LBf6qmgxYyUmIzCRbSEAAAQmtC/bx4InAAAAAASUVORK5CYII=');
      $(this).find('span').css('color', 'orange');
    },
    function () {
      $(this).find('img').attr('src', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAX0lEQVR4nO2UQQqAMAwE93kWny1+xPqPKT0KtiqJFDRz3bADe4gUeECDpyUzsGMnA+lMUAMvtuYMpi3V6QnBDycCVmC5UXi4uxR4oWECGeHTgvz2s0s18CgHJusSgdwoBMOg0T9wg1YAAAAASUVORK5CYII=');
      $(this).find('span').css('color', 'white');
    }
  );

  $('#jobHuntLink').click(function () {
    $(this).find('img').attr('src', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAXElEQVR4nGNgoBFoY2Bg+MnAwPAfikHsFlIM+MnAwCCExAexf5Bi438CGMNFP9FsJAQwXPSfBM1Y9fynpgH/cRiILo5hwH8y8CANA2LBf6qmgxYyUmIzCRbSEAAAQmtC/bx4InAAAAAASUVORK5CYII=');
  });

  // When hovering at EVENTS IT WILL CHANGE COLOR AND IMG
  $('#eventsLink').on('mouseenter', function () {
    $(this).find('img').attr('src', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAAA+0lEQVR4nOXUP0rEQBTH8Y8itot6CrXzFmthJzZ6BsvtjI13sNNSj+ANBA8gWGopHkBWiAy8QHBnQ7I7/gF/8CDMvPl9fy8Jwy+qjirV9zOAfby0DtUDK50ddwGWMa+jnvuMu2z9A8Ciqv88YBMn3wk4wxNWSwJ2cIoLvMb+NSY4KAFYx1XmT0mTbJcANLpr7b9jw6zS3n1mvRfgEW94iJ69TE8yH8Xz7hDACJfYwgqOo3J9SUeYohoyQVvZdF/Mk8/5IoAJPuZMcNgyn0nfdV3fYi16qlibRtpeyRuN4z7PQW46IL3Mh6pqQYqbN0rfo5kw+85LKKWem/wTnczENy8QTkQAAAAASUVORK5CYII=');
    $(this).find('#eventsText').css('color', 'orange');
  }).on('mouseleave', function () {
    $(this).find('img').attr('src', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAqUlEQVR4nN3UQQrCMBCF4aw9hLj1JuodXCkU9FzeSlzYa2g/kaZQxGpp2kp9EEiYyfyZR5IQ/laiQk954wCwQa67cqw/AVKKV7r2026X/aMBUhV+DghDWxTaFdti0QsAs5d1hgKXeqwTAHOccYzrPe4RcEjuQGlFEcepNs/aOvA1Abt4au9OngyoWXOrrBrkFmHZuLmMF9N5B10Bg3/X62dCSnGsGgGT1ANaBPWW0/Q4dwAAAABJRU5ErkJggg==');
    $(this).find('#eventsText').css('color', 'white');
  });

  // When hovering at FEED IT WILL CHANGE COLOR AND IMG
  $('#feedLink').on('mouseenter', function () {
    $(this).find('img').attr('src', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAtUlEQVR4nO3SP2oCQRhA8Z8WYitYS5qUYqFXsPMUdt4iV8gd0uUQAcXS1kYrEa3FToSRwAjLsv7dBQv3wYMphvcxH8M70cIs+n8ulDZWCNENukXF+9gl4mf3GOSND3HIiJ89YvRMuIKvK+G036jeG6/h54F4iP6ifivewN8T8RCdonkp/oF5jniILvCZjnewLiAeolv0kgOWBcZD4iWZ5A3f5GUD0pQDlCu6yCTjh4xz3CtxlRNLzeteM7X5aAAAAABJRU5ErkJggg==');
    $(this).find('#feedText').css('color', 'orange');
  }).on('mouseleave', function () {
    $(this).find('img').attr('src', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAAA5ElEQVR4nO3SMUoDURDG8aewaQSbgNYW2tnoDaz0CrmEYGObK3iHnMFKWy30AhamUrAVbSz0J4sT8lgSdjeriJgPpnnzzX/ezHsp/SvhpIyfAK9gaKozrH4XvIdRgN8ixFmvK3wN5wF8wSEO8Bxnl1hfFN7HdYCesJfldvEQuVtstoVv4S4A99iu8Yyx0xRe3u4xCm+wUTPlVTblfh083+9Fk/1W3ukVR/OMg+yHlCoajfxVW2R1JWNQNRzjPTNpCp8or8UHTtMsdW2QFjGao7/XILXwLBt0X9EspRae32uwVKroE2OChkKsj2YoAAAAAElFTkSuQmCC');
    $(this).find('#feedText').css('color', 'white');
  });  

   // Drop down PROFILE AND LOGOUT
   $('#dropdown-btn').click(function() {
    $('#dropdown-content').toggle();
    $(this).toggleClass('active');
  });

  // // Notification
  // $('#notification-content').hide(); // Hide the notification tab initially

  // $('#notif-btn').click(function() {
  //   $('#notification-content').toggle('fast'); // Show or hide the notification tab with a fast animation
  // });

  //MODAL
  // When either of the buttons is clicked, show the modal
  $('#writeBtn, #postButton').click(function() {
    $('#modal').removeClass('hidden');
  });

  // When the cancel button or the modal overlay is clicked, hide the modal
  $('.cancel, #modal').click(function() {
    $('#modal').addClass('hidden');
  });

  // Prevent closing the modal when clicking inside it
  $('.modal-container').click(function(e) {
    e.stopPropagation();
  }); 

  $("#yearbookButton").click(function() {
    $("#tabs-yrbook").show();
    $("#tabs-1").hide();
  });

  $("#feedLink").click(function() {
    $("#tabs-yrbook").hide();
    $("#tabs-1").show();
    $("#tabs-college").show();

  });
  
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

  var icon = document.querySelector("#verif-btn .fa-check-circle");
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
  bookmarkIcon.addEventListener('click', function() {
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

