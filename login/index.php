<?php

include "../connection.php";

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
      <form action="#">
        <input type="text" placeholder="Enter your username">
        <input type="password" placeholder="Enter your password">
        <input type="button" class="button" value="Login">
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