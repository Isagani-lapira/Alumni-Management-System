$(document).ready(function () {

    const imgFormat = "data:image/jpeg;base64,"
    //search suggestion
    $('#searchUser').on('input', function () {
        const profileName = $(this).val()
        if (profileName != "") { //if the search bar is not empty
            $('#searchProfile').removeClass('hidden')
            retrieveUserNames(profileName)
        }
        else $('#searchProfile').addClass('hidden') //hide again the search suggestion
    })

    function retrieveUserNames(profileName) {
        const action = { action: "searchPerson" }
        const formdata = new FormData();
        formdata.append('action', JSON.stringify(action));
        formdata.append('personName', profileName);

        $.ajax({
            url: '../PHP_process/person.php',
            method: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: response => {
                $('#searchProfile').empty()
                if (response.response = "Success") {
                    let length = response.personID.length

                    for (let i = 0; i < length; i++) {
                        const personID = response.personID[i];
                        const fullname = response.fullname[i];
                        const status = response.status[i];
                        const profilePic = response.profilePic[i];

                        displaySuggestedName(personID, fullname, profilePic, status);
                    }
                }
            },
            error: error => { console.log(error) }
        })
    }

    function displaySuggestedName(personID, fullname, profilePic, status) {
        // mark up for displaying suggestions

        const wrapper = $('<div>')
            .addClass('p-3 flex items-center gap-2 hover:bg-red-300 hover:text-white text-gray-500 cursor-pointer')

        const roundedColor = (status == "Alumni") ? 'border-accent' : 'border-blue-400'
        const imgSrc = (profilePic == "") ? "../assets/icons/person.png" : imgFormat + profilePic
        const imgElement = $('<img>')
            .addClass('rounded-full h-10 w-10 border ' + roundedColor)
            .attr('src', imgSrc);

        const name = $('<p>').addClass('font-bold').text(fullname)
        const statusElement = $('<p>').addClass('text-xs').text(status)


        const personDetailsWrapper = $('<div>')
            .addClass('flex flex-col text-sm')
            .append(name, statusElement)
        wrapper.append(imgElement, personDetailsWrapper)
        $('#searchProfile').append(wrapper)
    }
    document.addEventListener('click', function (event) {
        if (!$('#searchProfile').is(event.target) && !$('#searchProfile').has(event.target).length) {
            $('#searchProfile').addClass('hidden')
        }
    })

})