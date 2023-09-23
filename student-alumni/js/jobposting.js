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
                        const careerID = response.careerID[i];
                        const jobTitle = response.jobTitle[i];
                        const skills = response.skills[i];
                        const status = response.status[i];
                        const companyName = response.companyName[i];
                        const jobDescript = response.jobDescript[i];
                        const jobQuali = response.jobQuali[i];
                        const location = response.location[i];
                        const applicantcount = response.applicantCount[i];

                        displayJobRepo(careerID, jobTitle, skills, status, companyName,
                            jobDescript, jobQuali, location, applicantcount);
                    }

                    offsetUserJob += length
                    lengthChecker = length;
                }
            },
            error: error => { console.log(error) }
        })
    }
    //mark up for job repository in verified post
    function displayJobRepo(careerID, jobTitle, skills, status, companyName,
        jobDescript, jobQuali, location, applicantcount) {

        //sets of color
        const colorBG = (status === 'verified') ? 'accent' : 'gray-400'

        const wrapper = $('<div>')
            .addClass('rounded-md max-w-sm flex flex-col center-shadow')

        const headerPart = $('<div>')
            .addClass('h-full flex flex-col rounded-t-md p-3 justify-between bg-' + colorBG)

        const jobTitleElement = $('<h1>')
            .addClass('text-lg text-white font-bold my-2')
            .text(jobTitle);

        const list = $('<div>')
            .addClass('flex flex-wrap gap-1 text-xs text-white italic items-center')

        //retrieve all the skill and display in on a div to be included on the container
        skills.forEach(skill => {
            let bulletIcon = '<iconify-icon icon="fluent-mdl2:radio-bullet" style="color: #ffffff;"></iconify-icon>';
            let skillElement = $('<span>').html(skill)
            list.append(bulletIcon, skillElement)
        })

        const footer = $('<div>')
            .addClass('bg-gray-200 rounded-b-md p-3 flex flex-wrap justify-between items-center text-xs text-gray-400')

        const applicant = $('<span>')
            .addClass('flex items-center gap-2 text-' + colorBG)
            .html(
                '<iconify-icon icon="uiw:user" width="14" height="14"></iconify-icon>' +
                '<span>Applicant: <span class="font-bold">' + applicantcount + '</span></span>'
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
                $("#jobApplicant").addClass('text-' + colorBG).text('Applicant: ' + applicantcount);

                $('#aplicantListBtn').addClass('cursor-text') //default
                if (parseInt(applicantcount) > 0) {
                    $('#aplicantListBtn').removeClass('cursor-text')
                        .on('click', function () {
                            //open the list
                            displayApplicant(careerID)
                        })
                }

                $('.headerJob').addClass('bg-' + colorBG)
                //retrieve the skills
                skills.forEach((skill) => {
                    //create a span and append it in the div
                    let spSkill = $("<span>").html("&#x2022; " + skill);
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
        const threshold = 50; // Define the threshold in pixels

        //once the bottom ends, it will reach another sets of data (post)
        if (scrollOffset + containerHeight + threshold >= contentHeight)
            retrieveUserPost()//get another set of post

    })


    // retrieve details of applicant
    function displayApplicant(careerID) {
        const action = { action: 'applicantDetails' }
        const formData = new FormData();
        formData.append('action', JSON.stringify(action));
        formData.append('careerID', careerID);

        // process retrieval of applicant details
        $.ajax({
            url: '../PHP_process/jobTable.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: response => {
                if (response.response == "Success") {
                    $('#listApplicantContainer').empty()
                    $('#listOfApplicantModal').removeClass('hidden')
                    const length = response.fullname.length;

                    // mark up the data retrieved
                    for (let i = 0; i < length; i++) {
                        let fullname = response.fullname[i];
                        let resumeID = response.resumeID[i];

                        let wrapper = $('<div>')
                            .addClass('justify-between flex items-center')

                        let fullnameElement = $('<p>')
                            .addClass('italic text-gray-500')
                            .text(fullname)

                        let viewResume = $('<button>')
                            .addClass('py-2 px-4 rounded-lg bg-accent text-white font-bold text-xs hover:bg-darkAccent')
                            .text('Resume')

                        wrapper.append(fullnameElement, viewResume)
                        $('#listApplicantContainer').append(wrapper)
                    }
                }
            },
            error: error => { console.log(error) }
        })
    }


    $('.modaListApplicant button').on('click', function () {
        $('#listOfApplicantModal').addClass('hidden')
    })
})
