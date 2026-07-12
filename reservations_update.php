<?php include 'lip/conn.php'; ?>
<?php include 'lip/footer.php'; ?>
<?php include 'lip/head.php'; ?>
<?php include 'lip/nav.php'; ?>
<?php include 'lip/sidebar.php'; ?>
<?php include 'lip/script.php'; ?>


<?php



if (isset($_POST['btnedit'])) {

  // GET FORM DATA
  $reservation_id = mysqli_real_escape_string(
    $conn,
    $_POST['reservation_id']
  );

  $customer_id = mysqli_real_escape_string(
    $conn,
    $_POST['customer_id']
  );

  $table_id = mysqli_real_escape_string(
    $conn,
    $_POST['table_id']
  );

  $reservation_date = mysqli_real_escape_string(
    $conn,
    $_POST['reservation_date']
  );

  $reservation_time = mysqli_real_escape_string(
    $conn,
    $_POST['reservation_time']
  );

  $guests_count = mysqli_real_escape_string(
    $conn,
    $_POST['guests_count']
  );

  $status = mysqli_real_escape_string(
    $conn,
    $_POST['status']
  );

  // UPDATE QUERY
  $update = mysqli_query(
    $conn,

    "UPDATE reservations SET

        customer_id='$customer_id',
        table_id='$table_id',
        reservation_date='$reservation_date',
        reservation_time='$reservation_time',
        guests_count='$guests_count',
        status='$status'

        WHERE reservation_id='$reservation_id'
        "
  );

  // CHECK
  if ($update) {

    echo "<script>
                window.location.href='reservations.php';
              </script>";

    exit();

  } else {

    echo "<script>
                alert('Error: " . mysqli_error($conn) . "');
              </script>";
  }

} else {

  echo "<script>
            window.location.href='reservations.php';
          </script>";

  exit();
}

?>