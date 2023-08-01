const PHP_SERVER_URL = "../../PHP_process/";

fetchResource();

function fetchResource(url = "", callback = null) {
  $.ajax({
    url: "php/student-records/studentData.php",
    method: "GET",
    dataType: "json",
    processData: false,
    contentType: false,
    success: (response) => {
      if (response.response == "Success") {
        console.log("fetch " + url + " successs");
        if (!callback) {
          console.log(response);
          return response;
        } else callback(response);
      }
    },
    error: (error) => {
      console.error(error);
      if (!callback) {
        return null;
      } else callback(response);
    },
  });
}

function fetchStudentRecord() {
  let data = response;
  let length = data.studentNo.length; //length of the data has been retrieved

  //display the student record on the table
  let tbody = $("#studentTB");
  for (let i = 0; i < length; i++) {
    studentNo = data.studentNo[i];
    fullname = data.fullname[i];
    contactNo = data.contactNo[i];
    let tr = $("<tr>");
    let tdStudentNo = $("<td>")
      .addClass("text-center font-bold")
      .text(studentNo);
    let tdfullname = $("<td>").addClass("text-center").text(fullname);
    let tdcontactNo = $("<td>").addClass("text-center").text(contactNo);
    let viewProfile = $("<td>")
      .addClass(
        "text-center text-blue-400 font-light hover:cursor-pointer hover:text-accentBlue"
      )
      .text("VIEW PROFILE");

    tr.append(tdStudentNo, tdfullname, tdcontactNo, viewProfile);
    tbody.append(tr);
  }
}
