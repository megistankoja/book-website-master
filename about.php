<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$name = $user_name;


if (!isset($user_id)) {
   header('location:login.php');
}

// Fetch reviews from the database
$reviews_result = $conn->query("SELECT * FROM reviews ORDER BY created_at DESC");

if (!$reviews_result) {
   die('Error fetching reviews: ' . $conn->error);
}

// Handle form submission for reviews
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
   $name = $user_name; // USE LOGGED-IN USER'S NAME
   $review = $conn->real_escape_string($_POST['review']);
   $rating = intval($_POST['rating']);

   // Use the logged-in user's ID
   $insert_review = $conn->query("INSERT INTO reviews (user_id, name, review, rating, created_at) VALUES ('$user_id', '$name', '$review', '$rating', NOW())");

   if ($insert_review) {
      header("Location: " . $_SERVER['PHP_SELF']); // Refresh the page
      exit;
   } else {
      die('Error submitting review: ' . $conn->error);
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="style1.css">
   <style>
      /* Add the star rating CSS directly here for convenience */
      .star-rating {
         display: flex;
         justify-content: center;
         gap: 0.5rem;
         font-size: 2rem;
         cursor: pointer;
      }

      .star {
         color: lightgray;
         transition: color 0.3s ease;
      }

      .star:hover,
      .star.selected,
      .star:hover~.star {
         color: orange;
      }
   </style>
</head>

<body>

   <?php include 'header.php'; ?>

   <section class="about">
      <div class="flex">
         <div class="image">
            <iframe width="560" height="370" src="https://www.youtube.com/embed/ILmvKC-H1l0"
               title="YouTube video player" frameborder="0"
               allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
               allowfullscreen></iframe>
         </div>

         <div class="content">
            <h3>Librat më të bukur për t'u lexuar nga Ava Jules</h3>
            <p>Këta libra janë disa nga të preferuarit e mi!!! Shpresoj të kënaqeni! Më tregoni cili është i preferuari
               juaj???</p>
            <p>Bashkohuni me klubin tonë!</p>
            <a href="contact.php" class="btn">Na kontaktoni</a>
         </div>
      </div>
   </section>

   <section class="reviews">
      <h1 class="title">Customer Reviews</h1>

      <!-- Display Reviews -->
      <div class="review-container">
   <?php if ($reviews_result && $reviews_result->num_rows > 0) { ?>
      <?php while ($row = $reviews_result->fetch_assoc()) { ?>
         <div class="review-box">
            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
            <p><?php echo htmlspecialchars($row['review']); ?></p>
            <small>Posted on: <?php echo date('d M Y, H:i', strtotime($row['created_at'])); ?></small>
            <div class="stars">
               <?php for ($i = 0; $i < $row['rating']; $i++) { ?>
                  <i class="fas fa-star"></i>
               <?php } ?>
               <?php for ($i = $row['rating']; $i < 5; $i++) { ?>
                  <i class="far fa-star"></i>
               <?php } ?>
            </div>

            <!-- Edit and Delete buttons -->
            <?php if ($row['user_id'] == $user_id) { // Check if the current user is the one who posted the comment ?>
               
               <a href="delete_review.php?id=<?php echo $row['id']; ?>" class="btn">Delete</a>
            <?php } ?>
         </div>
      <?php } ?>
   <?php } else { ?>
      <p>No reviews yet. Be the first to leave one!</p>
   <?php } ?>
</div>
   </section>

   <section class="submit-review">
      <h2>Submit Your Review</h2>
      <form action="" method="POST">
      <input type="text" name="name" placeholder="Your Name" value="<?php echo ($user_name); ?>" readonly required>
         <textarea name="review" placeholder="Write your review here" required></textarea>
         <div class="star-rating">
            <span class="star" data-value="1">★</span>
            <span class="star" data-value="2">★</span>
            <span class="star" data-value="3">★</span>
            <span class="star" data-value="4">★</span>
            <span class="star" data-value="5">★</span>
         </div>
         <input type="hidden" id="rating-value" name="rating" value="0">
         <button type="submit" name="submit_review" class="btn">Submit</button>
      </form>
   </section>

   <?php include 'footer.php'; ?>
   <!-- custom js file link  -->
   <script src="script.js"></script>
   <script src="js/javascript.js"></script>
   /* per yjet ne review */
   <script>
      document.querySelectorAll('.btn').forEach(button => {
         button.addEventListener('click', function () {
            this.style.backgroundColor = 'var(--green)';
            this.style.color = 'var(--green)';
         });
      });
      const stars = document.querySelectorAll('.star-rating .star');
      const ratingInput = document.getElementById('rating-value');

      stars.forEach((star) => {
         star.addEventListener('click', () => {
            // Remove 'selected' class from all stars
            stars.forEach((s) => s.classList.remove('selected'));

            // Add 'selected' class to clicked star and all previous stars
            star.classList.add('selected');
            let previousStars = star.previousElementSibling;
            while (previousStars) {
               previousStars.classList.add('selected');
               previousStars = previousStars.previousElementSibling;
            }

            // Update hidden input value
            ratingInput.value = star.dataset.value;
         });
      });
   </script>
</body>

</html>