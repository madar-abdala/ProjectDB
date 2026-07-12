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
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Dashboard</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Category</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <section class="content">
        <div class="container-fluid">
          <div class="row">

            <!-- LEFT SIDE - FORM -->
            <div class="col-md-3">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Category Form</h3>
                </div>

                <!-- INSERT -->
                <?php
                if (isset($_POST['btnsave'])) {
                  $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);

                  $insert = mysqli_query($conn, "INSERT INTO menu_categories
                  (category_name)
                  VALUES
                  ('$category_name')
                ");

                  if ($insert) {
                    echo "<div class='alert alert-success m-2'>Category Added Successfully</div>";
                  } else {
                    echo "<div class='alert alert-danger m-2'>Error: " . mysqli_error($conn) . "</div>";
                  }
                }
                ?>

                <!-- DELETE -->
                <?php
                if (isset($_GET['idd'])) {
                  $id = mysqli_real_escape_string($conn, $_GET['idd']);
                  $delete = mysqli_query($conn, "DELETE FROM menu_categories WHERE category_id='$id'");
                  if ($delete) {
                    echo "<div class='alert alert-danger m-2'>Deleted Successfully</div>";
                  }
                }
                ?>

                <!-- FORM -->
                <form action="" method="POST">
                  <div class="card-body">

                    <div class="form-group">
                      <label>Category Name</label>
                      <input type="text" name="category_name" class="form-control" placeholder="Enter Category Name"
                        required>
                    </div>

                  </div>
                  <div class="card-footer">
                    <button type="submit" name="btnsave" class="btn btn-primary">INSERT</button>
                  </div>
                </form>

              </div>
            </div>

            <!-- RIGHT SIDE - TABLE -->
            <div class="col-md-9">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Category Record</h3>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped table-sm">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Category Name</th>
                          <th width="140">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $read = mysqli_query($conn, "SELECT * FROM menu_categories");
                        while ($row = mysqli_fetch_array($read)) {
                          ?>
                          <tr>
                            <td><?php echo $row['category_id']; ?></td>
                            <td><?php echo $row['category_name']; ?></td>
                            <td>
                              <!-- EDIT -->
                              <a href="category_edit.php?idd=<?php echo $row['category_id']; ?>"
                                class="btn btn-success btn-sm">
                                <i class="fa fa-edit"></i>
                              </a>
                              <!-- DELETE -->
                              <a href="category.php?idd=<?php echo $row['category_id']; ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Mahubtaa inaad tireysid xogtaan?')">
                                <i class="fa fa-trash"></i>
                              </a>
                              <!-- PRINT -->
                              <a href="category_report.php?idd=<?php echo $row['category_id']; ?>"
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