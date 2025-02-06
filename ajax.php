<?php
// Including Database configuration file.
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop_db";

$con = mysqli_connect($servername, $username, $password, $dbname);

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Getting value of "search" variable from "script.js".
if (isset($_POST['search'])) {
  // Search box value assigning to $Name variable.
  $name = $_POST['search']; //search_item

  // Search query.
  $query = "SELECT name FROM products WHERE name LIKE '$name%' LIMIT 5"; //search_item

  // Query execution
  $execQuery = mysqli_query($con, $query);

  // Creating unordered list to display result.
  echo '<ul>';

  // Fetching result from database.
  while ($result = mysqli_fetch_array($execQuery)) {
    // Creating unordered list items.
    // Calling javascript function named as "fill" found in "script.js" file.
    // By passing fetched result as parameter.
    echo "<li onclick='fill(\"{$result['name']}\")'><a>{$result['name']}</a></li>";//search_item
  }
  echo '</ul>';
}
?>