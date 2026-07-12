<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Order Report</title>

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
            <th>Employee</th>
            <th>Order Date</th>
            <th>Status</th>
            <th>Total Amount</th>

          </tr>

        </thead>

        <tbody>

          <?php

          // JOIN FK TABLES
          $read = mysqli_query(
            $conn,

            "SELECT

            orders.*,
            customers.fullname,
            tables.table_number,
            employee.name

          FROM orders

          LEFT JOIN customers
          ON orders.customer_id =
             customers.customer_id

          LEFT JOIN tables
          ON orders.table_id =
             tables.table_id

          LEFT JOIN employee
          ON orders.empid =
             employee.empid

          WHERE orders.order_id='$id'
          "
          );

          while ($row = mysqli_fetch_assoc($read)) {

            ?>

            <tr>

              <td>
                <?php echo $row['order_id']; ?>
              </td>

              <!-- CUSTOMER NAME -->
              <td>
                <?php echo $row['fullname']; ?>
              </td>

              <!-- TABLE NUMBER -->
              <td>
                Table
                <?php echo $row['table_number']; ?>
              </td>

              <!-- EMPLOYEE NAME -->
              <td>
                <?php echo $row['name']; ?>
              </td>

              <td>
                <?php echo $row['order_date']; ?>
              </td>

              <td>
                <?php echo $row['order_status']; ?>
              </td>

              <td>
                <?php echo $row['total_amount']; ?>
              </td>

            </tr>

          <?php } ?>

        </tbody>

      </table>

    </div>

  </div>

</body>

</html>