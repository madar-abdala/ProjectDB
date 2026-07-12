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
      die("Item ID Not Found");
    }

    $id = mysqli_real_escape_string($conn, $_GET['idd']);

    // FETCH DATA
    $res = mysqli_query(
      $conn,
      "SELECT * FROM menu_items WHERE item_id='$id'"
    );

    if (!$res || mysqli_num_rows($res) == 0) {
      die("Menu Item Not Found");
    }

    $row = mysqli_fetch_array($res);

    ?>

    <div class="content-wrapper">

      <!-- HEADER -->
      <div class="content-header">
        <div class="container-fluid">

          <div class="row mb-2">

            <div class="col-sm-6">
              <h1 class="m-0">Menu Item Edit</h1>
            </div>

            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                  <a href="#">Home</a>
                </li>

                <li class="breadcrumb-item active">
                  Edit Menu Item
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
                    Update Menu Item
                  </h3>
                </div>

                <!-- FORM -->
                <form action="item_update.php?idd=<?php echo $id; ?>" method="POST">

                  <div class="card-body">

                    <!-- HIDDEN ID -->
                    <input type="hidden" name="item_id" value="<?php echo $row['item_id']; ?>">

                    <!-- ITEM NAME -->
                    <div class="form-group">

                      <label>Item Name</label>

                      <input type="text" name="item_name" class="form-control" placeholder="Enter Item Name" required
                        value="<?php echo $row['item_name']; ?>">

                    </div>

                    <!-- DESCRIPTION -->
                    <div class="form-group">

                      <label>Description</label>

                      <textarea name="description" class="form-control"
                        placeholder="Enter Description"><?php echo $row['description']; ?></textarea>

                    </div>

                    <!-- PRICE -->
                    <div class="form-group">

                      <label>Price</label>

                      <input type="number" step="0.01" name="price" class="form-control" placeholder="Enter Price"
                        required value="<?php echo $row['price']; ?>">

                    </div>

                    <!-- STOCK -->
                    <div class="form-group">

                      <label>Stock Quantity</label>

                      <input type="number" name="stock_quantity" class="form-control" placeholder="Enter Quantity"
                        value="<?php echo $row['stock_quantity']; ?>">

                    </div>

                    <!-- CATEGORY -->
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

                          $selected = ($row['category_id']
                            == $crow['category_id'])
                            ? 'selected'
                            : '';

                          ?>

                          <option value="<?php echo $crow['category_id']; ?>" <?php echo $selected; ?>>

                            <?php echo $crow['category_name']; ?>

                          </option>

                        <?php } ?>

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