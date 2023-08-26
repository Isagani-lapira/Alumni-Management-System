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
                console.log(response)
                if (response == 'Successful') {
                    $('#successJobModal').removeClass('hidden')

                    setTimeout(() => {
                        $('#successJobModal').addClass('hidden')
                        $('#createJobModal').addClass('hidden')
                    }, 4000);
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
})