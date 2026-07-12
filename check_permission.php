<?php
/**
 * check_permission.php
 * Ku dar page kasta oo aad rabto inaad xiddo
 * Usage: checkPermission('submenuurl');
 */

function getUserPermissions($conn, $user_id)
{
  $permissions = [];
  $q = mysqli_query($conn, "
        SELECT s.submenuurl
        FROM role r
        INNER JOIN submenues s ON r.submenuid = s.submenuid
        WHERE r.user_id = '$user_id'
    ");
  while ($row = mysqli_fetch_assoc($q)) {
    $permissions[] = $row['submenuurl'];
  }
  return $permissions;
}

function checkPermission($conn, $user_id, $page)
{
  $perms = getUserPermissions($conn, $user_id);
  if (!in_array($page, $perms)) {
    // Access denied page
    ?>
    <!DOCTYPE html>
    <html>

    <head>
      <title>Access Denied</title>
      <link rel="stylesheet" href="dist/css/adminlte.min.css">
      <style>
        body {
          background: #f0f4f8;
          display: flex;
          align-items: center;
          justify-content: center;
          min-height: 100vh;
          margin: 0;
          font-family: 'Segoe UI', sans-serif;
        }

        .denied-box {
          background: #fff;
          border-radius: 16px;
          box-shadow: 0 4px 24px rgba(0, 0, 0, 0.10);
          padding: 60px 50px;
          text-align: center;
          max-width: 420px;
        }

        .denied-icon {
          font-size: 72px;
          color: #ef4444;
          margin-bottom: 20px;
        }

        .denied-box h2 {
          font-size: 26px;
          font-weight: 700;
          color: #1a202c;
          margin-bottom: 10px;
        }

        .denied-box p {
          color: #64748b;
          font-size: 15px;
          margin-bottom: 28px;
        }

        .btn-back {
          background: linear-gradient(135deg, #3b82f6, #6366f1);
          color: #fff;
          border: none;
          padding: 12px 36px;
          border-radius: 8px;
          font-size: 15px;
          font-weight: 600;
          cursor: pointer;
          text-decoration: none;
          display: inline-block;
        }

        .btn-back:hover {
          opacity: 0.9;
          color: #fff;
        }
      </style>
    </head>

    <body>
      <div class="denied-box">
        <div class="denied-icon">
          <i class="fas fa-lock"></i>
        </div>
        <h2>Access Denied!</h2>
        <p>Adigu kuma lihid fasax aad ku gasho boggan.<br>
          Fadlan xiriir admin-ka.</p>
        <a href="javascript:history.back()" class="btn-back">
          ← Dib u noqo
        </a>
      </div>
      <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    </body>

    </html>
    <?php
    exit();
  }
}
?>