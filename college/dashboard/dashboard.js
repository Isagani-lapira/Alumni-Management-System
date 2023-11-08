import { getJSONFromURL, postJSONFromURL } from "../scripts/utils.js";

$(document).ready(() => {
  let dataArray = [];

  // Add onchange whenever the dashboard is clicked
  $('a[href="#dashboard"]').on("click", function () {
    setTimeout(function () {
      let dataArray = [];
      getInitialLogs();
      loadHandlers();

      // retrievedLast5YearResponse();
    }, 1000);
  });

  loadHandlers();
  // get this later
  getInitialLogs();

  const redAccent = "#991B1B";
  const blueAccent = "#2E59C6";

  //chart for response by year
  const responseByYear = document.getElementById("responseByYear");
  const responseByYear_labels = [
    "2021",
    "2020",
    "2019",
    "2018",
    "2017",
    "2016",
    "2015",
    "2014",
  ];
  const responseByYear_data = [1000, 500, 247, 635, 323, 393, 290, 860];
  const responseByYear_type = "line";
  // FIXME change later
  // chartConfig(
  //   responseByYear,
  //   responseByYear_type,
  //   responseByYear_labels,
  //   responseByYear_data,
  //   true,
  //   redAccent
  // );

  function chartConfig(chartID, type, labels, data, responsive, colors) {
    //the chart
    new Chart(chartID, {
      type: type,
      data: {
        labels: labels,
        datasets: [
          {
            backgroundColor: colors,
            data: data,
            borderWidth: 1,
            borderColor: redAccent,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        scales: {
          y: {
            beginAtZero: true,
          },
        },
        plugins: {
          legend: {
            display: false,
            position: "bottom",
            labels: {
              font: {
                weight: "bold",
              },
            },
          },
        },
      },
    });
  }

  // Get initial logs
  async function getInitialLogs() {
    // send the request to the server
    const formData = new FormData();
    formData.append("action", "filterDate");
    formData.append("date", "today");

    try {
      const response = await postJSONFromURL("dashboard/logData.php", formData);

      console.log(response);
      const logListContainer = $("#logListContainer");
      logListContainer.empty();
      addLogs(logListContainer, response.result);
    } catch (error) {
      // TODO error handling
    }
  }

  function loadHandlers() {
    retrievedLast5YearResponse();

    $("#printLogsBtn").on("click", function () {
      const jsonString = JSON.stringify(dataArray);
      window.open(
        "dashboard/logtemplate.html?data=" + encodeURIComponent(jsonString),
        "_blank"
      );
    });

    // retrieve dashboard response
    function retrievedLast5YearResponse() {
      const action = "retrieveRespondent";
      const formData = new FormData();
      formData.append("action", action);

      $.ajax({
        url: "../../PHP_process/deploymentTracer.php",
        method: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        success: (response) => {
          let labels = [];
          let dataCount = [];
          if (response.response == "Success") {
            const data = response;
            let length = data.year.length;
            let lastYear = 0;
            for (let i = 0; i < length; i++) {
              let year = data.year[i];
              let respondent = data.respondent[i];

              lastYear = year;
              labels.push(year);
              dataCount.push(respondent);
            }

            const maxPrevYear = 4;
            const defaultRespondentCount = 0;
            // if the data doesnt have 5 year previous set it as default zero
            if (length != maxPrevYear) {
              while (length <= maxPrevYear) {
                lastYear--; //decreasing year from the last year retrieve
                labels.push(lastYear);
                dataCount.push(defaultRespondentCount);
                length++;
              }
            }

            labels.reverse();
            dataCount.reverse();

            // update the graph
            objResponse.data.labels = labels;
            objResponse.data.datasets[0].data = dataCount;
            objResponse.update();
          }
        },
      });
    }
    const responseByYear = $("#responseByYear")[0].getContext("2d");
    const objResponse = new Chart(responseByYear, {
      type: "line",
      data: {
        datasets: [
          {
            label: "# of Response",
            borderWidth: 1,
            borderColor: "#991B1B", // Set the line color
            backgroundColor: "#991b1b",
            borderWidth: 1,
            tension: 0.1,
          },
        ],
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1, // Specify the step size for Y-axis values
            },
          },
        },
      },
    });

    // Handles the logs
    $("#logDateSelect").on("change", async function () {
      const selectedDate = $(this).val();

      const formData = new FormData();
      formData.append("action", "filterDate");
      formData.append("date", selectedDate);

      // send the request to the server
      try {
        const response = await postJSONFromURL(
          "dashboard/logData.php",
          formData
        );

        console.log(selectedDate, response);
        const logListContainer = $("#logListContainer");
        logListContainer.empty();
        addLogs(logListContainer, response.result);
        console.log("done");
      } catch (error) {
        // TODO error handling
        console.log("error", error);
      }
    });
  }

  // handles the DOM manipulation of the logs
  function addLogs(container, datalist) {
    // change the DOM to display the logs

    console.log(datalist);
    dataArray = datalist;

    // If there's no data
    if (!datalist || datalist.length == 0) {
      const element = `
      <li class="flex justify-center items-center h-40">
        <p class="text-gray-500">No logs found</p>
      </li>
      `;
      container.append(element);
      return;
    }

    for (const data of datalist) {
      const datetime = moment(data.timestamp).format(
        "MMMM D, YYYY [at] hh:mm:ss A"
      );
      const element = `
            <li class="">
              <div class="flex justify-stretch actionWrapper items-center log-item">
                  <div class=" ms-2 font-light flex-1">
                      <p class="text-accent">${data.details}</p>
                  </div>
                  <span class="text-gray-500 ">${datetime}</span>
              </div>
            </li>
            `;
      container.append(element);
    }
  }
});
