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