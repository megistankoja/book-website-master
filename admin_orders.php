<?php

include 'config.php';


session_start();
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['super_admin_id'])) {
   header('location:login.php');
   exit(); // Ensure script stops executing after redirection
}

if (isset($_POST['update_order'])) {

   $order_update_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or
      die('query failed');
   $message[] = 'payment status has been updated!';

}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="admin_style.css">

</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="orders">

      <h1 class="title">Porosite</h1>

      <div class="box-container">
         <?php
         $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
         if (mysqli_num_rows($select_orders) > 0) {
            while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
               ?>
               <div class="box">
                  <p> Id : <span><?php echo $fetch_orders['user_id']; ?></span> </p>
                  <p> Data : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
                  <p> Emri : <span><?php echo $fetch_orders['name']; ?></span> </p>
                  <p> Numri : <span><?php echo $fetch_orders['number']; ?></span> </p>
                  <p> Emaili : <span><?php echo $fetch_orders['email']; ?></span> </p>
                  <p> Adresa : <span><?php echo $fetch_orders['address']; ?></span> </p>
                  <p> Librat e porositur : <span><?php echo $fetch_orders['total_products']; ?></span> </p>
                  <p> Shuma totale : <span>$<?php echo $fetch_orders['total_price']; ?>/-</span> </p>
                  <p> Pagesa : <span><?php echo $fetch_orders['method']; ?></span> </p>
                  <form action="" method="post">
                     <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                     <select name="update_payment">
                        <option value="" selected disabled><?php echo $fetch_orders['payment_status']; ?></option>
                        <option value="pending">Ne pritje</option>
                        <option value="completed">Kryer</option>
                     </select>
                     <input type="submit" value="update" name="update_order" class="option-btn">
                     <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>"
                        onclick="return confirm('delete this order?');" class="delete-btn">delete</a>
                  </form>
               </div>
               <?php
            }
         } else {
            echo '<p class="empty">no orders placed yet!</p>';
         }
         ?>
      </div>

   </section>

   <!-- custom admin js file link  -->
   <script src="admin_script.js"></script>

</body>

</html>