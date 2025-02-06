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

<?php
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
   ?>
   <header class="header">
      <div class="header-1">
         <div class="flex">
            <div class="share">
               <a href="https://www.facebook.com/" class="fab fa-facebook-f"></a>
               <a href="https://www.twitter.com/" class="fab fa-twitter"></a>
               <a href="https://www.instagram.com/" class="fab fa-instagram"></a>
               <a href="https://www.linkedin.com/" class="fab fa-linkedin"></a>
            </div>
            <p> new <a href="login.php">login</a> | <a href="register.php">register</a> </p>
         </div>
      </div>
   </header>
   <?php
}
?>


<header class="header">
   <div class="header-2">
      <div class="flex">
         <a href="home.php" class="logo"> <i class="fas fa-book"></i> Bookflix</a>

         <nav class="navbar">
            <a href="home.php">Home</a>
            <a href="about.php">About</a>
            <a href="shop.php">Shop</a>
            <a href="contact.php">Contact</a>
            <a href="orders.php">Orders</a>
         </nav>

         <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php" class="fas fa-search"></a>
            <div id="user-btn" class="fas fa-user"></div>
            <?php
            $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
            /* kërkon të gjitha rreshtat nga tabela "cart" që përmbajnë vlerën e "user_id". 
            Rezultatet e kësaj kërkese ruhen në variabël "$select_cart_number" */
            $cart_rows_number = mysqli_num_rows($select_cart_number);
            /*"mysqli_num_rows" thirret për të numëruar numrin e rreshtave të kthyer nga 
            kërkesa SELECT, që nënkupton numrin e artikujve në shportën e blerjeve të 
            përdoruesit aktualizuar. Rezultati ruhet në variabël "$cart_rows_number" */
            ?>

            <a href="cart.php"> <i class="fas fa-shopping-cart"></i> <span>(
                  <?php echo $cart_rows_number; ?>)
               </span> </a>

         </div>

         <div class="user-box">
            <p>username : <span>
                  <?php echo $_SESSION['user_name']; ?>
               </span></p>
            <p>email : <span>
                  <?php echo $_SESSION['user_email'];
                  /*Nëse përdoruesi është i kyçur në sistemin e aplikacionit,
                  atëherë do të shfaqen emri dhe emaili në këtë kuti të përdoruesit.
                   Gjithashtu, kodi ka një buton "logout" për të lejuar përdoruesin të 
                   dalë nga sesioni i tyre nëse ata zgjedhin ta bëjnë këtë.*/ ?>
               </span></p>
            <a href="logout.php" class="delete-btn">logout</a>
         </div>
      </div>
   </div>

</header>