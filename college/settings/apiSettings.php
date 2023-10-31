<?php

session_start();

require "../php/connection.php";
require "../php/logging.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['settings-info-form'])) {
        // get the form data

    } else {
        echo json_encode(
            array(
                "message" => "No post data",
                "status" => false,
                "response" => "failed"
            )
        );
    }
}
