<?php include 'lip/conn.php'; ?>
<?php include 'lip/footer.php'; ?>
<?php include 'lip/head.php'; ?>
<?php include 'lip/nav.php'; ?>
<?php include 'lip/sidebar.php'; ?>
<?php include 'lip/script.php'; ?>

<?php

if (isset($_POST['btnedit'])) {

  // Get Data
  $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
  $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);

  // Update Query
  $update = mysqli_query($conn, "UPDATE menu_categories SET
        category_name='$category_name'
        WHERE category_id='$category_id'
    ");

  // Check
  if ($update) {
    echo "<script>window.location.href='category.php';</script>";
    exit();
  } else {
    echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
  }
} else {
  echo "<script>window.location.href='category.php';</script>";
  exit();
}
?>