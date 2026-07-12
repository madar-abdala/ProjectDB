<?php
include "lip/conn.php";

$userid = isset($_GET['userid']) ? (int) $_GET['userid'] : 0;
$result = [];

if ($userid > 0) {
  $q = mysqli_query($conn, "
        SELECT submenuid FROM role WHERE user_id='$userid'
    ");
  while ($r = mysqli_fetch_assoc($q)) {
    $result[] = $r['submenuid'];
  }
}

header('Content-Type: application/json');
echo json_encode($result);
?>