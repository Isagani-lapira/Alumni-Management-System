<?php
require_once 'connection.php';
require 'studentTB.php';
require 'alumniTB.php';
require 'migration.php';

if (isset($_POST['action'])) {
    $actionArray = $_POST['action'];
    $actionJSON = json_decode($actionArray, true);
    $action = $actionJSON['action'];

    switch ($action) {
        case 'read':
            $currentYear = $actionJSON['currentYear'];
            $offset = $actionJSON['offset'];
            $colCode = $actionJSON['colCode'];
            $search = $actionJSON['search'];
            $student = new Student();
            $student->getAllStudent($offset, $currentYear, $colCode, $search, $mysql_con);
            break;
        case 'migrateStudent':
            //data to be used
            $studNo = $_POST['studNoMigration'];
            $personID = $_POST['personIDMigration'];
            $colCode = $_POST['colCodeMigration'];
            $username = $_POST['usernameMigration'];
            $empStatus = $_POST['empStatData'];
            $batchYr = $_POST['batchYrData'];
            $courseID = $_POST['courseID'];

            // process migrating data to alumni table
            $alumniTb = new Alumni();
            $student = new Student();
            $migration = new Migration($studNo);
            $result = $alumniTb->insertAlumni($studNo, $personID, $colCode, $username, $batchYr, $courseID, $empStatus, $mysql_con);
            if ($result) {
                $result = $migration->deleteMigrationData($mysql_con);

                if ($result === 'Success') {
                    // remove student record
                    $result = $student->deleteStudentRecord($username, $mysql_con);
                    echo $result;
                }
            }
            break;
        case 'updateCurrentYear':
            $username = $_POST['username'];
            $student = new Student();
            $student->updateCurrentYear($username, $mysql_con);
            break;
    }
} else {
    echo 'cant Enter';
}
