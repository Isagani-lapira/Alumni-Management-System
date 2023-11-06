$(document).ready(function () {
  //   Line 416
  const sectionQuestion = [];

  const GRADUATE_TRACER_LINK = "../../PHP_process/graduatetracer.php";
  const DEPLOYMENT_TRACER_LINK = "../../PHP_process/deploymentTracer.php";
  const ANSWER_LINK = "../../PHP_process/answer.php";

  $("#tracerbtn").on("click", function () {
    $("#formReport").addClass("hidden");
    $("#TracerWrapper").removeClass("hidden");
    $("#categoryWrapper").removeClass("hidden");
    $("#categoryWrapper").empty();
    $("#tracerRepo").removeClass("hidden");
    $("#questionSetContainer").find(".questionSet, .newQuestionBtn").remove();
    //restart everything in tracer form
    $("#questionSetContainer").empty();
    $("#categoryName").val("");
    retrieveCategory();
  });

  function retrieveCategory() {
    const action = "retrievedCategory";
    const formData = new FormData();
    formData.append("action", action);

    $.ajax({
      url: GRADUATE_TRACER_LINK,
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: (response) => {
        if (response.result == "Success") {
          const length = response.categoryID.length;
          const formTitle = response.tracerTitle;
          const tracerID = response.tracerID;
          $("#formTitle").val(formTitle); //add the form title
          for (let i = 0; i < length; i++) {
            const categoryID = response.categoryID[i];
            const categoryName = response.categoryName[i];

            addCategory(categoryID, categoryName, tracerID);
          }

          // button for adding new category
          const newCategoryBtn = $("<button>")
            .addClass(
              "w-full border border-gray-300 rounded-lg py-3 categoryBtn inactive relative flex gap-2 items-center " +
                "text-gray-400 justify-center hover:text-blue-500 hover:font-semibold"
            )
            .html(
              '<iconify-icon icon="fluent:add-12-filled" width="24" height="24"></iconify-icon> ' +
                "Add Category"
            )
            .on("click", function () {
              // modal for inserting new category
              $("#insertCategoryModal").removeClass("hidden");

              // submitting new category
              $("#addNewCategoryBtn").on("click", function () {
                const newCatVal = $("#categoryInputVal").val().trim();
                if (newCatVal !== "") {
                  // adding new category
                  $("#categoryInputVal")
                    .addClass("border-gray-300")
                    .removeClass("border-red-400");
                  addNewCategory(tracerID, newCatVal);
                } else {
                  $("#categoryInputVal")
                    .removeClass("border-gray-300")
                    .addClass("border-red-400");
                }
              });
            });
          $("#categoryWrapper").append(newCategoryBtn);
        }
      },
      error: (error) => {
        console.log(error);
      },
    });
  }

  // close the insertion of new category
  $("#cancelCatInsertion").on("click", function () {
    $("#insertCategoryModal").addClass("hidden");
  });

  $("#cancelDeployBtn").on("click", function () {
    $("#deploymentModal").addClass("hidden");
  });

  function addCategory(categoryID, categoryName, tracerID) {
    let isEditing = false;

    function enableEditing() {
      // Allows to be editable the content
      categoryBtn
        .attr("contentEditable", "true")
        .focus()
        .html("<u>" + categoryName + "</u>");
      isEditing = true;
    }

    function disableEditing() {
      const editedCategoryText = categoryBtn.text();
      updateCategoryName(categoryID, editedCategoryText); // Update category name

      categoryBtn.click(); // Refresh the display
      categoryBtn.removeAttr("contentEditable"); // Disable the editable text again
      categoryBtn.find("u").remove(); // Remove the underline indicating the editable is disabled
      //update the text of category name
      categoryBtn.text(editedCategoryText);
      $("#categoryName").val(editedCategoryText);

      // refresh by retrieving again the question
      $("#questionSetContainer").find(".questionSet, .newQuestionBtn").remove();
      retrieveCategoryQuestion(categoryID, tracerID);
      isEditing = false; // Back to normal
    }

    // Add edit button
    const editIcon = $(
      '<iconify-icon icon="bx:edit" style="color: white;" width="24" height="24"></iconify-icon>'
    )
      .addClass("absolute top-2 right-2")
      .on("click", function () {
        if (!isEditing) {
          enableEditing();
        }
      });

    const categoryBtn = $("<button>")
      .addClass(
        "w-full border border-gray-300 rounded-lg py-3 categoryBtn inactive relative"
      )
      .text(categoryName);

    // Attach the blur event handler regardless of whether it's in edit mode or not
    categoryBtn.on("blur", function () {
      if (isEditing) {
        disableEditing();
      }
    });

    categoryBtn.on("click", function () {
      if (!isEditing) {
        $(this).append(editIcon);
        $(".categoryBtn").removeClass("active"); // Remove the last active button
        $("#btnSaveChanges").addClass("hidden"); // Hide again the savechanges
        // Retrieve questions
        $(this).addClass("active").removeClass("inactive");
        $("#questionSetContainer")
          .find(".questionSet, .newQuestionBtn")
          .remove(); // Remove the recent retrieve
        retrieveCategoryQuestion(categoryID, tracerID);
      }
    });

    $("#categoryWrapper").append(categoryBtn);
  }

  function retrieveCategoryQuestion(categoryID, tracerID) {
    const action = "readQuestions";
    const formData = new FormData();
    formData.append("action", action);
    formData.append("categoryID", categoryID);

    //process retrieval of data for category questions
    $.ajax({
      url: GRADUATE_TRACER_LINK,
      method: "POST",
      data: formData,
      contentType: false,
      processData: false,
      dataType: "json",
      success: (response) => {
        if (response.response == "Success") {
          response.dataSet.forEach((element) => {
            const categoryID = element.categoryID;
            const categoryName = element.categoryName;
            const questionSets = element.questionSet;

            const container = "#questionSetContainer";
            displayQuestion(
              categoryID,
              categoryName,
              questionSets,
              container,
              tracerID,
              false
            );
          });
        }
      },
      error: (error) => {
        console.log(error);
      },
    });
  }

  function displayQuestion(
    categoryID,
    categoryName,
    questionSets,
    container,
    tracerID,
    isSection = true,
    choiceIDVal = ""
  ) {
    $("#categoryName").val(categoryName);
    const addNewQuestionBtn = $("<button>")
      .addClass(
        "block ml-auto bg-blue-400 text-white hover:bg-blue-500 py-2 my-2 rounded-lg text-sm px-3 newQuestionBtn"
      )
      .text("Add question")
      .on("click", function () {
        // insert new question
        displaySavedChanges();
        $("#newQuestionModal").removeClass("hidden");
        openCreateNewQuestionModal(categoryID, tracerID);
      });

    if (!isSection) $(container).append(addNewQuestionBtn);
    else {
      $("#catIDHolder").val(categoryID);
      $("#formIDHolder").val(tracerID);
      $("#choiceIDHolder").val(choiceIDVal);
    }
    // show all the questions with its choices
    questionSets.forEach((questionData) => {
      const questionID = questionData.questionID;
      const questionTxt = questionData.questionTxt;
      const questionType = questionData.inputType;
      const choices = questionData.choices;

      const choiceInput = $("<option>")
        .attr("value", "Input")
        .text("Input type");
      const choiceRadio = $("<option>")
        .attr("value", "Radio")
        .text("Radio type");
      const choiceDropDown = $("<option>")
        .attr("value", "DropDown")
        .text("DropDown type");
      const choiceCheckbox = $("<option>")
        .attr("value", "Checkbox")
        .text("Checkbox type");

      // Set the selected option based on questionType
      if (questionType === "Input") {
        choiceInput.prop("selected", true);
      } else if (questionType === "Radio") {
        choiceRadio.prop("selected", true);
      } else if (questionType === "DropDown") {
        choiceDropDown.prop("selected", true);
      } else if (questionType === "Checkbox") {
        choiceCheckbox.prop("selected", true);
      }

      const questionChoicesWrapper = $("<div>").addClass("flex flex-col");

      //drop down selection of input type
      const questionTypeDropDown = $("<select>")
        .addClass("p-2 w-full text-gray-500 outline-none")
        .append(choiceInput, choiceRadio, choiceDropDown, choiceCheckbox)
        .on("change", function () {
          displaySavedChanges();
          const newInputType = $(this).val();
          changeInputType(newInputType, questionID, questionChoicesWrapper); //change the type of choices
        });

      // get all the choices for a specific question
      choices.forEach((choice) => {
        const choiceID = choice.choiceID;
        const choice_text = choice.choice_text;
        const isSectionChoice = choice.isSectionChoice;

        const choicesWrapper = $("<div>").addClass(
          "flex gap-1 p-2 wrapperChoices"
        );
        const choiceInput = $("<input>")
          .addClass("border-b border-gray-300 p-2 flex-1")
          .val(choice_text)
          .on("change", function () {
            // change the option text/value
            const newChoiceTextVal = $(this).val();
            displaySavedChanges();
            changeOption(choiceID, newChoiceTextVal);
          });
        const removeChoiceBtn = $(
          '<iconify-icon icon="ant-design:close-outlined" style="color: #626262;" width="20" height="20"></iconify-icon>'
        ).on("click", function () {
          // remove a specific choice
          displaySavedChanges();
          removeChoice(choiceID, choicesWrapper);
        });
        const sectionChoiceBtn = $(
          '<iconify-icon class="sectionBtn" icon="uit:web-section-alt" class="p-2" style="color: #afafaf;" width="20" height="20"></iconify-icon>'
        ).on("click", function () {
          if (!isSectionChoice) createSection(categoryID, choiceID, tracerID);
          //add section per category
          else {
            $("#sectionBody").empty(); //remove the last section question retrieved
            retrievedSectionData(choiceID, tracerID);
          }
        });
        // hover effect
        sectionChoiceBtn.hover(
          function () {
            // Change the icon to the solid version on hover-in
            $(this).attr("icon", "uis:web-section-alt");
          },
          function () {
            // Change the icon back to the original version on hover-out
            $(this).attr("icon", "uit:web-section-alt");
          }
        );

        if (isSection) sectionChoiceBtn.addClass("hidden");

        // add indication for a choice with section
        if (isSectionChoice) {
          // Replace the icon with a different one
          sectionChoiceBtn
            .attr("icon", "uis:web-section-alt")
            .css("color", "#00a86b");
        }

        if (questionType !== "Input")
          choicesWrapper.append(choiceInput, sectionChoiceBtn, removeChoiceBtn);
        questionChoicesWrapper.append(choicesWrapper);
      });

      const questionName = $("<input>")
        .addClass(
          "text-center w-full text-lg border-b border-gray-300 py-3 text-gray-500"
        )
        .val(questionTxt);

      const addOption = $("<button>")
        .addClass("flex items-center gap-2 text-gray-400 m-2")
        .html(
          '<iconify-icon icon="gala:add" style="color: #afafaf;" width="20" height="20"></iconify-icon>' +
            "Add option"
        )
        .hover(
          function () {
            // over
            $(this).css({ color: "#1769AA" }); //for text
            $(this).find("iconify-icon").css({ color: "#1769AA" }); //for icon
          },
          function () {
            // out
            $(this).find("iconify-icon").css({ color: "#afafaf" });
            $(this).css({ color: "#afafaf" });
          }
        )
        .on("click", function () {
          const choicesWrapper = $("<div>").addClass("flex gap-1 p-2");
          const choiceInput = $("<input>")
            .addClass("border-b border-gray-300 p-2 flex-1")
            .attr("placeholder", "Add option")
            .on("change", function () {
              // insert new option
              displaySavedChanges();
              const choiceTextVal = $(this).val();
              let isSectionQuestion = 0;
              if (!isSection) isSectionQuestion = 1;

              insertSectionChoices(
                questionID,
                choiceTextVal,
                isSectionQuestion
              );
            });
          const removeChoiceBtn = $(
            '<iconify-icon icon="ant-design:close-outlined" style="color: #626262;" width="20" height="20"></iconify-icon>'
          ).on("click", function () {
            choicesWrapper.remove();
          });
          choicesWrapper.append(choiceInput, removeChoiceBtn);
          questionChoicesWrapper.append(choicesWrapper);
        });

      const removeQuestionBtn = $(
        '<iconify-icon icon="octicon:trash-24" class="absolute top-0 -right-14 p-3 rounded-md center-shadow remove-question h-max" style="color: #afafaf;" width="24" height="24"></iconify-icon>'
      ).on("click", function () {
        // open confirmation modal
        $(".deleteQuestionPost").removeClass("hidden");
        $("#deleteQuestionBtn").on("click", function () {
          displaySavedChanges();
          $(".deleteQuestionPost").addClass("hidden");
          removeQuestions(questionID, questionWrapper); //delete the question
        });
      });
      const questionWrapper = $("<div>")
        .addClass(
          "p-2 center-shadow rounded-lg w-4/5 mx-auto questionSet relative mb-5"
        )
        .append(
          questionName,
          questionTypeDropDown,
          questionChoicesWrapper,
          addOption,
          removeQuestionBtn
        );

      $(container).append(questionWrapper);
    });
  }

  $("#cancelDelQuestionBtn").on("click", function () {
    $(".deleteQuestionPost").addClass("hidden");
  });

  function insertSectionData(categoryID, choiceID, formID, modal) {
    const questionSet = [];
    $(".questionnaireSection").each(function () {
      const question = $(this).find(".sectionQuestion").val();
      const inputType = $(this).find("select").val();

      const choicesArray = [];
      $(this)
        .find(".choices")
        .each(function () {
          const choiceVal = $(this).val();
          choicesArray.push(choiceVal);
        });

      const questionObj = {
        Question: question,
        InputType: inputType,
        choice: choicesArray,
      };
      questionSet.push(questionObj);
    });
    const data = {
      FormID: formID,
      CategoryID: categoryID,
      ChoiceID: choiceID,
      QuestionSet: questionSet,
    };

    sectionQuestion.push(data);
    processInsertionOfSection(sectionQuestion, modal);
  }

  function processInsertionOfSection(sectionData, modal = "") {
    const action = "addSectionData";
    const formData = new FormData();
    formData.append("action", action);
    formData.append("sectionData", JSON.stringify(sectionData));

    $.ajax({
      url: GRADUATE_TRACER_LINK,
      method: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: (response) => {
        console.log(response);
        if (response == "Success") {
          modal.remove();
          $("#promptMsgSection").removeClass("hidden"); //display the message

          setTimeout(() => {
            $("#promptMsgSection").addClass("hidden"); //hide the message
          }, 3000);
        }
      },
      error: (error) => {
        console.log(error);
      },
    });
  }

  function createSecQuestion(body, isFirst = false) {
    const container = $("<div>").addClass(
      "center-shadow w-4/5 rounded-md border-t-4 border-gray-400 p-3 mx-auto flex flex-col relative mb-2 questionnaireSection "
    );

    const question = $("<input>")
      .addClass(
        "border-b border-gray-400 py-2 px-2 outline-none w-full categoryField text-center text-lg font-bold sectionQuestion"
      )
      .attr("type", "text")
      .attr("placeholder", "Untitled Question");

    // body part
    const bodyPart = $("<div>");

    //input type option
    const optInput = $("<option>").val("Input").text("Input type");
    const optRadio = $("<option>")
      .val("Radio")
      .text("Radio Choice")
      .attr("selected", true);
    const optDropDown = $("<option>").val("DropDown").text("DropDown type");
    const optCheckBox = $("<option>").val("Checkbox").text("Checkbox Type");
    const questionType = $("<select>")
      .append(optRadio, optInput, optDropDown, optCheckBox)
      .addClass("text-gray-400 py-3 outline-none w-full");

    // input field
    const optionContainer = $("<div>");
    const inputWrapper = $("<div>").addClass("flex items-center mb-2");
    const iconRadio = $(
      '<iconify-icon icon="bx:circle" style="color: #afafaf;" width="24" height="24"></iconify-icon>'
    );
    const inputField = $("<input>")
      .addClass("py-2 px-2 outline-none w-4/5 choices")
      .attr("placeholder", "option");
    inputWrapper.append(iconRadio, inputField);
    optionContainer.append(inputWrapper);

    //add choice button
    const addOption = $("<button>")
      .addClass("flex items-center gap-2 text-gray-400 my-2")
      .html(
        '<iconify-icon icon="gala:add" style="color: #afafaf;" width="20" height="20"></iconify-icon>' +
          "Add option"
      )
      .hover(
        function () {
          // over
          $(this).css({ color: "#1769AA" }); //for text
          $(this).find("iconify-icon").css({ color: "#1769AA" }); //for icon
        },
        function () {
          // out
          $(this).find("iconify-icon").css({ color: "#afafaf" });
          $(this).css({ color: "#afafaf" });
        }
      )
      .on("click", function () {
        //add new option
        const newinputWrapper = $("<div>").addClass("flex items-center mb-2");
        const newiconRadio = $(
          '<iconify-icon icon="bx:circle" style="color: #afafaf;" width="24" height="24"></iconify-icon>'
        );
        const newinputField = $("<input>")
          .addClass("py-2 px-2 outline-none w-4/5 flex-1 choices")
          .attr("placeholder", "option");
        const removeOption = $(
          '<iconify-icon icon="ei:close" style="color: #afafaf;" width="20" height="20"></iconify-icon>'
        ).on("click", function () {
          newinputWrapper.remove();
        });
        newinputWrapper.append(newiconRadio, newinputField, removeOption);
        optionContainer.append(newinputWrapper);
      });

    //remove question
    const removeQuestion = $(
      '<iconify-icon icon="octicon:trash-24" class="p-3 rounded-md center-shadow h-max" style="color: #afafaf;" width="24" height="24"></iconify-icon>'
    )
      .on("click", function () {
        container.remove();
      })
      .hover(
        function () {
          // over
          $(this).css("color", "red");
        },
        function () {
          // out
          $(this).css("color", "#afafaf");
        }
      );

    //check if the question is first
    if (!isFirst) removeQuestion.addClass("hidden");

    const addQuestion = $(
      '<iconify-icon class="p-3 rounded-md center-shadow h-max" icon="gala:add" style="color: #347cb5;" width="24" height="24"></iconify-icon>'
    ).on("click", function () {
      //check first if the question before create new is have value
      if (question.val() !== "") {
        question.addClass("border-gray-400").removeClass("border-red-400"); //back to default
        createSecQuestion(body, true);
      } else question.removeClass("border-gray-400").addClass("border-red-400"); //add warning color
    });

    const additionalChoices = $("<div>")
      .addClass("flex flex-col absolute top-0 -right-14 gap-2")
      .append(addQuestion, removeQuestion);

    //add to their corresponding container
    bodyPart.append(questionType, optionContainer, addOption);
    container.append(question, additionalChoices, bodyPart);
    body.append(container);
  }

  function retrievedSectionData(choiceID, tracerID) {
    const action = "getSectionData";
    const formData = new FormData();
    formData.append("action", action);
    formData.append("choiceID", choiceID);

    $.ajax({
      url: GRADUATE_TRACER_LINK,
      method: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: (response) => {
        const data = JSON.parse(response); // Assuming the response is in JSON format
        $("#sectionModalcontainer").removeClass("hidden");
        // Loop through the data and display questions
        data.forEach((questionSet) => {
          const categoryID = questionSet[0].categoryID;
          const status = questionSet[0].status;
          const container = "#sectionBody";

          if (status !== "archived")
            displayQuestion(
              categoryID,
              "",
              questionSet,
              container,
              tracerID,
              true,
              choiceID
            );
        });
      },
      erro: (error) => {
        console.log(error);
      },
    });
  }

  function createSection(categoryID, choiceID, formID) {
    const container = $("<div>").addClass(
      "post modal fixed inset-0 z-50 flex items-center justify-center p-3"
    );

    const modalContainer = $("<div>").addClass(
      "modal-container w-1/2 h-4/5 bg-white rounded-lg p-3 text-greyish_black flex flex-col gap-2 border-t-8 border-blue-400"
    );

    const header = $("<header>")
      .addClass(
        "font-bold text-4xl text-center text-blue-400 py-2 border-b border-gray-300"
      )
      .text("Section");

    // body of modal
    const body = $("<div>").addClass("h-full overflow-y-auto");
    createSecQuestion(body);

    // footer part
    const cancelBtn = $("<button>")
      .addClass("text-gray-400 hover:text-gray-500 text-sm")
      .text("Cancel")
      .on("click", function () {
        //close the modal
        container.remove();
      });

    const saveBtn = $("<button>")
      .addClass(
        "text-white px-4 py-2 rounded-md bg-green-400 hover:bg-green-500"
      )
      .text("Save Section")
      .on("click", function () {
        insertSectionData(categoryID, choiceID, formID, container);
      });
    const footer = $("<div>")
      .addClass("flex justify-end gap-2")
      .append(cancelBtn, saveBtn);

    // append to their corresponding container
    modalContainer.append(header, body, footer);
    container.append(modalContainer);
    $("body").append(container);
  }

  function removeQuestions(questionID, container) {
    const action = "removeQuestion";
    const formData = new FormData();
    formData.append("action", action);
    formData.append("questionID", questionID);

    // process archiving the question
    $.ajax({
      url: GRADUATE_TRACER_LINK,
      method: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: (response) => {
        if (response == "Success") {
          //remove the display of the question
          container.remove();
        }
      },
    });
  }
  function insertSectionChoices(questionID, choiceTextVal, isSectionQuestion) {
    const action = "addChoicesSection";
    const formData = new FormData();
    formData.append("action", action);
    formData.append("questionID", questionID);
    formData.append("choiceText", choiceTextVal);
    formData.append("isSectionQuestion", isSectionQuestion);

    $.ajax({
      url: GRADUATE_TRACER_LINK,
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: (response) => {
        console.log(response);
      },
      error: (error) => {
        console.log(error);
      },
    });
  }

  function removeChoice(choiceID, wrapper) {
    const action = "removeChoice";
    const formData = new FormData();
    formData.append("action", action);
    formData.append("choiceID", choiceID);

    $.ajax({
      url: GRADUATE_TRACER_LINK,
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: (response) => {
        if (response == "Success") wrapper.remove(); // remove the button
      },
      error: (error) => {
        console.log(error);
      },
    });
  }

  function displaySavedChanges() {
    // display saved indicator
    $("#btnSaveChanges")
      .removeClass("hidden")
      .html(
        '<iconify-icon icon="line-md:loading-twotone-loop" style="color: #afafaf;" width="20" height="20"></iconify-icon>' +
          "Saving changes"
      );

    setTimeout(() => {
      $("#btnSaveChanges")
        .removeClass("hidden")
        .html(
          '<iconify-icon icon="dashicons:saved" style="color: #afafaf;" width="20" height="20"></iconify-icon>' +
            "Saved"
        );
    }, 3000);
  }

  function changeOption(choiceID, choiceText) {
    const action = "changeChoiceText";
    const formData = new FormData();
    formData.append("action", action);
    formData.append("choiceText", choiceText);
    formData.append("choiceID", choiceID);

    $.ajax({
      url: GRADUATE_TRACER_LINK,
      method: "POST",
      data: formData,
      contentType: false,
      processData: false,
    });
  }

  function updateCategoryName(categoryID, categoryName) {
    const action = "updateCategoryName";
    //data to be sent
    const formData = new FormData();
    formData.append("action", action);
    formData.append("categoryID", categoryID);
    formData.append("categoryName", categoryName);

    //perform removal
    $.ajax({
      url: GRADUATE_TRACER_LINK,
      method: "POST",
      data: formData,
      contentType: false,
      processData: false,
    });
  }

  // for section adding question
  $(".iconAddModal").on("click", function () {
    const formID = $("#formIDHolder").val();
    const categoryID = $("#catIDHolder").val();
    const choiceID = $("#choiceIDHolder").val();

    $("#newQuestionModal").removeClass("hidden");
    $("#saveNewQuestion").on("click", function () {
      const questionName = $("#newQuestionInputName").val();
      if (questionName !== "")
        retrieveNewQuestionData(
          questionName,
          formID,
          categoryID,
          true,
          choiceID
        );
    });
  });

  $("#addOptionmodal").on("click", function () {
    //add additional choice field
    const fieldContainer = $("<div>").addClass(
      "fieldWrapper flex items-center gap-2"
    );

    const icon = $(
      '<iconify-icon icon="bx:circle" style="color: #afafaf;" width="24" height="24"></iconify-icon>'
    );
    const inputType = $("<input>")
      .addClass("flex-1 py-2")
      .attr("type", "text")
      .attr("placeholder", "Add choice");

    const removeOption = $(
      '<iconify-icon icon="ei:close" class="cursor-pointer" style="color: #afafaf;" width="20" height="20"></iconify-icon>'
    ).on("click", function () {
      fieldContainer.remove();
    });

    fieldContainer.append(icon, inputType, removeOption);
    $(".optionContainer").append(fieldContainer);
  });

  function openCreateNewQuestionModal(categoryID, formID) {
    $("#newQuestionModal").removeClass("hidden");
    $("#saveNewQuestion").on("click", function () {
      const questionName = $("#newQuestionInputName").val();
      if (questionName !== "")
        retrieveNewQuestionData(questionName, formID, categoryID);
    });
  }

  function retrieveNewQuestionData(
    questionName,
    formID,
    categoryID,
    isSectionQuestion = false,
    choiceID = ""
  ) {
    const inputTypeVal = $("#inputTypeModalNew").val();
    // Get all the option choices
    const choices = [];
    $(".fieldWrapper").each(function () {
      let choiceVal = $(this).find('input[type="text"]').val();
      choices.push(choiceVal);
    });

    const QuestionSet = {
      Question: questionName,
      choice: choices,
      InputType: inputTypeVal,
    };

    if (!isSectionQuestion) {
      const data = {
        FormID: formID,
        CategoryID: categoryID,
        Question: questionName,
        InputType: inputTypeVal,
        choices: choices,
      };
      insertNewCategoryQuestion(data); //process insertion of question for non section question
    } else {
      const data = {
        FormID: formID,
        CategoryID: categoryID,
        ChoiceID: choiceID,
        QuestionSet: QuestionSet,
      };
      insertNewQuestionSection(data, choiceID); //insertion of section question
    }
  }

  function insertNewQuestionSection(data, choiceID) {
    const action = "addNewSectionQuestion";
    const formData = new FormData();
    formData.append("action", action);
    formData.append("newQuestion", JSON.stringify(data));

    $.ajax({
      url: GRADUATE_TRACER_LINK,
      method: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: (response) => {
        if (response == "Success") {
          $("#sectionBody").empty();
          retrievedSectionData(choiceID); //display the newly refresh section questions
          restartAllVal();
          $("#newQuestionModal").addClass("hidden");
          //display prompt
          $("#promptMsgNewQuestion").removeClass("hidden");
          setTimeout(() => {
            $("#promptMsgNewQuestion").addClass("hidden");
          }, 3000);
        }
      },
    });
  }

  function insertNewCategoryQuestion(data) {
    const action = "addNewQuestionForCategory";
    const formData = new FormData();
    formData.append("action", action);
    formData.append("data", JSON.stringify(data));

    $.ajax({
      url: GRADUATE_TRACER_LINK,
      method: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: (response) => {
        if (response == "Success") {
          restartAllVal();
          $("#newQuestionModal").addClass("hidden");
          //display prompt
          $("#promptMsgNewQuestion").removeClass("hidden");
          setTimeout(() => {
            $("#promptMsgNewQuestion").addClass("hidden");
          }, 3000);
        }
      },
      error: (error) => {
        console.log(error);
      },
    });
  }

  function restartAllVal() {
    $("#newQuestionModal").addClass("hidden"); //hide again the modal

    //restart all the value
    $("#newQuestionInputName").val("");
    $("#inputTypeModalNew").val("");
    $(".fieldWrapper:first input.choicesVal").val("");
    $(".fieldWrapper:not(:first)").remove(); // remove all the choices available and assign it with no value
  }

  function changeInputType(inputType, questionID, container) {
    const action = "changeTypeOfInput";
    const formData = new FormData();
    formData.append("action", action);
    formData.append("inputType", inputType);
    formData.append("questionID", questionID);

    $.ajax({
      url: GRADUATE_TRACER_LINK,
      method: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: (response) => {
        if (response == "Success") {
          if (inputType == "Input") {
            $(container).find(".wrapperChoices").addClass("hidden");
          } else $(container).find(".wrapperChoices").removeClass("hidden");
        }
      },
    });
  }

  //hide all the modal when click outside or escape
  $(document).on("keydown", (event) => {
    if (event.key == "Escape" || event.keyCode == 27)
      $(".modal").each(function () {
        $(this).addClass("hidden");
      });
  });

  // close the section modal
  $("#sectionModalcontainer").on("click", function (e) {
    const target = e.target;
    const modal = $("#sectionModal");

    if (!modal.is(target) && !modal.has(target).length) {
      $("#sectionModalcontainer").addClass("hidden");
    }
  });

  // deploy tracer
  $("#deployTracerBtn").on("click", function () {
    $("#deploymentModal").removeClass("hidden"); //open the confirmation for deployment
  });

  // deploy new tracer
  $("#confirmDeployTracerBtn").on("click", function () {
    const action = "deployNewTracer";
    const formData = new FormData();
    formData.append("action", action);

    $.ajax({
      url: DEPLOYMENT_TRACER_LINK,
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: (response) => {
        console.log(response);
      },
      error: (error) => {
        console.log(error);
      },
    });
  });

  function addNewCategory(formID, categoryValue) {
    const categoryName = categoryValue;
    const action = "insertNewCategory";
    const formData = new FormData();

    //data to be sent
    formData.append("action", action);
    formData.append("categoryName", categoryName);
    formData.append("formID", formID);

    //proccess insertion
    $.ajax({
      url: GRADUATE_TRACER_LINK,
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: (response) => {
        if (response.result == "Success") {
          // hide and restart the insertion category modal
          $("#insertCategoryModal").addClass("hidden");
          $("#categoryInputVal").val();
          $("#categoryWrapper").empty();
          retrieveCategory();
        }
      },
    });
  }

  getTracerPercentage(); //display the percentage
  function getTracerPercentage() {
    const action = "getTotalTracer";
    const formData = new FormData();
    formData.append("action", action);

    // get the percentage
    console.log("get percentage");
    $.ajax({
      url: GRADUATE_TRACER_LINK,
      method: "POST",
      data: formData,
      contentType: false,
      processData: false,
      dataType: "json",
      success: (response) => {
        $("#alreadyAnswer").text(response.answered + "%");
        $("#notYetAnswering").text(response.notyetAnswering + "%");
        console.log("success getting percentage");
        console.log(response);
      },
      error: (error) => {
        console.log(error);

        console.log("error getting percentage");
      },
    });
  }

  // Tracer Chart
  //   START HERE
  // get the total completion chart
  // $("#formLi").on("click", function () {
  //   $("#forms-tab").removeClass("hidden");
  //   retrieveCompletionData();
  //   retrieveCollegeParticipation();
  //   addCategorySelection();
  // });

  $('a[href="#forms"]').on("click", function () {
    setTimeout(function () {
      // remove the loading screen

      bindHandlers();
    }, 1000);
  });
  // make it run the first load
  bindHandlers();

  function bindHandlers() {
    retrieveCollegeParticipation();
    retrieveCompletionData();
    addCategorySelection();

    const completionChart = $("#completionChart")[0].getContext("2d");
    const completionChartObj = new Chart(completionChart, {
      type: "pie",
      data: {
        labels: ["Completed", "Waiting"],
        datasets: [
          {
            label: "# of Votes",
            data: [35, 500],
            borderWidth: 1,
            backgroundColor: ["#CA472F", "#8DDDD0"],
          },
        ],
      },
      options: {
        responsive: true,
      },
    });

    function retrieveCollegeParticipation() {
      console.log("college particiaption");
      const collegeChart = $("#respondentPerCol")[0].getContext("2d");
      const chartData = {
        type: "bar",
        data: {
          labels: [],
          datasets: [
            {
              label: "# of College Alumni already finished answering",
              data: [],
              borderWidth: 1,
            },
          ],
        },
        options: {
          responsive: true, // Make the chart responsive
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                stepSize: 1, // Specify the step size for Y-axis values
              },
            },
          },
        },
      };
      const myChart = new Chart(collegeChart, chartData);
      // Function to update the chart with data
      function updateChart(labels, data) {
        myChart.data.labels = labels;
        myChart.data.datasets[0].data = data;
        myChart.update();
      }

      const action = "countDonePerCourse";
      const formData = new FormData();
      formData.append("action", action);

      $.ajax({
        url: ANSWER_LINK,
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: (response) => {
          // collect data retrieve
          console.log("participation", response);
          const labels = [];
          const countData = [];
          response.forEach((data) => {
            const collegeName = data.colCode;
            const dataCount = data.alumniCountFinished;

            labels.push(collegeName);
            countData.push(dataCount);
          });

          updateChart(labels, countData); //update chart using the data retrieved
        },
        error: (error) => {
          console.error("failed", error);
        },
      });
    }

    // college alumni chart
    function retrieveCompletionData() {
      const action = "completionStatus";
      const formData = new FormData();
      // formData.append("action", action);
      // get the colcode

      // formData.append("colCode", $("#colCode-hidden").val());

      $.ajax({
        url: ANSWER_LINK,
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: (response) => {
          const completedCount = response.completed;
          const waitingCount = response.waiting;

          // update the chart based on the retrieve data
          completionChartObj.data.datasets[0].data = [
            completedCount,
            waitingCount,
          ];
          completionChartObj.update();
        },
      });
    }

    // add category to the selection
    function addCategorySelection() {
      const action = "retrievedCategory";
      const formData = new FormData();
      formData.append("action", action);
      $.ajax({
        url: GRADUATE_TRACER_LINK,
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: (response) => {
          if (response.result == "Success") {
            // add the category to selection of category
            const length = response.categoryID.length;

            for (let i = 0; i < length; i++) {
              const categoryID = response.categoryID[i];
              const categoryName = response.categoryName[i];

              const option = $("<option>").text(categoryName).val(categoryID);
              $("#categorySelection").append(option);
            }
          }
        },
        error: (error) => {
          console.log(error);
        },
      });
    }

    // add question
    $("#categorySelection").on("change", function () {
      const categoryVal = $(this).val();
      const action = "readQuestions";
      const formData = new FormData();
      formData.append("action", action);
      formData.append("categoryID", categoryVal);

      // retrieve all question that is in category
      $.ajax({
        url: GRADUATE_TRACER_LINK,
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "JSON",
        success: (response) => {
          $("#questionSelection").find(":not(:first-child)").remove();
          if ((response.response = "Success")) {
            const dataSet = response.dataSet[0].questionSet;
            const choicesData = [];
            dataSet.forEach((data) => {
              let questionID = data.questionID;
              let questionTxt = data.questionTxt;
              let inputType = data.inputType;

              // only question with choices
              if (inputType !== "Input") {
                let option = $("<option>").text(questionTxt).val(questionID);
                $("#questionSelection").append(option);
              }
            });
          }
        },
        error: (error) => {
          console.log(error);
        },
      });
    });

    $("#questionSelection").on("change", function () {
      let value = $(this).val();

      $("#displayChart")
        .removeClass("off")
        .addClass("on")
        .on("click", function () {
          displayChartForQuestion(value);
        });
    });

    function displayChartForQuestion(questionID) {
      const action = "getQuestionChoices";
      const formData = new FormData();
      formData.append("action", action);
      formData.append("questionID", questionID);

      let labels = [];
      let counts = [];
      // get all the choices in  a particular question
      $.ajax({
        url: GRADUATE_TRACER_LINK,
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "JSON",
        success: (response) => {
          const length = response.length;
          for (let i = 0; i < length; i++) {
            // choice and its corresponding answers chosen
            const choiceText = response[i].choiceText;
            const count = response[i].count;
            labels.push(choiceText);
            counts.push(count);
          }

          typeChartSelection = $("#typeChartSelection").val();

          if (typeChartSelection != "")
            updateQuestionChartData(labels, counts, typeChartSelection);
          else updateQuestionChartData(labels, counts);
          labels = [];
          counts = [];
        },
      });
    }

    const colors = [
      "#E6B0AA",
      "#D7BDE2",
      "#A9CCE3",
      "#A3E4D7",
      "#A9DFBF",
      "#F9E79F",
      "#F5CBA7",
      "#D5DBDB",
      "#D5DBDB",
      "#AEB6BF",
      "#8D6E63",
      "#00BCD4",
      "#78909C",
      "#C0CA33",
      "#117864",
      "#212F3C",
    ];

    function updateQuestionChartData(labels, data, typeChartSelection = "pie") {
      // changing the chart based on reference of the user
      questionChartObj.data.labels = labels; //choices as labels
      questionChartObj.data.datasets[0].data = data; //count per choices
      questionChartObj.config.type = typeChartSelection; //type of chart
      const questionText = $("#questionSelection option:selected").text(); //label for chart the selected option text
      questionChartObj.data.datasets[0].label = "# of Votes in " + questionText;
      const backgroundColors = [];
      // background colors
      for (let i = 0; i < data.length; i++) {
        backgroundColors.push(colors[i]);
      }

      if (typeChartSelection !== "pie") {
        console.log("pumasok");
        questionChartObj.options.scales = {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1, // Specify the step size for Y-axis values
            },
          },
        };
      }
      questionChartObj.data.datasets[0].backgroundColor = backgroundColors;
      questionChartObj.update();
    }

    const questionChart = $("#chartPerQuestion")[0].getContext("2d");
    const questionChartObj = new Chart(questionChart, {
      type: "bar",
      data: {
        datasets: [
          {
            label: "# of Votes",
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
      },
    });
  }
});
