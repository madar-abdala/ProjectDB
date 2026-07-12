<?php
session_start();
include 'lip/conn.php';
include 'check_permission.php';

if (!isset($_SESSION['empid'])) {
  header("Location: login.php");
  exit();
}

checkPermission($conn, $_SESSION['user_id'], 'payments.php');

// CHECK ID
if (!isset($_GET['idd']) || empty($_GET['idd'])) {
  header("Location: payments.php");
  exit();
}

$id = (int) $_GET['idd'];

// FETCH DATA
$res = mysqli_query($conn, "SELECT * FROM payments WHERE payment_id='$id'");

if (!$res || mysqli_num_rows($res) == 0) {
  die("Payment Not Found");
}

$row = mysqli_fetch_assoc($res);

/* ── UPDATE ── */
if (isset($_POST['btnedit'])) {
  $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
  $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
  $total_amount = mysqli_real_escape_string($conn, $_POST['total_amount']);
  $paid_amount = mysqli_real_escape_string($conn, $_POST['paid_amount']);

  // Trigger ayaa balance iyo status auto u xisaabinayaa
  $update = mysqli_query($conn, "
        UPDATE payments SET
            order_id       = '$order_id',
            payment_method = '$payment_method',
            total_amount   = '$total_amount',
            paid_amount    = '$paid_amount'
        WHERE payment_id   = '$id'
    ");

  if ($update) {
    header("Location: payments.php?msg=updated");
    exit();
  } else {
    $error = "Update failed: " . mysqli_error($conn);
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'lip/head.php'; ?>
  <style>
    .badge-paid {
      background: #10b981;
      color: #fff;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }

    .badge-partial {
      background: #f59e0b;
      color: #fff;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }

    .badge-pending {
      background: #ef4444;
      color: #fff;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }

    .info-box-custom {
      background: #f8fafc;
      border-radius: 10px;
      padding: 16px 20px;
      border-left: 4px solid #3b82f6;
      margin-bottom: 20px;
    }

    .info-box-custom h6 {
      color: #64748b;
      font-size: 12px;
      margin: 0 0 4px 0;
      text-transform: uppercase;
    }

    .info-box-custom h4 {
      color: #1e293b;
      font-size: 20px;
      font-weight: 700;
      margin: 0;
    }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <?php include 'lip/nav.php'; ?>
    <?php include 'lip/sidebar.php'; ?>

    <div class="content-wrapper">

      <!-- Header -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Edit Payment</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="payments.php">Payments</a></li>
                <li class="breadcrumb-item active">Edit #<?php echo $id; ?></li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <section class="content">
        <div class="container-fluid">

          <!-- Error -->
          <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <?php echo $error; ?>
            </div>
          <?php endif; ?>

          <div class="row">

            <!-- LEFT: FORM -->
            <div class="col-md-6">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-edit mr-1"></i>
                    Update Payment #<?php echo $id; ?>
                  </h3>
                </div>

                <form method="POST">
                  <div class="card-body">

                    <input type="hidden" name="payment_id" value="<?php echo $row['payment_id']; ?>">

                    <!-- ORDER -->
                    <div class="form-group">
                      <label>Order</label>
                      <select name="order_id" class="form-control" required>
                        <option value="">-- Select Order --</option>
                        <?php
                        $orders = mysqli_query($conn, "
                                        SELECT o.order_id, c.fullname
                                        FROM orders o
                                        LEFT JOIN customers c ON o.customer_id = c.customer_id
                                    ");
                        while ($orow = mysqli_fetch_assoc($orders)):
                          $sel = ($row['order_id'] == $orow['order_id']) ? 'selected' : '';
                          ?>
                          <option value="<?php echo $orow['order_id']; ?>" <?php echo $sel; ?>>
                            Order #<?php echo $orow['order_id']; ?>
                            - <?php echo htmlspecialchars($orow['fullname']); ?>
                          </option>
                        <?php endwhile; ?>
                      </select>
                    </div>

                    <!-- PAYMENT METHOD -->
                    <div class="form-group">
                      <label>Payment Method</label>
                      <select name="payment_method" class="form-control" required>
                        <option value="cash" <?php echo ($row['payment_method'] == 'cash') ? 'selected' : ''; ?>>💵 Cash
                        </option>
                        <option value="card" <?php echo ($row['payment_method'] == 'card') ? 'selected' : ''; ?>>💳 Card
                        </option>
                        <option value="mobile" <?php echo ($row['payment_method'] == 'mobile') ? 'selected' : ''; ?>>📱
                          Mobile</option>
                      </select>
                    </div>

                    <!-- TOTAL AMOUNT -->
                    <div class="form-group">
                      <label>Total Amount <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">$</span>
                        </div>
                        <input type="number" step="0.01" min="0" name="total_amount" id="total_amount"
                          class="form-control" value="<?php echo $row['total_amount']; ?>" required
                          oninput="calcBalance()">
                      </div>
                      <small class="text-muted">Lacagta oo dhan</small>
                    </div>

                    <!-- PAID AMOUNT -->
                    <div class="form-group">
                      <label>Paid Amount <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">$</span>
                        </div>
                        <input type="number" step="0.01" min="0" name="paid_amount" id="paid_amount"
                          class="form-control" value="<?php echo $row['paid_amount']; ?>" required
                          oninput="calcBalance()">
                      </div>
                      <small class="text-muted">Lacagta la bixiyay</small>
                    </div>

                    <!-- BALANCE PREVIEW -->
                    <div class="form-group">
                      <label>Balance (Preview)</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">$</span>
                        </div>
                        <input type="text" id="balance_preview" class="form-control font-weight-bold" readonly
                          style="background:#f8f9fa" value="<?php echo $row['balance']; ?>">
                      </div>
                      <small id="status_preview" class="font-weight-bold mt-1 d-block"></small>
                    </div>

                  </div>
                  <div class="card-footer d-flex justify-content-between">
                    <a href="payments.php" class="btn btn-secondary">
                      <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                    <button type="submit" name="btnedit" class="btn btn-primary">
                      <i class="fas fa-save mr-1"></i> Update Payment
                    </button>
                  </div>
                </form>

              </div>
            </div>

            <!-- RIGHT: CURRENT INFO -->


          </div>
        </div>
      </section>

    </div>

    <?php include 'lip/footer.php'; ?>
    <aside class="control-sidebar control-sidebar-dark"></aside>
  </div>

  <?php include 'lip/script.php'; ?>

  <script>
    // ── Live Balance Preview ──
    function calcBalance() {
      const total = parseFloat(document.getElementById('total_amount').value) || 0;
      const paid = parseFloat(document.getElementById('paid_amount').value) || 0;
      const balance = total - paid;

      const preview = document.getElementById('balance_preview');
      const status = document.getElementById('status_preview');

      preview.value = balance >= 0 ? balance.toFixed(2) : '0.00';

      if (paid <= 0) {
        preview.style.color = '#ef4444';
        status.innerHTML = '<span style="color:#ef4444">🔴 Pending — Lacag la bixin</span>';
      } else if (paid >= total) {
        preview.style.color = '#10b981';
        status.innerHTML = '<span style="color:#10b981">🟢 Paid — Lacag la dhammeeyay!</span>';
      } else {
        preview.style.color = '#f59e0b';
        status.innerHTML = '<span style="color:#f59e0b">🟡 Partial — $' + balance.toFixed(2) + ' ayaa ku hartay</span>';
      }
    }

    // Init on load
    window.onload = calcBalance;
  </script>

</body>

</html>