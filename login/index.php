<?php
session_start();
include "../connection.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$usernameError = $passwordError = ''; // Initial definition of errors as blank

if(isset($_POST['username']) && isset($_POST["password"])) {
	$validUsername = $validPassword = false;
	$username = $conn->real_escape_string($_POST["username"]);
	$userData = [];
    if(!empty($username)) {
		$read = $conn->prepare('SELECT id, username, password FROM users WHERE username=?');
		$read->bind_param('s', $username);
		$read->execute();
		$result = $read->get_result();

		if ($result->num_rows != 0) {
			$userData = $result->fetch_assoc();
			$validUsername = true;
		} else {
			$usernameError = "User does not exist.";
		}
	} else {
		$usernameError = 'Username cannot be blank.';
	}

	$password = $conn->real_escape_string($_POST["password"]);
    if(!empty($password)) {
		$validPassword = true;
	} else {
		$passwordError = 'Password cannot be blank.';
	}

	if($validUsername && $validPassword) {
		if (password_verify($password, $userData["password"])) {
			$_SESSION["logged_in"] = true;
			$_SESSION["id"] = $userData["id"];
			$token = base64_encode(random_bytes(6));
			$updateToken = $conn->prepare("UPDATE users SET token=? WHERE username=?");
			$updateToken->bind_param("ss", $token, $username);
			$updateToken->execute();
			$result = $updateToken->get_result();

			if (mysqli_affected_rows($conn) > 0) {
				header("Location: ../dashboard/");
			} else {
				die("ERROR: Could not update token.");
			}
		} else {
			$validPassword = false;
			$passwordError = "This password is incorrect.";
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
    <div class="login form">
      <header>Log in </header>
      <form action="" method="post">
	  	<span class="error"><?php echo $usernameError; ?></span>
        <input type="text" id="username" name="username" placeholder="Enter your username">
		<span class="error"><?php echo $passwordError; ?></span>
        <input type="password" id="password" name="password" placeholder="Enter your password">
        <input type="submit" class="button" value="Login">
      </form>
      <div class="signup">
        <span class="signup">Don't have an account?
         <label for="check"><a href="../register/">Register</a></label>
        </span>
      </div>
    </div>
  </div>
</body>
</html>