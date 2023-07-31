<?php
require_once 'career.php';
require_once 'connection.php';

if (isset($_POST['action'])) {
    try {
        $data = $_POST['action'];
        $arrayData = json_decode($data, true);
        $action = $arrayData['action'];
        $readCareer = new Career();
        if ($action == 'create') {
            $author = $_POST['author'];

            //skills
            $skillData = $_POST['skills'];
            $skillArray = json_decode($skillData, true);

            //logo
            $image = addslashes(file_get_contents($_FILES['jobLogoInput']['tmp_name']));

            //retrieve the value 
            $jobTitle = $_POST['jobTitle'];
            $companyName = $_POST['companyName'];
            $projectDescript = $_POST['projDescriptTxt'];
            $minSalary = $_POST['minSalary'];
            $maxSalary = $_POST['maxSalary'];
            $qualification = $_POST['qualificationTxt'];
            $personID = $_POST['personID'];
            $college = $_POST['collegeJob'];
            $location = $_POST['jobLocation'];
            //for career ID
            $uniqueId = substr(md5(uniqid()), 0, 7); //unique id with length of 7
            $careerID = 'career' . $uniqueId;

            //insert a data 
            $career = new Career();
            $career->insertionJob(
                $careerID,
                $jobTitle,
                $companyName,
                $projectDescript,
                $qualification,
                $image,
                $minSalary,
                $maxSalary,
                $college,
                $author,
                $skillArray,
                $personID,
                $location,
                $mysql_con
            );

            if ($career) echo 'Job successfully added on the hunt';
            else echo 'Unexpected issue: Try again later';
        } else if ($action == 'read') {
            $offset = $_POST['offset'];
            $readCareer->selectData($offset, $mysql_con);
        } else if ($action == 'readWithCol') {
            $colCode = $arrayData['colCode'];
            $startDate = $_POST['startDate'];
            $endDate = $_POST['endDate'];
            $readCareer->selectDataForCollege($colCode, $startDate, $endDate, $mysql_con);
        } else if ($action == 'readWithCareerID') {
            $careerID = $arrayData['careerID'];
            $readCareer->selectWithCareerID($mysql_con, $careerID);
        } else if ($action == "searching") {
            $jobTitle = $arrayData['jobTitle'];
            $readCareer->selectSearchJob($jobTitle, $mysql_con);
        } else if ($action == 'readWithAuthor') {
            $colCode = $arrayData['colCode'];
            $startDate = $_POST['startDate'];
            $endDate = $_POST['endDate'];
            $readCareer->selectCareerAdmin($colCode, $startDate, $endDate, $mysql_con);
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
} else echo 'Action has not been yet initialized';
