<?php




class TracerForm
{

    // store the database connection
    private $conn;

    // initialize object with database connection
    public function __construct(mysqli $db)
    {
        $this->conn = $db;
    }



    public function get_user_answers($personID, $deploymentID)
    {
        $smt = $this->conn->prepare("SELECT
        TQ.questionID,
        TQ.question_text,
        TQ.inputType,
        CASE
            WHEN TQ.inputType = 'Input' THEN AD.answer_txt
            ELSE QC.choice_text
        END AS answer_text
    FROM
        tracer_question TQ
    INNER JOIN
        answer_data AD ON TQ.questionID = AD.questionID
    INNER JOIN
        answer A ON AD.answerID = A.answerID
    LEFT JOIN
        questionnaire_choice QC ON AD.choiceID = QC.choiceID
    WHERE
        A.tracer_deployID = ?
        AND A.personID = ? ; ");

        $smt->bind_param("ss", $deploymentID, $personID);
        $smt->execute();
        $result = $smt->get_result();
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function get_all_person_answered($deploymentID)
    {
        $smt = $this->conn->prepare("SELECT
        P.personID,
        CONCAT(P.fname, ' ', P.lname) AS fullname,
        AL.batchYr ,A.status,
        C.courseID,
        C.courseName,
        A.tracer_deployID
    FROM
        person P
        LEFT JOIN
    alumni AL ON P.personID = AL.personID
    JOIN
        course C ON AL.courseID = C.courseID
        
    LEFT JOIN
        answer A ON P.personID = A.personID AND A.tracer_deployID = ? 

        WHERE
        A.tracer_deployID = ?; ");

        $smt->bind_param("ss", $deploymentID, $deploymentID);
        $smt->execute();
        $result = $smt->get_result();
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }




    public function get_answers_json(string $deploymentID)
    {
        $stmt = $this->conn->prepare("SELECT
        A.personID,
        TQ.questionID,
        TQ.question_text,
        GROUP_CONCAT(COALESCE(QC.choice_text, AD.answer_txt) ORDER BY A.personID) AS answers
    FROM
        tracer_question TQ
    INNER JOIN
        answer_data AD ON TQ.questionID = AD.questionID
    INNER JOIN
        answer A ON AD.answerID = A.answerID
    LEFT JOIN
        questionnaire_choice QC ON AD.choiceID = QC.choiceID
    WHERE
        A.tracer_deployID = ?
    GROUP BY
        A.personID, TQ.questionID;;");
        $stmt->bind_param("s", $deploymentID);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }


    public function get_questions_answers_grouped(string $deploymentID)
    {

        $stmt = $this->conn->prepare("SELECT
        A.personID,
        TQ.questionID,
        TQ.question_text,
        GROUP_CONCAT(COALESCE(QC.choice_text, AD.answer_txt) ORDER BY A.personID) AS answers
    FROM
        tracer_question TQ
    INNER JOIN
        answer_data AD ON TQ.questionID = AD.questionID
    INNER JOIN
        answer A ON AD.answerID = A.answerID
    LEFT JOIN
        questionnaire_choice QC ON AD.choiceID = QC.choiceID
    WHERE
        A.tracer_deployID = ?
    GROUP BY
        A.personID, TQ.questionID
    ORDER BY
        TQ.questionID, A.personID;");


        $stmt->bind_param("s", $deploymentID);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function get_questions_answers(string $deploymentID)
    {


        $stmt = $this->conn->prepare("SELECT
        TQ.questionID,
        TQ.question_text,
        TQ.inputType,
        TQ.sequence,
        A.personID,
        COALESCE(QC.choice_text, AD.answer_txt) AS answer_text
    FROM
        tracer_question TQ
    INNER JOIN
        answer_data AD ON TQ.questionID = AD.questionID
    INNER JOIN
        answer A ON AD.answerID = A.answerID
    LEFT JOIN
        questionnaire_choice QC ON AD.choiceID = QC.choiceID
    WHERE
        A.tracer_deployID = ?;");


        $stmt->bind_param("s", $deploymentID);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function get_latest_deployment()
    {
        $stmt = $this->conn->prepare("SELECT
        TD.tracer_deployID,
        TF.formID,
        TF.formName,
        TF.year_created,
        TF.status
    FROM
        tracer_deployment TD
    INNER JOIN
        tracer_form TF ON TD.formID = TF.formID
        ORDER BY TD.timstamp DESC
        LIMIT 1;");
        try {
            $stmt->execute();
            $result = $stmt->get_result();
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function get_deployments()
    {
        $sql = "SELECT TD.tracer_deployID, TF.formID, TF.formName, TF.year_created, TF.status
        FROM tracer_deployment TD
        INNER JOIN tracer_form TF ON TD.formID = TF.formID;";

        try {

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            // get all the data from the mysqli database
            $result = $stmt->get_result();
            // store the data to an array
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            // return the data


            return $data;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
