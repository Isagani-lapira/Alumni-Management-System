$(document).ready(function () {

  const imgFormat = 'data:image/jpeg;base64,'


  const availablePost = 'available'
  const deletedPost = 'deleted'
  let offsetPost = 0;
  var profileAction = {
    action: 'readUserProfile',
  }
  const formDataAvailable = new FormData()
  formDataAvailable.append('action', JSON.stringify(profileAction));
  formDataAvailable.append('status', availablePost);

  let actionTracker = formDataAvailable
  let typeTracker = false
  getPost(formDataAvailable, false)
  function getPost(data, isDeleted) {
    data.append('offset', offsetPost)
    console.log(offsetPost)
    $.ajax({
      url: '../PHP_process/postDB.php',
      method: 'POST',
      data: data,
      processData: false,
      contentType: false,
      success: (response) => {
        if (response != "none") {
          const parsedResponse = JSON.parse(response); //parsed the json data
          const length = parsedResponse.username.length;
          for (let i = 0; i < length; i++) {
            //store data that retrieve
            const imgProfile = parsedResponse.profilePic[i];
            const postID = parsedResponse.postID[i];
            const fullname = parsedResponse.fullname[i];
            const username = parsedResponse.username[i];
            const isLiked = parsedResponse.isLiked[i];
            const images = parsedResponse.images[i];
            const caption = parsedResponse.caption[i];
            let date = parsedResponse.date[i];
            const likes = parsedResponse.likes[i];
            const comments = parsedResponse.comments[i];
            date = getFormattedDate(date) //formatted date for easy viewing of date

            displayPost(imgProfile, username, fullname, caption, images, date, likes, comments, postID, isDeleted, isLiked); //display the post on the container
          }

          offsetPost += length

        } else {
          $('#noProfPostMsg').removeClass('hidden')
            .appendTo('#feedContainer')
        }
      },
      error: (error) => { console.log(error) },
    })
  }

  function restartPost() {
    //reset everything
    $('#noProfPostMsg').addClass('hidden')
    $('.postWrapper').remove() // remove the current displayed post
    offsetPost = 0
  }

  let isScrolled = false
  //display achieved post
  $('#archievedBtn').on('click', function () {
    $(this).addClass('text-white bg-accent').removeClass('text-gray-400')
    $('#userPost').removeClass('text-white bg-accent font-bold').addClass('text-gray-400')
    restartPost();

    //data to be change to be delivered to verify what action to be use
    formDataAvailable.set('status', deletedPost);
    actionTracker = formDataAvailable
    typeTracker = true;

    if (!isScrolled)
      getPost(formDataAvailable, true)
  })

  $('#userPost').on('click', function () {
    $(this).addClass('text-white bg-accent').removeClass('text-gray-400')
    $('#archievedBtn').removeClass('text-white bg-accent font-bold').addClass('text-gray-400')
    //display the post available post again
    restartPost()

    formDataAvailable.set('status', availablePost);
    actionTracker = formDataAvailable
    typeTracker = false;
    if (!isScrolled)
      getPost(formDataAvailable, false)
  })

  $('.closeReportModal').each(function () {
    $(this).on('click', closeReport)
  })
  // close the report modal
  function closeReport() {
    $('#delete-modal').addClass('hidden')
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


  function displayPost(imgProfile, username, fullname, caption, images, date, likes, comments, postID, isDeleted, isLikedByUser) {
    let postWrapper = $('<div>').addClass("postWrapper mb-2 center-shadow w-full p-4 rounded-md mx-auto");

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

      swiperContainer.append(swiperWrapper);

      // Navigation buttons
      if (images.length > 1) {
        let pagination = $('<div>').addClass("swiper-pagination");
        let prevBtn = $('<div>').addClass("swiper-button-prev");
        let nextBtn = $('<div>').addClass("swiper-button-next");
        swiperContainer.append(pagination, prevBtn, nextBtn);
      }

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
    let isLiked = isLikedByUser;
    let heartIcon = $('<span>')
    if (isLiked)
      heartIcon = heartIcon.html('<iconify-icon icon="mdi:heart" style="color: #ed1d24;" width="20" height="20"></iconify-icon>');
    else
      heartIcon = heartIcon.html('<iconify-icon icon="mdi:heart-outline" style="color: #626262;" width="20" height="20"></iconify-icon>')

    let interactionContainer = $('<div>').addClass('border-t border-gray-400 p-2 flex items-center justify-between')
    heartIcon.addClass('cursor-pointer flex items-center')
      .on('click', function () {
        //toggle like button
        if (isLiked) {
          //decrease the current total number of likes by 1
          newlyAddedLike -= 1
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
    let deletePost = $('<p>').addClass('text-sm  cursor-pointer ')

    if (isDeleted) {
      deletePost.addClass('text-green-400 ')
        .text('Restore')
        .on('click', function () {
          $('#restoreModal').removeClass('hidden')
          //when click the restore button then process the restoration
          $('#restorePost').on('click', function () {
            const status = 'available'
            updatePostStatus(status, postID)
            isScrolled = false;
          })
        })
    }
    else {
      deletePost.addClass('text-red-400 ')
        .text('Delete')
        .on('click', function () {
          //update the status of the post into delete
          //open the delete prompt
          $('#delete-modal').removeClass('hidden')
          //update the post status into deleted
          $('#deletePostbtn').on('click', function () {
            const status = 'deleted'
            updatePostStatus(status, postID)
            isScrolled = false;
          })
        })
    }
    interactionContainer.append(leftContainer, deletePost)

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

  function updatePostStatus(status, postID) {
    let action = { action: 'updatePostStatus' };
    const formdata = new FormData()
    formdata.append('action', JSON.stringify(action));
    formdata.append('postID', postID);
    formdata.append('status', status);

    //process the deletion
    $.ajax({
      url: '../PHP_process/postDB.php',
      method: 'POST',
      data: formdata,
      processData: false,
      contentType: false,
      success: response => {
        //close the modal
        $('#delete-modal').addClass('hidden')
        restartPost()
        getPost(actionTracker, typeTracker) //reload the post again
        $('#restoreModal').addClass('hidden') //hide the modal again
      },
      error: error => { console.log(error) }
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
    var container = $(this);
    var scrollPosition = container.scrollTop();
    var containerHeight = container.height();
    var contentHeight = container[0].scrollHeight;
    var scrollThreshold = 40; // Adjust this threshold as needed

    isScrolled = true
    if (scrollPosition + containerHeight >= contentHeight - scrollThreshold) {
      getPost(actionTracker, typeTracker)
    }
  })


  //close the post modal view
  $('#closePostModal').on('click', function () {
    $('#viewingPost').addClass('hidden')
    $('#carousel-wrapper').empty()
    $("#carousel-indicators").empty();
  })

  function viewingOfPost(postID, name, accUN, description, images, likes) {
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
            const img = (parsedResponse.profile[i] == '') ? '../assets/icons/person.png' : imgFormat + parsedResponse.profile[i]

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


  // FOR POSTING 

  //close the post modal view
  $('#closePostModal').on('click', function () {
    $('#viewingPost').addClass('hidden')
    $('#carousel-wrapper').empty()
    $("#carousel-indicators").empty();
  })

  //open the post modal
  $('#postButton').on('click', function () {
    $('#modal').removeClass('hidden')
  })
  let validExtension = ['jpeg', 'jpg', 'png'] //only allowed extension
  let fileExtension
  //close modal
  $('.cancel').click(function () {
    prompt("#modal", false)

    //remove the images
    while (imgContPost.firstChild) {
      imgContPost.removeChild(imgContPost.firstChild)
      selectedFiles = [];
    }
    $('#TxtAreaAnnouncement').val('')
  })

  //show or close the prompt modal
  function prompt(id, openIt) {
    openIt == true ? $(id).removeClass('hidden') : $(id).addClass('hidden')
  }

  let imageSequence = 1;
  let selectedFiles = [];
  //add image to the modal
  $('#fileGallery').change(() => {

    $('#errorMsg').addClass('hidden') //always set the error message as hidden when changing the file
    $('#TxtAreaAnnouncement').addClass('h-5/6').removeClass('h-3/6')

    //file input
    var fileInput = $('#fileGallery')
    var file = fileInput[0].files[0] //get the first file that being select

    fileExtension = file.name.split('.').pop().toLowerCase() //getting the extension of the selected file
    //checking if the file is based on the extension we looking for
    if (validExtension.includes(fileExtension)) {
      var reader = new FileReader()
      selectedFiles.push(file); // Store the selected file in the array
      //new image element to be place on the  image container div
      const imageElement = document.createElement('img')

      const imgPlaceHolder = document.createElement('div')
      imgPlaceHolder.className = "relative"

      //for button x
      const xBtn = document.createElement('button')
      xBtn.innerHTML = 'X'
      xBtn.className = 'xBtn absolute h-5 w-5 top-0 text-center right-0 cursor-pointer rounded-full hover:bg-accent hover:text-white hover:font-bold'
      //remove the image
      xBtn.addEventListener('click', function (e) {
        var parent = e.target.parentNode
        var index = Array.from(parent.parentNode.children).indexOf(parent); //get a specific index which picture has been remove
        selectedFiles.splice(index, 1); // Remove the file from the selectedFiles array
        parent.parentNode.removeChild(parent)
      })

      // img element
      imageElement.className = 'flex-shrink-0 h-20 w-20 rounded-md m-2'
      imageElement.setAttribute('id', 'reservedPicture' + imageSequence) //to make sure every id is unique

      //add to its corresponding container
      imgPlaceHolder.appendChild(imageElement)
      imgPlaceHolder.appendChild(xBtn)
      imgContPost.appendChild(imgPlaceHolder)

      //assign the image path to the img element
      reader.onload = function (e) {
        $('#reservedPicture' + imageSequence).attr('src', e.target.result)
        $('#imgContPost').removeClass('hidden')
        $('#TxtAreaAnnouncement').addClass('h-3/6').removeClass('h-5/6') //make the text area smaller in height
        imageSequence++
      }

      reader.readAsDataURL(file)
    }
    else {
      $('#errorMsg').removeClass('hidden') //if the file is not based on the img extension we looking for
    }

  })

  //make a post
  $('#postBtn').on('click', function () {
    let formData = new FormData();
    let caption = $('#TxtAreaAnnouncement').val();
    let college = $('#colCode').html();

    let action = {
      action: 'insert',
    }
    formData.append('caption', caption);
    formData.append('college', college);
    formData.append('action', JSON.stringify(action));

    // Append each file individually to the FormData object
    for (let i = 0; i < selectedFiles.length; i++) {
      formData.append('files[]', selectedFiles[i]);
    }

    $.ajax({
      url: '../PHP_process/postDB.php',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: (response) => {
        console.log(response)
        $('#modal').hide();
        $('#promptMsg').removeClass('hidden')
        $('#message').text('Announcement successfully posted!')
        setTimeout(() => {
          $('#promptMsg').addClass('hidden')
        }, 4000)
        selectedFiles = [];
      },
      error: (error) => { console.log(error) }
    })
  })



  //open modal
  $('#modal-openBtn').on('click', function () {
    $('#profileModal').removeClass('hidden');
  })


  //update the user profile process
  let profileLbl = "";
  let profileBtn = "";

  //change picture if user edit it
  var profileImg = $("#profileImg");

  $('#profilePic').on('change', function () {
    let selectedFile = $(this).prop("files")[0];
    profileLbl = $('#profileLbl')
    profileBtn = $('#profileBtn')
    //change the current display profile to new profile
    if (selectedFile) {
      var reader = new FileReader();
      reader.onload = (e) => {
        profileImg.attr('src', e.target.result);
        profileBtn.removeClass('hidden')
        profileLbl.addClass('hidden');
      }
      reader.readAsDataURL(selectedFile)
    }

  })

  $('#saveProfile').on('click', function () {
    let newProfile = $('#profilePic').prop('files')[0];

    let action = {
      action: 'updateProfile',
      dataUpdate: 'profilepicture'
    }
    let profileBtn = $('#profileBtn')
    let label = $('#profileLbl')
    processImgUpdate(action, newProfile, profileBtn, label)
  })

  function processImgUpdate(action, img, confirmationCont, editIcon) {
    let formData = new FormData()
    formData.append('action', JSON.stringify(action))
    formData.append('imgSrc', img);

    $.ajax({
      url: '../PHP_process/person.php',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: (response) => {
        if (response == 'success') {
          confirmationCont.addClass('hidden')
          editIcon.removeClass('hidden');
        }
      },
      error: (error) => { console.log(error) }
    })
  }

  //change cover if the user edit it
  var coverImg = $("#coverImg");
  $('#coverPic').on('change', function () {
    let selectedFile = $(this).prop("files")[0];

    //change the current display profile to new profile
    if (selectedFile) {
      var reader = new FileReader();
      coverLbl = $('#coverLbl')
      coverBtn = $('#coverBtn')

      reader.onload = (e) => {
        coverImg.attr('src', e.target.result); //display the selected image
        coverBtn.removeClass('hidden') //show the save button
        coverLbl.addClass('hidden'); //remove the edit label
      }
      reader.readAsDataURL(selectedFile)
    }
  })

  $('#saveCover').on('click', function () {
    let newCoverPhoto = $('#coverPic').prop('files')[0];

    let action = {
      action: 'updateCover',
      dataUpdate: 'cover_photo'
    }
    let btnCont = $('#coverBtn')
    let label = $('#coverLbl')
    processImgUpdate(action, newCoverPhoto, btnCont, label)

  })

  //edit location
  $('#editAddLabel').on('click', function () {
    $('#editAddress').removeAttr('disabled') //allows to be edit
      .addClass('border-b border-gray-400')

    $('#locBtn').removeClass('hidden') //show the save button
    $('#editAddLabel').addClass('hidden'); //remove the edit label
  })

  $('#saveLocation').on('click', function () {
    let newAddress = $('#editAddress').val();

    let action = {
      action: 'updatePersonDetails',
      dataUpdate: 'address'
    }
    let btnCont = $('#locBtn')
    let label = $('#editAddLabel')
    processPersonalInfo(action, newAddress, btnCont, label)

  })


  //edit email address
  $('#editEmailLbl').on('click', function () {
    $('#editEmail').removeAttr('disabled') //allows to be edit
      .addClass('border-b border-gray-400')

    $('#emailBtn').removeClass('hidden') //show the save button
    $('#editEmailLbl').addClass('hidden'); //remove the edit label
  })

  $('#saveEmail').on('click', function () {
    let newEmail = $('#editEmail').val();

    let action = {
      action: 'updatePersonDetails',
      dataUpdate: 'personal_email'
    }
    let btnCont = $('#emailBtn')
    let label = $('#editEmailLbl')
    processPersonalInfo(action, newEmail, btnCont, label)

  })


  //edit contact Number
  $('#editContactLbl').on('click', function () {
    $('#editContact').removeAttr('disabled') //allows to be edit
      .addClass('border-b border-gray-400')

    $('#contactBtn').removeClass('hidden') //show the save button
    $('#editContactLbl').addClass('hidden'); //remove the edit label
  })

  $('#saveContact').on('click', function () {
    let newEmail = $('#editContact').val();

    let action = {
      action: 'updatePersonDetails',
      dataUpdate: 'contactNo'
    }
    let btnCont = $('#contactBtn')
    let label = $('#editContactLbl')
    processPersonalInfo(action, newEmail, btnCont, label)

  })


  //edit facebook
  $('#editFBLbl').on('click', function () {
    $('#editFacebook').removeAttr('disabled') //allows to be edit
      .addClass('border-b border-gray-400')

    $('#fbBtn').removeClass('hidden') //show the save button
    $('#editFBLbl').addClass('hidden'); //remove the edit label
  })

  $('#saveFB').on('click', function () {
    let newEmail = $('#editFacebook').val();

    let action = {
      action: 'updatePersonDetails',
      dataUpdate: 'facebookUN'
    }
    let btnCont = $('#fbBtn')
    let label = $('#editFBLbl')
    processPersonalInfo(action, newEmail, btnCont, label)

  })

  //edit instagram
  $('#editIGLbl').on('click', function () {
    $('#editInstagram').removeAttr('disabled') //allows to be edit
      .addClass('border-b border-gray-400')

    $('#igBtn').removeClass('hidden') //show the save button
    $('#editIGLbl').addClass('hidden'); //remove the edit label
  })

  $('#saveIG').on('click', function () {
    let newUN = $('#editInstagram').val();

    let action = {
      action: 'updatePersonDetails',
      dataUpdate: 'instagramUN'
    }
    let btnCont = $('#igBtn')
    let label = $('#editIGLbl')
    processPersonalInfo(action, newUN, btnCont, label)

  })

  //edit twitter
  $('#editTweetLbl').on('click', function () {
    $('#editTwitter').removeAttr('disabled') //allows to be edit
      .addClass('border-b border-gray-400')

    $('#tweetBtn').removeClass('hidden') //show the save button
    $('#editTweetLbl').addClass('hidden'); //remove the edit label
  })

  $('#saveTweet').on('click', function () {
    let newUN = $('#editTwitter').val();

    let action = {
      action: 'updatePersonDetails',
      dataUpdate: 'twitterUN'
    }
    let btnCont = $('#tweetBtn')
    let label = $('#editTweetLbl')
    processPersonalInfo(action, newUN, btnCont, label)

  })


  //edit linkedIn
  $('#editLinkedLbl').on('click', function () {
    $('#editLinked').removeAttr('disabled') //allows to be edit
      .addClass('border-b border-gray-400')

    $('#linkedBtn').removeClass('hidden') //show the save button
    $('#editLinkedLbl').addClass('hidden'); //remove the edit label
  })

  $('#saveLinkedIn').on('click', function () {
    let newUN = $('#editLinked').val();

    let action = {
      action: 'updatePersonDetails',
      dataUpdate: 'linkedInUN'
    }
    let btnCont = $('#linkedBtn')
    let label = $('#editLinkedLbl')
    processPersonalInfo(action, newUN, btnCont, label)

  })

  function processPersonalInfo(action, value, confirmationCont, editIcon) {
    let formData = new FormData()
    formData.append('action', JSON.stringify(action))
    formData.append('value', value);

    $.ajax({
      url: '../PHP_process/person.php',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: (response) => {
        if (response == 'success') {
          confirmationCont.addClass('hidden')
          editIcon.removeClass('hidden');
        }
      },
      error: (error) => { console.log(error) }
    })
  }


  //close the modal
  $('#profileModal').on('click', function (event) {
    const target = event.target
    const formUpdate = $('.formUpdate')

    //clicked outside the edit modal
    if (!formUpdate.is(target) && !formUpdate.has(target).length) {
      $(this).addClass('hidden')
    }
  })


  //close restore modal
  $('#closeRestore, #closeRestoreModal').on('click', function () {
    $('#restoreModal').addClass('hidden');
  });
})
