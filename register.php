<?php

include 'config.php';
session_start();
ob_start(); // Prevent "headers already sent" error
$message = [];

if (isset($_POST['submit'])) {
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, $_POST['password']);
   $cpass = mysqli_real_escape_string($conn, $_POST['cpassword']);
   $user_type = 'user';

   $message = [];

   // Validate email format
   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $message[] = 'Invalid email format!';
   }

   // Validate password length
   if (strlen($pass) < 8) {
      $message[] = 'Password must be at least 8 characters!';
   }

   // Ensure passwords match
   if ($pass != $cpass) {
      $message[] = 'Confirm password does not match!';
   }

   // Check if user already exists
   if (empty($message)) {
      $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('Query failed');

      if (mysqli_num_rows($select_users) > 0) {
         $message[] = 'User already exists!';
      } else {
         // Hash the password
         $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

         // Insert user into the database
         mysqli_query($conn, "INSERT INTO `users` (name, email, password, user_type) VALUES ('$name', '$email', '$hashed_password', '$user_type')") or die('Query failed');
         $message[] = 'Registered successfully!';
         $_SESSION['success_message'] = "Registration successful! You can now log in.";
         header('location:login.php');
         exit(); // Ensure no further code runs after redirection
      }
   }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="style.css">
</head>

<body>

   <?php
   if (!empty($message)) {
      foreach ($message as $msg) {
         echo '
        <div class="message">
           <span>' . $msg . '</span>
           <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
      }
   }
   ?>

   <div class="form-container">
      <form action="" method="post">
         <h3>Register Now</h3>
         <input type="text" name="name" placeholder="Enter your name" required class="box">
         <input type="email" name="email" placeholder="Enter your email" required class="box">
         <input type="password" name="password" placeholder="Enter your password" required class="box">
         <input type="password" name="cpassword" placeholder="Confirm your password" required class="box">
         <input type="submit" name="submit" value="Register Now" class="btn">
         <p>Already have an account? <a href="login.php">Login Now</a></p>
      </form>
   </div>

</body>

</html>