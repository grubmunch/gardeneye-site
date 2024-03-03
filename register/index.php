<?php

include "../connection.php";

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

// TODO: Have error messages appear within the actual page


if(isset($_POST['username']) && isset($_POST["password"]) && isset($_POST["confirmPassword"])) {
    // Define initial variable values for error checking
    $validUsername = $validPassword = $passwordConfirmed = false;

    $username = $conn->real_escape_string($_POST["username"]);
    if(!empty($username)) {
        if(strlen($username) > 3) {
            $read = $conn->prepare('SELECT username FROM users WHERE username=?');
            $read->bind_param('s', $username);
            $read->execute();
            $result = $read->get_result();

            if ($result->num_rows == 0) {
                $validUsername = true;
            } else {
                echo "That user already exists.";
            }
        } else {
            echo "Username must be over 3 characters.";
        }
    } else {
        echo "Username must not be blank.";
    }

    $password = $conn->real_escape_string($_POST["password"]);
    if(!empty($password)) {
        if(strlen($password) > 5) {
            $validPassword = true;
        } else {
            echo "Password must be at least 5 characters.";
        }
    } else {
        echo "Password must not be blank.";
    }

    $confirmPassword = $conn->real_escape_string($_POST["confirmPassword"]);
    if ($confirmPassword == $password) {
        $passwordConfirmed = true;
    } else {
        echo "Passwords do not match.";
    }

    // Final step, if everything is valid then create the account.
    if ($validUsername && $validPassword && $passwordConfirmed) {
        echo "Creating account.";
    }
}


?>