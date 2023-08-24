<?php
session_start();
include('database.inc.php');

$time = time();
$res = mysqli_query($con, "SELECT * FROM user"); // Corrected the SQL query

if (!$res) {
    die("Error fetching user data: " . mysqli_error($con));
}

$html = '';
while ($row = mysqli_fetch_assoc($res)) {
    $status = 'Offline';
    $class = "btn-danger";
    if ($row['last_login'] > $time) {
        $status = 'Online';
        $class = "btn-success";
    }

    // Assume the message function is called "sendMessage" and takes the recipient's user ID as a parameter.
    $button = '<button type="button" name="search" style="width: 80px;" class="btn btn-primary" onclick="openMessageDialog(\'' . $row['username'] . ' ID: ' . $row['id'] . '\')"> Göndər </button>';
    $remove = '<form method="post" onsubmit="return confirm(\'Bu istifadəçini silmək istədiyinizə əminsiniz?\');">
                <input type="hidden" name="ID" value="' . $row['id'] . '">
                <button type="submit" name="remove" style="width: 80px;background-color:red;border:none;" class="btn btn-primary">Remove</button>
            </form>';

    // Make sure to define the $notify variable before using it
    $notify = isset($row['notify']) ? $row['notify'] : ''; // Check if the 'notify' key exists and set a default value if it doesn't.
    // $pass = isset($row['password']) ? $row['password'] : ''; 

    $html .= '<tr>
                  <th scope="row">' . $row['id'] . '</th>
                  <td>' . $row['username'] . '</td>
                  <td>' . $notify . ' bildiriş işarəsi tezliklə görünəcək.. </p>' . '</td>
                  <td>' . $button . '</td>
                  <td>' . $remove . '</td>
                  <td><button type="button" class="btn ' . $class . '">' . $status . '</button></td>
                  </tr>';
}
echo $html;
?>
