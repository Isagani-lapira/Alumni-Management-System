<?php
require 'resume.php';
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

    public function isApplied($careerID, $username, $con)
    {
        $query = "SELECT * FROM `applicant` WHERE `careerID` = '$careerID'
        AND `username` = '$username'";
        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);

        if ($result && $row > 0) return true;
        else return false;
    }

    public function deleteApplication($username, $careerID, $con)
    {
        $query = "DELETE FROM `applicant` WHERE `username`= '$username' AND `careerID` = '$careerID'";
        $result = mysqli_query($con, $query);

        if ($result) echo 'Success';
        else echo 'Failed';
    }

    public function getApplicantCount($careerID, $con)
    {
        $query = "SELECT `careerID` FROM `applicant` WHERE `careerID` = ?";
        $stmt = mysqli_prepare($con, $query);
        // Bind the parameter
        $stmt->bind_param("s", $careerID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = mysqli_num_rows($result);

        // Close the statement
        mysqli_stmt_close($stmt);

        return $row;
    }

    public function getApplicantDetails($careerID, $con)
    {
        $query = "SELECT p.fname, p.lname, a.resumeID
        FROM applicant AS a
        LEFT JOIN student AS s ON a.username = s.username
        LEFT JOIN alumni AS al ON a.username = al.username
        LEFT JOIN person AS p ON s.personID = p.personID OR al.personID = p.personID
        WHERE a.careerID = ?";

        $stmt = mysqli_prepare($con, $query);
        $stmt->bind_param('s', $careerID);
        $stmt->execute();
        $result = $stmt->get_result();

        $response = "";
        $fullname = array();
        $resumeID = array();

        if ($result) {
            $response = "Success";
            while ($data = $result->fetch_assoc()) {
                $fullname[] = $data['fname'] . ' ' . $data['lname'];
                $resumeID[] = $data['resumeID'];
            }
        } else $response = "Unsuccess";

        $data = array(
            "response" => $response,
            "fullname" => $fullname,
            "resumeID" => $resumeID,
        );

        echo json_encode($data);
    }
}
