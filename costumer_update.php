<?php include 'lip/conn.php'; ?>
<?php include 'lip/footer.php'; ?>
<?php include 'lip/head.php'; ?>
<?php include 'lip/nav.php'; ?>
<?php include 'lip/sidebar.php'; ?>
<?php include 'lip/script.php'; ?>


<?php


if (isset($_POST['btnedit'])) {
  $id = $_POST['id'];
  $fullname = $_POST['fullname'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];

  $update = mysqli_query($conn, "UPDATE customers SET
    fullname='$fullname',
    phone='$phone',
    email='$email'
    WHERE customer_id='$id'
  ");

  if ($update) {
    echo "<script>window.location.href='customer.php';</script>";
    exit();
  } else {
    echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
  }
} else {
  echo "<script>window.location.href='customer.php';</script>";
  exit();
}
?>