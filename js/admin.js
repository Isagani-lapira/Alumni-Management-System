
const redAccent = '#991B1B'
const blueAccent = '#2E59C6'
const imgContPost = document.getElementById('imgContPost')

$(document).ready(function () {
  $("#tabs").tabs();


  //change the tab appearance when active and not
  $(".tabs li").click(function () {
    $(".tabs li").removeClass("ui-tabs-active")
    $(this).addClass("ui-tabs-active")

  })

  //open modal
  $('#btnAnnouncement').click(function () {
    $("#modal").removeClass('hidden')
  })

  //close modal
  $('.cancel').click(function () {
    $("#modal").addClass('hidden')

    //remove the images
    while (imgContPost.firstChild) {
      imgContPost.removeChild(imgContPost.firstChild)
    }
  })


  //go to creating college page
  $('#btnNewCol').click(function () {
    window.location.href = "/admin/NewCollege.html"
  })


  $('.college').click(() => {
    $('.individual-col').removeClass('hidden')
    $('.college-content').addClass('hidden')
  })

  $('.back-icon').click(() => {
    $('.individual-col').addClass('hidden')
    $('.college-content').removeClass('hidden')
  })

  let imageSequence = 1;
  //add image to the modal
  $('#fileGallery').change(() => {

    $('#errorMsg').addClass('hidden') //always set the error message as hidden when changing the file
    $('#TxtAreaAnnouncement').addClass('h-5/6').removeClass('h-3/6')
    //file input
    var fileInput = $('#fileGallery')
    var file = fileInput[0].files[0] //get the first file that being select

    let validExtension = ['jpeg', 'jpg', 'png'] //only allowed extension
    let fileExtension = file.name.split('.').pop().toLowerCase() //getting the extension of the selected file

    //checking if the file is based on the extension we looking for
    if (validExtension.includes(fileExtension)) {
      var reader = new FileReader()

      //new image element to be place on the  image container div
      const imageElement = document.createElement('img')

      const imgPlaceHolder = document.createElement('div')
      imgPlaceHolder.className = "relative"

      //for button x
      const xBtn = document.createElement('button')
      xBtn.innerHTML = 'X'
      xBtn.className = 'xBtn absolute h-5 w-5 top-0 text-center right-0 cursor-pointer rounded-full hover:bg-accent hover:text-white hover:font-bold'
      xBtn.addEventListener('click', function (e) {
        var parent = e.target.parentNode
        parent.parentNode.removeChild(parent)
      })

      // img element
      imageElement.className = 'flex-shrink-0 h-20 w-20 rounded-md m-2'
      imageElement.setAttribute('id', 'reservedPicture' + imageSequence) //to make sure every id is unique

      //add to its corresponding container
      imgPlaceHolder.appendChild(imageElement)
      imgPlaceHolder.appendChild(xBtn)
      imgContPost.appendChild(imgPlaceHolder)

      //assign the image path to the img element
      reader.onload = function (e) {
        $('#reservedPicture' + imageSequence).attr('src', e.target.result)
        $('#imgContPost').removeClass('hidden')
        $('#TxtAreaAnnouncement').addClass('h-3/6').removeClass('h-5/6') //make the text area smaller in height
        imageSequence++
      }

      reader.readAsDataURL(file)
    }
    else {
      $('#errorMsg').removeClass('hidden') //if the file is not based on the img extension we looking for
    }

  })


});



let batchAOM = document.getElementById('batchAOM')
let month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
month.forEach(addingMonth)

function addingMonth(e) {
  const option = document.createElement('option')
  option.value = e
  option.text = e
  batchAOM.appendChild(option)
}


//chart function
const tracerStatus = document.getElementById('myChart');
const tracerType = 'pie'
const tracerLabels = ["Already answered", "Haven't answer yet"]
const tracerData = [12, 1]
const color = [blueAccent, redAccent]

chartConfig(tracerStatus, tracerType, tracerLabels,
  tracerData, true, color, true)


//chart for response by year
const responseByYear = document.getElementById('responseByYear')
const responseByYear_labels = ["2021", "2020", "2019", "2018", "2017", "2016", "2015", "2014"]
const responseByYear_data = [1000, 500, 247, 635, 323, 393, 290, 860]
const responseByYear_type = 'bar'
chartConfig(responseByYear, responseByYear_type, responseByYear_labels,
  responseByYear_data, false, redAccent, false)


//chart for employee status
const empStatus = document.getElementById('empStatus')
const empStatus_labels = ['Employed', 'Unemployed', 'Not yet answering']
const empStatus_data = [6000, 3500, 1500]
const empStatus_type = 'pie'
const empStatus_color = [blueAccent, redAccent, '#FFFFFF']

chartConfig(empStatus, empStatus_type, empStatus_labels, empStatus_data,
  false, empStatus_color, true)


//chart for gender
const Gender = document.getElementById('Gender')
const Gender_labels = ["2021", "2020", "2019", "2018", "2017", "2016", "2015", "2014"]
const Gender_data = [1000, 500, 247, 635, 323, 393, 290, 860]
const Gender_type = 'bar'
const Gender_color = [redAccent, blueAccent]
chartConfig(Gender, Gender_type, Gender_labels, Gender_data,
  false, Gender_color, false)


