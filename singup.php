<?php
session_start();
include 'lip/conn.php';

if (isset($_POST['btnsignup'])) {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);
  $confirm = mysqli_real_escape_string($conn, $_POST['confirm_password']);

  if ($password !== $confirm) {
    $error = "Password-ka iyo xaqiijintiisu isku mid ma aha";
  } else {

    // Hubi haddii username-ku horey u jiro
    $check = mysqli_query($conn, "SELECT user_id FROM user WHERE username = '$username'");

    if ($check === false) {
      die("Query Error: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($check) > 0) {
      $error = "Username-kan horey ayaa loo isticmaalay";
    } else {

      // Hubi haddii email-ku horey u jiro
      $checkEmail = mysqli_query($conn, "SELECT empid FROM employee WHERE email = '$email'");

      if ($checkEmail === false) {
        die("Query Error: " . mysqli_error($conn));
      }

      if (mysqli_num_rows($checkEmail) > 0) {
        $error = "Email-kan horey ayaa loo diiwaan geliyay";
      } else {

        // 1) Ku dar shaqaalaha (employee) - magaca iyo email
        $insertEmp = mysqli_query($conn, "
                    INSERT INTO employee (name, email)
                    VALUES ('$name', '$email')
                ");

        if ($insertEmp === false) {
          die("Query Error: " . mysqli_error($conn));
        }

        $empid = mysqli_insert_id($conn);

        // 2) Ku dar user-ka (account login-ka) - role waa default
        $role = 'staff';
        $insertUser = mysqli_query($conn, "
                    INSERT INTO user (empid, username, password, role)
                    VALUES ('$empid', '$username', '$password', '$role')
                ");

        if ($insertUser === false) {
          die("Query Error: " . mysqli_error($conn));
        }

        $success = "Account-ka si guul leh ayaa loo abuuray. Fadlan Login samee.";
      }
    }
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
      margin-bottom: 60px;
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
  <title>Sign Up</title>
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
  <div class="login-box">

    <div class="top-header"></div>

    <div class="logo-area">
      <h1>LOGO</h1>
      <p>Ku soo biir 👋</p>
    </div>

    <div class="login-box">
      <div class="card">
        <div class="card-body login-card-body">

          <div class="login-title">Sign Up</div>
          <div class="login-subtitle">Samee account cusub</div>

          <?php if (isset($error)): ?>
            <div class="alert alert-danger">
              <?php echo $error; ?>
            </div>
          <?php endif; ?>

          <?php if (isset($success)): ?>
            <div class="alert alert-success">
              <?php echo $success; ?>
            </div>
          <?php endif; ?>

          <form method="post">

            <div class="form-group mb-3">
              <label>Magaca*</label>
              <input type="text" name="name" class="form-control" placeholder="Geli magacaaga" required>
            </div>

            <div class="form-group mb-3">
              <label>Username*</label>
              <input type="text" name="username" class="form-control" placeholder="Geli username" required>
            </div>

            <div class="form-group mb-3">
              <label>Email*</label>
              <input type="email" name="email" class="form-control" placeholder="Geli email-kaaga" required>
            </div>

            <div class="form-group mb-3">
              <label>Password*</label>
              <input type="password" name="password" class="form-control" placeholder="Geli password" required>
            </div>

            <div class="form-group mb-3">
              <label>Xaqiiji Password*</label>
              <input type="password" name="confirm_password" class="form-control" placeholder="Ku celi password-ka"
                required>
            </div>

            <button type="submit" name="btnsignup" class="btn btn-primary btn-block">
              Sign Up
            </button>

            <div class="options mt-3">
              <span>Account ma leedahay?</span>
              <a href="login.php">Login halkan</a>
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