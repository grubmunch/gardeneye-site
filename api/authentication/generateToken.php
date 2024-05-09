<?php
header("Content-Type:application/json");

session_start();
include "../../connection.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if(isset($_SESSION["id"])) {
    $token = base64_encode(random_bytes(6));
    $user = $conn->prepare('UPDATE users SET token=? WHERE id=?');
    $user->bind_param('si', $token, $_SESSION["id"]);
    if($user->execute()) {
        createResponse(true, $token);
    } else {
        createResponse(false, "Error: Could not update token.");
    }
} else {
    createResponse(false, "You must be logged in to regenerate your token.");
}

function createResponse($success, $message) {
    $response["success"] = $success;
    $response["message"] = $message;

    echo json_encode($response);

}
?>
