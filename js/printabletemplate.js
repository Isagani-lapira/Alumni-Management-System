$(document).ready(function () {
    function getParameterByName(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    $('header p').text(getCurrentDate())
    const dataParams = getParameterByName('data')
    if (dataParams) {
        try {
            const jsonData = JSON.parse(decodeURIComponent(dataParams));

            let count = 0
            //mark up the data for table body
            jsonData.forEach(data => {
                count++
                const tr = $('<tr>')
                let tdCount = $('<td>').text(count)
                let tdCollege = $('<td>').text(data.colCode)
                let tdAdmin = $('<td>').text(data.colAdmin)
                let tdAction = $('<td>').text(data.details)
                let tdTimestamp = $('<td>').text(data.timestamp)

                tr.append(tdCount, tdCollege, tdAdmin, tdAction, tdTimestamp)
                $('tbody').append(tr)
            })

            //print automatically
            autoPrint()
        } catch (error) {
            console.log(error)
        }
    }
    function autoPrint() {
        window.print();
    }
    function getCurrentDate() {
        const currentDate = new Date();

        const monthNames = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        const month = monthNames[currentDate.getMonth()];
        const day = currentDate.getDate();
        const year = currentDate.getFullYear();

        const formattedDate = `${month} ${day}, ${year}`;
        return formattedDate;
    }
})