<?php

include 'config.php';

session_start();


$super_admin_id = $_SESSION['super_admin_id'];

//e shtova per te mos e lejuar qasjen ne admin_page.php pa u loguar
if (!isset($super_admin_id)) {
   header('location:login.php');
}
if (isset($_POST['submit'])) {
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $password = mysqli_real_escape_string($conn, password_hash($_POST['password'], PASSWORD_DEFAULT)); // Secure password hashing
   $confirm_password = $_POST['confirm_password'];

   // Check if passwords match
   if (!password_verify($confirm_password, $password)) {
      $message[] = 'Passwords do not match!';
   } else {
      // Check if admin already exists
      $check_admin = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');
      if (mysqli_num_rows($check_admin) > 0) {
         $message[] = 'Admin with this email already exists!';
      } else {
         // Insert new admin into database
         $insert_admin = mysqli_query($conn, "INSERT INTO `users` (name, email, password, user_type) VALUES ('$name', '$email', '$password', 'admin')") or die('query failed');
         if ($insert_admin) {
            $message[] = 'Admin registered successfully!';
         } else {
            $message[] = 'Failed to register admin. Please try again.';
         }
      }
   }
}
//
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin panel</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="admin_style.css">
   <style>
      /* Admin register form container */

      .admin_register_form {
         box-sizing: 100%;
         width: 100%;
         max-width: 100%;
         margin: 30px auto;
         padding: 20px;
         background-color: #fff;
         border-radius: 10px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);

      }

      /* Title */
      .admin_register_form h3 {
         text-align: center;
         font-size: 24px;
         margin-bottom: 20px;
         color: #333;
      }

      /* Form input fields */
      .admin_register_form .box {
         width: 100%;
         padding: 12px;
         margin: 10px 0;
         border: 1px solid #ccc;
         border-radius: 5px;
         font-size: 16px;
         box-sizing: border-box;
      }

      /* Submit button */
      .admin_register_form .btn {
         width: 100%;
         padding: 12px;
         background-color: #4CAF50;
         /* Green button */
         border: none;
         color: white;
         font-size: 16px;
         border-radius: 5px;
         cursor: pointer;
         margin-top: 10px;
      }

      /* Hover effect for submit button */
      .admin_register_form .btn:hover {
         background-color: #45a049;
      }

      /* Link to go back */
      .admin_register_form .btn+a {
         display: inline-block;
         width: 100%;
         padding: 12px;
         text-align: center;
         background-color: #f44336;
         /* Red for go back */
         border-radius: 5px;
         color: white;
         text-decoration: none;
         margin-top: 10px;
      }

      /* Input focus effects */
      .admin_register_form .box:focus {
         border-color: #4CAF50;
         outline: none;
      }

      /* Error messages */
      .message {
         color: red;
         text-align: center;
         font-size: 16px;
         margin-top: 10px;
      }
   </style>
</head>

<body>

   <?php include 'admin_header.php'; ?>

   <!-- admin dashboard section starts  -->

   <section class="dashboard">

      <h1 class="title">Te dhenat</h1>

      <div class="box-container">

         <div class="box">
            <?php
            $total_pendings = 0;
            $select_pending = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'pending'") or die('query failed');
            if (mysqli_num_rows($select_pending) > 0) {
               while ($fetch_pendings = mysqli_fetch_assoc($select_pending)) {
                  $total_price = $fetch_pendings['total_price'];
                  //vlera e total_price merret dhe shtohet në vlerën 
                  //ekzistuese të $total_pendings duke përdorur operatorin "+="
                  $total_pendings += $total_price;
               }
               ;
            }
            ;
            ?>
            <h3>$<?php echo $total_pendings; ?>/-</h3>
            <p>Porosi ne pritje</p>
         </div>

         <div class="box">
            <?php
            $total_completed = 0;
            $select_completed = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'completed'") or die('query failed');
            if (mysqli_num_rows($select_completed) > 0) {
               while ($fetch_completed = mysqli_fetch_assoc($select_completed)) {
                  $total_price = $fetch_completed['total_price'];
                  $total_completed += $total_price;
               }
               ;
            }
            ;
            ?>
            <h3>$<?php echo $total_completed; ?>/-</h3>
            <p>Pagesa te kryera</p>
         </div>

         <div class="box">
            <?php
            $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
            $number_of_orders = mysqli_num_rows($select_orders);
            ?>
            <h3><?php echo $number_of_orders; ?></h3>
            <p>Porosi</p>
         </div>

         <div class="box">
            <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
            $number_of_products = mysqli_num_rows($select_products);
            ?>
            <h3><?php echo $number_of_products; ?></h3>
            <p>Numri i librave</p>
         </div>

         <div class="box">
            <?php
            $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'user'") or die('query failed');
            $number_of_users = mysqli_num_rows($select_users);
            ?>
            <h3><?php echo $number_of_users; ?></h3>
            <p>Users</p>
         </div>

         <div class="box">
            <?php
            $select_admins = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'admin'") or die('query failed');
            $number_of_admins = mysqli_num_rows($select_admins);
            ?>
            <h3><?php echo $number_of_admins; ?></h3>
            <p>Admin</p>
         </div>

         <div class="box">
            <?php
            $select_account = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
            $number_of_account = mysqli_num_rows($select_account);
            ?>
            <h3><?php echo $number_of_account; ?></h3>
            <p>Numri i llogarive</p>
         </div>
         <div class="box">
            <?php
            $select_messages = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
            $number_of_messages = mysqli_num_rows($select_messages);
            ?>
            <h3><?php echo $number_of_messages; ?></h3>
            <p>Mesazhe te reja</p>
         </div>
         <div class="box">
            <form action="" method="post" class="admin_register_form">
               <h3>Register Admin</h3>
               <input type="text" name="name" placeholder="Enter admin name" required class="box">
               <input type="email" name="email" placeholder="Enter admin email" required class="box">
               <input type="password" name="password" placeholder="Enter password" required class="box">
               <input type="password" name="confirm_password" placeholder="Confirm password" required class="box">
               <input type="submit" name="submit" value="Register Admin" class="btn">
               <a href="super_admin_page.php" class="btn">Go Back</a>
            </form>
         </div>

   </section>

   <!-- admin dashboard section ends -->

   <!-- custom admin js file link  -->
   <script src="admin_script.js"></script>

</body>

</html>