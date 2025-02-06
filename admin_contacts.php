<?php

include 'config.php';


session_start();

if (!isset($_SESSION['admin_id']) && !isset($_SESSION['super_admin_id'])) {
   header('location:login.php');
   exit(); // Ensure script stops executing after redirection
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `message` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_contacts.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>messages</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="admin_style.css">

</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="messages">

      <h1 class="title"> Mesazhet </h1>

      <div class="box-container">
         <?php
         $select_message = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
         if (mysqli_num_rows($select_message) > 0) {
            while ($fetch_message = mysqli_fetch_assoc($select_message)) {

               ?>
               <div class="box">
                  <p> Id : <span><?php echo $fetch_message['user_id']; ?></span> </p>
                  <p> Emri : <span><?php echo $fetch_message['name']; ?></span> </p>
                  <p> Numri : <span><?php echo $fetch_message['number']; ?></span> </p>
                  <p> Emaili : <span><?php echo $fetch_message['email']; ?></span> </p>
                  <p> Mesazhi : <span><?php echo $fetch_message['message']; ?></span> </p>
                  <a href="admin_contacts.php?delete=<?php echo $fetch_message['id']; ?>"
                     onclick="return confirm('delete this message?');" class="delete-btn">Fshi mesazhin</a>
               </div>
               <?php
            }
            ;
         } else {
            echo '<p class="empty">you have no messages!</p>';
         }
         ?>
      </div>

   </section>









   <!-- custom admin js file link  -->
   <script src="admin_script.js"></script>

</body>

</html>