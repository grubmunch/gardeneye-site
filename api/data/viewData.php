<?php
header("Content-Type:application/json");

session_start();
include "../../connection.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


$dashboardData = null;

if(isset($_GET["environ_id"])) {
    if(isset($_GET["date"])) {
        $id = $conn->real_escape_string($_GET["environ_id"]);
        $date = $conn->real_escape_string($_GET["date"]) . "%";

        $read = $conn->prepare('SELECT * FROM gardeneye_data WHERE environ_id=? AND datetime LIKE ?');
        $read->bind_param('is', $id, $date);
        $read->execute();
        $result = $read->get_result();
        if ($result->num_rows != 0) {
            while ($row = $result->fetch_assoc()) {
                $dashboardData[] = $row;
            }
            createResponse(true, $dashboardData);
        } else {
            createResponse(false, "Could not return any data for these parameters.");
        }
    } else {
        createResponse(false, "Missing date parameter.");
    }
} else {
    createResponse(false, "Missing environment ID");
}



function createResponse($success, $message) {
    $response["success"] = $success;
    $response["message"] = $message;

    echo json_encode($response);

}
?>