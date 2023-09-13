// function fetchResource(url = "", callback = null) {
//   $.ajax({
//     url: "php/studentData.php",
//     method: "GET",
//     dataType: "json",
//     processData: false,
//     contentType: false,
//     success: (response) => {
//       if (response.response == "Successful") {
//         console.log("fetch " + url + " successs");
//         if (!callback) {
//           console.log(response);
//           return response;
//         } else callback(response);
//       }
//     },
//     error: (error) => {
//       console.error(error);
//       console.log("i ran");
//       if (!callback) {
//         return null;
//       } else callback(response);
//     },
//   });
// }

async function getJSONResource(url) {
  const response = await fetch(url);
  return await response.json();
}

function setStudentInfo() {}

function setAlumniInfo(data) {
  const tbody = $("#alumniTB");
  // tbody.ht;

  // loops through and adds data to every row.
  for (const alumni of data) {
    let tr = $("<tr>");
    let tdStudentNo = $("<td>")
      .addClass("text-center font-bold")
      .text(alumni["studNo"]);
    // Css for rounded logo of picture
    // <div class="w-10 h-10 rounded-full border border-accent"></div>
    let tdfullname = $("<td>").append(
      `                    <div class="flex items-center justify-start">
                        
                        <span class="ml-2">${alumni["fullName"]}</span>
                    </div>`
    );
    // .addClass("text-center")
    // .text(student["fullName"]);
    let tdBatch = $("<td>").addClass("text-center").text(alumni["contactNo"]);
    let tdEmpStatus = $("<td>")
      .addClass("text-center")
      .text(alumni["employmentStatus"]);
    let viewProfile = $("<td>")
      .addClass(
        "text-center text-blue-400 font-light hover:cursor-pointer hover:text-accentBlue"
      )
      .text("VIEW PROFILE");

    tr.append(tdStudentNo, tdfullname, tdBatch, tdEmpStatus, viewProfile);
    tbody.append(tr);
  }
}
function setStudentTB(data) {
  const tbody = $("#studentTB");
  tbody.ht;

  // loops through and adds data to every row.
  for (const student of data) {
    let tr = $("<tr>");
    let tdStudentNo = $("<td>")
      .addClass("text-center font-bold")
      .text(student["studNo"]);
    // Css for rounded logo of picture
    // <div class="w-10 h-10 rounded-full border border-accent"></div>
    let tdfullname = $("<td>").append(
      `                    <div class="flex items-center justify-start">
                        
                        <span class="ml-2">${student["fullName"]}</span>
                    </div>`
    );
    // .addClass("text-center")
    // .text(student["fullName"]);
    let tdcontactNo = $("<td>")
      .addClass("text-center")
      .text(student["contactNo"]);
    let viewProfile = $("<td>")
      .addClass(
        "text-center text-blue-400 font-light hover:cursor-pointer hover:text-accentBlue"
      )
      .text("VIEW PROFILE");

    tr.append(tdStudentNo, tdfullname, tdcontactNo, viewProfile);
    tbody.append(tr);
  }
}

// function fetchStudentRecord()

$(document).ready(async function () {
  // fetch student data
  const result = await getJSONResource("php/studentData.php");
  console.log(result);
  // remove loading
  // sets the table
  setStudentTB(result.result);
});
