<?php
session_start();

require_once '../../config.php';
require_once "../php/connection.php";

require_once SITE_ROOT . "/PHP_process/TracerForm.php";

// check if session is set
if (!isset($_SESSION['colCode'])) {
    echo json_encode(array('error' => 'Session not set', 'success' => false));
}

if ($_SERVER['REQUEST_METHOD']) {
    if (isset($_GET['action'])) {

        $action = $_GET['action'];
        // colcode
        $colCode = $_SESSION['colCode'];

        $data = array();
        try {
            if ($action == 'get_deployments') {
                $tracer = new TracerForm($mysql_con);
                $data = $tracer->get_deployments();
            } else if ($action == 'get_user_answers') {
                $personID = $_GET['personID'];
                $deploymentID = $_GET['deploymentID'];
                $tracer = new TracerForm($mysql_con);
                $data = $tracer->get_user_answers($personID, $deploymentID);
            } else if ($action == 'get_all_answered') {
                $deploymentID = $_GET['deploymentID'];
                $tracer = new TracerForm($mysql_con);
                $data = $tracer->get_filtered_college_all_answered($deploymentID, $colCode);
            } else if ($action  === 'get_latest_deployment') {
                $tracer = new TracerForm($mysql_con);
                $data = $tracer->get_latest_deployment();
                $id = $data[0]['tracer_deployID'];
                // get all answered
                $data = $tracer->get_filtered_college_all_answered($id, $colCode);
            } else {
                $data = array('error' => 'Invalid action');
            }
            echo json_encode(array('data' => $data, 'action' => $action, 'success' => true));
        } catch (\Throwable $th) {
            //throw $th;
            echo json_encode(array('error' => $th->getMessage(), 'action' => $action, 'success' => false));
        }
    }
}
