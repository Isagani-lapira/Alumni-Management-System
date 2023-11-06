<?php

require_once '../../config.php';
require_once "../php/connection.php";

require_once SITE_ROOT . "/PHP_process/TracerForm.php";



if ($_SERVER['REQUEST_METHOD']) {
    if (isset($_GET['action'])) {

        $action = $_GET['action'];

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
                $data = $tracer->get_all_person_answered($deploymentID);
            } else if ($action  === 'get_latest_deployment') {
                $tracer = new TracerForm($mysql_con);
                $data = $tracer->get_latest_deployment();
                $id = $data[0]['tracer_deployID'];
                // get all answered
                $data = $tracer->get_all_person_answered($id);
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
