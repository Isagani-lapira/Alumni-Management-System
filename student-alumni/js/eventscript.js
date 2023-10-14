$(document).ready(function () {
    const imgFormat = "data:image/jpeg;base64,"

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
                if (response.response == 'Success') {

                    const eventName = response.eventName
                    const eventDate = formatDate(response.eventDate)
                    const about_event = response.about_event
                    const eventPlace = response.eventPlace
                    const eventStartTime = response.eventPlace
                    const aboutImg = response.aboutImg
                    const expectation = response.expectation

                    // mark up for event
                    displayEvent(eventName, eventDate, about_event, eventPlace, aboutImg, eventStartTime, expectation)

                    // for avoiding duplication of entry
                    $('#upcomingColEvent').empty()
                    $('#upcomingAlumniEvent').empty()

                    // get the upcoming event
                    let colCode = $('#colCode').text();
                    let category = "col_event_alumni"
                    retrieveNextCollegeEvent(colCode); //for college
                    retrieveNextCollegeEvent("", category); //alumni
                }


            },
            error: () => {
                $('#eventView').addClass('hidden');
                $('#defaultEvent').removeClass('hidden')
            }
        })
    }

    function displayEvent(eventName, eventDate, about_event, eventPlace, aboutImg, eventStartTime, expectation) {
        $('#eventName').text(eventName)
        $('#eventStartDate').text(eventDate)
        $('#eventDescriptData').text(about_event)

        // for viewing in details
        $('#viewInDetailsEvent').on('click', function () {
            $('#eventModal').removeClass('hidden')
            seeEventDetails(eventName, about_event, eventDate, eventPlace, eventStartTime, aboutImg, expectation)
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

    function formatDate(inputDate) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        const date = new Date(inputDate);
        return date.toLocaleDateString(undefined, options);
    }

    function seeEventDetails(eventTitle, aboutEvent, eventDate, eventPlace, eventStartTime, headerImg, expectation) {
        //display the data
        $('#eventTitleModal').text(eventTitle)
        $('#eventDescript').text(aboutEvent)
        $('#eventDateModal').text(eventDate)
        $('#eventPlaceModal').text(eventPlace)
        $('#eventTimeModal').text(eventStartTime)

        // header
        let src = imgFormat + headerImg
        $('#headerImg').attr('src', src)
        $('#expectationList').empty() //remove the previously display list of expectation
        // show expectation
        const expectationData = expectation.expectation
        expectationData.forEach(value => {
            const wrapper = $('<div>')
                .addClass('flex gap-2 items-center text-gray-500')

            const bulletIcon = '<iconify-icon icon="fluent-mdl2:radio-bullet" style="color: #6c6c6c;"></iconify-icon>';
            const expectationElement = $('<p>')
                .addClass('text-sm')
                .text(value)
            wrapper.append(bulletIcon, expectationElement)

            $('#expectationList').append(wrapper)
        })
    }

    function retrieveNextCollegeEvent(colCode = "", categoryVal = "") {
        const action = "nextEvents";
        const formatData = new FormData();
        formatData.append('action', action)
        formatData.append('colCode', colCode)
        formatData.append('category', categoryVal)

        $.ajax({
            url: '../PHP_process/event.php',
            method: 'POST',
            data: formatData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                if (response.response = "Success") {
                    // for avoiding duplication of retrieval

                    let length = response.eventName.length;

                    for (let i = 0; i < length; i++) {
                        const eventName = response.eventName[i]
                        const eventDate = formatDate(response.eventDate[i])
                        const about_event = response.about_event[i].substring(0, 150)
                        const aboutImg = imgFormat + response.aboutImg[i]

                        const eventWrapper = $('<div>')
                            .addClass('rounded-md w-64 center-shadow')

                        const header = $('<div>')
                            .addClass('rounded-t-md relative')
                            .css({ 'background-color': '#495057' })

                        const img = $('<img>')
                            .addClass('w-full h-48 object-contain')
                            .attr('src', aboutImg)
                        const date = $('<span>')
                            .addClass('p-2 rounded-tr-md text-xs text-white bg-black absolute bottom-0 left-0')
                            .css({ 'background-color': '#A54500' })
                            .text(eventDate);

                        const body = $('<div>')
                            .addClass('p-3 rounded-b-md')

                        const name = $('<h3>').addClass('text-greyish_black font-bold').text(eventName);
                        const description = $('<p>').addClass('text-gray-400 text-xs text-justify').text(about_event);


                        header.append(img, date);
                        body.append(name, description)
                        eventWrapper.append(header, body)

                        if (colCode != "")
                            $('#upcomingColEvent').append(eventWrapper)
                        else
                            $('#upcomingAlumniEvent').append(eventWrapper)
                    }

                }
            },
            error: error => { console.log(error) }
        })
    }
})