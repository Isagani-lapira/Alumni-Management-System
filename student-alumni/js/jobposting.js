const imgFormat = "data:image/jpeg;base64,";
$(document).ready(function () {
    //post a job
    $('#jobPostingForm').on('submit', function (e) {
        e.preventDefault();

        const action = {
            action: 'createjobuser',
        }
        const formData = new FormData(e.target)
        formData.append('action', JSON.stringify(action))
        formData.append('author', accUsername)

        //process ajax insertion
        $.ajax({
            url: '../PHP_process/jobTable.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: response => {
                if (response == 'Successful') {
                    $('#successJobModal').removeClass('hidden')

                    //close modal in 4seconds
                    setTimeout(() => {
                        $('#successJobModal').addClass('hidden')
                        $('#createJobModal').addClass('hidden')
                    }, 4000);

                    $('#jobPostingForm')[0].reset() //reset the data in the form
                }
            },
            error: error => { console.log(error) }
        })
    })

    $('#createJobPost').on('click', function () {
        $('#createJobModal').removeClass('hidden')
    })

    // close create job modal
    $('#createJobModal').on('click', function (e) {
        target = e.target
        modal = $('#jobContainer')

        if (!modal.is(target) && modal.has(target).length == 0) {
            $('#createJobModal').addClass('hidden')
        }
    })

    //close job
    $('#cancelJobPosting').on('click', function () {
        $('#createJobModal').addClass('hidden')
    })

    let offsetUserJob = 0;
    let lengthChecker = 0;
    // current user job post
    $('#verif-btn').on('click', function () {
        offsetUserJob = 0;
        $('#jobRepo').empty()
        retrieveUserPost()
    })

    function retrieveUserPost() {
        //process retrieval
        const action = { action: 'currentUserJobPost' };

        const formData = new FormData();
        formData.append('action', JSON.stringify(action));
        formData.append('offset', offsetUserJob)
        $.ajax({
            url: '../PHP_process/jobTable.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'JSON',
            success: response => {
                if (response.result === 'Success') {
                    const length = response.jobTitle.length;
                    //data that has been retrieved
                    for (let i = 0; i < length; i++) {
                        const jobTitle = response.jobTitle[i];
                        const skills = response.skills[i];
                        const status = response.status[i];
                        const companyName = response.companyName[i];
                        const jobDescript = response.jobDescript[i];
                        const jobQuali = response.jobQuali[i];
                        const location = response.location[i];

                        displayJobRepo(jobTitle, skills, status, companyName,
                            jobDescript, jobQuali, location);
                    }

                    offsetUserJob += length
                    lengthChecker = length;
                }
            },
            error: error => { console.log(error) }
        })
    }
    //mark up for job repository in verified post
    function displayJobRepo(jobTitle, skills, status, companyName,
        jobDescript, jobQuali, location) {

        //sets of color
        const colorSet = ["#A9FBC3", "#979DED", "#ACBDBA", "#6DA17C", "#CDDDDD", "#9DB5B2", "#F9B4ED",
            "#06908F", "#D7B49E", "#CEB992", "#947BD3", "#EFA9AE", "#F9ADA0", "#F4F1BB", "#809BCE",
            "#D6EADF", "#D3D4D9", "#FFE19C", "#ADBCA5", "#E8B9AB", "#EDDEA4", "#7C9885", "#CED0CE",
            "#58A4B0", "#BBC2E2", "#CFCCD6", "#364156", "#AC80A0"];

        //access random color to be use for header
        const randomIndex = Math.floor(Math.random() * colorSet.length);
        const color = colorSet[randomIndex];

        const wrapper = $('<div>')
            .addClass('rounded-md max-w-sm flex flex-col center-shadow')

        const headerPart = $('<div>')
            .addClass('h-full flex flex-col rounded-t-md p-3 justify-between')
            .css({
                "background-color": color //set the random color to header
            })

        const jobTitleElement = $('<h1>')
            .addClass('text-lg text-white font-bold my-2')
            .text(jobTitle);

        const list = $('<div>')
            .addClass('flex flex-wrap gap-1 text-xs text-gray-500 italic items-center')

        //retrieve all the skill and display in on a div to be included on the container
        skills.forEach(skill => {
            let bulletIcon = '<iconify-icon icon="fluent-mdl2:radio-bullet" style="color: #6c6c6c;"></iconify-icon>';
            let skillElement = $('<span>').html(skill)
            list.append(bulletIcon, skillElement)
        })

        const footer = $('<div>')
            .addClass('bg-gray-200 rounded-b-md p-3 flex flex-wrap justify-between items-center text-xs text-gray-400')

        const applicant = $('<span>')
            .addClass('flex items-center gap-2')
            .html(
                '<iconify-icon icon="uiw:user" style="color: #868e96;" width="14" height="14"></iconify-icon>' +
                'Applicant:'
            )

        const verifiedElement = $('<span>')
            .addClass('flex items-center gap-2')
            .html(
                '<iconify-icon icon="ri:verified-badge-line" style="color: #868e96;" width="14" height="14"></iconify-icon>' +
                status
            )

        if (status == 'unverified') {
            status = 'Not yet verified'
        } else {
            verifiedElement.addClass('text-blue-400')
                .html('<iconify-icon icon="ri:verified-badge-fill" style="color: #60a5fa;"></iconify-icon>' + status)
        }
        const leftSide = $('<div>').append(applicant, verifiedElement)
        const proceedBtn = $('<button>')
            .html('<iconify-icon icon="maki:arrow" style="color: #868e96;" width="24" height="24"></iconify-icon>')
            .on('click', function () {
                $("#skillSets").empty(); //remove the recent added skill and requirements
                $("#viewJob").removeClass("hidden");

                //set value to the view modal
                $("#viewJob").removeClass("hidden");
                $("#viewJobColText").text(jobTitle);
                $("#viewJobColCompany").text(companyName);
                $("#jobOverview").text(jobDescript);
                $("#jobQualification").text(jobQuali);
                $("#statusJob").text(status);
                $("#jobApplicant").text('Applicant: ' + '');

                $('.headerJob').css({ 'background-color': color })
                //retrieve the skills
                skills.forEach((skill) => {
                    //create a span and append it in the div
                    spSkill = $("<span>").html("&#x2022; " + skill);
                    $("#skillSets").append(spSkill);
                });

                $('#locationJobModal').text(location)

            })

        footer.append(leftSide, proceedBtn)
        headerPart.append(jobTitleElement, list);
        wrapper.append(headerPart, footer);

        $('#jobRepo').append(wrapper)
    }

    //allows modal to be close when Click else where
    $("#viewJob").on("click", function (e) {
        const target = e.target
        const modal = $('#viewingJobModal')

        if (!modal.is(target) && modal.has(target).length == 0) {
            $('#viewJob').addClass('hidden')
        }

    });

    $('#jobRepo').on('scroll', function () {
        const containerHeight = $(this).height();
        const contentHeight = $(this)[0].scrollHeight;
        const scrollOffset = $(this).scrollTop();

        //once the bottom ends, it will reach another sets of data (post)
        if (scrollOffset + containerHeight >= contentHeight && lengthChecker >= 10)
            retrieveUserPost()//get another set of post

    })

})
