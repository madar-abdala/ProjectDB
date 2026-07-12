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
      die("Table ID Not Found");
    }

    $id = mysqli_real_escape_string($conn, $_GET['idd']);

    // FETCH DATA
    $res = mysqli_query(
      $conn,
      "SELECT * FROM tables WHERE table_id='$id'"
    );

    if (!$res || mysqli_num_rows($res) == 0) {
      die("Table Not Found");
    }

    $row = mysqli_fetch_array($res);

    ?>

    <div class="content-wrapper">

      <!-- HEADER -->
      <div class="content-header">
        <div class="container-fluid">

          <div class="row mb-2">

            <div class="col-sm-6">
              <h1 class="m-0">Table Edit</h1>
            </div>

            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">

                <li class="breadcrumb-item">
                  <a href="#">Home</a>
                </li>

                <li class="breadcrumb-item active">
                  Edit Table
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

            <div class="col-md-5">

              <div class="card card-primary">

                <div class="card-header">
                  <h3 class="card-title">
                    Update Table
                  </h3>
                </div>

                <!-- FORM -->
                <form action="table_update.php?idd=<?php echo $id; ?>" method="POST">

                  <div class="card-body">

                    <!-- HIDDEN ID -->
                    <input type="hidden" name="table_id" value="<?php echo $row['table_id']; ?>">

                    <!-- TABLE NUMBER -->
                    <div class="form-group">

                      <label>Table Number</label>

                      <input type="number" name="table_number" class="form-control" placeholder="Enter Table Number"
                        required value="<?php echo $row['table_number']; ?>">

                    </div>

                    <!-- CAPACITY -->
                    <div class="form-group">

                      <label>Capacity</label>

                      <input type="number" name="capacity" class="form-control" placeholder="Enter Capacity" required
                        value="<?php echo $row['capacity']; ?>">

                    </div>

                    <!-- STATUS -->
                    <div class="form-group">

                      <label>Status</label>

                      <select name="status" class="form-control" required>

                        <option value="">
                          Select Status
                        </option>

                        <option value="available" <?php
                        if ($row['status'] == 'available') {
                          echo 'selected';
                        }
                        ?>>
                          Available
                        </option>

                        <option value="occupied" <?php
                        if ($row['status'] == 'occupied') {
                          echo 'selected';
                        }
                        ?>>
                          Occupied
                        </option>

                        <option value="reserved" <?php
                        if ($row['status'] == 'reserved') {
                          echo 'selected';
                        }
                        ?>>
                          Reserved
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