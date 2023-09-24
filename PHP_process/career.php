<?php
require_once 'applicant.php';

class Career
{
    //job insertion
    public function insertionJob(
        $careerID,
        $jobTitle,
        $companyName,
        $descript,
        $qualification,
        $logo,
        $minSalary,
        $maxSalary,
        $colCode,
        $author,
        $skill,
        $personID,
        $location,
        $status = "verified", //default for admins
        $con
    ) {
        $date_posted = date('y-m-d');
        $query = "INSERT INTO `career`(`careerID`, `jobTitle`, `companyName`, `jobDescript`,`jobqualification`,
            `companyLogo`, `minSalary`, `maxSalary`, `colCode`, `author`, `date_posted`, `personID`, `location`, `status`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($con, $query);

        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "ssssssddssssss",
                $careerID,
                $jobTitle,
                $companyName,
                $descript,
                $qualification,
                $logo,
                $minSalary,
                $maxSalary,
                $colCode,
                $author,
                $date_posted,
                $personID,
                $location,
                $status
            );

            $result = mysqli_stmt_execute($stmt);

            if ($result) {
                $skillLength = count($skill); //there's always one extra due to automatic creation of input field
                $index = 0;
                while ($index < $skillLength) {
                    $random = rand(0, 5000);
                    $skillID = $careerID . '-' . $random;

                    $this->insertSkill($skillID, $careerID, $skill[$index], $con);
                    $index++;
                }
                return true;
            } else {
                return false;
            }
            mysqli_stmt_close($stmt);
        } else {
            return false;
        }
    }

    function insertSkill($skillID, $careerID, $skill, $con)
    {
        $query = "INSERT INTO `skill` (`skillID`, `careerID`, `skill`) 
              VALUES (?, ?, ?)";

        $stmt = mysqli_prepare($con, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $skillID, $careerID, $skill);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                return true;
            } else {
                mysqli_stmt_close($stmt);
                echo 'Unexpected error, try again later!';
            }
        } else {
            echo 'Prepared statement error!';
        }
    }

    public function selectData($offset, $con)
    {
        $maxLimit = 5;
        $query = "SELECT * FROM `career` WHERE `status` = 'verified' ORDER BY`date_posted` DESC LIMIT $offset, $maxLimit"; //as defult
        $result = mysqli_query($con, $query);

        if ($result) $this->getCareerDetail($result, $con);
        else echo 'something went wrong, please try again';
    }
    public function selectWithCareerID($con, $careerID)
    {
        $query = 'SELECT * FROM `career` WHERE `careerID` = ? ORDER BY `date_posted` DESC';
        $stmt = mysqli_prepare($con, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $careerID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($result)
                $this->getCareerDetail($result, $con);
            else
                echo 'something went wrong, please try again';

            mysqli_stmt_close($stmt);
        } else echo 'error';
    }

    public function selectDataForCollege($college, $offset, $con)
    {
        $maxLimit = 9; //default number of retrieval
        // Properly formatted SQL query
        $query = "SELECT * FROM `career` WHERE `colCode` = '$college' AND `status` ='verified' 
        ORDER BY `date_posted` DESC LIMIT $offset,$maxLimit";

        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);

        if ($result && $row) $this->getCareerDetail($result, $con);
        else echo 'none';
    }
    //searching a particular job title
    public function selectSearchJob($jobTitle, $con)
    {
        $query = 'SELECT * FROM career WHERE `status` = "verified" AND jobTitle LIKE "%' . $jobTitle . '%"';
        $result = mysqli_query($con, $query);

        if ($result) $this->getCareerDetail($result, $con);
        else echo 'something went wrong, please try again';
    }
    function generatePseudonym($personID)
    {
        $pseudonym = md5($personID);

        return $pseudonym;
    }

    function getCareerDetail($result, $con)
    {
        $response = "";
        $careerID = array();
        $jobTitle = array();
        $companyName = array();
        $jobDescript = array();
        $jobQuali = array();
        $companyLogo = array();
        $minSalary = array();
        $maxSalary = array();
        $colCode = array();
        $author = array();
        $date_posted = array();
        $skills = array();
        $location = array();
        $personIDEncrypted = array();
        $isApplied = array();
        $isSaved = array();
        $status = array();
        $applicantNo = array();

        if (mysqli_num_rows($result) > 0) {
            $response = "Success";
            while ($row_data = mysqli_fetch_assoc($result)) {
                $careerID[] = $row_data['careerID'];
                $tempCareerID = $row_data['careerID'];
                $jobTitle[] = $row_data['jobTitle'];
                $companyName[] = $row_data['companyName'];
                $jobDescript[] = $row_data['jobDescript'];
                $jobQuali[] = $row_data['jobqualification'];
                $companyLogo[] = base64_encode($row_data['companyLogo']);
                $minSalary[] = $row_data['minSalary'];
                $maxSalary[] = $row_data['maxSalary'];
                $colCode[] = $row_data['colCode'];
                $author[] = $row_data['author'];
                $date = $row_data['date_posted'];
                $date_posted[] = $this->dateInText($date);
                $location[] = $row_data['location'];
                $status[] = $row_data['status'];

                // Pseudonymize the personID
                $personIDEncrypted[] = $this->generatePseudonym($row_data['personID']);

                //retrieve skills from the database
                $skillQuery = 'SELECT * FROM `skill` WHERE careerID = "' . $row_data['careerID'] . '"';
                $skillResult = mysqli_query($con, $skillQuery);
                $skillNames = array();

                if ($skillResult && mysqli_num_rows($skillResult) > 0) {
                    while ($skill_data = mysqli_fetch_assoc($skillResult)) {
                        $skillNames[] = $skill_data['skill'];
                    }
                }

                $skills[] = $skillNames;

                //check if current user is already applied to a post
                $username = $_SESSION['username'];
                $applicant = new Applicant();
                $isApplied[] = $applicant->isApplied($tempCareerID, $username, $con);

                $applicantNo[] = $applicant->getApplicantCount($tempCareerID, $con);
                //check if the current user saved the job post
                $isSaved[] = $this->isJobSaved($tempCareerID, $username, $con);
            }
        } else $response = "none";

        //data to be sent
        $data =  array(
            'result' => $response,
            'careerID' => $careerID,
            'jobTitle' => $jobTitle,
            'companyName' => $companyName,
            'companyLogo' => $companyLogo,
            'jobDescript' => $jobDescript,
            'jobQuali' => $jobQuali,
            'minSalary' => $minSalary,
            'maxSalary' => $maxSalary,
            'skills' => $skills,
            'colCode' => $colCode,
            'author' => $author,
            'date_posted' => $date_posted,
            'personID' => $personIDEncrypted,
            'location' => $location,
            "isApplied" => $isApplied,
            'isSaved' => $isSaved,
            "status" => $status,
            "applicantCount" => $applicantNo
        );

        echo json_encode($data); //return data as json
    }

    function isJobSaved($careerID, $username, $con)
    {
        $query = "SELECT * FROM `saved_career` WHERE `careerID` = ? AND `username` = ?";
        $stmt = mysqli_prepare($con, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $careerID, $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_num_rows($result);

            mysqli_stmt_close($stmt);

            return ($row > 0);
        } else {
            // Handle error if preparation fails
            return false;
        }
    }

    function dateInText($date)
    {
        $year = substr($date, 0, 4);
        $month = intval(substr($date, 5, 2));
        $day = substr($date, 8, 2);
        $months = [
            '', 'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        //2023-07-17
        //convert date month to text format
        $month = $months[$month];

        //return in a formatted date
        return $month . ' ' . $day . ', ' . $year;
    }

    public function selectCareerAdmin($college, $offset, $con)
    {
        $maxLimit = 10;
        $query = "SELECT * FROM `career` WHERE `colCode` = ? AND (`author` = 'University Admin' OR `author` = 'colAdmin')
              ORDER BY `date_posted` DESC LIMIT ?, ?";

        $stmt = mysqli_prepare($con, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sii", $college, $offset, $maxLimit);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_num_rows($result);

            if ($result && $row > 0)
                $this->getCareerDetail($result, $con);
            else echo 'none';

            mysqli_stmt_close($stmt);
        } else {
            // Handle error if preparation fails
            echo 'error';
        }
    }


    public function appliedJob($offset, $con)
    {
        $maxLimit = 10;
        $username = $_SESSION['username'];
        $query = "SELECT c.*
        FROM career c
        JOIN applicant a ON c.careerID = a.careerID
        WHERE a.username = ? ORDER BY `date_posted` LIMIT ?, ?";
        $stmt = mysqli_prepare($con, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sii", $username, $offset, $maxLimit);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_num_rows($result);

            if ($result && $row > 0)
                $this->getCareerDetail($result, $con);
            else echo 'none';

            mysqli_stmt_close($stmt);
        } else
            echo 'error';
    }

    public function userPost($username, $offset, $con)
    {
        $maxLimit = 10;
        $query = "SELECT * FROM `career` WHERE `author` = ? ORDER BY `date_posted` DESC LIMIT $offset, $maxLimit ";
        $stmt = mysqli_prepare($con, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_num_rows($result);

            if ($result && $row > 0)
                $this->getCareerDetail($result, $con);
            else echo 'none';

            mysqli_stmt_close($stmt);
        } else
            echo 'error';
    }
}
