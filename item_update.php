<?php include 'lip/conn.php'; ?>
<?php include 'lip/footer.php'; ?>
<?php include 'lip/head.php'; ?>
<?php include 'lip/nav.php'; ?>
<?php include 'lip/sidebar.php'; ?>
<?php include 'lip/script.php'; ?>



<?php

include 'lip/conn.php';

if (isset($_POST['btnedit'])) {

  // GET FORM DATA
  $item_id = $_POST['item_id'];
  $item_name = $_POST['item_name'];
  $description = $_POST['description'];
  $price = $_POST['price'];
  $stock_quantity = $_POST['stock_quantity'];
  $category_id = $_POST['category_id'];

  // UPDATE QUERY
  $update = mysqli_query($conn, "UPDATE menu_items SET

        item_name='$item_name',
        description='$description',
        price='$price',
        stock_quantity='$stock_quantity',
        category_id='$category_id'

        WHERE item_id='$item_id'
    ");

  // CHECK
  if ($update) {

    echo "<script>
                window.location.href='items.php';
              </script>";

    exit();

  } else {

    echo "<script>
                alert('Error: " . mysqli_error($conn) . "');
              </script>";
  }

} else {

  echo "<script>
            window.location.href='items.php';
          </script>";

  exit();
}

?>