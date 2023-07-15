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
            `bulsu_email`, `profilepicture`) VALUES ('$personID','$FName',
            '$LName','$age','$bday','$gender','$contactNo','$address','$personalEmail',
            '$bulsuEmail','$profilePic')";

        $result = mysqli_query($con, $query);

        if ($result) return true;
        else return false;
    }

    public function readPerson($personID, $con)
    {
        $query = 'SELECT `personID`, `fname`, `lname`, `age`, `bday`, `gender`, 
            `contactNo`, `address`, `personal_email`, `bulsu_email`, `profilepicture`
            FROM `person` WHERE `personID` = "' . $personID . '"';

        $result = mysqli_query($con, $query);

        $fname = "";
        $lname = "";
        $age = "";
        $bday = "";
        $gender = "";
        $contactNo = "";
        $personal_email = "";
        $bulsu_email = "";

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
                $profilepicture = base64_encode($row_data['profilepicture']);
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
            );

            return json_encode($personData);
        }
    }
}
