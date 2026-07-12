<?php include 'lip/conn.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'lip/head.php' ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Navbar -->
    <?php include 'lip/nav.php'; ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php include 'lip/sidebar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard v1</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-2"></div>
            <div class="col-8">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Submenu record</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="" method="post">
                  <div class="card-body">
                    <div class="form-group">
                      <label>Menu Name</label>
                      <select name="menuname" class="form-control">
                        <option value="select menu Name">select menu Name</option>
                        <?php
                        $res = mysqli_query($conn, "SELECT *FROM menues");
                        while ($row = mysqli_fetch_array($res)) {
                          echo "<option value='$row[0]'>$row[1]</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>sub Menu name</label>
                      <input type="text" class="form-control" name="submenuname" placeholder="Enter sub menuname"
                        required>
                    </div>
                    <div class="form-group">
                      <label>sub menu Icon</label>
                      <input type="text" class="form-control" name="icon" placeholder="Enter Icon" required>
                    </div>
                    <div class="form-group">
                      <label> submenu Url</label>
                      <input type="text" class="form-control" name="url" placeholder="Enter Url" required>
                    </div>
                  </div>
                  <!-- /.card-body -->

                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary" name="btnregister">Register</button>
                  </div>
                </form>
                <?php
                if (isset($_POST['btnregister'])) {
                  $name = mysqli_real_escape_string($conn, $_POST['menuname']);
                  $smenu = mysqli_real_escape_string($conn, $_POST['submenuname']);
                  $icon = mysqli_real_escape_string($conn, $_POST['icon']);
                  $url = mysqli_real_escape_string($conn, $_POST['url']);

                  $insert = mysqli_query($conn, "INSERT INTO submenues VALUES(null, '$name', '$smenu', '$icon', '$url')");
                  echo "<h1 class='btn btn-success'>Inserted Success</h1>";
                }
                ?>
              </div>
            </div>
            <div class="col-8">
              <div>

              </div>
            </div>
          </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <?php include 'lip/footer.php'; ?>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->
  <?php include 'lip/script.php'; ?>
</body>

</html>