<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Report</title>
  <?php
  include 'lip/head.php';
  include 'lip/conn.php';
  $id = isset($_GET['idd']) ? mysqli_real_escape_string($conn, $_GET['idd']) : '';
  ?>
</head>
<style>
  body {
    background: whitesmoke;
  }

  .container {
    background: white;
    min-height: 900px;
    padding: 30px;
  }
</style>

<body>
  <div class="container">
    <div class="row">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Fullname</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Created At</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $read = mysqli_query($conn, "SELECT * FROM customers WHERE customer_id='$id'");
          while ($row = mysqli_fetch_assoc($read)) {
            ?>
            <tr>
              <td><?php echo $row['customer_id']; ?></td>
              <td><?php echo $row['fullname']; ?></td>
              <td><?php echo $row['phone']; ?></td>
              <td><?php echo $row['email']; ?></td>
              <td><?php echo $row['created_at']; ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>