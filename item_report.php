<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Menu Item Report</title>

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
            <th>Item Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock Quantity</th>
            <th>Category</th>

          </tr>

        </thead>

        <tbody>

          <?php

          // JOIN FOR CATEGORY NAME
          $read = mysqli_query(
            $conn,

            "SELECT
            menu_items.*,
            menu_categories.category_name

          FROM menu_items

          LEFT JOIN menu_categories

          ON menu_items.category_id =
             menu_categories.category_id

          WHERE item_id='$id'
          "
          );

          while ($row = mysqli_fetch_assoc($read)) {

            ?>

            <tr>

              <td><?php echo $row['item_id']; ?></td>

              <td><?php echo $row['item_name']; ?></td>

              <td><?php echo $row['description']; ?></td>

              <td><?php echo $row['price']; ?></td>

              <td><?php echo $row['stock_quantity']; ?></td>

              <!-- CATEGORY NAME -->
              <td><?php echo $row['category_name']; ?></td>

            </tr>

          <?php } ?>

        </tbody>

      </table>

    </div>

  </div>

</body>

</html>