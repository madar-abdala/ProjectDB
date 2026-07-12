<?php
/**
 * sidebar.php — Permission-based sidebar
 * FIXED: Session check + null safety
 */

// ✅ Session hubi ka hor wax kasta
if (!isset($_SESSION['empid']) || !isset($_SESSION['user_id'])) {
  // Session ma jirto — sidebar default ah soo bandhig
  $allowed_menus = [];
  $session_user_id = 0;
} else {
  $session_user_id = (int) $_SESSION['user_id'];
  $allowed_menus = [];

  if ($session_user_id > 0) {
    $perm_q = mysqli_query($conn, "
            SELECT r.menuid, r.submenuid,
                   s.submenuname, s.submenuurl, s.submenuicon
            FROM role r
            INNER JOIN submenues s ON r.submenuid = s.submenuid
            WHERE r.user_id = '$session_user_id'
            ORDER BY r.menuid, s.submenuname
        ");

    if ($perm_q) {
      while ($p = mysqli_fetch_assoc($perm_q)) {
        $allowed_menus[$p['menuid']][] = $p;
      }
    }
  }
}

// URL-ka hadda jira
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">

  <!-- Brand -->
  <a href="role.php" class="brand-link">
    <span class="brand-text font-weight-light">
      <strong>Admin Panel</strong>
    </span>
  </a>

  <div class="sidebar">

    <!-- User Panel -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="dist/img/avatar5.png" class="img-circle elevation-2" alt="User">
      </div>
      <div class="info">
        <a href="#" class="d-block">
          <?php echo isset($_SESSION['name'])
            ? htmlspecialchars($_SESSION['name'])
            : 'User'; ?>
        </a>
        <small style="color:#adb5bd">
          <?php echo isset($_SESSION['role'])
            ? htmlspecialchars($_SESSION['role'])
            : ''; ?>
        </small>
      </div>
    </div>

    <!-- Nav -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <!-- Home -->
        <li class="nav-item">
          <a href="index.php" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-home"></i>
            <p>Home</p>
          </a>
        </li>

        <?php if (!empty($allowed_menus)): ?>

          <?php
          $menus_q = mysqli_query($conn, "SELECT * FROM menues ORDER BY menuid");
          while ($menu = mysqli_fetch_assoc($menus_q)):
            $mid = $menu['menuid'];

            // Skip haddaan la ogolaaday
            if (!isset($allowed_menus[$mid]))
              continue;

            $sub_items = $allowed_menus[$mid];
            $menu_icon = !empty($menu['menuicon']) ? $menu['menuicon'] : 'fas fa-circle';

            // Menu active hubi
            $menu_open = '';
            foreach ($sub_items as $si) {
              if ($current_page == basename($si['submenuurl'])) {
                $menu_open = 'menu-open';
                break;
              }
            }
            ?>

            <li class="nav-item <?php echo $menu_open; ?>">
              <a href="#" class="nav-link <?php echo $menu_open ? 'active' : ''; ?>">
                <i class="nav-icon <?php echo htmlspecialchars($menu_icon); ?>"></i>
                <p>
                  <?php echo htmlspecialchars($menu['menuname']); ?>
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>

              <ul class="nav nav-treeview">
                <?php foreach ($sub_items as $sub):
                  $sub_icon = !empty($sub['submenuicon']) ? $sub['submenuicon'] : 'far fa-circle';
                  $sub_url = $sub['submenuurl'];
                  $sub_active = ($current_page == basename($sub_url)) ? 'active' : '';
                  ?>
                  <li class="nav-item">
                    <a href="<?php echo htmlspecialchars($sub_url); ?>" class="nav-link <?php echo $sub_active; ?>">
                      <i class="<?php echo htmlspecialchars($sub_icon); ?> nav-icon"></i>
                      <p><?php echo htmlspecialchars($sub['submenuname']); ?></p>
                    </a>
                  </li>
                <?php endforeach; ?>
              </ul>
            </li>

          <?php endwhile; ?>

        <?php endif; ?>

        <!-- Logout -->
        <li class="nav-item" style="margin-top:20px">
          <a href="logout.php" class="nav-link" style="color:#fc8181">
            <i class="nav-icon fas fa-sign-out-alt" style="color:#fc8181"></i>
            <p>Logout</p>
          </a>
        </li>

      </ul>
    </nav>

  </div>
</aside>