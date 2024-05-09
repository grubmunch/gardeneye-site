<?php
header("Content-Type:application/json");

session_start();
include "../../connection.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


$DATA_INTERVAL_MINS = "10"; // How many minutes since the last data reading is allowed

if (isset($_GET["temperature"]) && !empty($_GET["temperature"]) && isset($_GET["humidity"]) && !empty($_GET["humidity"]) && isset($_GET['username']) && $_GET['username']!="" && isset($_GET["token"]) && $_GET["token"] != "") {
    $username = $conn->real_escape_string($_GET["username"]);
    $token = $conn->real_escape_string($_GET["token"]);

    $user = $conn->prepare('SELECT id, username FROM users WHERE username=?');
    $user->bind_param('s', $username);
    $user->execute();
    $result = $user->get_result();

    if ($result->num_rows == 1) {
        $userData = $result->fetch_assoc();

        $read = $conn->prepare('SELECT token FROM users WHERE username=? AND token=?');
        $read->bind_param('ss', $username, $token);
        $read->execute();
        $result = $read->get_result();

        if ($result->num_rows == 1) {
            
            $read = $conn->prepare('SELECT environ_id FROM environments WHERE user_id=?');
            $read->bind_param('i', $userData["id"]);
            $read->execute();
            $result = $read->get_result();
            $environmentId = $result->fetch_assoc()["environ_id"];

            $humidity = $conn->real_escape_string($_GET["humidity"]); // todo: validate
            $temperature = $conn->real_escape_string($_GET["temperature"]); // todo: validate

            $getLastTime = $conn->prepare("SELECT datetime FROM gardeneye_data WHERE environ_id=? ORDER BY id DESC LIMIT 1");
            $getLastTime->bind_param("i", $environmentId);
            $getLastTime->execute();
            $timeRes = $getLastTime->get_result();
            
            $timezone = new DateTimeZone('Europe/London');
            
            $currentDate = new DateTime("now", $timezone);
            $dateTime = new DateTime("1970-01-01 01:00:00", $timezone);
            if ($timeRes->num_rows != 0) {
                $dateTime = new DateTime($timeRes->fetch_assoc()["datetime"], $timezone);
            }
            $minutes = abs($dateTime->getTimestamp() - $currentDate->getTimestamp()) / 60;
                if($minutes >= $DATA_INTERVAL_MINS) {
                $insertData = $conn->prepare("INSERT INTO gardeneye_data (environ_id, humidity, temperature) VALUES (?, ?, ?)");
                $insertData->bind_param("iss", $environmentId, $humidity, $temperature);
                if($insertData->execute()) {
                    createResponse(true, "Successfully added data reading to environment.");
                }
            } else {
                createResponse(false, "Invalid interval since last data reading. Please wait a while longer.");
            }
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