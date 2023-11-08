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
            jsonData.forEach(column => {
                count++
                const tr = $('<tr>')
                let tdCount = $('<td>').text(count);
                tr.append(tdCount)
                column.forEach(value => {
                    let column = $('<td>').text(value)
                    tr.append(column)
                })
                $('tbody').append(tr)
            })

            window.print(); //automatically print the record
        } catch (error) {
            console.log(error)
        }
    }

    // get today's date
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