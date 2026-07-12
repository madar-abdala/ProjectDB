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
  <?php include 'lip/head.php';

  if (isset($_GET['idd'])) {
    $id = $_GET['idd'];

    $read = mysqli_query($conn, "SELECT * FROM  user");
    $data = mysqli_fetch_array($read);
  }
  ?>

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
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th>user_id</th>
                  <th>name</th>
                  <th>username</th>
                  <th>password</th>
                  <th>Data</th>
                  <th>Action</th>

                </tr>
              </thead>
              <tbody>


                <?php
                if (isset($_POST['btnedit'])) {

                  $id = $_POST['id'];
                  $username = $_POST['usname'];
                  $password = $_POST['pass'];
                  $date = $_POST['date'];

                  $update = mysqli_query($conn, "UPDATE user SET 
        username='$username',
        password='$password',
        date='$date'
        WHERE user_id='$id'
    ");

                  if ($update) {
                    echo "<h1 class='btn btn-success'>Updated Success</h1>";
                  } else {
                    echo "Error: " . mysqli_error($conn);
                  }
                }
                ?>


                <?php
                if (isset($_GET['idd'])) {
                  $id = $_GET['idd'];

                  $delete = mysqli_query($conn, "DELETE FROM user WHERE user_id='$id'");

                  echo "<h1 class='btn btn-danger'>Deleted Success</h1>";
                }
                ?>

                <?php
                $read = mysqli_query($conn, "SELECT 
    us.user_id,
    em.name,
    us.username,
    us.password,
   us.date
FROM user us
JOIN employee em 
    ON us.empid = em.empid;");
                while ($row = mysqli_fetch_array($read)) {
                  ?>
                  <tr>
                    <td><?php echo $row[0]; ?></td>
                    <td><?php echo $row[1]; ?></td>
                    <td><?php echo $row[2]; ?></td>
                    <td><?php echo $row[3]; ?></td>
                    <td><?php echo $row[4]; ?></td>
                    <td>
                      <a href="user_edit.php?idd=<?php echo $row[0]; ?>" class='btn btn-success fa fa-edit'>Edit</a>
                      <a href="user_view.php?idd=<?php echo $row[0]; ?>" class="btn btn-danger"
                        onclick="return confirm('Mahubtaa inaad tireysid xogtaan')">
                        <i class="fa fa-trash"></i> Del
                      </a>

                      <a href="user_report.php?idd=<?php echo $row[0]; ?>" class="btn btn-info">
                        <i class="fa fa-print"></i> Print
                      </a>
                    </td>
                  </tr>

                  <?php
                }
                ?>
              </tbody>
              <tfoot>

              </tfoot>
            </table>
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