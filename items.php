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
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Menu Items</li>
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
                  <h3 class="card-title">Menu Item Form</h3>
                </div>

                <!-- INSERT -->
                <?php

                if (isset($_POST['btnsave'])) {

                  $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
                  $description = mysqli_real_escape_string($conn, $_POST['description']);
                  $price = mysqli_real_escape_string($conn, $_POST['price']);
                  $stock_quantity = mysqli_real_escape_string($conn, $_POST['stock_quantity']);
                  $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);

                  $insert = mysqli_query($conn, "INSERT INTO menu_items
                (
                  item_name,
                  description,
                  price,
                  stock_quantity,
                  category_id
                )
                VALUES
                (
                  '$item_name',
                  '$description',
                  '$price',
                  '$stock_quantity',
                  '$category_id'
                )");

                  if ($insert) {
                    echo "<div class='alert alert-success m-2'>
                          Menu Item Added Successfully
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

                  $id = mysqli_real_escape_string($conn, $_GET['idd']);

                  $delete = mysqli_query(
                    $conn,
                    "DELETE FROM menu_items WHERE item_id='$id'"
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

                    <div class="form-group">
                      <label>Item Name</label>
                      <input type="text" name="item_name" class="form-control" placeholder="Enter Item Name" required>
                    </div>

                    <div class="form-group">
                      <label>Description</label>
                      <textarea name="description" class="form-control" placeholder="Enter Description"></textarea>
                    </div>

                    <div class="form-group">
                      <label>Price</label>
                      <input type="number" step="0.01" name="price" class="form-control" placeholder="Enter Price"
                        required>
                    </div>

                    <div class="form-group">
                      <label>Stock Quantity</label>
                      <input type="number" name="stock_quantity" class="form-control" placeholder="Enter Quantity">
                    </div>

                    <!-- FK CATEGORY -->
                    <div class="form-group">
                      <label>Category</label>

                      <select name="category_id" class="form-control" required>

                        <option value="">
                          Select Category
                        </option>

                        <?php

                        $cat = mysqli_query(
                          $conn,
                          "SELECT * FROM menu_categories"
                        );

                        while ($crow = mysqli_fetch_array($cat)) {
                          ?>

                          <option value="<?php echo $crow['category_id']; ?>">
                            <?php echo $crow['category_name']; ?>
                          </option>

                        <?php } ?>

                      </select>

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
                  <h3 class="card-title">Menu Items Record</h3>
                </div>

                <div class="card-body">

                  <div class="table-responsive">

                    <table id="example1" class="table table-bordered table-striped table-sm">

                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Item Name</th>
                          <th>Description</th>
                          <th>Price</th>
                          <th>Stock</th>
                          <th>Category</th>
                          <th width="140">Action</th>
                        </tr>
                      </thead>

                      <tbody>

                        <?php

                        // JOIN si magaca category-ga loo soo saaro
                        $read = mysqli_query(
                          $conn,
                          "SELECT
                          menu_items.*,
                          menu_categories.category_name
                        FROM menu_items
                        LEFT JOIN menu_categories
                        ON menu_items.category_id = menu_categories.category_id
                        "
                        );

                        while ($row = mysqli_fetch_array($read)) {
                          ?>

                          <tr>

                            <td><?php echo $row['item_id']; ?></td>

                            <td><?php echo $row['item_name']; ?></td>

                            <td><?php echo $row['description']; ?></td>

                            <td><?php echo $row['price']; ?></td>

                            <td><?php echo $row['stock_quantity']; ?></td>

                            <!-- FK NAME -->
                            <td><?php echo $row['category_name']; ?></td>

                            <td>

                              <!-- EDIT -->
                              <a href="menu_items_edit.php?idd=<?php echo $row['item_id']; ?>"
                                class="btn btn-success btn-sm">
                                <i class="fa fa-edit"></i>
                              </a>

                              <!-- DELETE -->
                              <a href="items.php?idd=<?php echo $row['item_id']; ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Mahubtaa inaad tireysid xogtaan?')">

                                <i class="fa fa-trash"></i>
                              </a>

                              <!-- REPORT -->
                              <a href="item_report.php?idd=<?php echo $row['item_id']; ?>" class="btn btn-info btn-sm">

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