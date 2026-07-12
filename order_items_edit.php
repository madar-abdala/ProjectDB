<?php include 'lip/conn.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'lip/head.php'; ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">

  <div class="wrapper">

    <?php include 'lip/nav.php'; ?>
    <?php include 'lip/sidebar.php'; ?>

    <?php

    // CHECK ID
    if (!isset($_GET['idd']) || empty($_GET['idd'])) {
      die("Order Item ID Not Found");
    }

    $id = mysqli_real_escape_string($conn, $_GET['idd']);

    // FETCH DATA
    $res = mysqli_query(
      $conn,
      "SELECT * FROM order_items
     WHERE order_item_id='$id'"
    );

    if (!$res || mysqli_num_rows($res) == 0) {
      die("Order Item Not Found");
    }

    $row = mysqli_fetch_array($res);

    ?>

    <div class="content-wrapper">

      <!-- HEADER -->
      <div class="content-header">
        <div class="container-fluid">

          <div class="row mb-2">

            <div class="col-sm-6">
              <h1 class="m-0">Order Items Edit</h1>
            </div>

            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">

                <li class="breadcrumb-item">
                  <a href="#">Home</a>
                </li>

                <li class="breadcrumb-item active">
                  Edit Order Item
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

            <div class="col-md-6">

              <div class="card card-primary">

                <div class="card-header">
                  <h3 class="card-title">
                    Update Order Item
                  </h3>
                </div>

                <!-- FORM -->
                <form action="order_items_update.php?idd=<?php echo $id; ?>" method="POST">

                  <div class="card-body">

                    <!-- HIDDEN ID -->
                    <input type="hidden" name="order_item_id" value="<?php echo $row['order_item_id']; ?>">

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

                          $selected =
                            ($row['order_id']
                              == $orow['order_id'])
                            ? 'selected'
                            : '';

                          ?>

                          <option value="<?php echo $orow['order_id']; ?>" <?php echo $selected; ?>>

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

                          $selected =
                            ($row['item_id']
                              == $irow['item_id'])
                            ? 'selected'
                            : '';

                          ?>

                          <option value="<?php echo $irow['item_id']; ?>" <?php echo $selected; ?>>

                            <?php echo $irow['item_name']; ?>

                          </option>

                        <?php } ?>

                      </select>

                    </div>

                    <!-- QUANTITY -->
                    <div class="form-group">

                      <label>Quantity</label>

                      <input type="number" name="quantity" class="form-control" placeholder="Enter Quantity" required
                        value="<?php echo $row['quantity']; ?>">

                    </div>

                    <!-- PRICE -->
                    <div class="form-group">

                      <label>Price</label>

                      <input type="number" step="0.01" name="price" class="form-control" placeholder="Enter Price"
                        required value="<?php echo $row['price']; ?>">

                    </div>

                    <!-- SUBTOTAL -->
                    <div class="form-group">

                      <label>Subtotal</label>

                      <input type="number" step="0.01" name="subtotal" class="form-control" placeholder="Enter Subtotal"
                        required value="<?php echo $row['subtotal']; ?>">

                    </div>

                  </div>

                  <div class="card-footer">

                    <button type="submit" name="btnedit" class="btn btn-primary">

                      UPDATE

                    </button>

                  </div>

                </form>

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