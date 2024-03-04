<?php

include "../connection.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$usernameError = $passwordError = $confirmPasswordError = $success = ''; // Initial definition of errors as blank

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
                $usernameError = "That user already exists.";
            }
        } else {
            $usernameError = "Username must be over 3 characters.";
        }
    } else {
        $usernameError = "Username must not be blank.";
    }

    $password = $conn->real_escape_string($_POST["password"]);
    if(!empty($password)) {
        if(strlen($password) > 5) {
            if (preg_match('/[A-Z]/', $password) && preg_match('/[a-z]/', $password) && preg_match('/[0-9]/', $password)) {
                $validPassword = true;
            } else {
                $passwordError = "Password must contain at least one uppercase letter, one lowercase letter and one number.";
            }
        } else {
            $passwordError = "Password must be at least 5 characters.";
        }
    } else {
        $passwordError = "Password must not be blank.";
    }

    $confirmPassword = $conn->real_escape_string($_POST["confirmPassword"]);
    if ($confirmPassword == $password) {
        $passwordConfirmed = true;
    } else {
        $confirmPasswordError = "Passwords do not match.";
    }

    // Final step, if everything is valid then create the account.
    if ($validUsername && $validPassword && $passwordConfirmed) {
        $options = [
            'cost' => 11
        ];
        $encryptedPassword = password_hash($password, PASSWORD_BCRYPT, $options);

        $registerUser = $conn->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        $registerUser->bind_param("ss", $username, $encryptedPassword);
        if($registerUser->execute()) {
            $success = "Account created successfully! You can now log in.";
        } else {
            die("Error inserting user into database.");
        }
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
  <link rel="stylesheet" href="../assets/css/styleregister.css">
</head>
<body>
  <div class="container">
    <div class="registration form">
      <header>Register</header>
      <form action="" method="post">
        <span class="error"><?php echo $usernameError; ?></span>
        <input type="text" id="username" name="username" placeholder="Enter your username">
        <span class="error"><?php echo $passwordError; ?></span>
        <input type="password" id="password" name="password" placeholder="Create a password">
        <span class="error"><?php echo $confirmPasswordError; ?></span>
        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password">
        
        <input type="submit" class="button" value="Signup">
      </form>
      <p class="success" style="color:green"><?php echo $success; ?></p>
      <div class="signup">
        <span class="signup">Already have an account?
         <label for="check"><a href="../login/">Login</a></label>
        </span>
      </div>
    </div>
  </div>
</body>
</html>