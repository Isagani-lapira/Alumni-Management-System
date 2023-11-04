$(document).on('ready', function () {

    $('#aoyLi').on('click', function () {
        $('#aomSelection').find('option:not([value=""])').remove() //back to default
        $('#aoyRecord').addClass('hidden')
        $('#aoydata').removeClass('hidden')

        let offset = 0;
        table.clear().draw() //remove the previously retrieved
        retrieveAOY(offset)

        let aoyCollege = $('#aoyCollege')
        let alumniCollege = $('#alumniCollege')
        // Copy all the options from the alumni college dropdown to the alumni of the year college
        $('#aoyCollege').empty()
        alumniCollege.find('option').each(function () {
            aoyCollege.append($(this).clone());
        })
    })
    function getAlumniOfTheMonth() {
        let action = "thisYearAOM";
        const formData = new FormData();
        formData.append('action', action)
        $.ajax({
            url: '../PHP_process/alumniofMonth.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: response => {
                if (response.response === "Success") {
                    // option to be shown in the alumni of the month selection
                    let length = response.names.length
                    for (let i = 0; i < length; i++) {
                        const name = response.names[i];
                        const personID = response.personID[i];
                        const aomID = response.aomID[i];
                        const colCode = response.colCode[i];
                        let option = $('<option>').val(aomID).text(name + ' - ' + colCode).attr('data-personid', personID);
                        $('#aomSelection').append(option)
                    }

                }

            }
        })
    }

    // display the selected alumni of the month
    $('#aomSelection').on('change', function () {
        const aomID = $(this).val()
        const action = "searchAlumniOfTheMonth";
        const personID = $(this).find('option:selected').attr('data-personid')
        const formData = new FormData();
        formData.append('action', action);
        formData.append('aomID', aomID)

        // retrieve selected alumni of the month
        $.ajax({
            url: '../PHP_process/alumniofMonth.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                if (response.response === 'Success') {
                    const data = response
                    const cover_img = imgFormat + data.cover;
                    const quotation = data.quote;
                    const fullname = data.fullname;
                    const colCode = data.colCode;
                    displaySelectedAOM(aomID, cover_img, quotation, fullname, personID, colCode)
                }
            }
        })
    })

    function displaySelectedAOM(aomID, cover_img, quotation, fullname, personID, colCode) {
        $('#aomCover').attr('src', cover_img).removeClass('hidden');
        $('.aomFullname').text(fullname);
        $('#aomQuotation').text(quotation);

        // retrieve the testimonials for this alumni of the month
        getTestimonials(aomID)
            .then(response => {
                if (response.response === 'Success') {
                    let length = response.message.length
                    $('.subtitle').removeClass('hidden')
                    $('.testimonyContainer').removeClass('hidden').empty();
                    // display all the testimonies
                    for (let i = 0; i < length; i++) {
                        const message = response.message[i]
                        const personName = response.personName[i]
                        const position = response.position[i]
                        const profile = imgFormat + response.profile[i]

                        // mark up for testimony
                        let wrapper = $('<div>').addClass('p-5 rounded-md center-shadow w-1/3 flex flex-col gap-3')
                        let messageElement = $('<p>').addClass('italic text-sm testimony flex-1 w-full').text(`" ${message} "`)
                        let personDetails = $('<div>').addClass('flex items-center gap-3')
                        let profileElement = $('<img>').attr('src', profile).addClass('w-8 h-8 rounded-full');
                        let information = $('<div>')
                        let nameElement = $('<p>').addClass('font-semibold text-sm').text(personName);
                        let positionElement = $('<p>').addClass('text-xs').text(position);

                        information.append(nameElement, positionElement)
                        personDetails.append(profileElement, information)
                        wrapper.append(messageElement, personDetails)
                        $('.testimonyContainer').append(wrapper) //root container
                    }
                }
            })

        // retrieve the achievements for this alumni of the month
        getAchievements(aomID)
            .then(response => {
                if (response.response === 'Success') {
                    $('.achievementsContainer').removeClass('hidden').empty();
                    let length = response.achievements.length;
                    for (let i = 0; i < length; i++) {
                        let achievement = response.achievements[i];
                        let description = response.description[i];
                        let date = response.date[i];
                        date = formatDate(date) //format date to easy to read


                        const achievementWrapper = $('<div>').addClass('w-1/3 p-3 text-center flex flex-col center-shadow text-sm rounded-md')
                        const achievementElement = $('<h2>').addClass('font-bold mb-5').text(achievement);
                        const dateElement = $('<span>').addClass('text-blue-500').text(date);
                        const descriptionElement = $('<p>').addClass('flex-1 italic mb-5').text(description);

                        achievementWrapper.append(achievementElement, descriptionElement, dateElement);
                        $('.achievementsContainer').append(achievementWrapper);
                    }
                }
            })

        // retrieve all the skills for this alumni of the month
        getSkills(aomID)
            .then(response => {
                if (response.response === 'Success') {
                    $('#skillContainer').empty()
                    const skills = response.skills
                    skills.forEach(skill => {
                        // mark up for skills
                        const wrapper = $('<div>').addClass('items-center flex gap-2')
                        const icon = $('<iconify-icon class="bg-blue-400 rounded-full p-1 text-white" icon="ant-design:check-outlined" width="10" height="10"></iconify-icon>');
                        const skillElement = $('<span>').text(skill);

                        wrapper.append(icon, skillElement);
                        $('#skillContainer').append(wrapper)
                    });
                }
            })

        getAOMSocMed(personID)
            .then(response => {
                $('#socMedContainer').parent().parent().removeClass('hidden')
                $('#socMedContainer').empty()
                let socialMedia = response
                let facebook = socialMedia.facebookUN
                let instagram = socialMedia.instagramUN
                let twitter = socialMedia.twitterUN
                let linkedIn = socialMedia.linkedInUN

                // for avoiding null value
                facebook = (facebook === null) ? 'None' : facebook
                instagram = (instagram === null) ? 'None' : instagram
                twitter = (twitter === null) ? 'None' : twitter
                linkedIn = (linkedIn === null) ? 'None' : linkedIn

                // social media logos
                const instagramLogo = $('<iconify-icon icon="skill-icons:instagram" width="20" height="20"></iconify-icon>')
                const facebookLogo = $('<iconify-icon icon="logos:facebook" width="20" height="20"></iconify-icon>')
                const twitterLogo = $('<iconify-icon icon="logos:twitter" width="20" height="20"></iconify-icon>')
                const linkedInLogo = $('<iconify-icon icon="devicon:linkedin" width="20" height="20"></iconify-icon>')

                const socMed = [facebook, instagram, twitter, linkedIn];
                const logos = [facebookLogo, instagramLogo, twitterLogo, linkedInLogo];

                for (let i = 0; i < socMed.length; i++) {
                    displaySocialMedia(socMed[i], logos[i], '#socMedContainer')
                }

            })

        isValidToAssign()
            .then(isValid => {
                if (isValid === '1') //allows only one time per year
                    $('.confirmAOY').parent().removeClass('hidden')
            })

        $('.assignAOY').on('click', function () {
            const reason = $('#reasonForAOY').val()

            if (reason !== '') {
                // assign new alumni of the year
                addAOY(aomID, personID, colCode, reason)
                $('#reasonForAOY').addClass('border-gray-400').removeClass('border-red-400').val('')

            }
            else
                $('#reasonForAOY').removeClass('border-gray-400').addClass('border-red-400')
        })
    }

    function displaySocialMedia(socMed, logo, container) {
        // mark up for social media
        const wrapper = $('<div>').addClass('items-center flex gap-2')
        const socMedElement = $('<span>').text(socMed);

        wrapper.append(logo, socMedElement);
        $(container).append(wrapper)
    }

    function getTestimonials(aomID) {
        const action = "getTestimonials";
        const formData = new FormData();
        formData.append('action', action);
        formData.append('aomID', aomID);

        return new Promise((resolve) => {
            $.ajax({
                url: '../PHP_process/alumniofMonth.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: response => {
                    resolve(response)
                }
            })
        })

    }

    function getAchievements(aomID) {
        const action = "getAchievements";
        const formData = new FormData();
        formData.append('action', action);
        formData.append('aomID', aomID);

        return new Promise((resolve) => {
            $.ajax({
                url: '../PHP_process/alumniofMonth.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: response => resolve(response)

            })
        })

    }

    function getSkills(aomID) {
        const action = "getSkills";
        const formData = new FormData();
        formData.append('action', action);
        formData.append('aomID', aomID);

        return new Promise((resolve) => {
            $.ajax({
                url: '../PHP_process/alumniofMonth.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: response => resolve(response)

            })
        })
    }

    function getAOMSocMed(personID) {
        const action = "getAOMSocMed";
        const formData = new FormData();
        formData.append('action', action);
        formData.append('personID', personID);

        return new Promise((resolve) => {
            $.ajax({
                url: '../PHP_process/alumniofMonth.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: response => resolve(response),
            })
        })
    }

    function formatDate(inputDate) {
        let date = new Date(inputDate);
        let options = { year: 'numeric', month: 'long', day: 'numeric' };
        let formattedDate = date.toLocaleDateString('en-US', options);

        return formattedDate
    }

    function restartSelectedAOM() {
        $('#aomCover').attr('src', '').addClass('hidden');
        $('#aomFullname').text('');
        $('.aomFullname').text('');
        $('#aomQuotation').text('');
        $('.testimonyContainer').empty().addClass('hidden');
        $('.achievementsContainer').empty();
        $('.skillContainer').empty();
        $('.socMedContainer').empty();

        $('.subtitle').addClass('hidden')
        $('#socMedContainer').parent().parent().addClass('hidden')
        $('.confirmAOY').addClass('hidden')

    }
    $('.confirmAOY').on('click', function () {
        $('.alumniOfYearModal').parent().removeClass('hidden')
    })

    $('.cancelAOY').on('click', function () {
        $('.alumniOfYearModal').parent().addClass('hidden')
    })

    function addAOY(aomID, personID, colCode, reason) {
        const action = "insertAOY";
        const formData = new FormData();
        formData.append('action', action);
        formData.append('aomID', aomID);
        formData.append('personID', personID);
        formData.append('colCode', colCode);
        formData.append('reason', reason);

        $.ajax({
            url: '../PHP_process/alumniOfYear.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: response => {
                if (response === 'Success') {
                    $('.alumniOfYearModal').parent().addClass('hidden')
                    $('#successModalAOY').removeClass('hidden')

                    setTimeout(function () {
                        $('#successModalAOY').addClass('hidden')
                        restartSelectedAOM()
                        $('#aoyLi').click()
                    }, 6000)
                }

            }
        })
    }

    function isValidToAssign() {
        const action = "checkForThisYearAOY";
        const formData = new FormData();
        formData.append('action', action);

        return new Promise((resolve) => {
            $.ajax({
                url: '../PHP_process/alumniOfYear.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: response => { resolve(response) }
            })
        })

    }


    const table = $('#AOYID').DataTable({
        "info": false,
        "pageLength": 12,
        "ordering": false,
        "paging": false,
    })


    $('#AOYID').removeClass('dataTable').addClass('rounded-lg')
    $('#AOYID_filter').addClass('w-full flex gap-2 items-center').prepend($('#aoyCollege'))

    function retrieveAOY(offset) {
        const action = "retrieveAOY";
        const formData = new FormData();
        formData.append('action', action);
        formData.append('offset', offset);

        $.ajax({
            url: '../PHP_process/alumniOfYear.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                if (response.response == 'Success') {
                    const length = response.year.length;

                    // add all the data retrieve to the table
                    for (let i = 0; i < length; i++) {
                        let aoyID = response.aoyID[i];
                        let aomID = response.aomID[i];
                        let fullname = response.fullname[i];
                        let collegeCode = response.colCode[i];
                        let reason = response.reason[i];
                        let personID = response.personID[i];
                        let year = response.year[i];

                        let row = [
                            fullname,
                            collegeCode,
                            year,
                            `<button class="bg-postButton hover:bg-postHoverButton text-white rounded-md px-4 py-2 view-button" data-personid="${personID}" data-aomid="${aomID}">View</button>`
                        ];
                        table.row.add(row).draw();
                    }

                    offset += length
                    if (length === 10) retrieveAOY(offset); //retrieve another set of alumni of the year

                }
            }
        })
    }


    $('#aoyCollege').on('change', function () {
        const college = $(this).val();
        filterTable(college); //filter data in the table
    })

    function filterTable(college) {
        if (college !== '') table.column(1).search(college).draw() //search particular college
        else table.column(1).search('').draw() //back to default view
    }

    $('#assigningAOYbtn').on('click', function () {
        $('#aoyRecord').removeClass('hidden')
        $('#aoydata').addClass('hidden')

        getAlumniOfTheMonth() //retrieve alumni of the month
    })

    // view the selected alumni of the year
    $('#AOYID').on('click', '.view-button', function () {
        const aomID = $(this).attr('data-aomid')
        const personID = $(this).attr('data-personid')
        const action = "searchAlumniOfTheMonth";
        const formData = new FormData();
        formData.append('action', action);
        formData.append('aomID', aomID)

        // retrieve selected alumni of the month
        $.ajax({
            url: '../PHP_process/alumniofMonth.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                if (response.response === 'Success') {
                    const data = response
                    const cover_img = imgFormat + data.cover;
                    const quotation = data.quote;
                    const fullname = data.fullname;
                    const colCode = data.colCode;

                    displayAOYModal(cover_img, quotation, fullname, colCode, aomID, personID)
                }
            }
        })
    })


    function displayAOYModal(cover_img, quotation, fullname, colCode, aomID, personID) {
        $('.coverImgAOY').attr('src', cover_img)
        $('.fullnameAOY').text(fullname)
        $('.quotationAOY').text("\"" + quotation + "\"")

        getTestimonials(aomID)
            .then(response => {
                if (response.response === 'Success') {
                    $('.testimonyWrapper').empty()
                    let length = response.message.length
                    // display all the testimonies
                    for (let i = 0; i < length; i++) {
                        const message = response.message[i]
                        const personName = response.personName[i]
                        const position = response.position[i]
                        const profile = imgFormat + response.profile[i]

                        // mark up for testimony
                        let wrapper = $('<div>').addClass('p-5 rounded-md center-shadow w-1/3 flex flex-col gap-3')
                        let messageElement = $('<p>').addClass('italic text-sm testimony flex-1 w-full').text(`" ${message} "`)
                        let personDetails = $('<div>').addClass('flex items-center gap-3')
                        let profileElement = $('<img>').attr('src', profile).addClass('w-8 h-8 rounded-full');
                        let information = $('<div>')
                        let nameElement = $('<p>').addClass('font-semibold text-sm').text(personName);
                        let positionElement = $('<p>').addClass('text-xs').text(position);

                        information.append(nameElement, positionElement)
                        personDetails.append(profileElement, information)
                        wrapper.append(messageElement, personDetails)
                        $('.testimonyWrapper').append(wrapper) //root container
                    }
                }
            })

        getAchievements(aomID)
            .then(response => {
                if (response.response === 'Success') {
                    $('.achievementWrapper').empty() //to avoid duplication of data
                    let length = response.achievements.length;
                    for (let i = 0; i < length; i++) {
                        let achievement = response.achievements[i];
                        let description = response.description[i];
                        let date = response.date[i];
                        date = formatDate(date) //format date to easy to read


                        const achievementWrapper = $('<div>').addClass('w-1/3 p-3 text-center flex flex-col center-shadow text-sm rounded-md')
                        const achievementElement = $('<h2>').addClass('font-bold mb-5').text(achievement);
                        const dateElement = $('<span>').addClass('text-blue-500').text(date);
                        const descriptionElement = $('<p>').addClass('flex-1 italic mb-5').text(description);

                        achievementWrapper.append(achievementElement, descriptionElement, dateElement);
                        $('.achievementWrapper').append(achievementWrapper);
                    }
                }
            })

        getSkills(aomID)
            .then(response => {
                if (response.response === 'Success') {
                    $('.skillWrapper').empty(); //avoid duplicate of data
                    const skills = response.skills
                    skills.forEach(skill => {
                        // mark up for skills
                        const wrapper = $('<div>').addClass('items-center flex gap-2')
                        const icon = $('<iconify-icon class="bg-blue-400 rounded-full p-1 text-white" icon="ant-design:check-outlined" width="10" height="10"></iconify-icon>');
                        const skillElement = $('<span>').text(skill);

                        wrapper.append(icon, skillElement);
                        $('.skillWrapper').append(wrapper)
                    });
                }
            })

        getAOMSocMed(personID)
            .then(response => {
                $('.socMedWrapper').empty() //avoid duplicate data
                let socialMedia = response
                let facebook = socialMedia.facebookUN
                let instagram = socialMedia.instagramUN
                let twitter = socialMedia.twitterUN
                let linkedIn = socialMedia.linkedInUN

                // for avoiding null value
                facebook = (facebook === null) ? 'None' : facebook
                instagram = (instagram === null) ? 'None' : instagram
                twitter = (twitter === null) ? 'None' : twitter
                linkedIn = (linkedIn === null) ? 'None' : linkedIn

                // social media logos
                const instagramLogo = $('<iconify-icon icon="skill-icons:instagram" width="20" height="20"></iconify-icon>')
                const facebookLogo = $('<iconify-icon icon="logos:facebook" width="20" height="20"></iconify-icon>')
                const twitterLogo = $('<iconify-icon icon="logos:twitter" width="20" height="20"></iconify-icon>')
                const linkedInLogo = $('<iconify-icon icon="devicon:linkedin" width="20" height="20"></iconify-icon>')

                const socMed = [facebook, instagram, twitter, linkedIn];
                const logos = [facebookLogo, instagramLogo, twitterLogo, linkedInLogo];

                for (let i = 0; i < socMed.length; i++) {
                    displaySocialMedia(socMed[i], logos[i], '.socMedWrapper')
                }

            })

        $('.aoyModal').removeClass('hidden')
    }


    $('.aoyModal').on('click', function (e) {
        const modal = $('.aoyModalContainer')
        const target = e.target

        if (!modal.is(target) && modal.has(target).length === 0) {
            $('.aoyModal').addClass('hidden')
        }
    })
})