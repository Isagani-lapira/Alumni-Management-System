<?php

class Migration
{
    private $studentNo;
    public function __construct($studentNo)
    {
        $this->studentNo = $studentNo;
    }

    public function createEntry($con)
    {
        $query = "INSERT INTO `migration_status`(`studentNo`) VALUES ( ? )";
        $stmt = mysqli_prepare($con, $query);

        if ($stmt) {
            $stmt->bind_param('s', $this->studentNo);
            $stmt->execute();
        }
    }

    // check if the notification has been shown before to the user
    public function isNotifAlreadyShown($con)
    {
        $query = "SELECT COUNT(`studentNo`) FROM `migration_status` WHERE `studentNo` = ?";
        $stmt = mysqli_prepare($con, $query);

        if ($stmt) {
            $stmt->bind_param('s',  $this->studentNo);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            return $count > 0;
        }
    }

    public function deleteMigrationData($con)
    {
        $query = "DELETE FROM `migration_status` WHERE `studentNo` = ?";
        $stmt = mysqli_prepare($con, $query);

        if ($stmt) {
            $stmt->bind_param('d', $this->studentNo);
            $result = $stmt->execute();

            if ($result) return 'Success';
            else return 'Failed';
        }
    }
}
