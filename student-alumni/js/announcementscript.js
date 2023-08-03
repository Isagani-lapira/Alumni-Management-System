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

    let descriptionArr = [];
    getAnnouncement()
    function getAnnouncement() {
        const currentDate = getCurrentDate();
        let action = 'readAnnouncement'

        //data to be send to database
        const formdata = new FormData();
        formdata.append('action', action)
        formdata.append('currentDate', currentDate);

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
                        const univAdminID = response.univAdminID[i];
                        const date_posted = response.date_posted[i];
                        const headline_img = response.headline_img[i];

                        displayAnnouncement(headline_img, Descrip);
                    }
                }
            },
            error: error => { console.log(error) }
        })
    }

    //display announcement content as carousel
    function displayAnnouncement(headline_img, description) {

        // set up the markup for slides
        const swiper_slide = $('<div>').addClass('swiper-slide h-max')
        const imgSrc = imgFormat + headline_img
        const img = $('<img>').attr('src', imgSrc)
            .addClass('rounded-md object-contain bg-gray-300')

        let trimedDescription = description.substring(0, 200);
        const descriptElement = $('<p>')
            .addClass('text-sm text-gray-500')
            .text(trimedDescription)
        const viewDetails = $('<button>')
            .addClass('text-blue-400 text-xs italic block ml-auto')
            .text('View details')
        swiper_slide.append(img, descriptElement, viewDetails)
        $('#announcementWrapper').append(swiper_slide)

        console.log(descriptionArr)
        // set up swiper configuration
        var swiper = new Swiper(".announcementSwiper", {
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
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

    }
})