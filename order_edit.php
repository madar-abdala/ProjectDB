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
      die("Order ID Not Found");
    }

    $id = mysqli_real_escape_string($conn, $_GET['idd']);

    // FETCH ORDER
    $res = mysqli_query(
      $conn,
      "SELECT * FROM orders WHERE order_id='$id'"
    );

    if (!$res || mysqli_num_rows($res) == 0) {
      die("Order Not Found");
    }

    $row = mysqli_fetch_array($res);

    ?>

    <div class="content-wrapper">

      <!-- HEADER -->
      <div class="content-header">
        <div class="container-fluid">

          <div class="row mb-2">

            <div class="col-sm-6">
              <h1 class="m-0">Order Edit</h1>
            </div>

            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">

                <li class="breadcrumb-item">
                  <a href="#">Home</a>
                </li>

                <li class="breadcrumb-item active">
                  Edit Order
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
                    Update Order
                  </h3>
                </div>

                <!-- FORM -->
                <form action="order_update.php?idd=<?php echo $id; ?>" method="POST">

                  <div class="card-body">

                    <!-- HIDDEN ID -->
                    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">

                    <!-- CUSTOMER -->
                    <div class="form-group">

                      <label>Customer</label>

                      <select name="customer_id" class="form-control" required>

                        <option value="">
                          Select Customer
                        </option>

                        <?php

                        $customer = mysqli_query(
                          $conn,
                          "SELECT * FROM customers"
                        );

                        while ($crow = mysqli_fetch_array($customer)) {

                          $selected =
                            ($row['customer_id']
                              == $crow['customer_id'])
                            ? 'selected'
                            : '';

                          ?>

                          <option value="<?php echo $crow['customer_id']; ?>" <?php echo $selected; ?>>

                            <?php echo $crow['fullname']; ?>

                          </option>

                        <?php } ?>

                      </select>

                    </div>

                    <!-- TABLE -->
                    <div class="form-group">

                      <label>Table</label>

                      <select name="table_id" class="form-control" required>

                        <option value="">
                          Select Table
                        </option>

                        <?php

                        $table = mysqli_query(
                          $conn,
                          "SELECT * FROM tables"
                        );

                        while ($trow = mysqli_fetch_array($table)) {

                          $selected =
                            ($row['table_id']
                              == $trow['table_id'])
                            ? 'selected'
                            : '';

                          ?>

                          <option value="<?php echo $trow['table_id']; ?>" <?php echo $selected; ?>>

                            Table
                            <?php echo $trow['table_number']; ?>

                          </option>

                        <?php } ?>

                      </select>

                    </div>

                    <!-- EMPLOYEE -->
                    <div class="form-group">

                      <label>Employee</label>

                      <select name="empid" class="form-control" required>

                        <option value="">
                          Select Employee
                        </option>

                        <?php

                        $emp = mysqli_query(
                          $conn,
                          "SELECT * FROM employee"
                        );

                        while ($erow = mysqli_fetch_array($emp)) {

                          $selected =
                            ($row['empid']
                              == $erow['empid'])
                            ? 'selected'
                            : '';

                          ?>

                          <option value="<?php echo $erow['empid']; ?>" <?php echo $selected; ?>>

                            <?php echo $erow['name']; ?>

                          </option>

                        <?php } ?>

                      </select>

                    </div>

                    <!-- ORDER STATUS -->
                    <div class="form-group">

                      <label>Order Status</label>

                      <select name="order_status" class="form-control" required>

                        <option value="pending" <?php
                        if ($row['order_status'] == 'pending') {
                          echo 'selected';
                        }
                        ?>>
                          Pending
                        </option>

                        <option value="preparing" <?php
                        if ($row['order_status'] == 'preparing') {
                          echo 'selected';
                        }
                        ?>>
                          Preparing
                        </option>

                        <option value="served" <?php
                        if ($row['order_status'] == 'served') {
                          echo 'selected';
                        }
                        ?>>
                          Served
                        </option>

                        <option value="completed" <?php
                        if ($row['order_status'] == 'completed') {
                          echo 'selected';
                        }
                        ?>>
                          Completed
                        </option>

                      </select>

                    </div>

                    <!-- TOTAL AMOUNT -->
                    <div class="form-group">

                      <label>Total Amount</label>

                      <input type="number" step="0.01" name="total_amount" class="form-control"
                        placeholder="Enter Total Amount" required value="<?php echo $row['total_amount']; ?>">

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