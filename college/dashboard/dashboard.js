import { getJSONFromURL, postJSONFromURL } from "../scripts/utils.js";

$(document).ready(() => {
  // Add onchange whenever the dashboard is clicked
  $('a[href="#dashboard"]').on("click", function () {
    setTimeout(function () {
      getInitialLogs();
      loadHandlers();
    }, 1000);
  });

  loadHandlers();

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
  chartConfig(
    responseByYear,
    responseByYear_type,
    responseByYear_labels,
    responseByYear_data,
    true,
    redAccent
  );

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
      addLogs(logListContainer);
    } catch (error) {
      // TODO error handling
    }
  }

  getInitialLogs();

  function loadHandlers() {
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
                  <img src="" alt="" class="circle rounded-full bg-gray-400 h-10 w-10 p-5 ">
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
