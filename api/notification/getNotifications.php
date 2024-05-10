<?php
header("Content-Type:application/json");

session_start();
include "../../connection.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


$notifData = [];

if(isset($_SESSION["id"])) {
    $read = $conn->prepare('SELECT * FROM notifications WHERE user_id=? AND unread=1 ORDER BY id DESC');
    $read->bind_param('i', $_SESSION["id"]);
    $read->execute();
    $result = $read->get_result();
    if ($result->num_rows != 0) {
        while ($row = $result->fetch_assoc()) {
            $notifData[] = $row;
        }
        createResponse(true, $notifData);
    } else {
        createResponse(false, "No notifications found.");
    }
} else {
    createResponse(false, "You must be logged in to use this API.");
}



function createResponse($success, $message) {
    $response["success"] = $success;
    $response["message"] = $message;

    echo json_encode($response);

}
?>