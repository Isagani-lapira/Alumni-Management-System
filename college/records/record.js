import { getJSONFromURL, postJSONFromURL } from "../scripts/utils.js";

// initialize jquery
$(document).ready(function () {
  $("#reportdaterange").daterangepicker();

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

  // const result = await getJSONFromURL();
  // console.log(result);
  // remove loading
  // sets the table
  // setStudentTB(result.result);

  updateAlumniTable();
  updateStudentTable();

  function updateStudentTable() {
    $("#student-record-table").DataTable({
      ajax: {
        url: "./records/apiRecords.php?student=all",
        dataSrc: "",
      },
      paging: true,
      ordering: true,
      info: false,
      lengthChange: false,
      searching: true,
      pageLength: 10,
      columns: [
        { data: "studNo", width: "25%" },
        { data: "full_name" },
        { data: "contactNo" },
        { data: "currentYear" },
        {
          data: null,
          render: function (data, type, row) {
            // Define the buttons for the Actions column
            return `
                        <label for="view-modal" class="daisy-btn" data-id="${row.personID}">View</label>
                    `;
          },
        },
      ],
    });
  }

  function updateAlumniTable() {
    // remove the loading screen

    $("#alumni-record-table").DataTable({
      ajax: {
        url: "./records/apiRecords.php?alumni=all",
        dataSrc: "",
      },
      paging: true,
      ordering: true,
      info: false,
      lengthChange: false,
      searching: true,
      pageLength: 10,
      columns: [
        { data: "studNo", width: "25%" },
        { data: "full_name" },
        { data: "contactNo" },
        { data: "batchYr" },
        { data: "employment_status" },
        {
          data: null,
          render: function (data, type, row) {
            // Define the buttons for the Actions column
            return `
                        <label for="view-modal" class="daisy-btn" data-id="${row.personID}">View</label>
                    `;
          },
        },
      ],
    });
  }
});
