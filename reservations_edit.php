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
      die("Reservation ID Not Found");
    }

    $id = mysqli_real_escape_string($conn, $_GET['idd']);

    // FETCH DATA
    $res = mysqli_query(
      $conn,
      "SELECT * FROM reservations
     WHERE reservation_id='$id'"
    );

    if (!$res || mysqli_num_rows($res) == 0) {
      die("Reservation Not Found");
    }

    $row = mysqli_fetch_array($res);

    ?>

    <div class="content-wrapper">

      <!-- HEADER -->
      <div class="content-header">
        <div class="container-fluid">

          <div class="row mb-2">

            <div class="col-sm-6">
              <h1 class="m-0">Reservations Edit</h1>
            </div>

            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">

                <li class="breadcrumb-item">
                  <a href="#">Home</a>
                </li>

                <li class="breadcrumb-item active">
                  Edit Reservation
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
                    Update Reservation
                  </h3>
                </div>

                <!-- FORM -->
                <form action="reservations_update.php?idd=<?php echo $id; ?>" method="POST">

                  <div class="card-body">

                    <!-- HIDDEN ID -->
                    <input type="hidden" name="reservation_id" value="<?php echo $row['reservation_id']; ?>">

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

                            Table <?php echo $trow['table_number']; ?>

                          </option>

                        <?php } ?>

                      </select>

                    </div>

                    <!-- DATE -->
                    <div class="form-group">

                      <label>Reservation Date</label>

                      <input type="date" name="reservation_date" class="form-control" required
                        value="<?php echo $row['reservation_date']; ?>">

                    </div>

                    <!-- TIME -->
                    <div class="form-group">

                      <label>Reservation Time</label>

                      <input type="time" name="reservation_time" class="form-control" required
                        value="<?php echo $row['reservation_time']; ?>">

                    </div>

                    <!-- GUESTS -->
                    <div class="form-group">

                      <label>Guests Count</label>

                      <input type="number" name="guests_count" class="form-control" placeholder="Enter Guests Count"
                        required value="<?php echo $row['guests_count']; ?>">

                    </div>

                    <!-- STATUS -->
                    <div class="form-group">

                      <label>Status</label>

                      <select name="status" class="form-control" required>

                        <option value="pending" <?php
                        if ($row['status'] == 'pending') {
                          echo 'selected';
                        }
                        ?>>
                          Pending
                        </option>

                        <option value="confirmed" <?php
                        if ($row['status'] == 'confirmed') {
                          echo 'selected';
                        }
                        ?>>
                          Confirmed
                        </option>

                        <option value="cancelled" <?php
                        if ($row['status'] == 'cancelled') {
                          echo 'selected';
                        }
                        ?>>
                          Cancelled
                        </option>

                      </select>

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