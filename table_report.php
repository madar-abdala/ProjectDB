<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Table Report</title>

  <?php

  include 'lip/head.php';
  include 'lip/conn.php';

  // GET ID
  $id = isset($_GET['idd'])
    ? mysqli_real_escape_string($conn, $_GET['idd'])
    : '';

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
            <th>Table Number</th>
            <th>Capacity</th>
            <th>Status</th>

          </tr>

        </thead>

        <tbody>

          <?php

          $read = mysqli_query(
            $conn,

            "SELECT * FROM tables
           WHERE table_id='$id'"
          );

          while ($row = mysqli_fetch_assoc($read)) {

            ?>

            <tr>

              <td>
                <?php echo $row['table_id']; ?>
              </td>
              <td>
                  <?php echo $row['table_number']; ?>
              </td>

              <td>
                <?php echo $row['capacity']; ?>
              </td>

              <td>
                <?php echo $row['status']; ?>
              </td>

            </tr>

          <?php } ?>

        </tbody>

      </table>

    </div>

  </div>

</body>

</html>