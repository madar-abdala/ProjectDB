<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Report</title>

  <?php
  $id = $_GET['idd'];
  include 'lip/head.php';
  include 'lip/conn.php';
  ?>

</head>
<style>
  body {
    background: whitesmoke;
  }

  .container {
    background: white;
    height: 900;


  }
</style>

<body>
  <div class="container">
    <div class="row">
      <table class="table table-bodered">


        <tr>
          <th>ID</th>
          <th>Fullname</th>
          <th>Username</th>
          <th>Password</th>
          <th>Data</th>


        </tr>

        <?php
        $read = mysqli_query($conn, "SELECT * FROM user where user_id='$id'");
        while ($row = mysqli_fetch_array($read)) {
          ?>
          <tr>
            <td><?php echo $row[0]; ?></td>
            <td><?php echo $row[1]; ?></td>
            <td><?php echo $row[2]; ?></td>
            <td><?php echo $row[3]; ?></td>
            <td><?php echo $row[4]; ?></td>
          </tr>
        <?php }

        ?>

      </table>
    </div>


  </div>
</body>

</html>