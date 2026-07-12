<?php
session_start();
include 'lip/conn.php';

if (isset($_POST['btnlogin'])) {
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  $sql = mysqli_query($conn, "
        SELECT
            e.empid,
            e.name,
            e.email,
            e.jobtitle,
            u.user_id,
            u.username,
            u.password,
            u.role
        FROM employee e
        INNER JOIN user u ON e.empid = u.empid
        WHERE e.email   = '$email'
        AND   u.password = '$password'
    ");

  if ($sql === false) {
    die("Query Error: " . mysqli_error($conn));
  }

  if (mysqli_num_rows($sql) > 0) {
    $row = mysqli_fetch_assoc($sql);

    $_SESSION['empid'] = $row['empid'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['name'] = $row['name'];
    $_SESSION['role'] = $row['role'];
    $_SESSION['user_id'] = $row['user_id']; // ✅ SIDEBAR u baahan

    header("Location: role.php");
    exit();

  } else {
    $error = "Email ama Password waa qalad";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #f7f7f7;
    }

    .login-page {
      background: #f7f7f7 !important;
    }

    .top-header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 320px;
      background: linear-gradient(135deg, #6a11cb, #8e2de2);
      border-bottom-left-radius: 50% 20%;
      border-bottom-right-radius: 50% 20%;
      z-index: 1;
    }

    .logo-area {
      position: absolute;
      top: 40px;
      width: 100%;
      text-align: center;
      color: #fff;
      z-index: 2;
    }

    .logo-area h1 {
      font-size: 48px;
      font-weight: bold;
    }

    .logo-area p {
      font-size: 26px;
    }

    .login-box {
      width: 550px;
      position: relative;
      z-index: 5;
      margin-top: 140px;
    }

    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, .15);
    }

    .login-card-body {
      padding: 40px;
    }

    .login-title {
      text-align: center;
      font-size: 42px;
      font-weight: bold;
      color: #222;
    }

    .login-subtitle {
      text-align: center;
      color: #777;
      margin-bottom: 25px;
    }

    .form-control {
      height: 50px;
      border-radius: 8px;
    }

    .btn-primary {
      background: #6a11cb;
      border: none;
      height: 50px;
      border-radius: 8px;
      font-size: 17px;
      font-weight: bold;
    }

    .btn-primary:hover {
      background: #5710a8;
    }

    .options {
      display: flex;
      justify-content: space-between;
      margin-bottom: 15px;
      font-size: 14px;
    }

    .options a {
      color: #6a11cb;
    }
  </style>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
  <div class="login-box">

    <div class="top-header"></div>

    <div class="logo-area">
      <h1>LOGO</h1>
      <p>Hello 👋 Welcome!</p>
    </div>

    <div class="login-box">
      <div class="card">
        <div class="card-body login-card-body">

          <div class="login-title">LogIn</div>
          <div class="login-subtitle">Please login to admin dashboard</div>

          <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
          <?php endif; ?>

          <form method="post">

            <div class="form-group mb-3">
              <label>Email*</label>
              <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>

            <div class="form-group mb-3">
              <label>Password*</label>
              <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>

            <div class="options">
              <label><input type="checkbox"> Remember me</label>
              <a href="#">Forgot password?</a>
            </div>

            <button type="submit" name="btnlogin" class="btn btn-primary btn-block">
              Login
            </button>
            <div class="options mt-3">
              <span>Account Samayso?</span>
              <a href="singup.php">Singup</a>
            </div>

          </form>

        </div>
      </div>
    </div>

  </div>

  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="dist/js/adminlte.min.js"></script>
</body>

</html>