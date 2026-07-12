<?php include 'lip/conn.php'; ?>
<?php include 'lip/footer.php'; ?>
<?php include 'lip/head.php'; ?>
<?php include 'lip/nav.php'; ?>
<?php include 'lip/sidebar.php'; ?>
<?php include 'lip/script.php'; ?>

<?php



if (isset($_POST['btnedit'])) {

  // GET FORM DATA
  $payment_id = mysqli_real_escape_string(
    $conn,
    $_POST['payment_id']
  );

  $order_id = mysqli_real_escape_string(
    $conn,
    $_POST['order_id']
  );

  $payment_method = mysqli_real_escape_string(
    $conn,
    $_POST['payment_method']
  );

  $amount = mysqli_real_escape_string(
    $conn,
    $_POST['amount']
  );

  // UPDATE QUERY
  $update = mysqli_query(
    $conn,

    "UPDATE payments SET

        order_id='$order_id',
        payment_method='$payment_method',
        amount='$amount'

        WHERE payment_id='$payment_id'
        "
  );

  // CHECK
  if ($update) {

    echo "<script>
                window.location.href='payments.php';
              </script>";

    exit();

  } else {

    echo "<script>
                alert('Error: " . mysqli_error($conn) . "');
              </script>";
  }

} else {

  echo "<script>
            window.location.href='payments.php';
          </script>";

  exit();
}

?>