<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Report</title>
  <?php
  include 'lip/conn.php';

  $id = isset($_GET['idd']) ? (int) $_GET['idd'] : 0;

  if ($id == 0) {
    die("Payment ID Not Found");
  }

  // Fetch payment + order + customer
  $read = mysqli_query($conn, "
        SELECT
            p.*,
            o.order_id,
            c.fullname,
            c.email     AS customer_email,
            c.phone     AS customer_phone
        FROM payments p
        LEFT JOIN orders    o ON p.order_id    = o.order_id
        LEFT JOIN customers c ON o.customer_id = c.customer_id
        WHERE p.payment_id = '$id'
    ");

  if (!$read || mysqli_num_rows($read) == 0) {
    die("Payment Not Found");
  }

  $row = mysqli_fetch_assoc($read);
  ?>

  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      background: #e2e8f0;
      font-family: 'Segoe UI', sans-serif;
      padding: 30px 0;
    }

    .receipt-wrap {
      max-width: 720px;
      margin: 0 auto;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 24px rgba(0, 0, 0, 0.10);
      overflow: hidden;
    }

    /* ── Header ── */
    .receipt-header {
      background: linear-gradient(135deg, #3b82f6, #6366f1);
      color: #fff;
      padding: 36px 40px 28px 40px;
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
    }

    .receipt-header .brand h2 {
      font-size: 28px;
      font-weight: 800;
      margin-bottom: 4px;
    }

    .receipt-header .brand p {
      font-size: 13px;
      opacity: 0.85;
    }

    .receipt-header .receipt-no {
      text-align: right;
    }

    .receipt-header .receipt-no h3 {
      font-size: 20px;
      font-weight: 700;
    }

    .receipt-header .receipt-no p {
      font-size: 12px;
      opacity: 0.85;
      margin-top: 4px;
    }

    /* ── Status Bar ── */
    .status-bar {
      padding: 14px 40px;
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 14px;
      font-weight: 600;
    }

    .status-bar.paid {
      background: #d1fae5;
      color: #065f46;
      border-left: 5px solid #10b981;
    }

    .status-bar.partial {
      background: #fef3c7;
      color: #92400e;
      border-left: 5px solid #f59e0b;
    }

    .status-bar.pending {
      background: #fee2e2;
      color: #991b1b;
      border-left: 5px solid #ef4444;
    }

    /* ── Body ── */
    .receipt-body {
      padding: 30px 40px;
    }

    /* Info grid */
    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 28px;
    }

    .info-block h6 {
      font-size: 11px;
      text-transform: uppercase;
      color: #94a3b8;
      font-weight: 700;
      letter-spacing: 0.05em;
      margin-bottom: 4px;
    }

    .info-block p {
      font-size: 15px;
      color: #1e293b;
      font-weight: 600;
    }

    /* Divider */
    .divider {
      border: none;
      border-top: 1.5px dashed #e2e8f0;
      margin: 24px 0;
    }

    /* Amount table */
    .amount-table {
      width: 100%;
      border-collapse: collapse;
    }

    .amount-table tr td {
      padding: 10px 0;
      font-size: 15px;
      color: #374151;
      border-bottom: 1px solid #f1f5f9;
    }

    .amount-table tr:last-child td {
      border-bottom: none;
    }

    .amount-table td:last-child {
      text-align: right;
      font-weight: 600;
    }

    .amount-table .total-row td {
      font-size: 16px;
      font-weight: 800;
      color: #1e293b;
      padding-top: 14px;
    }

    .amount-table .balance-row td {
      color: #f59e0b;
    }

    .amount-table .paid-row td {
      color: #10b981;
    }

    /* ── Footer ── */
    .receipt-footer {
      background: #f8fafc;
      border-top: 1px solid #e2e8f0;
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .receipt-footer small {
      color: #94a3b8;
      font-size: 12px;
    }

    /* Buttons */
    .btn-print {
      background: linear-gradient(135deg, #3b82f6, #6366f1);
      color: #fff;
      border: none;
      padding: 10px 28px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-back {
      background: #e2e8f0;
      color: #374151;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .btn-back:hover {
      background: #cbd5e1;
      color: #374151;
      text-decoration: none;
    }

    /* Print */
    @media print {
      body {
        background: #fff;
        padding: 0;
      }

      .receipt-wrap {
        box-shadow: none;
        border-radius: 0;
      }

      .no-print {
        display: none !important;
      }

      .receipt-header {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }

      .status-bar {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
    }
  </style>
</head>

<body>
  <div class="receipt-wrap">

    <!-- Header -->
    <div class="receipt-header">
      <div class="brand">
        <h2><i class="fas fa-file-invoice-dollar mr-2"></i>Payment Receipt</h2>
        <p>Official Payment Document</p>
      </div>
      <div class="receipt-no">
        <h3>Receipt #
          <?php echo str_pad($row['payment_id'], 5, '0', STR_PAD_LEFT); ?>
        </h3>
        <p>
          <?php echo date('d M Y, H:i', strtotime($row['payment_date'])); ?>
        </p>
      </div>
    </div>

    <!-- Status Bar -->
    <?php
    $status = $row['status'];
    $status_icons = [
      'paid' => '✅ Lacagta Oo Dhammi La Bixiyay',
      'partial' => '⚠️ Qayb Ka Mid Ah Lacagta Ayaa La Bixiyay',
      'pending' => '🔴 Lacag Wali La Bixin'
    ];
    $status_text = $status_icons[$status] ?? ucfirst($status);
    ?>
    <div class="status-bar <?php echo $status; ?>">
      <?php echo $status_text; ?>
    </div>

    <!-- Body -->
    <div class="receipt-body">

      <!-- Info Grid -->
      <div class="info-grid">
        <div class="info-block">
          <h6>Customer Name</h6>
          <p>
            <?php echo htmlspecialchars($row['fullname']); ?>
          </p>
        </div>
        <div class="info-block">
          <h6>Order</h6>
          <p>Order #
            <?php echo $row['order_id']; ?>
          </p>
        </div>
        <div class="info-block">
          <h6>Payment Method</h6>
          <p>
            <?php
            $icons = ['cash' => '💵', 'card' => '💳', 'mobile' => '📱'];
            echo ($icons[$row['payment_method']] ?? '') . ' ' . ucfirst($row['payment_method']);
            ?>
          </p>
        </div>
        <div class="info-block">
          <h6>Payment Date</h6>
          <p>
            <?php echo date('d M Y, H:i', strtotime($row['payment_date'])); ?>
          </p>
        </div>
        <?php if (!empty($row['customer_email'])): ?>
          <div class="info-block">
            <h6>Customer Email</h6>
            <p>
              <?php echo htmlspecialchars($row['customer_email']); ?>
            </p>
          </div>
        <?php endif; ?>
        <?php if (!empty($row['customer_phone'])): ?>
          <div class="info-block">
            <h6>Customer Phone</h6>
            <p>
              <?php echo htmlspecialchars($row['customer_phone']); ?>
            </p>
          </div>
        <?php endif; ?>
      </div>

      <hr class="divider">

      <!-- Amount Breakdown -->
      <table class="amount-table">
        <tr>
          <td>Total Amount (Lacagta Oo Dhan)</td>
          <td><strong>$
              <?php echo number_format($row['total_amount'], 2); ?>
            </strong></td>
        </tr>
        <tr class="paid-row">
          <td>Paid Amount (Lacagta La Bixiyay)</td>
          <td>- $
            <?php echo number_format($row['paid_amount'], 2); ?>
          </td>
        </tr>
        <hr class="divider" style="margin:8px 0">
        <tr class="total-row balance-row">
          <td>Remaining Balance (Lacagta Ku Hartay)</td>
          <td>$
            <?php echo number_format($row['balance'], 2); ?>
          </td>
        </tr>
      </table>

      <hr class="divider">

      <!-- Thank you note -->
      <div style="text-align:center; padding:10px 0 0 0">
        <p style="color:#94a3b8; font-size:13px;">
          <?php if ($status == 'paid'): ?>
            ✅ Mahadsanid! Lacagta oo dhammi waa la bixiyay.
          <?php elseif ($status == 'partial'): ?>
            ⚠️ Fadlan soo bixi lacagta ku hartay:
            <strong style="color:#f59e0b">$
              <?php echo number_format($row['balance'], 2); ?>
            </strong>
          <?php else: ?>
            🔴 Fadlan lacagta bixi si loo dhameeyo.
          <?php endif; ?>
        </p>
      </div>

    </div>

    <!-- Footer -->
    <div class="receipt-footer">
      <small>
        Generated:
        <?php echo date('d M Y H:i'); ?> |
        Receipt #
        <?php echo str_pad($row['payment_id'], 5, '0', STR_PAD_LEFT); ?>
      </small>
      <div class="no-print" style="display:flex; gap:10px;">
        <a href="payments.php" class="btn-back">
          <i class="fas fa-arrow-left"></i> Back
        </a>
        <button class="btn-print" onclick="window.print()">
          <i class="fas fa-print"></i> Print
        </button>
      </div>
    </div>

  </div>
</body>

</html>