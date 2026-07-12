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

    <!-- Navbar -->
    <?php include 'lip/nav.php'; ?>

    <!-- Sidebar -->
    <?php include 'lip/sidebar.php'; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">

      <!-- Header -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Dashboard</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard v1</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">

            <div class="col-md-5">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">User Register</h3>
                </div>

                <!-- Form -->
                <form action="" method="post">
                  <div class="card-body">

                    <div class="form-group">
                      <label>Employee Name</label>

                      <select name="empname" class="form-control">
                        <option value="">Select Employee Name</option>

                        <?php
                        $read = mysqli_query($conn, "SELECT * FROM employee");

                        while ($row = mysqli_fetch_array($read)) {
                          ?>
                          <option value="<?php echo $row[0]; ?>">
                            <?php echo $row[1]; ?>
                          </option>
                          <?php
                        }
                        ?>
                      </select>
                    </div>

                    <div class="form-group">
                      <label>Username</label>
                      <input type="text" class="form-control" name="usname" placeholder="Enter Username" required>
                    </div>

                    <div class="form-group">
                      <label>Password</label>
                      <input type="password" class="form-control" name="pass" placeholder="Enter Password" required>
                    </div>

                    <div class="form-group">
                      <label>Date</label>
                      <input type="date" class="form-control" name="date" required>
                    </div>

                  </div>

                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary" name="btnregister">INSERT</button>
                  </div>



                  <?php
                  if (isset($_POST['btnregister'])) {
                    $na = $_POST['empname'];
                    $un = $_POST['usname'];
                    $pa = $_POST['pass'];
                    $da = $_POST['date'];

                    $insert = mysqli_query($conn, "INSERT INTO user  VALUES (null, '$na', '$un', '$pa', '$da')");

                    if ($insert) {
                      echo "<h1 class='btn btn-success'>Inserted Success</h1>";
                    } else {
                      echo "Error: " . mysqli_error($conn);
                    }
                  }
                  ?>


              </div>
            </div>

            <div class="col-md-2"></div>

          </div>
        </div>
      </section>

    </div>

    <!-- Footer -->
    <?php include 'lip/footer.php'; ?>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark"></aside>

  </div>

  <?php include 'lip/script.php'; ?>
</body>

</html>