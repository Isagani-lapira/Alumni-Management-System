
const redAccent = '#991B1B'
const blueAccent = '#2E59C6'
const imgContPost = document.getElementById('imgContPost')
const skillDiv = 'skillDiv'
const holderSkill = "Add skill/s that needed"

const reqDiv = 'reqDiv'
const holderReq = "Add skill/s that needed"

$(document).ready(function () {
  $("#tabs").tabs();


  let validExtension = ['jpeg', 'jpg', 'png'] //only allowed extension
  let fileExtension

  //change the tab appearance when active and not
  $(".tabs li").click(function () {
    $(".tabs li").removeClass("ui-tabs-active")
    $(this).addClass("ui-tabs-active")

  })

  // //open modal post
  $('#btnAnnouncement').click(function () {
    prompt("#modal", true)
  })
  // //open modal email
  $('#btnEmail').click(function () {
    prompt("#modalEmail", true)
  })
  // //close modal email
  $('.cancelEmail').click(function () {
    prompt("#modalEmail", false)
  })

  //close modal
  $('.cancel').click(function () {
    prompt("#modal", false)

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

    fileExtension = file.name.split('.').pop().toLowerCase() //getting the extension of the selected file
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


  $('#btnDelPost').click(function () {
    prompt("#modalDelete", true)
  })
  $('.cancelDel').click(function () {
    prompt("#modalDelete", false)
  })

  $('.viewProfile').click(function () {
    prompt("#viewProfile", true)
  })

  $('.closeProfile').click(function () {
    prompt("#viewProfile", false)
  })

  //show the prompt modal
  function prompt(id, openIt) {
    openIt == true ? $(id).removeClass('hidden') : $(id).addClass('hidden')
  }

  //show new posting of job 
  $('#addNewbtn').click(function () {
    $('#jobPosting').show()
    $('#jobList').hide()
    $('.jobPostingBack').show()
  })

  //show the default job posting content
  $('.jobPostingBack').click(function () {
    $('#jobPosting').hide()
    $('#jobList').show()
    $('.jobPostingBack').hide()
  })

  $('.inputSkill').on('input', function () {
    addNewField(skillDiv, holderSkill, true)
  })
  $('.inputReq').on('input', function () {
    addNewField(reqDiv, holderReq, false)
  })


  //error handling for logo
  $('#jobLogoInput').change(function () {
    console.log('rara')
    const fileInput = $(this)
    const file = fileInput[0].files[0]

    fileExtension = file.name.split('.').pop().toLowerCase() //getting the extension of the selected file
    let fileName = ""
    if (validExtension.includes(fileExtension)) {
      fileName = file.name
    } else fileName = "Wrong file"

    //set the text as the name of the selected file
    fileName == 'Wrong file' ? $('#jobFileName').addClass('text-accent') : $('#jobFileName').removeClass('text-accent')
    $('#jobFileName').html(fileName)
  })


  //open and close the view job modal
  $('.viewJobModal').on('click', function () {
    $('#viewJob').removeClass('hidden')
  })

  //allows modal to be close when Click else where
  $('#viewJob').on('click', function (e) {
    if (!(e.target).closest('#viewJob').length)
      $('#viewJob').addClass('hidden')
  })

  $(function () {
    $('input[name="daterange"]').daterangepicker({
      opens: 'left'
    }, function (start, end, label) {
      console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
    });
  });

});


let typingTimeout = null;

//
function addNewField(container, holder, isSkill) {
  clearTimeout(typingTimeout)

  var field = isSkill == true ? true : false

  typingTimeout = setTimeout(function () {
    const containerSkill = document.getElementById(container)

    //image element
    const imageSkill = document.createElement('img')
    const srcAddIcon = '/assets/icons/add-circle.png'
    imageSkill.className = 'h-12 w-12 inline cursor-pointer'
    imageSkill.setAttribute('src', srcAddIcon)

    //input element
    const inputField = document.createElement('input')
    inputField.setAttribute('placeholder', holder)
    inputField.setAttribute('type', "text")
    inputField.setAttribute('oninput', 'checkField(' + field + ')')

    const fieldContainer = document.createElement('div')
    fieldContainer.appendChild(imageSkill)
    fieldContainer.appendChild(inputField)
    containerSkill.appendChild(fieldContainer)

  }, 500)
}

//checker which value should be pass
function checkField(checker) {
  var containerDiv = checker == true ? skillDiv : reqDiv
  var placeHolder = checker == true ? holderSkill : holderReq
  var field = checker == true ? true : false

  addNewField(containerDiv, placeHolder, field)
}

let batchAOM = document.getElementById('batchAOM')
let month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
month.forEach(addingMonth)

function addingMonth(e) {
  const option = document.createElement('option')
  option.value = e
  option.text = e
  batchAOM.appendChild(option)
}



//chart for response by year
const responseByYear = document.getElementById('responseByYear')
const responseByYear_labels = ["2021", "2020", "2019", "2018", "2017", "2016", "2015", "2014"]
const responseByYear_data = [1000, 500, 247, 635, 323, 393, 290, 860]
const responseByYear_type = 'line'
chartConfig(responseByYear, responseByYear_type, responseByYear_labels,
  responseByYear_data, false, redAccent, false)


//tracer status
const tracerStatus = document.getElementById('myChart');
const tracerType = 'bar'
const tracerLabels = ["Already answered", "Haven't answer yet"]
const tracerData = [12, 5]
const color = [blueAccent, redAccent]

chartConfig(tracerStatus, tracerType, tracerLabels,
  tracerData, false, color, false)


//chart for employement status
const empStatus = document.getElementById('empStatus')
const empStatus_labels = ["2021", "2020", "2019", "2018", "2017", "2016", "2015", "2014"]
const empStatus_data = [1000, 500, 247, 635, 323, 393, 290, 860]
const empStatus_type = 'line'
const empStatus_color = [redAccent, blueAccent]
chartConfig(empStatus, empStatus_type, empStatus_labels, empStatus_data,
  true, empStatus_color, false)


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
  true, salaryChart_color, false)



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
        borderColor: redAccent, // Set the line color
        borderWidth: 1,
        tension: 0.1
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
