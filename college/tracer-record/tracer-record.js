import { getJSONFromURL, postJSONFromURL } from "../scripts/utils.js";
$(document).ready(function () {
  const RECORD_URL = "./tracer-record/apiTracer.php";
  const GET_DEPLOYMNENT_URL =
    "./tracer-record/apiTracer.php?action=get_deployments";
  const GET_USER_ANSWER_URL =
    "./tracer-record/apiTracer.php?action=get_user_answers";
  const GET_ALL_ANSWERED_URL =
    "./tracer-record/apiTracer.php?action=get_all_answered";
  const GET_LATEST_DEPLOYMENT_URL =
    "./tracer-record/apiTracer.php?action=get_latest_deployment";

  // Binds the section link in order to reload the list whenever the section is clicked
  $('a[data-link="tracer-record"]').on("click", function () {
    // refreshList();
    setTimeout(function () {
      setHandlers();

      console.log('refreshed the handlers of "tracer-record"');
    }, 1000);
  });
  /*

  $("#alumni-month-table").DataTable().clear().draw();
  $("#alumni-month-table").DataTable().ajax.reload();

  */
  setHandlers();

  function populateDetails(data, container) {
    // make a design for the details.
    // question_text, inputType, answer_text
    container.html("");
    // make it in a form of a table
    let html = `<table class="table table-bordered table-striped overflow daisy-table daisy-table-zebra ">
    <thead class>
      <tr class="bg-accent text-white  rounded-tl-md">
        <th>Question</th>
        <th>Input Type</th>
        <th>Answer</th>
      </tr>
    </thead>
    <tbody>`;
    // loop through the data
    data.forEach((row) => {
      html += `<tr>
      <td>${row.question_text}</td>
      <td>${row.inputType}</td>
      <td>${row.answer_text}</td>
    </tr>`;
    });

    html += `</tbody>
    </table>`;
    container.html(html);
  }

  function setHandlers() {
    // set handler on the view-person-btn in the table
    $("#tracer-table").on("click", "label", async function () {
      // query the details of the person
      const personID = $(this).data("id");
      // data-deploy-id
      const deployID = $(this).data("deploy-id");
      console.log(personID, deployID);
      const container = $("#form-details-container");
      try {
        //   get the user details
        const res = await getJSONFromURL(
          GET_USER_ANSWER_URL + `&personID=${personID}&deploymentID=${deployID}`
        );
        // check if there is a response
        if (res.success) {
          // populate
          populateDetails(res.data, container);
        } else {
          // show error
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Something went wrong!",
          });
        }

        console.log(res);
      } catch (error) {
        console.log(error);
        // make sweet alert dialog
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Something went wrong!",
        });
      }
    });

    $("#tracer-table").DataTable({
      ajax: {
        url: GET_LATEST_DEPLOYMENT_URL,
        dataSrc: "data",
      },
      paging: true,
      ordering: true,
      info: false,
      lengthChange: false,
      searching: true,
      pageLength: 10,
      columns: [
        /**
         *       "personID": "User23\/11\/03 02:47:21-1533",
      "fullname": "Isagani Lapira Jr",
      "batchYr": 2023,
      "status": "ongoing",
      "courseID": 1,
      "courseName": "Associate in Computer Technology"
      tracer_deployID
         */
        { data: "fullname", width: "25%" },
        { data: "batchYr" },
        { data: "courseName" },
        { data: "status" },
        {
          data: null,
          render: function (data, type, row) {
            // Define the buttons for the Actions column
            return `
                          <label for="view-person-modal" class="view-person-btn daisy-btn daisy-btn-sm daisy-btn-info daisy-btn-outline " data-deploy-id=${row.tracer_deployID} data-id="${row.personID}">View Details</label>
                      `;
          },
        },
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
          targets: [1, 2],
        },
      ],
    });
  }
});
