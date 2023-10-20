import { getJSONFromURL, postJSONFromURL } from "../scripts/utils.js";

$(document).ready(() => {
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

  // Handles the logs
  $("#logDateSelect").on("change", async function () {
    const selectedDate = $(this).val();
    console.log("changed the date!", selectedDate);

    const formData = new FormData();
    formData.append("action", "filterDate");
    formData.append("date", selectedDate);

    // send the request to the server
    const result = await postJSONFromURL("dashboard/logData.php", formData);

    console.log(result);

    // change the DOM to display the logs
  });

  /**
   * Make a request to the server to retrieve the logs
   *
   * on change, the data will be filtered by the selected date
   *
   *
   */
});

// TODO add function to be easier later
