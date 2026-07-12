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
    if (!isset($_GET['idd']) || empty($_GET['idd'])) {
      die("Khalad: ID lama helin.");
    }

    $id = mysqli_real_escape_string($conn, $_GET['idd']);
    $res = mysqli_query($conn, "SELECT * FROM customers WHERE customer_id='$id'");

    if (!$res || mysqli_num_rows($res) == 0) {
      die("Khalad: Customer lama helin.");
    }

    $row = mysqli_fetch_array($res);
    ?>

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
                <li class="breadcrumb-item active">Dashboard v1</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-5">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Customer Edit</h3>
                </div>

                <form action="costumer_update.php?idd=<?php echo $id; ?>" method="post">
                  <div class="card-body">

                    <input type="hidden" name="id" value="<?php echo $row['customer_id']; ?>">

                    <div class="form-group">
                      <label>Fullname</label>
                      <input type="text" class="form-control" name="fullname" placeholder="Enter Fullname" required
                        value="<?php echo $row['fullname']; ?>">
                    </div>

                    <div class="form-group">
                      <label>Phone</label>
                      <input type="text" class="form-control" name="phone" placeholder="Enter Phone"
                        value="<?php echo $row['phone']; ?>">
                    </div>

                    <div class="form-group">
                      <label>Email</label>
                      <input type="email" class="form-control" name="email" placeholder="Enter Email"
                        value="<?php echo $row['email']; ?>">
                    </div>

                  </div>
                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary" name="btnedit">Update</button>
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

</html>s