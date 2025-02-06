<?php

include 'config.php';

session_start();
$user_id = $_SESSION['user_id'];

if (isset($_SESSION['success_message'])) {
    echo '<div class="success-message">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']); // Clear the message after showing it
}

if (isset($_POST['add_to_cart'])) {

    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($check_cart_numbers) > 0) {
        $message[] = 'already added to cart!';
    } else {
        mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
        $message[] = 'product added to cart!';
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookflix</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="style.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">






</head>

<body>

    <!-- header section starts  -->
    <?php include 'header.php'; ?>

    <!-- header section ends -->

    <!-- bottom navbar  -->
    <script>document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', function () {
                this.style.backgroundColor = 'var(--green)';
                this.style.color = 'var(--green)';
            });
        }
    </script>

    <!-- home section starts  -->

    <section class="home" id="home">

        <div class="row">

            <div class="content">
                <h3 style="color:white;">Deri ne 50% zbritje</h3>
                <a href="shop.php" class="btn">Bli tani</a>
                <p style="color:white; font-size:20px;"> Blini libra te rinj dhe te perdorur,online dhe ne dyqan vetem
                    tek Bookflix me qender ne qytetin e bukur te Shkodrës!</p>
            </div>

            <div class="swiper books-slider">
                <div class="swiper-wrapper">
                    <a href="#" class="swiper-slide"><img src="images/wind name.jpg" alt=""></a>
                    <a href="#" class="swiper-slide"><img src="images/birdbox2.jpg" alt=""></a>
                    <a href="#" class="swiper-slide"><img src="images/bone1.jpg" alt=""></a>
                    <a href="#" class="swiper-slide"><img src="images/november1.jpg" alt=""></a>
                    <a href="#" class="swiper-slide"><img src="images/Hamlet.jpg" alt=""></a>
                    <a href="#" class="swiper-slide"><img src="images/duke1.jpg" alt=""></a>
                </div>
                <img src="image/stand.png" class="stand" alt="">
            </div>

        </div>

    </section>

    <!-- home section ense  -->

    <!-- icons section starts  -->


    <section class="icons-container">

        <div class="icons">
            <i class="fas fa-shipping-fast"></i>
            <div class="content">
                <h3>Posta falas</h3>
                <p>per porosi mbi $100</p>
            </div>
        </div>

        <div class="icons">
            <i class="fas fa-lock"></i>
            <div class="content">
                <h3>Pagese e sigurte</h3>
                <p>100% sigurim te dhenash</p>
            </div>
        </div>

        <div class="icons">
            <i class="fas fa-redo-alt"></i>
            <div class="content">
                <h3>Kthime te lehta</h3>
                <p>kthimi deri ne 10 dite </p>
            </div>
        </div>

        <div class="icons">
            <i class="fas fa-headset"></i>
            <div class="content">
                <h3>24/7 suport</h3>
                <p>sms/telefonate ne cdo kohe </p>
            </div>
        </div>

    </section>

    <!-- icons section ends -->

    <!-- featured section starts  -->

    <section class="products">

        <h1 class="title">Librat tone</h1>

        <div class="box-container">

            <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 6") or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                    ?>
                    <form action="" method="post" class="box">
                        <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                        <div class="name"><?php echo $fetch_products['name']; ?></div>
                        <div class="price">$<?php echo $fetch_products['price']; ?>/-</div>
                        <input type="number" min="1" name="product_quantity" value="1" class="qty">
                        <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                        <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                        <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                        <input type="submit" value="Shto ne shporte" name="add_to_cart" class="btn">
                    </form>
                    <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>
        </div>

        <div class="load-more" style="margin-top: 2rem; text-align:center">
            <a href="shop.php" class="option-btn">Me shume</a>
        </div>

    </section>


    <!-- newsletter section starts -->

    <section class="newsletter">

        <form action="">


        </form>

    </section>

    <!-- newsletter section ends -->

    <!-- arrivals section starts  -->

    <section class="arrivals" id="arrivals">

        <h1 class="heading"> <span>New arrivals</span> </h1>

        <div class="swiper arrivals-slider">

            <div class="swiper-wrapper">

                <a href="#" class="swiper-slide box">
                    <div class="image">
                        <img src="images/divergent1.jpg" alt="">
                    </div>
                    <div class="content">
                        <h3>Divergent</h3>
                        <div class="price">$55 <span>$58</span></div>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </a>

                <a href="#" class="swiper-slide box">
                    <div class="image">
                        <img src="images/ring.jpg" alt="">
                    </div>
                    <div class="content">
                        <h3>The Lord of the ring</h3>
                        <div class="price">$51 <span>$53</span></div>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </a>

                <a href="#" class="swiper-slide box">
                    <div class="image">
                        <img src="images/wind name.jpg" alt="">
                    </div>
                    <div class="content">
                        <h3>The name of the wind</h3>
                        <div class="price">$32 <span>$35</span></div>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </a>

                <a href="#" class="swiper-slide box">
                    <div class="image">
                        <img src="images/it-9781982127794_hr.jpg" alt="">
                    </div>
                    <div class="content">
                        <h3>It</h3>
                        <div class="price">$29 <span>$31</span></div>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </a>

                <a href="#" class="swiper-slide box">
                    <div class="image">
                        <img src="images/Hamlet.jpg" alt="">
                    </div>
                    <div class="content">
                        <h3>Hamlet</h3>
                        <div class="price">$70 <span>$75</span></div>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </a>

            </div>

        </div>

        <div class="swiper arrivals-slider">

            <div class="swiper-wrapper">

                <a href="#" class="swiper-slide box">
                    <div class="image">
                        <img src="images/one_hundret_years.jpg" alt="">
                    </div>
                    <div class="content">
                        <h3>One hundred years</h3>
                        <div class="price">$49 <span>$51</span></div>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </a>

                <a href="#" class="swiper-slide box">
                    <div class="image">
                        <img src="images/notebook1.jpg" alt="">
                    </div>
                    <div class="content">
                        <h3>The notebook</h3>
                        <div class="price">$49 <span>$50</span></div>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </a>

                <a href="#" class="swiper-slide box">
                    <div class="image">
                        <img src="images/Divine.jpg" alt="">
                    </div>
                    <div class="content">
                        <h3>The divine comedy</h3>
                        <div class="price">$54 <span>$56</span></div>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </a>

                <a href="#" class="swiper-slide box">
                    <div class="image">
                        <img src="images/birdbox2.jpg" alt="">
                    </div>
                    <div class="content">
                        <h3>Bird Box</h3>
                        <div class="price">$36 <span>$40</span></div>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </a>

                <a href="#" class="swiper-slide box">
                    <div class="image">
                        <img src="images/Duke1.jpg" alt="">
                    </div>
                    <div class="content">
                        <h3>Bridgerton the Duke & I</h3>
                        <div class="price">$36 <span>$46</span></div>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </a>

            </div>

        </div>

    </section>

    <!-- arrivals section ends -->

    <!-- deal section starts  -->

    <section class="deal">

        <div class="content">
            <h3>oferta e dites</h3>
            <h1>deri ne 50% zbritje</h1>
            <p>Shfletoni librat ekzluziv sot! Nxitoni- oferta e dites skadon ne mesnate!</p>
            <a href="shop.php" class="btn">Bli tani</a>
        </div>

        <div class="image">
            <img src="image/deal-img.jpeg" alt="">
        </div>

    </section>
    <!-- deal section ends -->

    <!-- footer section starts  -->
    <?php include 'footer.php'; ?>
    <!-- footer section ends -->

    <!-- custom js file link  -->

    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
    <!-- custom js file link  -->
    <script src="script.js"></script>
    <script src="js/javascript.js"></script>

</body>

</html>