<?php include 'lip/conn.php'; ?>
<?php include 'lip/footer.php'; ?>
<?php include 'lip/head.php'; ?>
<?php include 'lip/nav.php'; ?>
<?php include 'lip/sidebar.php'; ?>
<?php include 'lip/script.php'; ?>



<?php



if (isset($_POST['btnedit'])) {

  // GET FORM DATA
  $order_item_id = mysqli_real_escape_string(
    $conn,
    $_POST['order_item_id']
  );

  $order_id = mysqli_real_escape_string(
    $conn,
    $_POST['order_id']
  );

  $item_id = mysqli_real_escape_string(
    $conn,
    $_POST['item_id']
  );

  $quantity = mysqli_real_escape_string(
    $conn,
    $_POST['quantity']
  );

  $price = mysqli_real_escape_string(
    $conn,
    $_POST['price']
  );

  $subtotal = mysqli_real_escape_string(
    $conn,
    $_POST['subtotal']
  );

  // UPDATE QUERY
  $update = mysqli_query(
    $conn,

    "UPDATE order_items SET

        order_id='$order_id',
        item_id='$item_id',
        quantity='$quantity',
        price='$price',
        subtotal='$subtotal'

        WHERE order_item_id='$order_item_id'
        "
  );

  // CHECK
  if ($update) {

    echo "<script>
                window.location.href='order_items.php';
              </script>";

    exit();

  } else {

    echo "<script>
                alert('Error: " . mysqli_error($conn) . "');
              </script>";
  }

} else {

  echo "<script>
            window.location.href='order_items.php';
          </script>";

  exit();
}

?>