//FOR SETTING IMAGE
const dpImage = document.getElementById('pic');
const choosePhotoBtn = document.getElementById('btn');

choosePhotoBtn.addEventListener('click', function() {
  const input = document.createElement('input');
  input.type = 'file';
  input.accept = 'image/*';

  input.addEventListener('change', function(event) {
    const file = event.target.files[0];

    if (file) {
      // Check if the file is an image
      if (file.type.startsWith('image/')) {
        const reader = new FileReader();

        reader.addEventListener('load', function() {
          dpImage.src = reader.result;
        });

        reader.readAsDataURL(file);
      } else {
        // Show an alert if the file is not an image
        const msg = document.getElementById('msg');

        msg.classList.remove('hidden');
      }
    }
  });

  input.click();
});


  // const addWEButton = document.getElementById('addWE');
  // const addWEButton2 = document.getElementById('addWE2');
  // const textarea = document.getElementById('we');

  // // Add event listener to the "AddWE" button
  // addWEButton.addEventListener('click', function() {
  //   // Create a new textarea element
  //   const newTextarea = document.createElement('textarea');
  //   newTextarea.className = 'text-sm w-1/5 ml-3 h-20 max-h-20 p-1 rounded-md border border-gray-500';
  //   newTextarea.style.width = '2.5in';
  //   newTextarea.required = true;

  //   // Append the new textarea after the existing textarea
  //   textarea.parentNode.insertBefore(newTextarea, textarea.nextSibling);
  // });

  
  const addWEButton = document.getElementById('addWE');
  const addWEButton2 = document.getElementById('addWE2');
  const textarea = document.getElementById('we');
  let clickCount = 0;
  let addWEButton2ClickCount = 0;
  
  // Add event listener to the "AddWE" button
  addWEButton.addEventListener('click', function() {
    clickCount++;
  
    if (clickCount === 2) {
      addWEButton.style.display = 'none';
      addWEButton2.style.display = 'inline';
    }
  
    // Create a new textarea element
    const newTextarea = document.createElement('textarea');
    newTextarea.className = 'text-sm w-1/5 ml-3 h-20 max-h-20 p-1 rounded-md border border-gray-500';
    newTextarea.style.width = '2.5in';
    newTextarea.required = true;
  
    // Append the new textarea after the existing textarea
    textarea.parentNode.insertBefore(newTextarea, textarea.nextSibling);
  });
  
  // Add event listener to the "AddWE2" button
  addWEButton2.addEventListener('click', function() {
    addWEButton2ClickCount++;
  
    // Create a new textarea element
    const newTextarea = document.createElement('textarea');
    newTextarea.className = 'text-sm w-1/5 mr-3 h-20 max-h-20 p-1 rounded-md border border-gray-500';
    newTextarea.style.width = '2.5in';
    newTextarea.required = true;
  
    // Append the new textarea to the secWE2 div
    const secWE2 = document.getElementById('secWE');
    secWE2.prepend(newTextarea);
  
    // Check if secWE2 has been clicked 3 times
    if (addWEButton2ClickCount === 3) {
      addWEButton2.style.display = 'none';
    }
  });
  
  


const skAdd = document.getElementById('skAdd');
let count = 0;

// Add event listener to the "Add" button
skAdd.addEventListener('click', function() {
  count++;
  const input = document.getElementById('sk1');
  const newInput = input.cloneNode(true);

  // Clear the value of the cloned input
  newInput.value = '';

  // Insert the cloned input below the existing input
  input.parentNode.insertBefore(newInput, input.nextSibling);

  // Check if the button has been clicked 9 times
  if (count === 9) {
    skAdd.style.display = 'none';
  }
});





// const doneButton = document.getElementById('done');

// doneButton.addEventListener('click', function() {
//   const destinationUrl = 'https://facebook.com';

//   window.location.href = destinationUrl;
// });

  

  
