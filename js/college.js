$(document).ready(function(){
    $(".college").on("click", function () {
        var colName = $(this).data("colname");
        var data = {
          action: "read",
          query: true,
        };
    
        var formData = new FormData();
        formData.append("data", JSON.stringify(data));
        formData.append("college", colName);
        $('.courses-offered div').empty()
        $.ajax({
          url: "../PHP_process/collegeDB.php",
          type: "POST",
          data: formData,
          processData: false,
          contentType: false,
          dataType: "json",
          success: (response) => {

            if (response.result == "Success") {
              $(".individual-col").removeClass("hidden");
              $(".college-content").addClass("hidden");
    
              //fetch the data that has been retrieve
              let colData = response;
              let colCode = colData["colCode"];
              let colName = colData["colName"];
              let colEmailAdd = colData["colEmailAdd"];
              let colContactNo = colData["colContactNo"];
              let colWebLink = colData["colWebLink"];
              let colLogo = colData["colLogo"];
              let colDean = colData["colDean"];
              let colDeanImg = colData["colDeanImg"];
              let colAdmin = colData["colAdminName"];
              let colAdminImg = colData["colAdminImg"];
              let courses = colData['courses'];

              
              //add the images
              let logo = imgFormat + colLogo;
              let deanImgFormat = imgFormat + colDeanImg;
              let adminImgFormat = imgFormat + colAdminImg;
              let deanImg = colDeanImg == "" ? defaultProfileSrc : deanImgFormat; //check if still no value
              let adminImg = colAdminImg == "" ? defaultProfileSrc : adminImgFormat;
    
              //display the data
              $("#colLogo").attr("src", logo);
              $("#deanImg").attr("src", deanImg);
              $("#adminImg").attr("src", adminImg);
    
              $("#colName").text(colName + "(" + colCode + ")");
              $("#collegeCode").text(colCode);
              $("#colContact").text(colContactNo);
              $("#colEmail").text(colEmailAdd);
              $("#colWebLink").attr("href", colWebLink).text(colWebLink);
              colDean = colDean == null ? "No inserted dean yet" : "MR. " + colDean;
              $("#colDean").text(colDean);
              $("#colAdminName").text("MR. " + colAdmin);

              // adding courses
              if(courses.length>0){
                $('.courses-offered p').addClass('hidden')
                for(let i=0; i<courses.length; i++){
                    $('.courses-offered div').append('<p>'+courses[i]+'</p>')
                }

              }else $('.courses-offered p').removeClass('hidden')
              
            } 
          }

        });
      });
})