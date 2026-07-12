<?php include 'lip/conn.php'; ?>
<?php include 'lip/footer.php'; ?>
<?php include 'lip/head.php'; ?>
<?php include 'lip/nav.php'; ?>
<?php include 'lip/sidebar.php'; ?>
<?php include 'lip/script.php'; ?>



<?php


if (isset($_POST['btnedit'])) {

  // GET FORM DATA
  $table_id = mysqli_real_escape_string($conn, $_POST['table_id']);

  $table_number = mysqli_real_escape_string(
    $conn,
    $_POST['table_number']
  );

  $capacity = mysqli_real_escape_string(
    $conn,
    $_POST['capacity']
  );

  $status = mysqli_real_escape_string(
    $conn,
    $_POST['status']
  );

  // UPDATE QUERY
  $update = mysqli_query(
    $conn,

    "UPDATE tables SET

        table_number='$table_number',
        capacity='$capacity',
        status='$status'

        WHERE table_id='$table_id'
        "
  );

  // CHECK
  if ($update) {

    echo "<script>
                window.location.href='table.php';
              </script>";

    exit();

  } else {

    echo "<script>
                alert('Error: " . mysqli_error($conn) . "');
              </script>";
  }

} else {

  echo "<script>
            window.location.href='table.php';
          </script>";

  exit();
}

?>