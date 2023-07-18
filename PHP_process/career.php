<?php
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
        $requirement,
        $personID,
        $con
    ) {

        $date_posted  = date('y-m-d');
        $query = "INSERT INTO `career`(`careerID`, `jobTitle`, `companyName`, `jobDescript`,`jobqualification`,
            `companyLogo`, `minSalary`, `maxSalary`, `colCode`, `author`, `date_posted`, `personID`) 
            VALUES ('$careerID','$jobTitle','$companyName','$descript','$qualification','$logo','$minSalary',
            '$maxSalary','$colCode','$author','$date_posted','$personID')";

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

            $reqLength = count($requirement) - 1; //there's always a one extra due to automatic creation of input field
            $indexReq = 0;
            while ($indexReq < $reqLength) {
                $random = rand(0, 5000);
                $uniqueId = substr(md5(uniqid()), 0, 7); //unique id with length of 7
                $reqID = $uniqueId . '-' . $random;

                $this->insertRequirement($reqID, $careerID, $requirement[$indexReq], $con);
                $indexReq++;
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

    function insertRequirement($reqID, $careerID, $requirement, $con)
    {
        $query = "INSERT INTO `requirement`(`reqID`, `careerID`, `requirement`) 
            VALUES ('$reqID','$careerID','$requirement')";

        if (mysqli_query($con, $query)) echo '';
        else echo 'Unexpected error, try again later!';
    }


    public function selectData($con)
    {

        $query = "SELECT * FROM `career` ORDER BY`date_posted`DESC"; //as defult
        $result = mysqli_query($con, $query);

        if ($result) $this->getCareerDetail($result, $con);
        else echo 'something went wrong, please try again';
    }
    public function selectDataForCollege($college, $con)
    {
        $query = 'SELECT * FROM `career` WHERE `colCode` ="' . $college . '"';
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
        $requirements = array();
        $personIDEncrypted = array();

        if (mysqli_num_rows($result) > 0) {
            $response = "Success";
            while ($row_data = mysqli_fetch_assoc($result)) {
                $careerID[] = $row_data['careerID'];
                $jobTitle[] = $row_data['jobTitle'];
                $companyName[] = $row_data['companyName'];
                $jobDescript[] = $row_data['jobDescript'];
                $jobQuali[] = $row_data['jobqualification'];
                $companyLogo[] = base64_encode($row_data['companyLogo']);
                $minSalary[] = $row_data['minSalary'];
                $maxSalary[] = $row_data['colCode'];
                $colCode[] = $row_data['colCode'];
                $author[] = $row_data['author'];
                $date_posted[] = $row_data['date_posted'];
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

                //retrieve requirements
                $reqQuery = 'SELECT * FROM `requirement` WHERE careerID = "' . $row_data['careerID'] . '"';
                $reqResult = mysqli_query($con, $reqQuery);
                $requirement = array();

                if ($reqResult && mysqli_num_rows($reqResult) > 0) {
                    while ($req_data = mysqli_fetch_assoc($reqResult)) {
                        $requirement[] = $req_data['requirement'];
                    }
                }
                $requirements[] = $requirement;
            }
        } else $response = "Error";

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
            'requirements' => $requirements,
            'colCode' => $colCode,
            'author' => $author,
            'date_posted' => $date_posted,
            'personID' => $personIDEncrypted,
        );

        echo json_encode($data); //return data as json
    }
}
