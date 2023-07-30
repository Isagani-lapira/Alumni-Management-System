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
}
