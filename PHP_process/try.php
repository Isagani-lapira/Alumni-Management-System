<?php


// get action and email from url
$action = $_GET['action'];
$email = $_GET['email'];

require_once './connection.php';

// insert into email_verfications table
if ($action == 'insert') {
    $verification_code = (string) rand(100000, 999999); // Generates a 6-digit code

    $query = "INSERT INTO email_verification (email, verification_code) VALUES (?, ?)";
    $stmt = $mysql_con->prepare($query);
    $stmt->bind_param("ss", $email, $verification_code);
    // check if it executed
    if (!$stmt->execute()) {
        echo json_encode(array("success" => false, "message" => "Email not sent! " . $stmt->error, 'error' => true));
        exit();
    }
    if ($result) {
        echo "inserted";
    } else {
        echo "not inserted";
    }
}
