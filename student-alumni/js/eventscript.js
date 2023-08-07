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
                const headerPhrase = response.headerPhrase
                const eventName = response.eventName
                const eventDate = response.eventDate
                const about_event = response.about_event
                const contactLink = response.contactLink
                const eventPlace = response.eventPlace
                const eventStartTime = response.eventPlace
                const aboutImg = response.aboutImg
                const images = response.images
                const expectation = response.expectation

                displayEvent(headerPhrase, eventName, eventDate, about_event, contactLink, eventPlace, aboutImg, images, eventStartTime, expectation)
            },
            error: error => { console.log(error) }
        })
    }

    function displayEvent(headerPhrase, eventName, eventDate, about_event, contactLink, eventPlace, aboutImg, images, eventStartTime, expectation) {
        //display it on the content of event
        $('#headerEvent').html(headerPhrase)
        $('#eventName').html(eventName)
        $('#eventNameHeader').html(eventName)
        $('#aboutEvent').html(about_event)
        $('#connectURL').attr('href', contactLink)

        //set image for about event
        const imgSrc = imgFormat + aboutImg
        $('#aboutImg').attr('src', imgSrc);
        $('#eventDate').html(eventDate)
        $('#eventPlace').html(eventPlace)
        $('#eventStartTime').html(eventStartTime)

        // set up the carousel
        images.forEach(element => {
            const swiperSlider = $('<div>').addClass("swiper-slide event-carousel flex justify-center")
            const imgElement = $('<img>').addClass("rounded-md");
            const carouselImg = imgFormat + element
            imgElement.attr('src', carouselImg); //set source of image

            swiperSlider.append(imgElement)
            $('#swiperWrapperEvent').append(swiperSlider) // append to the root container
        });

        const expectationLength = expectation.expectation.length;
        $('#expectContainer').empty();
        for (let i = 0; i < expectationLength; i++) {
            const description = expectation.expectation[i];
            const imgSrc = imgFormat + expectation.sampleImg[i];

            //create a markup for expectation
            const wrapper = $('<div>').addClass('rounded-md center-shadow text-sm bg-white w-72 p-3 text-gray-600 text-justify')
            const img = $('<img>').addClass("h-36 w-full object-contain bg-gray-300 rounded-t-md my-2")
                .attr('src', imgSrc)
            const descriptElement = $('<p>').text(description)

            wrapper.append(img, descriptElement)
            $('#expectContainer').append(wrapper)
        }
    }


    // close the event modal
    $('#eventModal').on('click', e => {
        const target = e.target
        const container = $('#eventContainer')

        if (!container.is(target) && !container.has(target).length) {
            $('#eventModal').addClass('hidden')
        }
    })
})