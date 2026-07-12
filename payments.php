<?php
session_start();
include 'lip/conn.php';
include 'check_permission.php';

if (!isset($_SESSION['empid'])) {
  header("Location: login.php");
  exit();
}

checkPermission($conn, $_SESSION['user_id'], 'payments.php');

/* ── INSERT ── */
if (isset($_POST['btnsave'])) {
  $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
  $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
  $total_amount = mysqli_real_escape_string($conn, $_POST['total_amount']);
  $paid_amount = mysqli_real_escape_string($conn, $_POST['paid_amount']);

  $insert = mysqli_query($conn, "
        INSERT INTO payments (order_id, payment_method, total_amount, paid_amount)
        VALUES ('$order_id','$payment_method','$total_amount','$paid_amount')
    ");

  if ($insert) {
    $msg = "Payment Added Successfully!";
    $msg_type = "success";
  } else {
    $msg = "Error: " . mysqli_error($conn);
    $msg_type = "danger";
  }
}

/* ── DELETE ── */
if (isset($_GET['idd'])) {
  $id = (int) $_GET['idd'];
  mysqli_query($conn, "DELETE FROM payments WHERE payment_id='$id'");
  $msg = "Deleted Successfully!";
  $msg_type = "warning";
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
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
    }

    .badge-partial {
      background: #f59e0b;
      color: #fff;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
    }

    .badge-pending {
      background: #ef4444;
      color: #fff;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
    }

    .balance-zero {
      color: #10b981;
      font-weight: 700;
    }

    .balance-partial {
      color: #f59e0b;
      font-weight: 700;
    }

    .balance-full {
      color: #ef4444;
      font-weight: 700;
    }

    .summary-card {
      border-radius: 10px;
      padding: 16px 20px;
      color: #fff;
      margin-bottom: 20px;
    }

    .summary-card h6 {
      font-size: 12px;
      opacity: .85;
      margin: 0;
    }

    .summary-card h3 {
      font-size: 22px;
      font-weight: 700;
      margin: 4px 0 0 0;
    }

    /* ✅ FIX: Table action buttons always visible */
    #example1 {
      width: 100% !important;
    }

    #example1 td {
      white-space: nowrap;
      vertical-align: middle;
    }

    #example1 th {
      white-space: nowrap;
    }

    .action-btns {
      display: flex;
      gap: 4px;
      flex-wrap: nowrap;
    }

    .action-btns .btn {
      padding: 3px 8px;
      font-size: 12px;
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
              <h1 class="m-0">Payments</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Payments</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <section class="content">
        <div class="container-fluid">

          <!-- Alert -->
          <?php if (isset($msg)): ?>
            <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong><?php echo $msg; ?></strong>
            </div>
          <?php endif; ?>

          <!-- Summary Cards -->
          <?php
          $sum_q = mysqli_query($conn, "
                SELECT
                    COALESCE(SUM(total_amount),0) AS total,
                    COALESCE(SUM(paid_amount),0)  AS paid,
                    COALESCE(SUM(balance),0)       AS remaining,
                    COUNT(*)                       AS cnt
                FROM payments
            ");
          $sum = mysqli_fetch_assoc($sum_q);
          ?>
          <div class="row">
            <div class="col-6 col-md-3">
              <div class="summary-card" style="background:linear-gradient(135deg,#3b82f6,#6366f1)">
                <h6><i class="fas fa-file-invoice-dollar mr-1"></i> Total Amount</h6>
                <h3>$<?php echo number_format($sum['total'], 2); ?></h3>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="summary-card" style="background:linear-gradient(135deg,#10b981,#059669)">
                <h6><i class="fas fa-check-circle mr-1"></i> Total Paid</h6>
                <h3>$<?php echo number_format($sum['paid'], 2); ?></h3>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="summary-card" style="background:linear-gradient(135deg,#f59e0b,#d97706)">
                <h6><i class="fas fa-clock mr-1"></i> Remaining</h6>
                <h3>$<?php echo number_format($sum['remaining'], 2); ?></h3>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="summary-card" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed)">
                <h6><i class="fas fa-list mr-1"></i> Total Records</h6>
                <h3><?php echo $sum['cnt']; ?></h3>
              </div>
            </div>
          </div>

          <div class="row">

            <!-- FORM -->
            <div class="col-lg-4 col-md-5">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-plus mr-1"></i> Payment Form
                  </h3>
                </div>
                <form method="POST">
                  <div class="card-body">

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
                          ?>
                          <option value="<?php echo $orow['order_id']; ?>">
                            Order #<?php echo $orow['order_id']; ?>
                            - <?php echo htmlspecialchars($orow['fullname']); ?>
                          </option>
                        <?php endwhile; ?>
                      </select>
                    </div>

                    <div class="form-group">
                      <label>Payment Method</label>
                      <select name="payment_method" class="form-control" required>
                        <option value="cash">💵 Cash</option>
                        <option value="card">💳 Card</option>
                        <option value="mobile">📱 Mobile</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label>Total Amount <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">$</span>
                        </div>
                        <input type="number" step="0.01" min="0" name="total_amount" id="total_amount"
                          class="form-control" placeholder="100.00" required oninput="calcBalance()">
                      </div>
                      <small class="text-muted">Lacagta oo dhan</small>
                    </div>

                    <div class="form-group">
                      <label>Paid Amount <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">$</span>
                        </div>
                        <input type="number" step="0.01" min="0" name="paid_amount" id="paid_amount"
                          class="form-control" placeholder="70.00" required oninput="calcBalance()">
                      </div>
                      <small class="text-muted">Lacagta la bixiyay</small>
                    </div>

                    <div class="form-group">
                      <label>Balance (Preview)</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">$</span>
                        </div>
                        <input type="text" id="balance_preview" class="form-control font-weight-bold" placeholder="0.00"
                          readonly style="background:#f8f9fa">
                      </div>
                      <small id="status_preview" class="font-weight-bold mt-1 d-block"></small>
                    </div>

                  </div>
                  <div class="card-footer">
                    <button type="submit" name="btnsave" class="btn btn-primary btn-block">
                      <i class="fas fa-save mr-1"></i> Save Payment
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <!-- TABLE -->
            <div class="col-lg-8 col-md-7">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-list mr-1"></i> Payments Record
                  </h3>
                </div>
                <div class="card-body p-0">
                  <!-- ✅ overflow-x scroll si action column u muuqdo -->
                  <div style="overflow-x:auto; width:100%">
                    <table id="example1" class="table table-bordered table-striped table-sm mb-0" style="width:100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>#</th>
                          <th>Order</th>
                          <th>Customer</th>
                          <th>Method</th>
                          <th>Total</th>
                          <th>Paid</th>
                          <th>Balance</th>
                          <th>Status</th>
                          <th>Date</th>
                          <th>Action</th><!-- ✅ always last -->
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $read = mysqli_query($conn, "
                                        SELECT p.*, o.order_id, c.fullname
                                        FROM payments p
                                        LEFT JOIN orders o    ON p.order_id    = o.order_id
                                        LEFT JOIN customers c ON o.customer_id = c.customer_id
                                        ORDER BY p.payment_id DESC
                                    ");
                        while ($row = mysqli_fetch_assoc($read)):
                          if ($row['balance'] == 0) {
                            $bal_class = 'balance-zero';
                          } elseif ($row['balance'] < $row['total_amount']) {
                            $bal_class = 'balance-partial';
                          } else {
                            $bal_class = 'balance-full';
                          }
                          $badge = 'badge-pending';
                          if ($row['status'] == 'paid')
                            $badge = 'badge-paid';
                          if ($row['status'] == 'partial')
                            $badge = 'badge-partial';
                          ?>
                          <tr>
                            <td><?php echo $row['payment_id']; ?></td>
                            <td>Order #<?php echo $row['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                            <td>
                              <?php
                              $icons = ['cash' => '💵', 'card' => '💳', 'mobile' => '📱'];
                              echo ($icons[$row['payment_method']] ?? '') . ' ' . ucfirst($row['payment_method']);
                              ?>
                            </td>
                            <td><strong>$<?php echo number_format($row['total_amount'], 2); ?></strong></td>
                            <td style="color:#10b981;font-weight:600">
                              $<?php echo number_format($row['paid_amount'], 2); ?>
                            </td>
                            <td class="<?php echo $bal_class; ?>">
                              $<?php echo number_format($row['balance'], 2); ?>
                            </td>
                            <td>
                              <span class="<?php echo $badge; ?>">
                                <?php echo ucfirst($row['status']); ?>
                              </span>
                            </td>
                            <td><?php echo date('Y-m-d', strtotime($row['payment_date'])); ?></td>
                            <!-- ✅ Action buttons - always visible -->
                            <td>
                              <div class="action-btns">
                                <a href="payments_edit.php?idd=<?php echo $row['payment_id']; ?>"
                                  class="btn btn-success btn-sm" title="Edit">
                                  <i class="fa fa-edit"></i>
                                </a>
                                <a href="payments.php?idd=<?php echo $row['payment_id']; ?>" class="btn btn-danger btn-sm"
                                  title="Delete" onclick="return confirm('Mahubtaa inaad tireysid?')">
                                  <i class="fa fa-trash"></i>
                                </a>
                                <a href="payments_report.php?idd=<?php echo $row['payment_id']; ?>"
                                  class="btn btn-info btn-sm" title="Print">
                                  <i class="fa fa-print"></i>
                                </a>
                              </div>
                            </td>
                          </tr>
                        <?php endwhile; ?>
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

  <script>
    // ── DataTable - scrollX ON si action u muuqdo ──
    $(function () {
      $("#example1").DataTable({
        "responsive": false,
        "scrollX": true,       // ✅ horizontal scroll
        "autoWidth": false,
        "columnDefs": [
          { "orderable": false, "targets": -1 }  // Action column sort disable
        ],
        "order": [[0, "desc"]],
        "language": {
          "search": "Raadi:",
          "lengthMenu": "Muuji _MENU_",
          "info": "_START_ ilaa _END_ (Wadarta: _TOTAL_)",
          "paginate": { "previous": "Hore", "next": "Xiga" }
        }
      });
    });

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
  </script>

</body>

</html>