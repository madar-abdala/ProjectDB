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
                  <h3 class="card-title">menu registration form</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="" method="post">
                  <div class="card-body">
                    <div class="form-group">
                      <label>Menu Name</label>
                      <input type="text" class="form-control" name="mname" placeholder="Enter menu name" required>
                    </div>
                    <div class="form-group">
                      <label>MenuIcon</label>
                      <input type="text" class="form-control" name="micon" placeholder="Enter Menu Icon" required>
                    </div>
                    <div class="form-group">
                      <label>Menu Url</label>
                      <input type="text" class="form-control" name="murl" placeholder="Enter Menu Url" required>
                    </div>
                  </div>
                  <!-- /.card-body -->

                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary" name="btnregister">Register</button>
                  </div>
                </form>
                  <?php
                  if (isset($_POST['btnregister'])) {
                    $mn = mysqli_real_escape_string($conn, $_POST['mname']);
                    $mi = mysqli_real_escape_string($conn, $_POST['micon']);
                    $murl = mysqli_real_escape_string($conn, $_POST['murl']);
                    $insert = mysqli_query($conn, "INSERT INTO menues VALUES(null, '$mn', '$mi', '$murl')");
                    echo "<h1 class='btn btn-success'>Inserted Success</h1>";
                  }
                  ?>
              </div>
            </div>
              <div class="col-2"></div>
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