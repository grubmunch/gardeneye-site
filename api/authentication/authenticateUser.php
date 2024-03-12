<?php
header("Content-Type:application/json");

session_start();
include "../../connection.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if (isset($_GET['username']) && $_GET['username']!="" && isset($_GET["token"]) && $_GET["token"] != "") {
    $username = $conn->real_escape_string($_GET["username"]);
    $token = $conn->real_escape_string($_GET["token"]);

    $user = $conn->prepare('SELECT username FROM users WHERE username=?');
    $user->bind_param('s', $username);
    $user->execute();
    $result = $user->get_result();

    if ($result->num_rows == 1) {
        $read = $conn->prepare('SELECT token FROM users WHERE username=? AND token=?');
        $read->bind_param('ss', $username, $token);
        $read->execute();
        $result = $read->get_result();

        if ($result->num_rows == 1) {
            createResponse(true, "Successfully authenticated. You may now login.");
        } else {
            createResponse(false, "Invalid authentication token.");
        }
    } else {
        createResponse(false, "Invalid user.");
    }
} else {
    createResponse(false, "Missing parameters.");
}

function createResponse($success, $message) {
    $response["success"] = $success;
    $response["message"] = $message;

    echo json_encode($response);

}
?>