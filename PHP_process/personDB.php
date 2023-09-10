<?php
class personDB
{
    // insertion data
    public function insertPerson(
        $personID,
        $FName,
        $LName,
        $age,
        $bday,
        $contactNo,
        $address,
        $personalEmail,
        $bulsuEmail,
        $gender,
        $profilePic,
        $con
    ) {

        $query = "INSERT INTO `person`(`personID`, `fname`, `lname`, 
            `age`, `bday`, `gender`, `contactNo`, `address`, `personal_email`, 
            `bulsu_email`, `profilepicture`,`cover_photo`,`facebookUN`, `instagramUN`, `twitterUN`, `linkedInUN`) 
            VALUES ('$personID','$FName', '$LName','$age','$bday','$gender','$contactNo','$address','$personalEmail',
            '$bulsuEmail','$profilePic',null,null,null,null,null)";

        $result = mysqli_query($con, $query);

        if ($result) return true;
        else return false;
    }

    public function readPerson($personID, $con)
    {
        $query = 'SELECT * FROM `person` WHERE `personID` = "' . $personID . '"';

        $result = mysqli_query($con, $query);

        $fname = "";
        $lname = "";
        $age = "";
        $bday = "";
        $gender = "";
        $contactNo = "";
        $personal_email = "";
        $bulsu_email = "";
        $facebookUN = "";
        $instagramUN = "";
        $twitterUN = "";
        $linkedInUN = "";
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row_data = mysqli_fetch_assoc($result)) {

                $fname = $row_data['fname'];
                $lname = $row_data['lname'];
                $age = $row_data['age'];
                $address = $row_data['address'];
                $bday = $row_data['bday'];
                $gender = $row_data['gender'];
                $contactNo = $row_data['contactNo'];
                $personal_email = $row_data['personal_email'];
                $bulsu_email = $row_data['bulsu_email'];
                $facebookUN = $row_data['facebookUN'];
                $instagramUN = $row_data['instagramUN'];
                $twitterUN = $row_data['twitterUN'];
                $linkedInUN = $row_data['linkedInUN'];
                $profilepicture = base64_encode($row_data['profilepicture']);
                $coverPhoto = base64_encode($row_data['cover_photo']);
            }

            $personData = array(
                "fname" => $fname,
                "lname" => $lname,
                "age" => $age,
                "address" => $address,
                "bday" => $bday,
                "gender" => $gender,
                "contactNo" => $contactNo,
                "personal_email" => $personal_email,
                "bulsu_email" => $bulsu_email,
                "profilepicture" => $profilepicture,
                "coverPhoto" => $coverPhoto,
                "facebookUN" => $facebookUN,
                "instagramUN" => $instagramUN,
                "twitterUN" => $twitterUN,
                "linkedInUN" => $linkedInUN,
            );

            return json_encode($personData);
        }
    }

    public function updateImage($personID, $query, $newValue, $con)
    {
        $temp = $newValue['tmp_name'];
        $fileContent = addslashes(file_get_contents($temp));

        // //query for update
        $query = "UPDATE `person` SET  `$query` = '$fileContent'
        WHERE`personID` = '$personID'";
        $result = mysqli_query($con, $query);

        if ($result) echo 'success';
        else echo 'error';
    }

    public function updateCover($personID, $query, $newValue, $con)
    {
        $temp = $newValue['tmp_name'];
        $fileContent = addslashes(file_get_contents($temp));

        // //query for update
        $query = "UPDATE `person` SET  `$query` = '$fileContent'
        WHERE`personID` = '$personID'";
        $result = mysqli_query($con, $query);

        if ($result) echo 'success';
        else echo 'error';
    }

    public function updateInfo($personID, $query, $newValue, $con)
    {
        $query = "UPDATE `person` SET  `$query` = '$newValue'
        WHERE`personID` = '$personID'";
        $result = mysqli_query($con, $query);

        if ($result) echo 'success';
        else echo 'error';
    }

    public function searchPerson($personName, $con)
    {
        $query = "SELECT
        p.`personID`,
        CONCAT(p.`fname`, ' ', p.`lname`) AS 'Fullname',
        `profilepicture`,
        CASE
            WHEN a.`personID` IS NOT NULL THEN 'Alumni'
            WHEN s.`personID` IS NOT NULL THEN 'Student'
        END AS 'Status'
        FROM `person` p
        LEFT JOIN `alumni` a ON p.`personID` = a.`personID`
        LEFT JOIN `student` s ON p.`personID` = s.`personID`
        WHERE (a.`personID` IS NOT NULL OR s.`personID` IS NOT NULL)
        AND CONCAT(`fname`, ' ', `lname`) LIKE CONCAT('%', ?, '%')";


        $stmt = mysqli_prepare($con, $query);

        $response = "Unsuccess";
        $personID = array();
        $fullname = array();
        $profilePic = array();
        $status = array();

        if ($stmt) {
            $stmt->bind_param('s', $personName);
            $stmt->execute();

            $result = $stmt->get_result();
            $response = "Success";

            // fetch all the names retrieve
            while ($row = $result->fetch_assoc()) {
                $personID[] = $row['personID'];
                $fullname[] = $row['Fullname'];
                $status[] = $row['Status'];
                $profilePic[] = base64_encode($row['profilepicture']);
            }
        }

        $data = array(
            "response" => $response,
            "personID" => $personID,
            "profilePic" => $profilePic,
            "fullname" => $fullname,
            "status" => $status,
        );

        echo json_encode($data);
    }


    public function getUserProfile($personID, $con)
    {
        $query = "SELECT 
        CONCAT(p.fname, ' ', p.lname) AS full_name,
        p.profilepicture,
        p.cover_photo,
        p.facebookUN,
        p.instagramUN,
        p.twitterUN,
        p.linkedInUN,
        COALESCE(a.username, s.username) AS username
        FROM person AS p
        LEFT JOIN alumni AS a ON p.personID = a.personID
        LEFT JOIN student AS s ON p.personID = s.personID
        WHERE p.personID = ? ";

        $stmt = mysqli_prepare($con, $query);

        $response = "Unsuccess";
        $fullname = "";
        $profilepicture = "";
        $cover_photo = "";
        $facebookUN = "";
        $instagramUN = "";
        $twitterUN = "";
        $linkedInUN = "";
        $username = "";

        if ($stmt) {
            $stmt->bind_param('s', $personID);
            $stmt->execute();
            $result = $stmt->get_result();

            // fetch all the names retrieve
            while ($row = $result->fetch_assoc()) {
                $response = "Success";
                $fullname = $row['full_name'];
                $facebookUN = $row['facebookUN'];
                $instagramUN = $row['instagramUN'];
                $twitterUN = $row['twitterUN'];
                $linkedInUN = $row['linkedInUN'];
                $username = $row['username'];
                $profilepicture = base64_encode($row['profilepicture']);
                $cover_photo = base64_encode($row['cover_photo']);
            }
        }

        $data = array(
            "response" => $response,
            "profilePic" => $profilepicture,
            "coverPhoto" => $cover_photo,
            "fullname" => $fullname,
            "facebookUN" => $facebookUN,
            "instagramUN" => $instagramUN,
            "twitterUN" => $twitterUN,
            "linkedInUN" => $linkedInUN,
            "username" => $username,
        );

        echo json_encode($data);
    }
}
