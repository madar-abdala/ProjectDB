<?php include 'lip/conn.php'; ?>
<?php include 'lip/footer.php'; ?>
<?php include 'lip/head.php'; ?>
<?php include 'lip/nav.php'; ?>
<?php include 'lip/sidebar.php'; ?>
<?php include 'lip/script.php'; ?>




<?php



if (isset($_POST['btnedit'])) {

  // GET FORM DATA
  $order_id = mysqli_real_escape_string(
    $conn,
    $_POST['order_id']
  );

  $customer_id = mysqli_real_escape_string(
    $conn,
    $_POST['customer_id']
  );

  $table_id = mysqli_real_escape_string(
    $conn,
    $_POST['table_id']
  );

  $empid = mysqli_real_escape_string(
    $conn,
    $_POST['empid']
  );

  $order_status = mysqli_real_escape_string(
    $conn,
    $_POST['order_status']
  );

  $total_amount = mysqli_real_escape_string(
    $conn,
    $_POST['total_amount']
  );

  // UPDATE QUERY
  $update = mysqli_query(
    $conn,

    "UPDATE orders SET

        customer_id='$customer_id',
        table_id='$table_id',
        empid='$empid',
        order_status='$order_status',
        total_amount='$total_amount'

        WHERE order_id='$order_id'
        "
  );

  // CHECK
  if ($update) {

    echo "<script>
                window.location.href='order.php';
              </script>";

    exit();

  } else {

    echo "<script>
                alert('Error: " . mysqli_error($conn) . "');
              </script>";
  }

} else {

  echo "<script>
            window.location.href='order.php';
          </script>";

  exit();
}

?>