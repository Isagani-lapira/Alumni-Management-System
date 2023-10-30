$(document).ready(function () {

    const date = new Date();
    const year = date.getFullYear();
    const day = date.getDate();
    const month = date.getMonth();

    const currentDate = new Date(year, month, day);
    const updateDay = new Date(year, 6, 31); // July is month 6 (0-based)

    // check if today is the update day
    if (currentDate >= updateDay) updateCurrentYear()

    function updateCurrentYear() {
        const action = { action: "updateCurrentYear" };
        const username = $('#accUsername').text()
        const formData = new FormData();
        formData.append('action', JSON.stringify(action));
        formData.append('username', username);

        $.ajax({
            url: '../PHP_process/studentData.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
        })
    }
});