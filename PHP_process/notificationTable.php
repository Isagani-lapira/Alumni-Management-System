<?php

class Notification
{
    public function ReadNotification($date, $con)
    {
        $query = "SELECT * FROM `notification` WHERE `date_notification`= '$date'";
        $result = mysqli_query($con, $query);
        $row = mysqli_num_rows($result);

        //data that will retrieve
        $notifID = array();
        $username = array();
        $typeOfNotif = array();
        $content = array();
        $date_notification = array();
        $timestamp = array();
        $is_read = array();
        $profile = array();
        if ($result && $row > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
                //data
                $notifID[] = $data['notifID'];
                $username[] = $data['username'];
                $typeOfNotif[] = $data['typeOfNotif'];
                $content[] = $data['content'];
                $date_notification[] = $data['date_notification'];
                $timestamp[] = $data['timestamp'];
                $is_read[] = $data['is_read'];
                $profile[] = $data['profile'];
            }

            //data that will be send
            $data = array(
                'notifID' => $notifID,
                'username' => $username,
                'typeOfNotif' => $typeOfNotif,
                'content' => $content,
                'date' => $date_notification,
                'timestamp' => $timestamp,
                'is_read' => $is_read,
                'profile' => $profile,
            );

            echo json_encode($data); //send as json
        } else echo 'None';
    }
}
