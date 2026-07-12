<?php
session_start();
include "lip/conn.php";

if (!isset($_SESSION['empid'])) {
    header("Location: login.php");
    exit();
}

$msg = "";
$msg_type = "";

/* ── SAVE ROLE ── */
if (isset($_POST['btnsave'])) {
    $userid = (int) $_POST['userid'];

    if ($userid > 0) {
        mysqli_query($conn, "DELETE FROM role WHERE user_id='$userid'");

        if (isset($_POST['submenu']) && is_array($_POST['submenu'])) {
            foreach ($_POST['submenu'] as $submenuid) {
                $submenuid = (int) $submenuid;
                $sub = mysqli_query($conn, "SELECT menuid FROM submenues WHERE submenuid='$submenuid'");
                if ($subrow = mysqli_fetch_assoc($sub)) {
                    $menuid = (int) $subrow['menuid'];
                    mysqli_query($conn, "INSERT INTO role(user_id, menuid, submenuid) VALUES('$userid','$menuid','$submenuid')");
                }
            }
            $msg      = "Role Saved Successfully!";
            $msg_type = "success";
        } else {
            $msg      = "No permissions selected. All roles cleared.";
            $msg_type = "warning";
        }
    } else {
        $msg      = "Please select a valid user.";
        $msg_type = "danger";
    }
}

/* ── LOAD EXISTING ROLES ── */
$selected_user = isset($_POST['userid']) ? (int) $_POST['userid'] : 0;
$assigned_subs = [];

