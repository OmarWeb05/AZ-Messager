<?php
// Assuming you have a database connection setup in database.inc.php
include('database.inc.php');

// Get the input query parameter
$q = $_REQUEST["q"];

if ($q !== "") {
  $q = strtolower($q);
  $query = "SELECT username, email, filename FROM user WHERE LOWER(username) LIKE '$q%' OR LOWER(email) LIKE '$q%'";
  $result = mysqli_query($con, $query);

  if (mysqli_num_rows($result) > 0) {
    echo '<ul style="margin-top:60px;margin-left:-70px;" class="search-results-list">';
    while ($row = mysqli_fetch_assoc($result)) {
      echo '<li class="search-result-item">';
      echo '<h2 style="margin-left:5px;color:white;font-size:19px;" class="username">' . $row["username"] . '</h2>';
      // echo '<p class="email">Email: ' . $row["email"] . '</p>';
      // echo '<img src="' . $row["filename"] . '" alt="User Image" class="user-image" width="50" height="50">';
      echo '<button style="background-color: #1d8745;border-radius:8px; border:none;font-size:13px; height:29px;color:white;" class="send-message-btn" onclick="openMessageDialog(\'' . $row["username"] . '\')">Send Message</button> <br> <br>';
      echo '</li>';
    }
    echo '</ul>';
  } else {
    echo '<p style="margin-top:10px;" class="no-results">No User</p>';
  }
}
?>
