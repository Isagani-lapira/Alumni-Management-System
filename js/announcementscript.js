$(document).ready(function () {
    const imgFormat = "data:image/jpeg;base64,"
    $('#newsAndUpdate').on('click', function () {
        // restart everything first
        announcementTb.clear()
        offsetAnnouncement = 0
        retrievedList = 0
        retrievedAnnouncement()
    })

    let offsetAnnouncement = 0;
    let retrievedList = 0;

    const announcementTb = $('.announcementTable').DataTable({
        "paging": true,
        "ordering": true,
        "info": false,
        "lengthChange": false,
        "searching": true,
        "pageLength": 8
    });

    $('.announcementTable').removeClass('dataTable').addClass('rounded-lg')
    //retrieve the announcement data
    function retrievedAnnouncement() {
        let action = "readAdminPost"
        let formData = new FormData();
        formData.append('action', action)
        formData.append('offset', offsetAnnouncement);

        //process retrieval
        $.ajax({
            url: '../PHP_process/announcement.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: response => {
                if (response.result == 'Success') {
                    const data = response
                    $('#announcementList').empty() //remove the previously displayed

                    //get all the data retrieved from the processing
                    const length = data.title.length
                    if (length > 0) {

                        for (let i = 0; i < length; i++) {
                            const announcementID = data.announcementID[i];
                            const title = data.title[i];
                            const tempDescription = data.Descrip[i].substring(0, 50);
                            const description = data.Descrip[i];
                            const date_posted = getFormattedDate(data.date_posted[i]); //format the date as well
                            const headline_img = response.headline_img[i];
                            const fullname = response.fullname[i];

                            const row = [
                                title,
                                tempDescription,
                                date_posted,
                                `<button class="bg-postButton hover:bg-postHoverButton rounded-md text-white px-3 py-1 text-xs view-button"
                                data-announcementID="${announcementID}"
                                data-description="${description}"
                                data-title="${title}"
                                data-date_posted="${date_posted}"
                                data-headline_img="${headline_img}"
                                data-fullname="${fullname}">View</button>`
                            ]

                            announcementTb.row.add(row)
                        }

                        announcementTb.draw();

                        offsetAnnouncement += length
                        retrievedList = length

                        // retrieve more
                        if (retrievedList == 10) retrievedAnnouncement()

                    }
                }

            }

        })
    }

    // open the announcement
    $('.announcementTable').on('click', ".view-button", function () {
        const announcementID = $(this).data('announcementid');
        const title = $(this).data('title');
        const description = $(this).data('description');
        const date_posted = $(this).data('date_posted');
        const headline_img = imgFormat + $(this).data('headline_img');
        const fullname = $(this).data('fullname');

        displayAnnouncementDetails(announcementID, headline_img, date_posted, fullname, title, description)
    })

    // format the date into easy to read date
    function getFormattedDate(date) {
        //parts out the date
        let year = date.substring(0, 4);
        let dateMonth = parseInt(date.substring(5, 7));
        let day = date.substring(8, 10);

        const listOfMonths = ['', 'January', 'February', 'March', 'April', 'May',
            'June', 'July', 'August', 'September', 'October', 'November', 'December']
        let month = listOfMonths[dateMonth];

        return month + ' ' + day + ', ' + year
    }

    $('#prevAnnouncement').on('click', function () {
        offsetAnnouncement -= retrievedList
        //check if the offset is already in 0
        if (offsetAnnouncement !== 0 || offsetAnnouncement > 0) {
            retrievedAnnouncement()
        } else $(this).addClass('hidden')
    })

    //retrieve next sets of data
    $('#nextAnnouncement').on('click', function () {
        retrievedAnnouncement()
    })

    // assign value in the announcement modal
    function displayAnnouncementDetails(ID, headline, date_posted, author, title, description) {
        $('#announcementModal').removeClass('hidden') //open modal
        // details set up
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

    // Close the announcement modal
    $('#announcementModal').on('click', function (e) {
        const target = e.target;
        const container = $('#announcementDetails');

        // Check if the clicked element is outside the container
        if (!container.is(target) && container.has(target).length === 0)
            $('#announcementModal').addClass('hidden');
    });


    // make news and announcement
    $("#headerImg").on("change", function () {
        const file = this.files[0]; //get the first file selected

        if (file) {
            const reader = new FileReader();

            //display the file on image element
            reader.onload = (e) => {
                $("#imgHeader")
                    .addClass('cursor-pointer')
                    .attr("src", e.target.result)
                    .on('click', function () {
                        $('#headerImg').click()
                    })

                //hide the label
                $(".headerLbl").addClass("hidden");
            };

            //read the selected file to trigger onload
            reader.readAsDataURL(file);
        }
    });

    $("#newsTitle, #newstTxtArea").on("input", enableAnnouncementBtn);
    $("#headerImg").on("change", enableAnnouncementBtn);

    function enableAnnouncementBtn() {
        const fieldToTest = ["#newsTitle", "#newstTxtArea", "#headerImg"];
        let isComplete = false;
        $.each(fieldToTest, function (index, field) {
            let value = $(field).val().trim();
            if (value === "") {
                isComplete = false; // If any field is empty, set isComplete to false
                return false; // Exit the loop early if we find an empty field
            } else isComplete = true;
        });

        const enabledBtn = "text-white bg-accent";
        const disabledBnt = "text-gray-300  bg-red-300";
        //if everything is added then remove the disabled in button
        if (isComplete) {
            $("#postNewsBtn")
                .prop("disabled", false)
                .addClass(enabledBtn)
                .removeClass(disabledBnt);
        } else {
            //disable again the button
            $("#postNewsBtn")
                .prop("disabled", true)
                .addClass(disabledBnt)
                .removeClass(enabledBtn);
        }
    }

    let imageCollection = [];
    $("#collectionFile").on("change", function () {
        let imgSrc = this.files[0];

        imageCollection.push(imgSrc);
        //get image selected
        if (imgSrc) {
            var reader = new FileReader();

            //load the selected file
            reader.onload = (e) => {
                //create a new container
                const imgWrapper = $("<div>").addClass(
                    "imgWrapper w-24 h-24 rounded-md"
                );
                const imgElement = $("<img>")
                    .addClass("h-full w-full rounded-md")
                    .attr("src", e.target.result);

                //attach everything
                imgWrapper.append(imgElement);
                $("#collectionContainer").append(imgWrapper); //attach to the root
            };

            //read the file for onload to be trigger
            reader.readAsDataURL(imgSrc);
        }
    });

    $("#closeNewsModal").on("click", function () {
        $("#newsUpdateModal").addClass("hidden");
        restartNewsModal();
    });

    //restart the news modal
    function restartNewsModal() {
        $("#imgHeader").removeAttr('src')
        $(".headerLbl").removeClass("hidden");
        $("#newsTitle").val("");
        $("#newstTxtArea").val("");
        $(".imgWrapper").remove();

        imageCollection = [];
    }
    //open announcement modal
    $("#newsBtn").on("click", function () {
        $("#newsUpdateModal").removeClass("hidden");
    });

    $("#postNewsBtn").on("click", function () {
        let imgHeader = $("#headerImg").prop("files")[0]; //get the header
        let action = "insertData";
        let title = $("#newsTitle").val();
        let description = $("#newstTxtArea").val();

        //data to be send
        const formData = new FormData();
        formData.append("action", action);
        formData.append("imgHeader", imgHeader);
        formData.append("title", title);
        formData.append("description", description);

        //send images when there's a collection added
        if (imageCollection.length != 0) {
            for (let i = 0; i < imageCollection.length; i++) {
                formData.append("file[]", imageCollection[i]);
            }
        }

        //process the insertion
        $.ajax({
            url: "../PHP_process/announcement.php",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: (response) => {
                //close the modal
                $("#newsUpdateModal").addClass("hidden");
                restartNewsModal();
                if (response == "Success") {
                    announcementTb.clear()
                    offsetAnnouncement = 0;
                    retrievedList = 0;
                    retrievedAnnouncement()
                    setTimeout(function () {
                        $("#successModal").removeClass("hidden");

                        setTimeout(function () {
                            $("#successModal").addClass("hidden");
                        }, 5000);
                    }, 1000);
                }
            },
            error: (error) => {
                console.log(error);
            },
        });
    });
})