if ($selected_user > 0) {
    $existing = mysqli_query($conn, "SELECT submenuid FROM role WHERE user_id='$selected_user'");
    while ($er = mysqli_fetch_assoc($existing)) {
        $assigned_subs[] = $er['submenuid'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include 'lip/head.php'; ?>
    <style>
        .content-wrapper { background: #f0f4f8; }

        /* ── Page Header ── */
        .role-page-header {
            background: #fff;
            padding: 18px 28px 0 28px;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 24px;
        }
        .role-page-header h2 {
            font-size: 22px;
            font-weight: 700;
            color: #1a202c;
            margin: 0 0 4px 0;
        }
        .role-page-header .breadcrumb {
            background: none;
            padding: 0;
            margin: 0 0 12px 0;
            font-size: 13px;
        }
        .role-page-header .breadcrumb-item a { color: #4a90d9; }
        .role-page-header .breadcrumb-item.active { color: #718096; }

        /* ── Action Bar ── */
        .role-action-bar {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 0 28px 18px 28px;
        }
        .btn-action-bar {
            padding: 7px 22px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
        }
        .btn-bar-primary { background: #3b82f6; color: #fff; }
        .btn-bar-primary:hover { background: #2563eb; }
        .btn-bar-secondary { background: #e2e8f0; color: #374151; }
        .btn-bar-secondary:hover { background: #cbd5e1; }

        /* ── Card ── */
        .role-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            margin: 0 28px 28px 28px;
            overflow: hidden;
        }

        /* ── Employee Select ── */
        .employee-select-wrap {
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .employee-select-wrap label {
            font-weight: 600;
            font-size: 14px;
            color: #374151;
            white-space: nowrap;
            min-width: 140px;
        }
        .employee-select-wrap select {
            flex: 1;
            max-width: 380px;
            height: 42px;
            border: 1.5px solid #d1d5db;
            border-radius: 7px;
            padding: 0 12px;
            font-size: 14px;
            color: #374151;
            background: #fff;
            outline: none;
        }
        .employee-select-wrap select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        }

        /* ── Table ── */
        .perm-table-wrap { padding: 20px 24px; }
        .perm-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .perm-table thead tr th {
            background: #f1f5f9;
            color: #475569;
            font-size: 13px;
            font-weight: 700;
            padding: 11px 16px;
            border-bottom: 2px solid #e2e8f0;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .perm-table thead tr th:first-child { border-radius: 8px 0 0 0; }
        .perm-table thead tr th:last-child  { border-radius: 0 8px 0 0; }
        .perm-table tbody tr { transition: background 0.15s; }
        .perm-table tbody tr:hover { background: #f8fafc; }
        .perm-table tbody tr td {
            padding: 13px 16px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            font-size: 14px;
            color: #374151;
        }
        .perm-table tbody tr:last-child td { border-bottom: none; }

        .row-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            background: #e0e7ff;
            color: #3730a3;
            border-radius: 50%;
            font-size: 12px;
            font-weight: 700;
        }
        .menu-name { font-weight: 600; color: #1e293b; }

        /* ── Checkboxes ── */
        .check-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 22px;
        }
        .check-item {
            display: flex;
            align-items: center;
            gap: 7px;
            cursor: pointer;
        }
        .check-item input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #3b82f6;
            cursor: pointer;
        }
        .check-item span {
            font-size: 14px;
            color: #374151;
        }
        .select-all-cell { text-align: center; }
        .select-all-cell input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #6366f1;
            cursor: pointer;
        }

        /* ── Footer ── */
        .role-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 24px;
            border-top: 1px solid #e2e8f0;
            background: #f8fafc;
        }
        .perm-count { font-size: 13px; color: #64748b; }
        .perm-count span { font-weight: 700; color: #3b82f6; }
        .btn-save {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            color: #fff;
            border: none;
            padding: 10px 32px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: opacity 0.2s;
        }
        .btn-save:hover { opacity: 0.9; }

        /* ── Alert ── */
        .role-alert {
            margin: 0 28px 16px 28px;
            padding: 12px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .role-alert.success { background:#d1fae5; color:#065f46; border-left:4px solid #10b981; }
        .role-alert.warning { background:#fef3c7; color:#92400e; border-left:4px solid #f59e0b; }
        .role-alert.danger  { background:#fee2e2; color:#991b1b; border-left:4px solid #ef4444; }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <?php include 'lip/nav.php'; ?>
    <?php include 'lip/sidebar.php'; ?>

    <div class="content-wrapper">

        <!-- Page Header -->
        <div class="role-page-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Contact</a></li>
                    <li class="breadcrumb-item active">Role Management</li>
                </ol>
            </nav>
            <h2><i class="fas fa-user-shield mr-2" style="color:#3b82f6"></i>User Roles</h2>
        </div>

        <!-- Action Bar -->
        <div class="role-action-bar">
            <button type="button" class="btn-action-bar btn-bar-secondary" onclick="history.back()">
                <i class="fas fa-arrow-left mr-1"></i> Back
            </button>
            <button type="submit" form="roleForm" name="btnsave" class="btn-action-bar btn-bar-primary">
                <i class="fas fa-save mr-1"></i> Actions
            </button>
        </div>

        <!-- Alert -->
        <?php if ($msg): ?>
            <div class="role-alert <?php echo $msg_type; ?>">
                <?php
                    if ($msg_type === 'success')      echo '<i class="fas fa-check-circle"></i>';
                    elseif ($msg_type === 'warning')  echo '<i class="fas fa-exclamation-triangle"></i>';
                    else                              echo '<i class="fas fa-times-circle"></i>';
                ?>
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <!-- Main Form & Card -->
        <form method="post" id="roleForm">
        <div class="role-card" style="margin-top:4px">

            <!-- ✅ Employee Name with JOIN -->
            <div class="employee-select-wrap">
                <label><i class="fas fa-user mr-1" style="color:#3b82f6"></i> Employee Name</label>
                <select name="userid" id="userid" required onchange="loadUserRoles(this.value)">
                    <option value="">-- Select Employee --</option>
                    <?php
                    // ✅ JOIN employee table - magaca shaqaalaha soo bandhig
                    $users = mysqli_query($conn, "
                        SELECT u.user_id, u.username, e.name
                        FROM user u
                        INNER JOIN employee e ON u.empid = e.empid
                        ORDER BY e.name
                    ");
                    if (!$users) die("Error: " . mysqli_error($conn));

                    while ($u = mysqli_fetch_assoc($users)):
                        $sel = ($selected_user == $u['user_id']) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $u['user_id']; ?>" <?php echo $sel; ?>>
                            <?php echo htmlspecialchars($u['name']); ?>
                            (<?php echo htmlspecialchars($u['username']); ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Permissions Table -->
            <div class="perm-table-wrap">
                <table class="perm-table">
                    <thead>
                        <tr>
                            <th style="width:55px">#</th>
                            <th style="width:210px">Menu Name</th>
                            <th>Sub Name</th>
                            <th style="width:90px" class="text-center">
                                <input type="checkbox" id="checkAll" title="Select All">
                                <div style="font-size:10px;margin-top:2px;color:#6366f1">All</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $x     = 1;
                    $menus = mysqli_query($conn, "SELECT * FROM menues ORDER BY menuid");

                    while ($m = mysqli_fetch_assoc($menus)):
                        $subs = mysqli_query($conn, "
                            SELECT * FROM submenues
                            WHERE menuid='" . (int)$m['menuid'] . "'
                            ORDER BY submenuname
                        ");
                        if (mysqli_num_rows($subs) == 0) continue;
                    ?>
                        <tr>
                            <td><span class="row-num"><?php echo $x++; ?></span></td>
                            <td><span class="menu-name"><?php echo htmlspecialchars($m['menuname']); ?></span></td>
                            <td>
                                <div class="check-group">
                                <?php while ($s = mysqli_fetch_assoc($subs)):
                                    $checked = in_array($s['submenuid'], $assigned_subs) ? 'checked' : '';
                                ?>
                                    <label class="check-item">
                                        <input type="checkbox"
                                               name="submenu[]"
                                               id="sub_<?php echo $s['submenuid']; ?>"
                                               value="<?php echo $s['submenuid']; ?>"
                                               class="sub-check"
                                               <?php echo $checked; ?>>
                                        <span><?php echo htmlspecialchars($s['submenuname']); ?></span>
                                    </label>
                                <?php endwhile; ?>
                                </div>
                            </td>
                            <td class="select-all-cell">
                                <input type="checkbox" class="row-check"
                                       title="Select all in this row"
                                       onclick="toggleRow(this)">
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Card Footer -->
            <div class="role-card-footer">
                <div class="perm-count">
                    <span id="checkedCount">0</span> permission(s) selected
                </div>
                <button type="submit" name="btnsave" class="btn-save">
                    <i class="fas fa-save"></i> Save Role
                </button>
            </div>

        </div>
        </form>

    </div><!-- /content-wrapper -->

    <?php include 'lip/footer.php'; ?>
</div>

<?php include 'lip/script.php'; ?>

<script>
    // ── Check All ──
    document.getElementById('checkAll').addEventListener('change', function () {
        document.querySelectorAll('.sub-check').forEach(cb => cb.checked = this.checked);
        updateCount();
        syncRowChecks();
    });

    // ── Toggle whole row ──
    function toggleRow(rowCb) {
        const row  = rowCb.closest('tr');
        const subs = row.querySelectorAll('.sub-check');
        subs.forEach(cb => cb.checked = rowCb.checked);
        updateCount();
    }

    // ── Individual change ──
    document.querySelectorAll('.sub-check').forEach(cb => {
        cb.addEventListener('change', () => {
            updateCount();
            syncRowChecks();
        });
    });

    function syncRowChecks() {
        document.querySelectorAll('.row-check').forEach(rowCb => {
            const row  = rowCb.closest('tr');
            const subs = row.querySelectorAll('.sub-check');
            rowCb.checked = subs.length > 0 && [...subs].every(cb => cb.checked);
        });
    }

    function updateCount() {
        const count = document.querySelectorAll('.sub-check:checked').length;
        document.getElementById('checkedCount').textContent = count;
    }

    // ── AJAX: load roles when user selected ──
    function loadUserRoles(userid) {
        if (!userid) return;
        fetch('ajax_get_roles.php?userid=' + userid)
            .then(r => r.json())
            .then(data => {
                document.querySelectorAll('.sub-check').forEach(cb => cb.checked = false);
                data.forEach(subid => {
                    const cb = document.getElementById('sub_' + subid);
                    if (cb) cb.checked = true;
                });
                updateCount();
                syncRowChecks();
            })
            .catch(() => console.log('AJAX error'));
    }

    updateCount();
    syncRowChecks();
</script>

</body>
</html>