<?php

class Notification
{
    public function ReadNotification($username, $date, $maxLimit, $con)
    {
        $query = "SELECT * FROM `notification` 
        WHERE `username`= '$username' AND `date_notification` = '$date'
        ORDER BY `date_notification` AND `timestamp` DESC LIMIT 0, $maxLimit";

        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);

        //data that will retrieve
        $response = "";
        $notifID = array();
        $added_by = array();
        $typeOfNotif = array();
        $content = array();
        $date_notification = array();
        $timestamp = array();
        $is_read = array();
        $profile = array();


        if ($result && $row > 0) {
            $response = 'Success';
            while ($data = mysqli_fetch_assoc($result)) {
                $notifID[] = $data['notifID'];
                $added_by[] = $data['added_by'];
                $typeOfNotif[] = $data['typeOfNotif'];
                $content[] = $data['content'];
                $date_notification[] = $this->dateInText($data['date_notification']);
                $timestamp[] = $data['timestamp'];
                $is_read[] = $data['is_read'];
                $profile[] = base64_encode($data['profile']);
            }
        } else $response = "Nothing";

        $notification = array(
            'result' => $response,
            "notifID" => $notifID,
            "added_by" => $added_by,
            "typeOfNotif" => $typeOfNotif,
            "content" => $content,
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
}
