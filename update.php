<?php include 'lip/conn.php'; ?>
<?php include 'lip/footer.php'; ?>
<?php include 'lip/head.php'; ?>
<?php include 'lip/nav.php'; ?>
<?php include 'lip/sidebar.php'; ?>
<?php include 'lip/script.php'; ?>



<?php


if (isset($_POST['btnedit'])) {
  $empid = mysqli_real_escape_string($conn, $_POST['id']);
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $tell = mysqli_real_escape_string($conn, $_POST['tell']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $address = mysqli_real_escape_string($conn, $_POST['address']);
  $jobtitle = mysqli_real_escape_string($conn, $_POST['jobtitle']);
  $salary = mysqli_real_escape_string($conn, $_POST['salary']);
  $date = mysqli_real_escape_string($conn, $_POST['date']);

  $update = mysqli_query($conn, "UPDATE employee SET
    name='$name',
    tell='$tell',
    email='$email',
    address='$address',
    jobtitle='$jobtitle',
    salary='$salary',
    date='$date'
    WHERE empid='$empid'
  ");

  if ($update) {
    echo "<script>window.location.href='employee.php';</script>";
    exit();
  } else {
    echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
  }
} else {
  echo "<script>window.location.href='employee.php';</script>";
  exit();
}
?>