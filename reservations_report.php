<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Reservations Report</title>

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
            <th>Customer</th>
            <th>Table</th>
            <th>Reservation Date</th>
            <th>Reservation Time</th>
            <th>Guests Count</th>
            <th>Status</th>

          </tr>

        </thead>

        <tbody>

          <?php

          // JOIN FK TABLES
          $read = mysqli_query(
            $conn,

            "SELECT

            reservations.*,
            customers.fullname,
            tables.table_number

          FROM reservations

          LEFT JOIN customers
          ON reservations.customer_id =
             customers.customer_id

          LEFT JOIN tables
          ON reservations.table_id =
             tables.table_id

          WHERE reservations.reservation_id='$id'
          "
          );

          while ($row = mysqli_fetch_assoc($read)) {

            ?>

            <tr>

              <td>
                <?php echo $row['reservation_id']; ?>
              </td>

              <!-- CUSTOMER NAME -->
              <td>
                <?php echo $row['fullname']; ?>
              </td>

              <!-- TABLE NUMBER -->
              <td>
                Table <?php echo $row['table_number']; ?>
              </td>

              <td>
                <?php echo $row['reservation_date']; ?>
              </td>

              <td>
                <?php echo $row['reservation_time']; ?>
              </td>

              <td>
                <?php echo $row['guests_count']; ?>
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