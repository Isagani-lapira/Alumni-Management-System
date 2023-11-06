$(document).ready(function () {
  const RECORD_URL = "./records/apiRecords.php?filter=alumni";

  // Binds the section link in order to reload the list whenever the section is clicked
  $('a[data-link="tracer-record"]').on("click", function () {
    // refreshList();
    setTimeout(function () {
      setHandlers();

      console.log('refreshed the handlers of "tracer-record"');
    }, 1000);
  });

  setHandlers();

  function setHandlers() {
    $("#alumni-record-table").DataTable({
      ajax: {
        url: RECORD_URL,
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
          targets: [3, 4],
        },
      ],
    });
  }

  $("#alumni-month-table").DataTable().clear().draw();
  $("#alumni-month-table").DataTable().ajax.reload();
});
