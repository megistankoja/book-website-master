<?php

include 'config.php';
if (!isset($_SESSION)) // Starto sesionin nëse nuk është startuar
{
   session_start();
}

if (isset($_POST['submit'])) { // Kur klikohet butoni 'login'

   $email = mysqli_real_escape_string($conn, $_POST['email']); // Siguria në SQL
   $pass = mysqli_real_escape_string($conn, $_POST['password']);

   // Kontrollo nëse ekziston përdoruesi me këtë email
   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');

   if (mysqli_num_rows($select_users) > 0) {
      $row = mysqli_fetch_assoc($select_users); // Merr të dhënat e përdoruesit nga databaza

      // Verifiko fjalëkalimin e futur me hash-in në databazë
      if (password_verify($pass, $row['password'])) {

         // Kontrollo llojin e përdoruesit
         if ($row['user_type'] == 'admin') {
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['success_message'] = "Welcome, " . $row['name'] . "! Login successful.";
            header('location:admin_page.php'); // Ridrejto te faqja e adminit
         } elseif ($row['user_type'] == 'user') {
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['success_message'] = "Welcome, " . $row['name'] . "! Login successful.";
            header('location:home.php'); // Ridrejto te faqja e përdoruesit

         } elseif ($row['user_type'] == 'super_admin') {
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['super_admin_id'] = $row['id'];
            header('location:super_admin_page.php'); // Ridrejto te faqja e super-adminit
         }
      } else {
         $message[] = 'Incorrect password!'; // Mesazh për fjalëkalim të gabuar
      }
   } else {
      $message[] = 'User not found!'; // Mesazh nëse email-i nuk ekziston
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

</head>

<body>

   <?php
   if (isset($message)) {/*Ky kod perdoret per te paraqitur nje mesazh gabimi 
se informacioni ne dritaren e aplikacionit ne rast se 
ndodh ndonje gabim gjate procesimit te te dhenave te postuara nga 
perdoruesi ne formen e login-it.*/
      foreach ($message as $message) {/*Nese variabla $message ka ndonje gabim, 
atehere kjo pjese e kodit do te krijoje nje loop te foreach qe do te kalohet 
nepermjet mesazheve ne variablen $message dhe do te paraqese secilin mesazh 
ne nje div me klasen "message".*/
         echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';/* tekstin e mesazhit dhe nje ikone me klasen "fas fa-times" qe do te jete 
e vendosur ne fund te div-it. Kur perdoruesi klikon ikonen e times, 
elementi div do te largohet nga dritarja e aplikacionit*/
      }
   }
   ?>

   <div class="form-container">

      <form action="" method="post">
         <h3>login now</h3>
         <input type="email" name="email" placeholder="enter your email" required class="box">
         <input type="password" name="password" placeholder="enter your password" required class="box">
         <input type="submit" name="submit" value="login now" class="btn">
         <p>Don't have an account? <a href="register.php">register now</a></p>
      </form>

   </div>

</body>

</html>