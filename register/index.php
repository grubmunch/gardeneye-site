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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Login & Registration Form</title>
  <link rel="stylesheet" href="styleregister.css">
</head>
<body>
  <div class="container">
    <input type="checkbox" id="check">
    <div class="login form">
      <header>Log in </header>
      <form action="#">
        <input type="text" placeholder="Enter your username">
        <input type="password" placeholder="Enter your password">
        <input type="button" class="button" value="Login">
      </form>
      <div class="signup">
        <span class="signup">Don't have an account?
         <label for="check">Signup</label>
        </span>
      </div>
    </div>
    <div class="registration form">
      <header>Register</header>
      <form action="#">
        <input type="text" placeholder="Enter your username">
        <input type="password" placeholder="Create a password">
        <input type="password" placeholder="Confirm your password">
        <input type="button" class="button" value="Signup">
      </form>
      <div class="signup">
        <span class="signup">Already have an account?
         <label for="check">Login</label>
        </span>
      </div>
    </div>
  </div>
</body>
</html>