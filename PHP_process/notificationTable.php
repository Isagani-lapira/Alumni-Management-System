<?php

require '../PHP_process/personDB.php';
class Notification
{
    public function ReadNotification($username, $offset, $con)
    {
        $maxLimit = 10;
        $query = "SELECT * FROM `notification` WHERE `username` = '$username' 
        ORDER BY `date_notification`DESC LIMIT $offset, $maxLimit";

        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);

        //data that will retrieve
        $response = "";
        $notifID = array();
        $postID = array();
        $added_by = array();
        $typeOfNotif = array();
        $date_notification = array();
        $timestamp = array();
        $is_read = array();
        $profile = array();


        if ($result && $row > 0) {
            $response = 'Success';
            while ($data = mysqli_fetch_assoc($result)) {
                $notifID[] = $data['notifID'];
                $postID[] = $data['postID'];
                $addBy = $data['added_by'];
                $typeOfNotif[] = $data['typeOfNotif'];
                $date_notification[] = $this->dateInText($data['date_notification']);
                $timestamp[] = $data['timestamp'];
                $is_read[] = $data['is_read'];

                //get user personID
                //get the person ID of that user
                $query = "
                SELECT 'student' AS user_details, student.personID
                FROM student
                WHERE student.username = '$addBy'
                
                UNION
                
                SELECT 'alumni' AS user_details, alumni.personID
                FROM alumni
                WHERE alumni.username = '$addBy'
                
                UNION
                
                SELECT 'univadmin' AS user_details, univadmin.personID
                FROM univadmin
                WHERE univadmin.username = '$addBy'
                
                UNION
                
                SELECT 'coladmin' AS user_details, coladmin.personID
                FROM coladmin
                WHERE coladmin.username = '$addBy' ";

                $resultPersonId = mysqli_query($con, $query);
                $dataID = mysqli_fetch_assoc($resultPersonId);
                $personID = $dataID['personID'];

                //get person details
                $personObj = new personDB();
                $personDataJSON = $personObj->readPerson($personID, $con);
                $personData = json_decode($personDataJSON, true);

                $added_by[] = $personData['fname'] . ' ' . $personData['lname'];
                $profile[] = base64_encode($personData['profilepicture']);
            }
        } else $response = "Nothing";

        $notification = array(
            'result' => $response,
            "notifID" => $notifID,
            "postID" => $postID,
            "added_by" => $added_by,
            "typeOfNotif" => $typeOfNotif,
            "date_notification" => $date_notification,
            "timestamp" => $timestamp,
            "is_read" => $is_read,
            "profile" => $profile,
        );

        echo json_encode($notification);
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

    public function totalUnreadNotif($username, $con)
    {
        $query = "SELECT * FROM `notification` WHERE `username` = '$username' AND `is_read`= 0";
        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);

        if ($result && $row > 0) echo $row;
        else echo 'none';
    }

    public function updateStat($notifID, $con)
    {
        $query = "UPDATE `notification` SET `is_read` ='1' WHERE `notifID` = '$notifID'";
        $result = mysqli_query($con, $query);

        if ($result) echo 'Success';
        else echo 'Failed';
    }
}
