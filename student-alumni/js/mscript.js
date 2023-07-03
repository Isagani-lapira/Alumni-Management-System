//FOR MANIPULATING THE VISIBILITY OF THE MODAL
  const modalOpenBtn = document.getElementById('modal-openBtn');
  const modalOverlay = document.getElementById('overlay');
  const modalCloseBtn = document.getElementById('close');
  const modalSaveBtn = document.getElementById('done');

  // OPEN MODAL
  modalOpenBtn.addEventListener('click', () => {
    modalOverlay.classList.remove('hidden');
  });

  // CLOSE MODAL
  modalCloseBtn.addEventListener('click', () => {
    modalOverlay.classList.add('hidden');
  });

  // JUST TO CLOSE THE MODAL WHEN THE SAVE BUTTON IS CLICKED
  modalSaveBtn.addEventListener('click', () => {
    modalOverlay.classList.add('hidden');
  });










//SETTING IMAGE FOR PROFILE PICTURE OR COVER PHOTO
var editButtons = document.getElementsByClassName("editpic");

for (var i = 0; i < editButtons.length; i++) {
  editButtons[i].addEventListener('click', function() {
    var targetImageId = this.dataset.target;
    var targetImage = document.getElementById(targetImageId);

    var fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.accept = 'image/*';
    fileInput.directory = true;

    fileInput.addEventListener('change', function(event) {
      var selectedFile = event.target.files[0];

      if (selectedFile && selectedFile.type.startsWith('image/')) {
        var reader = new FileReader();

        reader.onload = function(e) {
          targetImage.src = e.target.result;
        };

        reader.readAsDataURL(selectedFile);

      } else {
        var profile = document.getElementById('profilePicAlert');
        var cover = document.getElementById('coverPicAlert');

        if (targetImageId === "dp") {
          profile.classList.remove('hidden');
        } else {
          cover.classList.remove('hidden');
        }
      }
    });

    fileInput.click();
  });
}










//FOR VIEWING IMAGE
  var modal = document.getElementById("myModal");
  var images = document.getElementsByClassName("images");
  var modalImg = document.getElementById("modalImage");

  for (var i = 0; i < images.length; i++) {
    images[i].onclick = function() {
      modalImg.src = this.src;

      modal.style.display = "block";
    };
  }

  var closeBtn = document.getElementsByClassName("close")[0];
  closeBtn.onclick = function() {
    modal.style.display = "none";
  };









//FOR EDITING TEXTS/DETAILS
function setCursorToEnd(element) {
  var range = document.createRange();
  var selection = window.getSelection();
  range.selectNodeContents(element);
  range.collapse(false);
  selection.removeAllRanges();
  selection.addRange(range);
}

const editBtns = document.querySelectorAll('.editBtn');
const cancelBtns = document.querySelectorAll('.cancelBtn');
const saveBtns = document.querySelectorAll('.saveBtn');
const displayTexts = document.querySelectorAll('.editText');
let originalTexts = [];

for (let i = 0; i < editBtns.length; i++) {
  const edit = editBtns[i];
  const cancel = cancelBtns[i];
  const save = saveBtns[i];
  const displayText = displayTexts[i];

  edit.addEventListener('click', () => {
    originalTexts[i] = displayText.innerText;
    displayText.contentEditable = true;
    displayText.focus();
    setCursorToEnd(displayText);
    edit.style.display = 'none';
    cancel.style.display = 'inline';
    save.style.display = 'inline';
  });

  save.addEventListener('click', () => {
    displayText.contentEditable = false;
    edit.style.display = 'inline';
    cancel.style.display = 'none';
    save.style.display = 'none';

    const updatedText = displayText.innerText;
    console.log('Original text:', originalTexts[i]);
    console.log('Updated text:', updatedText);
  });

  cancel.addEventListener('click', () => {
    displayText.contentEditable = false;
    displayText.innerText = originalTexts[i];
    edit.style.display = 'inline';
    cancel.style.display = 'none';
    save.style.display = 'none';
  });
}











