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



    // close the event modal
    $('#eventModal').on('click', e => {
        const target = e.target
        const container = $('#eventContainer')

        if (!container.is(target) && !container.has(target).length) {
            $('#eventModal').addClass('hidden')
        }
    })
})