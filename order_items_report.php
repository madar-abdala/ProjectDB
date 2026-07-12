<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Order Items Report</title>

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
            <th>Order</th>
            <th>Item</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Subtotal</th>

          </tr>

        </thead>

        <tbody>

          <?php

          // JOIN FK TABLES
          $read = mysqli_query(
            $conn,

            "SELECT

            order_items.*,
            menu_items.item_name

          FROM order_items

          LEFT JOIN menu_items
          ON order_items.item_id =
             menu_items.item_id

          WHERE order_items.order_item_id='$id'
          "
          );

          while ($row = mysqli_fetch_assoc($read)) {

            ?>

            <tr>

              <td>
                <?php echo $row['order_item_id']; ?>
              </td>

              <td>
                Order #<?php echo $row['order_id']; ?>
              </td>

              <!-- ITEM NAME -->
              <td>
                <?php echo $row['item_name']; ?>
              </td>

              <td>
                <?php echo $row['quantity']; ?>
              </td>

              <td>
                <?php echo $row['price']; ?>
              </td>

              <td>
                <?php echo $row['subtotal']; ?>
              </td>

            </tr>

          <?php } ?>

        </tbody>

      </table>

    </div>

  </div>

</body>

</html>