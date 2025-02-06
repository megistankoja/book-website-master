<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}

if (isset($_POST['order_btn'])) {

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $number = $_POST['number'];
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $method = mysqli_real_escape_string($conn, $_POST['method']);
   $address = mysqli_real_escape_string($conn, 'flat no. ' . $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . ' - ' . $_POST['pin_code']);
   $placed_on = date('d-M-Y');

   $cart_total = 0;
   $cart_products[] = '';

   $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   if (mysqli_num_rows($cart_query) > 0) {
      while ($cart_item = mysqli_fetch_assoc($cart_query)) {
         $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ') ';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      }
   }

   $total_products = implode(', ', $cart_products);

   $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

   if ($cart_total == 0) {
      $message[] = 'your cart is empty';
   } else {
      if (mysqli_num_rows($order_query) > 0) {
         $message[] = 'order already placed!';
      } else {
         mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
         $message[] = 'order placed successfully!';
         mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
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
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

</head>

<body>
   <script
      src="https://www.paypal.com/sdk/js?client-id=ATCkdZBsYF8Ib7Nxz030yPsFiYholNXtBjqGQYIE3OzEyry6KjhdUE-h81xIwGK3Sgwv1KNnhphY4hSZ"></script>


   <?php include 'header.php'; ?>

   <section class="display-order">

      <?php
      $grand_total = 0;
      $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      if (mysqli_num_rows($select_cart) > 0) {
         while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
            ?>
            <p> <?php echo $fetch_cart['name']; ?>
               <span>(<?php echo '$' . $fetch_cart['price'] . '/-' . ' x ' . $fetch_cart['quantity']; ?>)</span>
            </p>
            <?php
         }
      } else {
         echo '<p class="empty">your cart is empty</p>';
      }
      ?>
      <input type="hidden" id="grand_total" value="<?php echo $grand_total; ?>">
      <div class="grand-total"> grand total : <span>$<?php echo $grand_total; ?>/-</span> </div>

   </section>

   <section class="checkout">

      <form action="" method="post">
         <h3>place your order</h3>
         <div class="flex">
            <div class="inputBox">
               <span>your name :</span>
               <input type="text" name="name" required placeholder="enter your name">
            </div>
            <div class="inputBox">
               <span>your number :</span>
               <input type="number" name="number" required placeholder="enter your number">
            </div>
            <div class="inputBox">
               <span>your email :</span>
               <input type="email" name="email" required placeholder="enter your email">
            </div>
            <div class="inputBox">
               <span>address line 01 :</span>
               <input type="number" min="0" name="flat" required placeholder="e.g. flat no.">
            </div>
            <div class="inputBox">
               <span>address line 01 :</span>
               <input type="text" name="street" required placeholder="e.g. street name">
            </div>
            <div class="inputBox">
               <span>city :</span>
               <input type="text" name="city" required placeholder="e.g. tirana">
            </div>
            <div class="inputBox">
               <span>state :</span>
               <input type="text" name="state" required placeholder="e.g. albania">
            </div>
            <div class="inputBox">
               <label for="country"><span>Country:</span></label>
               <select id="country" name="european-countries">
                  <option value="AL">Albania</option>
                  <option value="AD">Andorra</option>
                  <option value="AM">Armenia</option>
                  <option value="AT">Austria</option>
                  <option value="BY">Belarus</option>
                  <option value="BE">Belgium</option>
                  <option value="BA">Bosnia and Herzegovina</option>
                  <option value="BG">Bulgaria</option>
                  <option value="HR">Croatia</option>
                  <option value="CY">Cyprus</option>
                  <option value="CZ">Czech Republic</option>
                  <option value="DK">Denmark</option>
                  <option value="EE">Estonia</option>
                  <option value="FI">Finland</option>
                  <option value="FR">France</option>
                  <option value="GE">Georgia</option>
                  <option value="DE">Germany</option>
                  <option value="GR">Greece</option>
                  <option value="HU">Hungary</option>
                  <option value="IS">Iceland</option>
                  <option value="IE">Ireland</option>
                  <option value="IT">Italy</option>
                  <option value="LV">Latvia</option>
                  <option value="LI">Liechtenstein</option>
                  <option value="LT">Lithuania</option>
                  <option value="LU">Luxembourg</option>
                  <option value="MT">Malta</option>
                  <option value="MD">Moldova</option>
                  <option value="MC">Monaco</option>
                  <option value="ME">Montenegro</option>
                  <option value="NL">Netherlands</option>
                  <option value="MK">North Macedonia</option>
                  <option value="NO">Norway</option>
                  <option value="PL">Poland</option>
                  <option value="PT">Portugal</option>
                  <option value="RO">Romania</option>
                  <option value="RU">Russia</option>
                  <option value="SM">San Marino</option>
                  <option value="RS">Serbia</option>
                  <option value="SK">Slovakia</option>
                  <option value="SI">Slovenia</option>
                  <option value="ES">Spain</option>
                  <option value="SE">Sweden</option>
                  <option value="CH">Switzerland</option>
                  <option value="TR">Turkey</option>
                  <option value="UA">Ukraine</option>
                  <option value="GB">United Kingdom</option>
               </select>
            </div>
            <div class="inputBox">
               <span>pin code :</span>
               <input type="number" min="0" name="pin_code" required placeholder="e.g. 4001">
            </div>
            <div class="inputBox">
               <span>payment method :</span>
               <select name="method" id="payment-method">
                  <option value="cash on delivery">cash on delivery</option>
                  <option value="credit card">credit card</option>
                  <option value="paypal">paypal</option>
                  <option value="paytm">paytm</option>
               </select>
            </div>
         </div>
         </div>
         <div id="paypal-button-container" style="display: none; margin-top: 20px;"></div>
         <input type="submit" value="order now" class="btn" name="order_btn">
      </form>

   </section>





   <script>document.getElementById("payment-method").addEventListener("change", togglePayPalButton);</script>

   <script src="togglefunction.js"></script>

   <?php include 'footer.php'; ?>

   <!-- custom js file link  -->
   <script src="script.js"></script>

</body>

</html>