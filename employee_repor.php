```php
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Report</title>

  <?php
  include 'lip/conn.php';

  $id = isset($_GET['idd']) ? (int) $_GET['idd'] : 0;

  if ($id == 0) {
    die("Employee ID Not Found");
  }

  $read = mysqli_query($conn, "
        SELECT * FROM employee
        WHERE empid='$id'
    ");

  if (!$read || mysqli_num_rows($read) == 0) {
    die("Employee Not Found");
  }

  $row = mysqli_fetch_assoc($read);
  ?>

  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <style>
    body {
      background: #e2e8f0;
      font-family: 'Segoe UI', sans-serif;
      padding: 30px 0;
    }

    .report-wrap {
      max-width: 800px;
      margin: auto;
      background: #fff;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 4px 24px rgba(0, 0, 0, .1);
    }

    .report-header {
      background: linear-gradient(135deg, #3b82f6, #6366f1);
      color: #fff;
      padding: 35px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .report-header h2 {
      margin: 0;
      font-weight: 800;
    }

    .report-header p {
      margin: 5px 0 0;
      opacity: .85;
    }

    .employee-avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: #fff;
      color: #3b82f6;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 32px;
      font-weight: bold;
    }

    .report-body {
      padding: 35px;
    }

    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }

    .info-box h6 {
      font-size: 12px;
      color: #94a3b8;
      text-transform: uppercase;
      margin-bottom: 5px;
      font-weight: 700;
    }

    .info-box p {
      font-size: 15px;
      color: #1e293b;
      font-weight: 600;
      margin: 0;
    }

    .divider {
      border: none;
      border-top: 1px dashed #cbd5e1;
      margin: 25px 0;
    }

    .salary-card {
      background: #f8fafc;
      border-radius: 12px;
      padding: 20px;
      text-align: center;
    }

    .salary-card h6 {
      color: #64748b;
      margin-bottom: 8px;
    }

    .salary-card h2 {
      color: #10b981;
      font-weight: 800;
      margin: 0;
    }

    .job-badge {
      display: inline-block;
      padding: 8px 15px;
      border-radius: 30px;
      font-size: 13px;
      font-weight: 700;
      background: #dbeafe;
      color: #1d4ed8;
    }

    .footer {
      background: #f8fafc;
      padding: 20px 35px;
      border-top: 1px solid #e2e8f0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .btn-print {
      background: linear-gradient(135deg, #3b82f6, #6366f1);
      color: #fff;
      border: none;
      padding: 10px 25px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 700;
    }

    .btn-back {
      background: #e2e8f0;
      color: #374151;
      padding: 10px 20px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
    }

    @media print {
      body {
        background: #fff;
        padding: 0;
      }

      .report-wrap {
        box-shadow: none;
      }

      .no-print {
        display: none !important;
      }
    }
  </style>
</head>

<body>

  <div class="report-wrap">

    <!-- Header -->
    <div class="report-header">

      <div>
        <h2>
          <i class="fas fa-user-tie mr-2"></i>
          Employee Report
        </h2>
        <p>Official Employee Information</p>
      </div>

      <div class="employee-avatar">
        <?php echo strtoupper(substr($row['name'], 0, 1)); ?>
      </div>

    </div>

    <!-- Body -->
    <div class="report-body">

      <div class="info-grid">

        <div class="info-box">
          <h6>Employee ID</h6>
          <p>#<?php echo $row['empid']; ?></p>
        </div>

        <div class="info-box">
          <h6>Full Name</h6>
          <p><?php echo htmlspecialchars($row['name']); ?></p>
        </div>

        <div class="info-box">
          <h6>Phone</h6>
          <p><?php echo htmlspecialchars($row['tell']); ?></p>
        </div>

        <div class="info-box">
          <h6>Email</h6>
          <p><?php echo htmlspecialchars($row['email']); ?></p>
        </div>

        <div class="info-box">
          <h6>Address</h6>
          <p><?php echo htmlspecialchars($row['address']); ?></p>
        </div>

        <div class="info-box">
          <h6>Employment Date</h6>
          <p><?php echo date('d M Y', strtotime($row['date'])); ?></p>
        </div>

      </div>

      <hr class="divider">

      <div style="text-align:center;">
        <h6 style="color:#64748b;margin-bottom:10px;">Job Position</h6>

        <span class="job-badge">
          <?php
          $icons = [
            'Manager' => '👔',
            'Cashier' => '💵',
            'Marketer' => '📢',
            'Security' => '🛡️'
          ];

          echo ($icons[$row['jobtitle']] ?? '👨‍💼') . ' ' . $row['jobtitle'];
          ?>
        </span>
      </div>

      <hr class="divider">

      <div class="salary-card">
        <h6>Monthly Salary</h6>
        <h2>$<?php echo number_format($row['salary'], 2); ?></h2>
      </div>

    </div>

    <!-- Footer -->
    <div class="footer">

      <small>
        Generated:
        <?php echo date('d M Y H:i'); ?>
      </small>

      <div class="no-print">
        <a href="employee.php" class="btn-back">
          <i class="fas fa-arrow-left"></i>
          Back
        </a>

        <button onclick="window.print()" class="btn-print">
          <i class="fas fa-print"></i>
          Print
        </button>
      </div>

    </div>

  </div>

</body>

</html>
```