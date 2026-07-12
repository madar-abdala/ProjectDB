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
                  Orders
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
                    Order Form
                  </h3>
                </div>

                <!-- INSERT -->
                <?php

                if (isset($_POST['btnsave'])) {

                  $customer_id = mysqli_real_escape_string(
                    $conn,
                    $_POST['customer_id']
                  );

                  $table_id = mysqli_real_escape_string(
                    $conn,
                    $_POST['table_id']
                  );

                  $empid = mysqli_real_escape_string(
                    $conn,
                    $_POST['empid']
                  );

                  $order_status = mysqli_real_escape_string(
                    $conn,
                    $_POST['order_status']
                  );

                  $total_amount = mysqli_real_escape_string(
                    $conn,
                    $_POST['total_amount']
                  );

                  $insert = mysqli_query(
                    $conn,

                    "INSERT INTO orders
                  (
                    customer_id,
                    table_id,
                    empid,
                    order_status,
                    total_amount
                  )

                  VALUES
                  (
                    '$customer_id',
                    '$table_id',
                    '$empid',
                    '$order_status',
                    '$total_amount'
                  )"
                  );

                  if ($insert) {

                    echo "<div class='alert alert-success m-2'>
                          Order Added Successfully
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
                    "DELETE FROM orders WHERE order_id='$id'"
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

                          ?>

                          <option value="<?php echo $crow['customer_id']; ?>">

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

                          ?>

                          <option value="<?php echo $trow['table_id']; ?>">

                            Table <?php echo $trow['table_number']; ?>

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

                          ?>

                          <option value="<?php echo $erow['empid']; ?>">

                            <?php echo $erow['name']; ?>

                          </option>

                        <?php } ?>

                      </select>

                    </div>

                    <!-- ORDER STATUS -->
                    <div class="form-group">

                      <label>Order Status</label>

                      <select name="order_status" class="form-control" required>

                        <option value="pending">
                          Pending
                        </option>

                        <option value="preparing">
                          Preparing
                        </option>

                        <option value="served">
                          Served
                        </option>

                        <option value="completed">
                          Completed
                        </option>

                      </select>

                    </div>

                    <!-- TOTAL AMOUNT -->
                    <div class="form-group">

                      <label>Total Amount</label>

                      <input type="number" step="0.01" name="total_amount" class="form-control"
                        placeholder="Enter Total Amount" required>

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
                    Orders Record
                  </h3>
                </div>

                <div class="card-body">

                  <div class="table-responsive">

                    <table id="example1" class="table table-bordered table-striped table-sm">

                      <thead>

                        <tr>

                          <th>ID</th>
                          <th>Customer</th>
                          <th>Table</th>
                          <th>Employee</th>
                          <th>Order Date</th>
                          <th>Status</th>
                          <th>Total Amount</th>
                          <th width="190">Action</th>

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
                        "
                        );

                        while ($row = mysqli_fetch_array($read)) {

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
                              Table <?php echo $row['table_number']; ?>
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

                            <td>

                              <!-- EDIT -->
                              <a href="order_edit.php?idd=<?php echo $row['order_id']; ?>" class="btn btn-success btn-sm">

                                <i class="fa fa-edit"></i>

                              </a>

                              <!-- DELETE -->
                              <a href="order.php?idd=<?php echo $row['order_id']; ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Mahubtaa inaad tireysid xogtaan?')">

                                <i class="fa fa-trash"></i>

                              </a>

                              <!-- REPORT -->
                              <a href="order_report.php?idd=<?php echo $row['order_id']; ?>" class="btn btn-info btn-sm">

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