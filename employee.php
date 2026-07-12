<?php
session_start();
include 'lip/conn.php';
include 'check_permission.php';

if (!isset($_SESSION['empid'])) {
  header("Location: login.php");
  exit();
}

checkPermission($conn, $_SESSION['user_id'], 'employee.php');

$msg = "";
$msg_type = "";

/* ── INSERT ── */
if (isset($_POST['btnsave'])) {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $tell = mysqli_real_escape_string($conn, $_POST['tell']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $address = mysqli_real_escape_string($conn, $_POST['address']);
  $jobtitle = mysqli_real_escape_string($conn, $_POST['jobtitle']);
  $salary = mysqli_real_escape_string($conn, $_POST['salary']);
  $date = mysqli_real_escape_string($conn, $_POST['date']);

  $insert = mysqli_query($conn, "
        INSERT INTO employee (name, tell, email, address, jobtitle, salary, date)
        VALUES ('$name','$tell','$email','$address','$jobtitle','$salary','$date')
    ");

  if ($insert) {
    $msg = "✅ Employee Registered Successfully!";
    $msg_type = "success";
  } else {
    $msg = "❌ Error: " . mysqli_error($conn);
    $msg_type = "danger";
  }
}

/* ── DELETE ── */
if (isset($_GET['idd'])) {
  $id = (int) $_GET['idd'];
  $delete = mysqli_query($conn, "DELETE FROM employee WHERE empid='$id'");
  if ($delete) {
    $msg = "🗑️ Employee Deleted Successfully!";
    $msg_type = "warning";
  }
}

/* ── SUMMARY ── */
$total_emp = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM employee"))['cnt'];
$total_sal = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(salary),0) AS total FROM employee"))['total'];

/* ── JOB COUNTS ── */
$jobs = ['Manager', 'Cashier', 'Marketer', 'Security'];
$colors = ['#8b5cf6', '#f59e0b', '#ef4444', '#06b6d4'];
$icons = ['fas fa-user-tie', 'fas fa-cash-register', 'fas fa-bullhorn', 'fas fa-shield-alt'];
$job_counts = [];
foreach ($jobs as $job) {
  $job_counts[$job] = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS c FROM employee WHERE jobtitle='$job'")
  )['c'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'lip/head.php'; ?>
  <style>
    /* Summary Cards */
    .emp-card {
      border-radius: 12px;
      padding: 18px 22px;
      color: #fff;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .emp-card .icon {
      font-size: 36px;
      opacity: 0.85;
    }

    .emp-card h6 {
      font-size: 12px;
      opacity: .85;
      margin: 0;
    }

    .emp-card h3 {
      font-size: 24px;
      font-weight: 800;
      margin: 3px 0 0 0;
    }

    /* Job badge */
    .job-badge {
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 700;
      display: inline-block;
    }

    .job-Manager {
      background: #dbeafe;
      color: #1d4ed8;
    }

    .job-Cashier {
      background: #d1fae5;
      color: #065f46;
    }

    .job-Marketer {
      background: #fef3c7;
      color: #92400e;
    }

    .job-Security {
      background: #fee2e2;
      color: #991b1b;
    }

    .job-default {
      background: #f1f5f9;
      color: #475569;
    }

    /* Table */
    #example1 td,
    #example1 th {
      vertical-align: middle !important;
      white-space: nowrap !important;
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

    /* Form labels */
    .form-group label {
      font-weight: 600;
      font-size: 13px;
      color: #374151;
    }

    /* Avatar circle */
    .emp-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: linear-gradient(135deg, #3b82f6, #6366f1);
      color: #fff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 13px;
      font-weight: 700;
      margin-right: 6px;
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
              <h1 class="m-0">
                <i class="fas fa-users mr-2" style="color:#3b82f6"></i>
                Employees
              </h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Employees</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <section class="content">
        <div class="container-fluid">

          <!-- Alert -->
          <?php if ($msg): ?>
            <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong><?php echo $msg; ?></strong>
            </div>
          <?php endif; ?>

          <!-- ── Summary Cards ── -->
          <div class="row">

            <!-- Total Employees -->
            <div class="col-6 col-md-2">
              <div class="emp-card" style="background:linear-gradient(135deg,#3b82f6,#6366f1)">
                <div class="icon"><i class="fas fa-users"></i></div>
                <div>
                  <h6>Total Emp.</h6>
                  <h3><?php echo $total_emp; ?></h3>
                </div>
              </div>
            </div>

            <!-- Total Salary -->
            <div class="col-6 col-md-2">
              <div class="emp-card" style="background:linear-gradient(135deg,#10b981,#059669)">
                <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
                <div>
                  <h6>Total Salary</h6>
                  <h3>$<?php echo number_format($total_sal, 0); ?></h3>
                </div>
              </div>
            </div>

            <!-- Per-Job Cards -->
            <?php foreach ($jobs as $i => $job): ?>
              <div class="col-6 col-md-2">
                <div class="emp-card"
                  style="background:linear-gradient(135deg,<?php echo $colors[$i]; ?>,<?php echo $colors[$i]; ?>cc)">
                  <div class="icon"><i class="<?php echo $icons[$i]; ?>"></i></div>
                  <div>
                    <h6><?php echo $job; ?></h6>
                    <h3><?php echo $job_counts[$job]; ?></h3>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>

          </div>
          <!-- ── End Summary Cards ── -->

          <div class="row">

            <!-- LEFT: FORM -->
            <div class="col-lg-4 col-md-5">
              <div class="card card-primary card-outline">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-user-plus mr-1"></i> Employee Form
                  </h3>
                </div>

                <form method="POST">
                  <div class="card-body">

                    <!-- NAME -->
                    <div class="form-group">
                      <label><i class="fas fa-user mr-1 text-primary"></i> Full Name</label>
                      <input type="text" name="name" class="form-control" placeholder="Enter Full Name" required>
                    </div>

                    <!-- TELL + EMAIL -->
                    <div class="row">
                      <div class="col-6">
                        <div class="form-group">
                          <label><i class="fas fa-phone mr-1 text-success"></i> Phone</label>
                          <input type="number" name="tell" class="form-control" placeholder="Phone" required>
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label><i class="fas fa-envelope mr-1 text-danger"></i> Email</label>
                          <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                      </div>
                    </div>

                    <!-- ADDRESS + JOBTITLE -->
                    <div class="row">
                      <div class="col-6">
                        <div class="form-group">
                          <label><i class="fas fa-map-marker-alt mr-1 text-warning"></i> Address</label>
                          <input type="text" name="address" class="form-control" placeholder="Address" required>
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label><i class="fas fa-briefcase mr-1 text-info"></i> Job Title</label>
                          <select name="jobtitle" class="form-control" required>
                            <option value="">Select...</option>
                            <option value="Manager">👔 Manager</option>
                            <option value="Cashier">💵 Cashier</option>
                            <option value="Marketer">📢 Marketer</option>
                            <option value="Security">🛡️ Security</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <!-- SALARY -->
                    <div class="form-group">
                      <label><i class="fas fa-dollar-sign mr-1 text-success"></i> Salary</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">$</span>
                        </div>
                        <input type="number" step="0.01" name="salary" class="form-control" placeholder="0.00" required>
                      </div>
                    </div>

                    <!-- DATE -->
                    <div class="form-group">
                      <label><i class="fas fa-calendar mr-1 text-purple"></i> Date</label>
                      <input type="date" name="date" class="form-control" required>
                    </div>

                  </div>
                  <div class="card-footer">
                    <button type="submit" name="btnsave" class="btn btn-primary btn-block">
                      <i class="fas fa-save mr-1"></i> Save Employee
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <!-- RIGHT: TABLE -->
            <div class="col-lg-8 col-md-7">
              <div class="card card-primary card-outline">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-list mr-1"></i> Employee Records
                  </h3>
                  <div class="card-tools">
                    <span class="badge badge-primary">
                      <?php echo $total_emp; ?> Employees
                    </span>
                  </div>
                </div>
                <div class="card-body p-0">
                  <div style="overflow-x:auto">
                    <table id="example1" class="table table-bordered table-striped table-sm mb-0" style="width:100%">
                      <thead class="thead-dark">
                        <tr>
                          <th>#</th>
                          <th>Name</th>
                          <th>Phone</th>
                          <th>Email</th>
                          <th>Job Title</th>
                          <th>Salary</th>
                          <th>Date</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $read = mysqli_query($conn, "SELECT * FROM employee ORDER BY empid DESC");
                        while ($row = mysqli_fetch_assoc($read)):
                          $initial = strtoupper(substr($row['name'], 0, 1));
                          $job = $row['jobtitle'];
                          $job_cls = 'job-' . (in_array($job, ['Manager', 'Cashier', 'Marketer', 'Security']) ? $job : 'default');
                          $job_icon = ['Manager' => '👔', 'Cashier' => '💵', 'Marketer' => '📢', 'Security' => '🛡️'];
                          ?>
                          <tr>
                            <td><?php echo $row['empid']; ?></td>
                            <td>
                              <span class="emp-avatar"><?php echo $initial; ?></span>
                              <?php echo htmlspecialchars($row['name']); ?>
                            </td>
                            <td>
                              <i class="fas fa-phone text-success mr-1" style="font-size:11px"></i>
                              <?php echo $row['tell']; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td>
                              <span class="job-badge <?php echo $job_cls; ?>">
                                <?php echo ($job_icon[$job] ?? '') . ' ' . $job; ?>
                              </span>
                            </td>
                            <td>
                              <strong style="color:#10b981">
                                $<?php echo number_format($row['salary'], 2); ?>
                              </strong>
                            </td>
                            <td style="white-space:nowrap"><?php echo $row['date']; ?></td>
                            <td style="white-space:nowrap">
                              <div class="action-btns">
                                <a href="employee_edit.php?idd=<?php echo $row['empid']; ?>"
                                  class="btn btn-success btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                                <a href="employee.php?idd=<?php echo $row['empid']; ?>" class="btn btn-danger btn-sm"
                                  title="Delete" onclick="return confirm('Mahubtaa inaad tireysid?')"><i
                                    class="fa fa-trash"></i></a>
                                <a href="employee_repor.php?idd=<?php echo $row['empid']; ?>" class="btn btn-info btn-sm"
                                  title="Print"><i class="fa fa-print"></i></a>
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
    $(function () {
      $("#example1").DataTable({
        "responsive": false,
        "scrollX": true,
        "autoWidth": false,
        "columnDefs": [{ "orderable": false, "targets": -1 }],
        "order": [[0, "desc"]],
        "language": {
          "search": "Raadi:",
          "lengthMenu": "Muuji _MENU_",
          "info": "_START_ ilaa _END_ (Wadarta: _TOTAL_)",
          "paginate": { "previous": "Hore", "next": "Xiga" }
        }
      });
    });
  </script>

</body>

</html>