//chart for salary
const salaryChart = document.getElementById('salaryChart')
const salaryChart_labels = ["₱10k-20k", "₱21k-30k", "₱31k-40k", "₱51k-60k", "₱60k-70k", "₱71k-80k"]
const salaryChart_data = [1000, 500, 247, 635, 323, 393]
const salaryChart_type = 'bar'
const lightBlue = '#ACCEE9'
const lightGreen = '#BAC3B0'
const lightRed = '#F2AA84'
const lightYellow = '#E7E7A1'
const lightPink = '#F0B3C3'
const lightPurple = '#CBB5CA'
const salaryChart_color = [lightBlue, lightGreen, lightRed, lightYellow, lightPink, lightPurple]
chartConfig(salaryChart, salaryChart_type, salaryChart_labels, salaryChart_data,
  false, salaryChart_color, false)


//chart for communication skill
const comSkill = document.getElementById('comSkill')
const comSkill_data = [1000, 500, 247, 635]
const comSkill_type = 'doughnut'
const comSkilllightBlue = '#ACCEE9'
const comSkilllightGreen = '#BAC3B0'
const comSkilllightRed = '#F2AA84'
const comSkilllightYellow = '#E7E7A1'
const comSkilllightPink = '#F0B3C3'
const comSkilllightPurple = '#CBB5CA'
const comSkill_color = [lightBlue, lightGreen, lightRed, lightYellow]

chartConfig(comSkill, comSkill_type, null, comSkill_data,
  false, comSkill_color, false)

//chart for human relation skill
const humanRelSkill = document.getElementById('humanRelSkill')
const humanRelSkill_data = [35, 500, 247, 1000]
const humanRelSkill_type = 'doughnut'
const humanRelSkilllightBlue = '#ACCEE9'
const humanRelSkilllightGreen = '#BAC3B0'
const humanRelSkilllightRed = '#F2AA84'
const humanRelSkilllightYellow = '#E7E7A1'
const humanRelSkilllightPink = '#F0B3C3'
const humanRelSkilllightPurple = '#CBB5CA'
const humanRelSkill_color = [lightBlue, lightGreen, lightRed, lightYellow]

chartConfig(humanRelSkill, humanRelSkill_type, null, humanRelSkill_data,
  false, humanRelSkill_color, false)

//chart for entrepreneur skill
const entrepSkill = document.getElementById('entrepSkill')
const entrepSkill_data = [100, 200, 1000, 333]
const entrepSkill_type = 'doughnut'
const entrepSkilllightBlue = '#ACCEE9'
const entrepSkilllightGreen = '#BAC3B0'
const entrepSkilllightRed = '#F2AA84'
const entrepSkilllightYellow = '#E7E7A1'
const entrepSkilllightPink = '#F0B3C3'
const entrepSkilllightPurple = '#CBB5CA'
const entrepSkill_color = [lightBlue, lightGreen, lightRed, lightYellow]

chartConfig(entrepSkill, entrepSkill_type, null, entrepSkill_data,
  false, entrepSkill_color, false)


//chart for information skill
const ITSkill = document.getElementById('ITSkill')
const ITSkill_data = [100, 200, 1000, 333]
const ITSkill_type = 'doughnut'
const ITSkilllightBlue = '#ACCEE9'
const ITSkilllightGreen = '#BAC3B0'
const ITSkilllightRed = '#F2AA84'
const ITSkilllightYellow = '#E7E7A1'
const ITSkilllightPink = '#F0B3C3'
const ITSkilllightPurple = '#CBB5CA'
const ITSkill_color = [lightBlue, lightGreen, lightRed, lightYellow]
chartConfig(ITSkill, ITSkill_type, null, ITSkill_data,
  false, ITSkill_color, false)

//chart for problem solving skill
const probSolvSkill = document.getElementById('probSolvSkill')
const probSolvSkill_data = [100, 200, 1000, 333]
const probSolvSkill_type = 'doughnut'
const probSolvSkilllightBlue = '#ACCEE9'
const probSolvSkilllightGreen = '#BAC3B0'
const probSolvSkilllightRed = '#F2AA84'
const probSolvSkilllightYellow = '#E7E7A1'
const probSolvSkilllightPink = '#F0B3C3'
const probSolvSkilllightPurple = '#CBB5CA'
const probSolvSkill_color = [lightBlue, lightGreen, lightRed, lightYellow]

chartConfig(probSolvSkill, probSolvSkill_type, null, probSolvSkill_data,
  false, probSolvSkill_color, false)

//chart for critical thinking skill
const critThinkSkill = document.getElementById('critThinkSkill')
const critThinkSkill_data = [100, 200, 1000, 333]
const critThinkSkill_type = 'doughnut'
const critThinkSkilllightBlue = '#ACCEE9'
const critThinkSkilllightGreen = '#BAC3B0'
const critThinkSkilllightRed = '#F2AA84'
const critThinkSkilllightYellow = '#E7E7A1'
const critThinkSkilllightPink = '#F0B3C3'
const critThinkSkilllightPurple = '#CBB5CA'
const critThinkSkill_color = [lightBlue, lightGreen, lightRed, lightYellow]

chartConfig(critThinkSkill, critThinkSkill_type, null, critThinkSkill_data,
  false, critThinkSkill_color, false)


//for creation of chart
function chartConfig(chartID, type, labels, data, responsive, colors, displayLegend) {
  //the chart
  new Chart(chartID, {
    type: type,
    data: {
      labels: labels,
      datasets: [{
        backgroundColor: colors,
        data: data,
        borderWidth: 1
      }]
    },
    options: {
      responsive: responsive, // Disable responsiveness
      maintainAspectRatio: false, // Disable aspect ratio

      plugins: {
        legend: {
          display: displayLegend,
          position: 'bottom',
          labels: {
            font: {
              weight: 'bold'
            }
          },

        }
      }
    }
  });
}
