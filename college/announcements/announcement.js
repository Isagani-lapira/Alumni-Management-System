$(document).ready(function () {
  // Base URL
  const baseURL = "php/";
  const imgFormat = "data:image/jpeg;base64,";
  $("#newsAndUpdate").on("click", retrievedAnnouncement);

  let offsetAnnouncement = 0;
  let retrievedList = 0;

  retrievedAnnouncement();

  //retrieve the announcement data
  function retrievedAnnouncement() {
    let action = "readAdminPost";
    let formData = new FormData();
    formData.append("action", action);
    formData.append("offset", offsetAnnouncement);

    //process retrieval
    $.ajax({
      url: baseURL + "announcement.php",
      method: "POST",
      data: formData,
      contentType: false,
      processData: false,
      dataType: "json",
      success: (response) => {
        if (response.result == "Success") {
          const data = response;
          $("#announcementList").empty(); //remove the previously displayed
          $("#noAvailMsgAnnouncement").addClass("hidden");

          //get all the data retrieved from the processing
          const length = data.title.length;
          for (let i = 0; i < length; i++) {
            const announcementID = data.announcementID[i];
            const title = data.title[i];
            const tempDescription = data.Descrip[i].substring(0, 50);
            const description = data.Descrip[i];
            const date_posted = getFormattedDate(data.date_posted[i]); //format the date as well
            const headline_img = response.headline_img[i];
            const fullname = response.fullname[i];

            // create markup for table
            const row = $("<tr>").addClass("border-b border-gray-300");
            const titleTD = $("<td>").addClass("font-semibold").text(title);
            const descriptTD = $("<td>").text(tempDescription);
            const datePostedTD = $("<td>").text(date_posted);

            const delBtn = $("<button>")
              .addClass(
                "border border-red-400 text-red-400 hover:text-white hover:bg-red-500 rounded-sm text-white px-3 py-1 text-xs"
              )
              .text("Archive");

            const viewBtn = $("<button>")
              .addClass(
                "bg-postButton hover:bg-postHoverButton rounded-sm text-white px-3 py-1 text-xs"
              )
              .text("View")
              .on("click", function () {
                $("#announcementModal").removeClass("hidden");
                const imgSrc = imgFormat + headline_img;
                displayAnnouncementDetails(
                  announcementID,
                  imgSrc,
                  date_posted,
                  fullname,
                  title,
                  description
                );
              });

            const actionContainer = $("<div>")
              .addClass("flex flex-wrap justify-center gap-2")
              .append(delBtn, viewBtn);

            //action data
            const actionTD = $("<td>").append(actionContainer);

            //attach every data to the table
            row.append(titleTD, descriptTD, datePostedTD, actionTD);
            $("#announcementList").append(row);
          }

          offsetAnnouncement += length;
          retrievedList = length;
          //if the retrieved data is not get to 10 then it's because there's no more data left
          if (retrievedList < 10) {
            $("#nextAnnouncement").addClass("hidden");
          } else {
            $("#nextAnnouncement").removeClass("hidden");
            $("#prevAnnouncement").removeClass("hidden");
          }
        } else $("#noAvailMsgAnnouncement").removeClass("hidden");
      },
    });
  }

  // format the date into easy to read date
  function getFormattedDate(date) {
    //parts out the date
    let year = date.substring(0, 4);
    let dateMonth = parseInt(date.substring(5, 7));
    let day = date.substring(8, 10);

    const listOfMonths = [
      "",
      "January",
      "February",
      "March",
      "April",
      "May",
      "June",
      "July",
      "August",
      "September",
      "October",
      "November",
      "December",
    ];
    let month = listOfMonths[dateMonth];

    return month + " " + day + ", " + year;
  }

  $("#prevAnnouncement").on("click", function () {
    offsetAnnouncement -= retrievedList;
    //check if the offset is already in 0
    if (offsetAnnouncement !== 0 || offsetAnnouncement > 0) {
      retrievedAnnouncement();
    } else $(this).addClass("hidden");
  });

  //retrieve next sets of data
  $("#nextAnnouncement").on("click", function () {
    retrievedAnnouncement();
  });

  // assign value in the announcement modal
  function displayAnnouncementDetails(
    ID,
    headline,
    date_posted,
    author,
    title,
    description
  ) {
    $("#headline_img").attr("src", headline);
    $("#announceDatePosted").text(date_posted);
    $("#announcementAuthor").text(author);
    $("#announcementTitle").text(title);
    $("#announcementDescript").text(description);

    const action = "readImageOfAnnouncement";
    const formdata = new FormData();
    formdata.append("action", action);
    formdata.append("announcementID", ID);
    // retrieve images if theres any
    $.ajax({
      url: "../PHP_process/announcement.php",
      method: "POST",
      data: formdata,
      processData: false,
      contentType: false,
      dataType: "json",
      success: (response) => {
        //remove the previous display images
        $("#imagesWrapper").empty();

        if (response.result != "Nothing") {
          $("#imagesContainer").removeClass("hidden"); // show the container

          // const image = response.images
          const images = response.images;

          //display all the images
          images.forEach((value) => {
            const imgSrc = imgFormat + value; //convert into base64
            displayImages(imgSrc);
          });
        } else $("#imagesContainer").addClass("hidden"); //hide the container
      },
      error: (error) => {
        console.log(error);
      },
    });
  }

  function displayImages(imgSrc) {
    const imgElement = $("<img>")
      .addClass("w-40 object-contain bg-gray-500 rounded-md")
      .attr("src", imgSrc);

    console.log("rar");
    $("#imagesWrapper").append(imgElement);
  }

  //close the announcement modal
  $("#announcementModal").on("click", function (e) {
    const target = e.target;
    let container = $("#announcementContainer");

    //check if the clicked is outside the container
    if (!container.is(target) && !container.has(target).length) {
      $("#announcementModal").addClass("hidden");
    }
  });
});