//FOR BIRTHDAY
  const editBday = document.querySelector('.editBtnBday');
  const cancelBday = document.querySelector('.cancelBtnBday');
  const saveBday = document.querySelector('.saveBtnBday');
  const displayTextBday = document.querySelector('.editTextBday');
  let originalTextBday;

  editBday.addEventListener('click', () => {
    originalTextBday = displayTextBday.innerText;
    displayTextBday.contentEditable = true;
    editBday.style.display = 'none';
    cancelBday.style.display = 'inline';
    saveBday.style.display = 'inline';
  });

  saveBday.addEventListener('click', () => {
    displayTextBday.contentEditable = false;
    editBday.style.display = 'inline';
    cancelBday.style.display = 'none';
    saveBday.style.display = 'none';

    const updatedText = displayText.innerText;
    console.log('Original text:', originalText);
    console.log('Updated text:', updatedText);
  });

  let datePicker;

  editBday.addEventListener('click', () => {
    datePicker = document.createElement('input');
    datePicker.type = 'date';
    datePicker.classList.add('text-gray-600', 'px-2', 'py-1', 'border', 'border-gray-300', 'rounded', 'focus:outline-none', 'focus:border-blue-500');
    datePicker.style.width = '100%';

    const birthdateField = document.getElementById('birthdate');
    birthdateField.innerHTML = '';
    birthdateField.appendChild(datePicker);
  });

  cancelBday.addEventListener('click', () => {
    displayTextBday.contentEditable = false;
    displayTextBday.innerText = originalTextBday;
    editBday.style.display = 'inline';
    cancelBday.style.display = 'none';
    saveBday.style.display = 'none';
  });  

  saveBday.addEventListener('click', () => {
    if (datePicker) {
      const birthdateField = document.getElementById('birthdate');
      const selectedDate = new Date(datePicker.value);
      const monthName = selectedDate.toLocaleString('default', { month: 'long' });
      const formattedDate = monthName + ' ' + selectedDate.getDate() + ', ' + selectedDate.getFullYear();
      birthdateField.innerText = formattedDate;
      birthdateField.innerHTML = '';
      birthdateField.innerText = formattedDate;
    }
  });









  
//FOR CONTACT NO.
  const editBtnCN = document.querySelector('.editBtnCN');
  const cancelBtnCN = document.querySelector('.cancelBtnCN');
  const saveBtnCN = document.querySelector('.saveBtnCN');
  const displayTextCN = document.querySelector('.editTextCN');
  let originalTextCN;
      
    editBtnCN.addEventListener('click', () => {
      originalTextCN = displayTextCN.innerText;
      if (displayTextCN.innerText === "Contact No.") {
        displayTextCN.innerText = ""; // Clear the content if it is "Contact No."
      }
      displayTextCN.contentEditable = true;
      displayTextCN.focus();
      setCursorToEnd(displayTextCN);
      editBtnCN.style.display = 'none';
      cancelBtnCN.style.display = 'inline';
      saveBtnCN.style.display = 'inline';
    });

    cancelBtnCN.addEventListener('click', () => {
      displayTextCN.contentEditable = false;
      displayTextCN.innerText = originalTextCN;
      editBtnCN.style.display = 'inline';
      cancelBtnCN.style.display = 'none';
      saveBtnCN.style.display = 'none';
    });    
          
    saveBtnCN.addEventListener('click', () => {
      displayTextCN.contentEditable = false;
      editBtnCN.style.display = 'inline';
      cancelBtnCN.style.display = 'none';
      saveBtnCN.style.display = 'none';
      
      const updatedTextCN = displayTextCN.innerText;
      console.log('Original text:', originalTextCN);
      console.log('Updated text:', updatedTextCN);
    });
      
          // Allow only numeric input
    displayTextCN.addEventListener('input', (event) => {
      const inputCN = event.target;
      inputCN.textContent = inputCN.textContent.replace(/[^0-9]/g, '');
    });