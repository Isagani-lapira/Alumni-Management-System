<?php
require_once 'resume.php';

class Applicant
{
    public function resumeApplication($careerID, $con)
    {
        $personID = $_SESSION['personID'];
        $haveResume = haveResume($personID, $con);

        //check first if the user has already a resume
        //have resume
        if ($haveResume) {
            $random = rand(200, 1000);
            $uniqID = substr(md5(uniqid()), 0, 5);
            $applicantID = $random . '-' . $uniqID;
            $username = $_SESSION['username'];
            $resumeID = getResumeID($con);

            $query = "INSERT INTO `applicant`(`applicantID`, `username`, `careerID`, `resumeID`)
            VALUES ('$applicantID','$username','$careerID','$resumeID')";
            $result = mysqli_query($con, $query);

            if ($result) echo 'Success';
            else echo 'Failed';
        }
    }
}
