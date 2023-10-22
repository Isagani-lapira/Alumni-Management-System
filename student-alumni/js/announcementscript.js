$(document).ready(function () {
    const imgFormat = "data:image/jpeg;base64,"
    var swiper = new Swiper(".announcementSwiper", {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        autoplay: {
            delay: 2500,
            disableOnInteraction: false
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });
    function getCurrentDate() {
        var today = new Date();
        var year = today.getFullYear();
        var month = String(today.getMonth() + 1).padStart(2, '0');
        var day = String(today.getDate()).padStart(2, '0');
        return year + '-' + month + '-' + day;
    }

    setTimeout(getAnnouncement, 6000)
    // setTimeout(getAnnouncement, 5000);
    function getAnnouncement() {
        const currentDate = getCurrentDate();
        let action = 'readAnnouncement'
        const maxLimit = 4;
        //data to be send to database
        const formdata = new FormData();
        formdata.append('action', action)
        formdata.append('currentDate', currentDate);
        formdata.append('maxLimit', maxLimit)

        //process retrieval
        $.ajax({
            url: '../PHP_process/announcement.php',
            method: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: response => {
                if (response.result == "Success") {
                    const length = response.announcementID.length;

                    //data that retrieved
                    for (let i = 0; i < length; i++) {

                        const announcementID = response.announcementID[i];
                        const title = response.title[i];
                        const Descrip = response.Descrip[i];
                        const fullname = response.fullname[i];
                        const date_posted = response.date_posted[i];
                        const headline_img = response.headline_img[i];

                        displayAnnouncement(announcementID, headline_img, title, date_posted, fullname, Descrip);
                    }
                }
            },
            error: error => { console.log(error) }
        })
    }

    //display announcement content as carousel
    function displayAnnouncement(announcementID, headline_img, title, date_posted, author, description) {

        // set up the markup for slides
        const swiper_slide = $('<div>').addClass('swiper-slide w-full h-auto')
        const imgSrc = imgFormat + headline_img
        const img = $('<img>').attr('src', imgSrc)
            .addClass('rounded-md object-contain bg-gray-300 max-h-80')

        let trimedDescription = description.substring(0, 200);
        const titleElement = $('<p>')
            .addClass('font-bold text-greyish_black text-lg')
            .text(title)
        const descriptElement = $('<p>')
            .addClass('text-sm text-gray-500')
            .text(trimedDescription)
        const viewDetails = $('<button>')
            .addClass('text-blue-400 text-xs italic block ml-auto')
            .text('View details')
            .on('click', function () {
                $('#announcementModal').removeClass('hidden')
                displayAnnouncementDetails(announcementID, imgSrc, date_posted, author, title, description)
            })
        swiper_slide.append(img, titleElement, descriptElement, viewDetails)
        $('#announcementWrapper').append(swiper_slide)

        // set up swiper configuration
        new Swiper(".announcementSwiper", {
            slidesPerView: 1,
            spaceBetween: 30,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });

    }

    // assign value in the announcement modal
    function displayAnnouncementDetails(ID, headline, date_posted, author, title, description) {
        $('#headline_img').attr('src', headline)
        $('#announceDatePosted').text(date_posted)
        $('#announcementAuthor').text(author)
        $('#announcementTitle').text(title)
        $('#announcementDescript').text(description)

        const action = "readImageOfAnnouncement";
        const formdata = new FormData();
        formdata.append('action', action);
        formdata.append('announcementID', ID)
        // retrieve images if theres any
        $.ajax({
            url: '../PHP_process/announcement.php',
            method: 'POST',
            data: formdata,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                //remove the previous display images
                $('#imagesWrapper').empty();

                if (response.result != "Nothing") {
                    $('#imagesContainer').removeClass('hidden') // show the container

                    // const image = response.images
                    const images = response.images

                    //display all the images
                    images.forEach(value => {
                        const imgSrc = imgFormat + value;//convert into base64
                        displayImages(imgSrc)
                    })

                } else $('#imagesContainer').addClass('hidden') //hide the container
            },
            error: error => { console.log(error) }
        })
    }

    function displayImages(imgSrc) {
        const imgElement = $('<img>')
            .addClass('w-40 object-contain bg-gray-500 rounded-md')
            .attr('src', imgSrc);

        console.log('rar')
        $("#imagesWrapper").append(imgElement);
    }

    //close the announcement modal
    $('#announcementModal').on('click', function (e) {
        const target = e.target
        let container = $('#announcementContainer')

        //check if the clicked is outside the container
        if (!container.is(target) && !container.has(target).length) {
            $('#announcementModal').addClass('hidden')
        }
    })

})