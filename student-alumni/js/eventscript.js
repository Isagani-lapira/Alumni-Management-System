$(document).ready(function () {
    const imgFormat = "data:image/jpeg;base64,"
    var swiper = new Swiper(".mySwiper", {
        effect: "cards",
        pagination: {
            el: ".swiper-pagination",
        },
        autoplay: {
            delay: 2500,
            disableOnInteraction: false,
        },
        grabCursor: true,
    });

    function getCurrentDate() {
        var today = new Date();
        var year = today.getFullYear();
        var month = String(today.getMonth() + 1).padStart(2, '0');
        var day = String(today.getDate()).padStart(2, '0');
        return year + '-' + month + '-' + day;
    }
    const currentDate = getCurrentDate();
    let action = "readEvent";
    let formatData = new FormData();
    formatData.append('action', action);
    formatData.append('currentDate', currentDate);

    //after opening the event tab the process only begins retrieving
    $('#eventLI').on('click', function () {
        getEvent();
    })
    function getEvent() {
        $.ajax({
            method: "POST",
            url: '../PHP_process/event.php',
            data: formatData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                console.log(response)
                const headerPhrase = response.headerPhrase
                const eventName = response.eventName
                const eventDate = response.eventDate
                const date_posted = response.date_posted
                const about_event = response.about_event
                const contactLink = response.contactLink
                const when_where = response.when_where
                const aboutImg = response.aboutImg
                const images = response.images

                displayEvent(headerPhrase, eventName, eventDate, about_event, contactLink, when_where, aboutImg, images)
            },
            error: error => { console.log(error) }
        })
    }

    function displayEvent(headerPhrase, eventName, eventDate, about_event, contactLink, when_where, aboutImg, images) {
        //display it on the content of event
        $('#headerEvent').html(headerPhrase)
        $('#eventName').html(eventName)
        $('#eventNameHeader').html(eventName)
        $('#aboutEvent').html(about_event)
        $('#connectURL').attr('href', contactLink)

        //set image for about event
        const imgSrc = imgFormat + aboutImg
        $('#aboutImg').attr('src', imgSrc);
        $('#whenWhere').html(when_where)

        // set up the carousel
        images.forEach(element => {
            const swiperSlider = $('<div>').addClass("swiper-slide flex justify-center")
            const imgElement = $('<img>').addClass("rounded-md");
            const carouselImg = imgFormat + element
            imgElement.attr('src', carouselImg); //set source of image

            swiperSlider.append(imgElement)
            $('#swiperWrapperEvent').append(swiperSlider) // append to the root container
        });

    }
})