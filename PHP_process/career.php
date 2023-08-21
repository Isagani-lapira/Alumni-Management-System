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
        $con
    ) {

        $status = "verified"; //default for admins
        $date_posted  = date('y-m-d');
        $query = "INSERT INTO `career`(`careerID`, `jobTitle`, `companyName`, `jobDescript`,`jobqualification`,
            `companyLogo`, `minSalary`, `maxSalary`, `colCode`, `author`, `date_posted`, `personID`, `location`, `status`) 
            VALUES ('$careerID','$jobTitle','$companyName','$descript','$qualification','$logo','$minSalary',
            '$maxSalary','$colCode','$author','$date_posted','$personID','$location','$status')";

        $result = mysqli_query($con, $query);

        //check if the result if success
        if ($result) {
            $skillLength = count($skill) - 1; //there's always a one extra due to automatic creation of input field
            $index = 0;
            while ($index < $skillLength) {
                $random = rand(0, 5000);
                $skillID = $careerID . '-' . $random;

                $this->insertSkill($skillID, $careerID, $skill[$index], $con);
                $index++;
            }
            return true;
        } else return false;
    }

    public function userInsertionJob(
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
        $con
    ) {

        $status = "unverified"; //default for admins
        $date_posted  = date('y-m-d');
        $query = "INSERT INTO `career`(`careerID`, `jobTitle`, `companyName`, `jobDescript`,`jobqualification`,
            `companyLogo`, `minSalary`, `maxSalary`, `colCode`, `author`, `date_posted`, `personID`, `location`, `status`) 
            VALUES ('$careerID','$jobTitle','$companyName','$descript','$qualification','$logo','$minSalary',
            '$maxSalary','$colCode','$author','$date_posted','$personID','$location','$status')";

        $result = mysqli_query($con, $query);

        //check if the result if success
        if ($result) {
            $skillLength = count($skill) - 1; //there's always a one extra due to automatic creation of input field
            $index = 0;
            while ($index < $skillLength) {
                $random = rand(0, 5000);
                $skillID = $careerID . '-' . $random;

                $this->insertSkill($skillID, $careerID, $skill[$index], $con);
                $index++;
            }
            return true;
        } else return false;
    }

    function insertSkill($skillID, $careerID, $skill, $con)
    {
        $query = "INSERT INTO `skill`(`skillID`, `careerID`, `skill`) 
            VALUES ('$skillID','$careerID','$skill')";

        if (mysqli_query($con, $query)) return true;
        else echo 'Unexpected error, try again later!';
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
        $query = 'SELECT * FROM `career` WHERE `careerID` = "' . $careerID . '"  ORDER BY`date_posted`DESC'; //as defult
        $result = mysqli_query($con, $query);

        if ($result) $this->getCareerDetail($result, $con);
        else echo 'something went wrong, please try again';
    }
    public function selectDataForCollege($college, $offset, $con)
    {
        $maxLimit = 10; //default number of retrieval
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
            'isSaved' => $isSaved
        );

        echo json_encode($data); //return data as json
    }

    function isJobSaved($careerID, $username, $con)
    {
        $query = "SELECT * FROM `saved_career` WHERE 
        `careerID` = '$careerID' AND `username` = '$username'";
        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);

        if ($result && $row > 0) return true;
        else return false;
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
        $query = "SELECT * FROM `career` WHERE `colCode` = '$college' AND 
        `author` = 'University Admin' OR `author` = 'colAdmin'
        ORDER BY `date_posted` DESC LIMIT $offset, $maxLimit";

        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);
        if ($result && $row > 0) $this->getCareerDetail($result, $con);
        else echo 'none';
    }

    public function appliedJob($offset, $con)
    {
        $maxLimit = 10;
        $username = $_SESSION['username'];
        $query = "SELECT c.*
        FROM career c
        JOIN applicant a ON c.careerID = a.careerID
        WHERE a.username = '$username' ORDER BY`date_posted` LIMIT $offset,$maxLimit";

        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);
        if ($result && $row > 0) $this->getCareerDetail($result, $con);
        else echo 'none';
    }
}
