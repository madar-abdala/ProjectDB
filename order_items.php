<?php
session_start();
include 'lip/conn.php';
include 'check_permission.php';

// Session check
if (!isset($_SESSION['empid'])) {
  header("Location: login.php");
  exit();
}

// ✅ KU DAR HALKAN - 'employee.php' URL-ka page-kaaga
checkPermission($conn, $_SESSION['user_id'], 'employee.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'lip/head.php'; ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">

  <div class="wrapper">

    <?php include 'lip/nav.php'; ?>
    <?php include 'lip/sidebar.php'; ?>

    <div class="content-wrapper">

      <!-- HEADER -->
      <div class="content-header">
        <div class="container-fluid">

          <div class="row mb-2">

            <div class="col-sm-6">
              <h1 class="m-0">Dashboard</h1>
            </div>

            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">

                <li class="breadcrumb-item">
                  <a href="#">Home</a>
                </li>

                <li class="breadcrumb-item active">
                  Order Items
                </li>

              </ol>
            </div>

          </div>

        </div>
      </div>

      <!-- CONTENT -->
      <section class="content">

        <div class="container-fluid">

          <div class="row">

            <!-- LEFT SIDE FORM -->
            <div class="col-md-4">

              <div class="card card-primary">

                <div class="card-header">
                  <h3 class="card-title">
                    Order Item Form
                  </h3>
                </div>

                <!-- INSERT -->
                <?php

                if (isset($_POST['btnsave'])) {

                  $order_id = mysqli_real_escape_string(
                    $conn,
                    $_POST['order_id']
                  );

                  $item_id = mysqli_real_escape_string(
                    $conn,
                    $_POST['item_id']
                  );

                  $quantity = mysqli_real_escape_string(
                    $conn,
                    $_POST['quantity']
                  );

                  $price = mysqli_real_escape_string(
                    $conn,
                    $_POST['price']
                  );

                  $subtotal = mysqli_real_escape_string(
                    $conn,
                    $_POST['subtotal']
                  );

                  $insert = mysqli_query(
                    $conn,

                    "INSERT INTO order_items
                  (
                    order_id,
                    item_id,
                    quantity,
                    price,
                    subtotal
                  )

                  VALUES
                  (
                    '$order_id',
                    '$item_id',
                    '$quantity',
                    '$price',
                    '$subtotal'
                  )"
                  );

                  if ($insert) {

                    echo "<div class='alert alert-success m-2'>
                          Order Item Added Successfully
                        </div>";

                  } else {

                    echo "<div class='alert alert-danger m-2'>
                          Error: " . mysqli_error($conn) . "
                        </div>";
                  }
                }

                ?>

                <!-- DELETE -->
                <?php

                if (isset($_GET['idd'])) {

                  $id = mysqli_real_escape_string(
                    $conn,
                    $_GET['idd']
                  );

                  $delete = mysqli_query(
                    $conn,
                    "DELETE FROM order_items
                   WHERE order_item_id='$id'"
                  );

                  if ($delete) {

                    echo "<div class='alert alert-danger m-2'>
                          Deleted Successfully
                        </div>";
                  }
                }

                ?>

                <!-- FORM -->
                <form action="" method="POST">

                  <div class="card-body">

                    <!-- ORDER -->
                    <div class="form-group">

                      <label>Order</label>

                      <select name="order_id" class="form-control" required>

                        <option value="">
                          Select Order
                        </option>

                        <?php

                        $order = mysqli_query(
                          $conn,
                          "SELECT * FROM orders"
                        );

                        while ($orow = mysqli_fetch_array($order)) {

                          ?>

                          <option value="<?php echo $orow['order_id']; ?>">

                            Order #<?php echo $orow['order_id']; ?>

                          </option>

                        <?php } ?>

                      </select>

                    </div>

                    <!-- ITEM -->
                    <div class="form-group">

                      <label>Menu Item</label>

                      <select name="item_id" class="form-control" required>

                        <option value="">
                          Select Item
                        </option>

                        <?php

                        $item = mysqli_query(
                          $conn,
                          "SELECT * FROM menu_items"
                        );

                        while ($irow = mysqli_fetch_array($item)) {

                          ?>

                          <option value="<?php echo $irow['item_id']; ?>">

                            <?php echo $irow['item_name']; ?>

                          </option>

                        <?php } ?>

                      </select>

                    </div>

                    <!-- QUANTITY -->
                    <div class="form-group">

                      <label>Quantity</label>

                      <input type="number" name="quantity" class="form-control" placeholder="Enter Quantity" required>

                    </div>

                    <!-- PRICE -->
                    <div class="form-group">

                      <label>Price</label>

                      <input type="number" step="0.01" name="price" class="form-control" placeholder="Enter Price"
                        required>

                    </div>

                    <!-- SUBTOTAL -->
                    <div class="form-group">

                      <label>Subtotal</label>

                      <input type="number" step="0.01" name="subtotal" class="form-control" placeholder="Enter Subtotal"
                        required>

                    </div>

                  </div>

                  <div class="card-footer">

                    <button type="submit" name="btnsave" class="btn btn-primary">

                      INSERT

                    </button>

                  </div>

                </form>

              </div>

            </div>

            <!-- RIGHT SIDE TABLE -->
            <div class="col-md-8">

              <div class="card card-primary">

                <div class="card-header">
                  <h3 class="card-title">
                    Order Items Record
                  </h3>
                </div>

                <div class="card-body">

                  <div class="table-responsive">

                    <table id="example1" class="table table-bordered table-striped table-sm">

                      <thead>

                        <tr>

                          <th>ID</th>
                          <th>Order</th>
                          <th>Item</th>
                          <th>Quantity</th>
                          <th>Price</th>
                          <th>Subtotal</th>
                          <th width="140">Action</th>

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
                        "
                        );

                        while ($row = mysqli_fetch_array($read)) {

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

                            <td>

                              <!-- EDIT -->
                              <a href="order_items_edit.php?idd=<?php echo $row['order_item_id']; ?>"
                                class="btn btn-success btn-sm">

                                <i class="fa fa-edit"></i>

                              </a>

                              <!-- DELETE -->
                              <a href="order_items.php?idd=<?php echo $row['order_item_id']; ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Mahubtaa inaad tireysid xogtaan?')">

                                <i class="fa fa-trash"></i>

                              </a>

                              <!-- REPORT -->
                              <a href="order_items_report.php?idd=<?php echo $row['order_item_id']; ?>"
                                class="btn btn-info btn-sm">

                                <i class="fa fa-print"></i>

                              </a>

                            </td>

                          </tr>

                        <?php } ?>

                      </tbody>

                    </table>

                  </div>

                </div>

              </div>

            </div>

          </div>

        </div>

      </section>

    </div>

    <?php include 'lip/footer.php'; ?>

    <aside class="control-sidebar control-sidebar-dark"></aside>

  </div>

  <?php include 'lip/script.php'; ?>

</body>

</html>