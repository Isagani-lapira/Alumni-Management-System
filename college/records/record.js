import { getJSONFromURL, postJSONFromURL } from "../scripts/utils.js";

// initialize jquery
$(document).ready(function () {
  $("#reportdaterange").daterangepicker();

  // add onchange whenever the link is clicked
  $('a[href="#records"]').on("click", function () {
    setTimeout(function () {
      // loadHandlers();
      updateAlumniTable();
      updateStudentTable();
      // updateAllTable();
      setHandlers();
    }, 1000);
  });

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

  updateAlumniTable();
  updateStudentTable();
  // updateAllTable();

  setHandlers();

  function setHandlers() {
    // view modal
    $("table").on("click", 'label[for="view-modal"]', function () {
      // get the id
      const id = $(this).attr("data-id");
      console.log(id);

      // get the data from the api
      getJSONFromURL(
        "./records/apiRecords.php?filter=person&personID=" + id
      ).then((data) => {
        console.log(data);
        // set the data to the modal
        $("#view-modal #studNo").text(data.studNo);
        $("#view-modal #fullName").text(data.fullName);
        $("#view-modal #contactNo").text(data.contactNo);
        $("#view-modal #email").text(data.email);
        $("#view-modal #address").text(data.address);
        $("#view-modal #currentYear").text(data.currentYear);
        $("#view-modal #batchYr").text(data.batchYr);
        $("#view-modal #employmentStatus").text(data.employmentStatus);
      });
    });

    $("#select-user-filter").on("change", function () {
      const selected = $(this).val();
      console.log(selected);
      if (selected == "student") {
        $(".alumni-table-container").addClass("hidden");
        $(".all-table-container").addClass("hidden");

        $(".student-table-container")
          .removeClass("hidden")
          // Remove the width of the table. Datatable makes the width 0 by default
          .find("table")
          .css("width", "");
      } else if (selected == "alumni") {
        $(".student-table-container").addClass("hidden");
        $(".alumni-table-container")
          .removeClass("hidden")
          .find("table")
          .css("width", "");
        $(".all-table-container").addClass("hidden");
      } else if (selected == "all") {
        $(".student-table-container").addClass("hidden");
        $(".alumni-table-container").addClass("hidden");
        $(".all-table-container")
          .removeClass("hidden")
          .find("table")
          .css("width", "");
      }
    });
  }

  function updateAllTable() {
    console.log("updating all table");
    $("#all-record-table").DataTable({
      ajax: {
        url: "./records/apiRecords.php?filter=all",
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
        // { data: "courseCode" },
        { data: "contactNo" },
        // {
        // data: null,
        // render: function (data, type, row) {
        //   // Define the buttons for the Actions column
        //   return `
        //               <label for="view-modal" class="daisy-btn daisy-btn-secondary"  data-id="${row.personID}">View</label>
        //           `;
        // },
        // },
      ],
      // searchPanes: {
      //   viewTotal: true,
      // },
      // dom: "Plfrtip",
    });
  }

  function updateStudentTable() {
    $("#student-record-table").DataTable({
      ajax: {
        url: "./records/apiRecords.php?filter=student",
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
        { data: "courseCode" },

        { data: "contactNo" },
        { data: "currentYear" },
        // {
        //   data: null,
        //   render: function (data, type, row) {
        //     // Define the buttons for the Actions column
        //     return `
        //                 <label for="view-modal" class="daisy-btn" data-type="student" data-id="${row.personID}">View</label>
        //             `;
        //   },
        // },
      ],
      searchPanes: {
        viewTotal: true,
        layout: "columns-2",
      },
      dom: "Plfrtip",
      columnDefs: [
        {
          searchPanes: {
            show: true,
            layout: "columns-3",
          },
          targets: [2, 4],
        },
      ],
    });
  }

  function updateAlumniTable() {
    // remove the loading screen

    $("#alumni-record-table").DataTable({
      ajax: {
        url: "./records/apiRecords.php?filter=alumni",
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
        // { data: "courseCode" },
        { data: "contactNo" },
        { data: "batchYr" },
        { data: "employment_status" },
        // {
        //   data: null,
        //   render: function (data, type, row) {
        //     // Define the buttons for the Actions column
        //     return `
        //                 <label for="view-modal" class="daisy-btn" data-type="alumni" data-id="${row.personID}">View</label>
        //             `;
        //   },
        // },
      ],
      searchPanes: {
        viewTotal: true,
        layout: "columns-3",
      },
      dom: "Plfrtip",
      columnDefs: [
        {
          searchPanes: {
            show: true,
          },
          targets: [2, 4, 5],
        },
      ],
    });
  }
});
