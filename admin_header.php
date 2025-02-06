<?php

if (!isset($_SESSION['admin_id']) && !isset($_SESSION['super_admin_id'])) {
   header('location:login.php');
   exit(); // Ensure script stops executing after redirection
}

if (isset($message)) {
   foreach ($message as $message) {
      echo '
        <div class="message">
            <span>' . $message . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
   }
}
?>

<header class="header">

   <div class="flex">
      <?php

      if (isset($_SESSION['super_admin_id'])) {
         echo '<a href="super_admin_page.php" class="logo">Bookflix</a>';
      } elseif (isset($_SESSION['admin_id'])) {
         echo '<a href="admin_page.php"></a>';
      }
      ?>
      <nav class="navbar">
         <?php
         if (isset($_SESSION['super_admin_id'])) {
            echo '<a href="super_admin_page.php">Home</a>';
         } elseif (isset($_SESSION['admin_id'])) {
            echo '<a href="admin_page.php">Home</a>';
         }
         ?>
         <a href="admin_products.php">Products</a>
         <a href="admin_orders.php">Orders</a>
         <a href="admin_users.php">Users</a>
         <a href="admin_contacts.php">Messages</a>
      </nav>

      <div class="icons">
         <div id="line-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="account-box">
         <?php if (isset($_SESSION['admin_name']) && isset($_SESSION['admin_email'])): ?>
            <p>username : <span><?php echo htmlspecialchars($_SESSION['admin_name']); ?></span></p>
            <p>email : <span><?php echo htmlspecialchars($_SESSION['admin_email']); ?></span></p>
            <a href="logout.php" class="delete-btn">logout</a>
         <?php else: ?>
            <p>You are not logged in.</p>
            <div><a href="login.php">Login</a> | <a href="register.php">Register</a></div>
         <?php endif; ?>
      </div>

   </div>

</header>