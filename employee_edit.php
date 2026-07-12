<?php
session_start();
include 'lip/conn.php';
include 'check_permission.php';

if (!isset($_SESSION['empid'])) {
  header("Location: login.php");
  exit();
}

checkPermission($conn, $_SESSION['user_id'], 'employee.php');

// CHECK ID
if (!isset($_GET['idd']) || empty($_GET['idd'])) {
  header("Location: employee.php");
  exit();
}

$id = (int) $_GET['idd'];

// FETCH DATA
$res = mysqli_query($conn, "SELECT * FROM employee WHERE empid='$id'");

if (!$res || mysqli_num_rows($res) == 0) {
  die("Employee Not Found");
}

$row = mysqli_fetch_assoc($res);

/* ── UPDATE ── */
if (isset($_POST['btnedit'])) {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $tell = mysqli_real_escape_string($conn, $_POST['tell']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $address = mysqli_real_escape_string($conn, $_POST['address']);
  $jobtitle = mysqli_real_escape_string($conn, $_POST['jobtitle']);
  $salary = mysqli_real_escape_string($conn, $_POST['salary']);
  $date = mysqli_real_escape_string($conn, $_POST['date']);

  $update = mysqli_query($conn, "
        UPDATE employee SET
            name     = '$name',
            tell     = '$tell',
            email    = '$email',
            address  = '$address',
            jobtitle = '$jobtitle',
            salary   = '$salary',
            date     = '$date'
        WHERE empid  = '$id'
    ");

  if ($update) {
    header("Location: employee.php?msg=updated");
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
    .form-group label {
      font-weight: 600;
      font-size: 13px;
      color: #374151;
    }

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
                <i class="fas fa-user-edit mr-2" style="color:#3b82f6"></i>
                Edit Employee
              </h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="employee.php">Employees</a></li>
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
              <div class="card card-primary card-outline">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-edit mr-1"></i>
                    Update Employee #<?php echo $id; ?>
                  </h3>
                </div>

                <form method="POST">
                  <div class="card-body">

                    <!-- NAME -->
                    <div class="form-group">
                      <label><i class="fas fa-user mr-1 text-primary"></i> Full Name</label>
                      <input type="text" name="name" class="form-control" placeholder="Enter Full Name" required
                        value="<?php echo htmlspecialchars($row['name']); ?>">
                    </div>

                    <!-- TELL + EMAIL -->
                    <div class="row">
                      <div class="col-6">
                        <div class="form-group">
                          <label><i class="fas fa-phone mr-1 text-success"></i> Phone</label>
                          <input type="number" name="tell" class="form-control" placeholder="Phone" required
                            value="<?php echo $row['tell']; ?>">
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label><i class="fas fa-envelope mr-1 text-danger"></i> Email</label>
                          <input type="email" name="email" class="form-control" placeholder="Email" required
                            value="<?php echo htmlspecialchars($row['email']); ?>">
                        </div>
                      </div>
                    </div>

                    <!-- ADDRESS + JOBTITLE -->
                    <div class="row">
                      <div class="col-6">
                        <div class="form-group">
                          <label><i class="fas fa-map-marker-alt mr-1 text-warning"></i> Address</label>
                          <input type="text" name="address" class="form-control" placeholder="Address" required
                            value="<?php echo htmlspecialchars($row['address']); ?>">
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label><i class="fas fa-briefcase mr-1 text-info"></i> Job Title</label>
                          <select name="jobtitle" class="form-control" required>
                            <option value="">Select...</option>
                            <?php
                            $jobs = [
                              'Manager' => '👔 Manager',
                              'Cashier' => '💵 Cashier',
                              'Marketer' => '📢 Marketer',
                              'Security' => '🛡️ Security',
                            ];
                            foreach ($jobs as $val => $label):
                              $sel = ($row['jobtitle'] == $val) ? 'selected' : '';
                              ?>
                              <option value="<?php echo $val; ?>" <?php echo $sel; ?>>
                                <?php echo $label; ?>
                              </option>
                            <?php endforeach; ?>
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
                        <input type="number" step="0.01" name="salary" class="form-control" placeholder="0.00" required
                          value="<?php echo $row['salary']; ?>">
                      </div>
                    </div>

                    <!-- DATE -->
                    <div class="form-group">
                      <label><i class="fas fa-calendar mr-1 text-purple"></i> Date</label>
                      <input type="date" name="date" class="form-control" required value="<?php echo $row['date']; ?>">
                    </div>

                  </div>
                  <div class="card-footer d-flex justify-content-between">
                    <a href="employee.php" class="btn btn-secondary">
                      <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                    <button type="submit" name="btnedit" class="btn btn-primary">
                      <i class="fas fa-save mr-1"></i> Update Employee
                    </button>
                  </div>
                </form>

              </div>
            </div>

            <!-- RIGHT: CURRENT INFO -->
            <div class="col-md-6">
              <div class="card card-outline card-info">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    Current Information
                  </h3>
                </div>
                <div class="card-body">

                  <!-- Avatar + Name -->
                  <div class="text-center mb-4">
                    <div style="
                                    width:70px; height:70px; border-radius:50%;
                                    background:linear-gradient(135deg,#3b82f6,#6366f1);
                                    color:#fff; font-size:28px; font-weight:700;
                                    display:inline-flex; align-items:center; justify-content:center;
                                    margin-bottom:10px;">
                      <?php echo strtoupper(substr($row['name'], 0, 1)); ?>
                    </div>
                    <h5 class="mb-0 font-weight-bold"><?php echo htmlspecialchars($row['name']); ?></h5>
                    <?php
                    $job_icons = ['Manager' => '👔', 'Cashier' => '💵', 'Marketer' => '📢', 'Security' => '🛡️'];
                    $job_cls = 'job-' . (array_key_exists($row['jobtitle'], $job_icons) ? $row['jobtitle'] : 'default');
                    ?>
                    <span class="job-badge <?php echo $job_cls; ?> mt-1">
                      <?php echo ($job_icons[$row['jobtitle']] ?? '') . ' ' . $row['jobtitle']; ?>
                    </span>
                  </div>

                  <table class="table table-sm table-borderless">
                    <tr>
                      <td style="width:40%;color:#64748b;font-size:13px;">
                        <i class="fas fa-id-badge mr-1"></i> Employee ID
                      </td>
                      <td><strong>#<?php echo $row['empid']; ?></strong></td>
                    </tr>
                    <tr>
                      <td style="color:#64748b;font-size:13px;">
                        <i class="fas fa-phone mr-1 text-success"></i> Phone
                      </td>
                      <td><?php echo $row['tell']; ?></td>
                    </tr>
                    <tr>
                      <td style="color:#64748b;font-size:13px;">
                        <i class="fas fa-envelope mr-1 text-danger"></i> Email
                      </td>
                      <td><?php echo htmlspecialchars($row['email']); ?></td>
                    </tr>
                    <tr>
                      <td style="color:#64748b;font-size:13px;">
                        <i class="fas fa-map-marker-alt mr-1 text-warning"></i> Address
                      </td>
                      <td><?php echo htmlspecialchars($row['address']); ?></td>
                    </tr>
                    <tr>
                      <td style="color:#64748b;font-size:13px;">
                        <i class="fas fa-dollar-sign mr-1 text-success"></i> Salary
                      </td>
                      <td>
                        <strong style="color:#10b981;font-size:16px;">
                          $<?php echo number_format($row['salary'], 2); ?>
                        </strong>
                      </td>
                    </tr>
                    <tr>
                      <td style="color:#64748b;font-size:13px;">
                        <i class="fas fa-calendar mr-1 text-info"></i> Date
                      </td>
                      <td><?php echo $row['date']; ?></td>
                    </tr>
                  </table>

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