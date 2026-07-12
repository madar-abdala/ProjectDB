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
    // Check ID
    if (!isset($_GET['idd']) || empty($_GET['idd'])) {
      die("Category ID not found");
    }

    $id = mysqli_real_escape_string($conn, $_GET['idd']);

    // Fetch category data
    $res = mysqli_query($conn, "SELECT * FROM menu_categories WHERE category_id='$id'");

    if (!$res || mysqli_num_rows($res) == 0) {
      die("Category not found");
    }

    $row = mysqli_fetch_array($res);
    ?>

    <div class="content-wrapper">

      <!-- Header -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">

            <div class="col-sm-6">
              <h1 class="m-0">Category Edit</h1>
            </div>

            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Edit Category</li>
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
                  <h3 class="card-title">Update Category</h3>
                </div>

                <form action="caregory_update.php?idd=<?php echo $id; ?>" method="post">

                  <div class="card-body">

                    <!-- Hidden ID -->
                    <input type="hidden" name="category_id" value="<?php echo $row['category_id']; ?>">

                    <!-- Category Name -->
                    <div class="form-group">
                      <label>Category Name</label>
                      <input type="text" class="form-control" name="category_name" placeholder="Enter Category Name"
                        required value="<?php echo $row['category_name']; ?>">
                    </div>

                  </div>

                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary" name="btnedit">
                      Update
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