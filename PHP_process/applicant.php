<?php
require 'resume.php';
class Applicant
{
    public function resumeApplication($careerID, $message, $con)
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

            $query = "INSERT INTO `applicant`(`applicantID`, `username`, `careerID`, `resumeID`, `message`) 
            VALUES (? ,? ,? ,? ,? )";
            $stmt = mysqli_prepare($con, $query);

            if ($stmt) {
                $stmt->bind_param('sssss', $applicantID, $username, $careerID, $resumeID, $message);
                $result = $stmt->execute();

                if ($result) echo 'Success';
                else echo 'Failed';
            }
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
        $username = $_SESSION['username'];
        $query = "SELECT `careerID` FROM `applicant` WHERE `careerID` = ? AND username != ?";
        $stmt = mysqli_prepare($con, $query);
        // Bind the parameter
        $stmt->bind_param("ss", $careerID, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = mysqli_num_rows($result);

        // Close the statement
        mysqli_stmt_close($stmt);

        return $row;
    }

    public function getApplicantDetails($careerID, $con)
    {
        $username = $_SESSION['username'];

        $query = "SELECT p.fname, p.lname, a.resumeID,a.message,a.appliedDate
        FROM applicant AS a
        LEFT JOIN student AS s ON a.username = s.username
        LEFT JOIN alumni AS al ON a.username = al.username
        LEFT JOIN person AS p ON s.personID = p.personID OR al.personID = p.personID
        WHERE a.careerID = ? AND a.username != ? 
        ORDER BY a.appliedDate DESC";

        $stmt = mysqli_prepare($con, $query);
        $stmt->bind_param('ss', $careerID, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        $response = "";
        $fullname = array();
        $resumeID = array();
        $message = array();
        $date = array();

        if ($result) {
            $response = "Success";
            while ($data = $result->fetch_assoc()) {
                $fullname[] = $data['fname'] . ' ' . $data['lname'];
                $resumeID[] = $data['resumeID'];
                $message[] = $data['message'];
                $date[] = $data['appliedDate'];
            }
        } else $response = "Unsuccess";

        $data = array(
            "response" => $response,
            "fullname" => $fullname,
            "resumeID" => $resumeID,
            "message" => $message,
            "date" => $date,
        );

        echo json_encode($data);
    }
}
