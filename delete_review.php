<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$review_id = $_GET['id']; // Get the review ID from the URL

// Check if the review exists and if the logged-in user is the owner
$query = $conn->prepare("SELECT * FROM reviews WHERE id = ? AND user_id = ?");
$query->bind_param("ii", $review_id, $user_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    // Delete the review
    $delete_query = $conn->prepare("DELETE FROM reviews WHERE id = ?");
    $delete_query->bind_param("i", $review_id);
    $delete_query->execute();

    if ($delete_query) {
        header('Location: ' . $_SERVER['HTTP_REFERER']); // Redirect back to the previous page
        exit();
    } else {
        die('Error deleting review: ' . $conn->error);
    }
} else {
    echo "You are not authorized to delete this review.";
}
?>
