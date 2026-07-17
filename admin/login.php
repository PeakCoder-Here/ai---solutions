<?php
$pageTitle = 'Admin Login'; $currentPage = 'admin'; $base = '../';
require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/db.php');

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php'); exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Both username and password are required.';
    } else {
        $admin = $db->admin_users->findOne(['username' => $username]);
        if ($admin && password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username']  = $admin['username'];
            $_SESSION['admin_role']      = $admin['role'];
            header('Location: dashboard.php'); exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — AI-Solutions</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha384-/o6I2CkkWC//PSjvWC/eYN7l3xM3tJm8ZzVkCOfp//W05QcE3mlGskpoHB6XqI+B" crossorigin="anonymous">
</head>
<body>
<div class="admin-login-wrap">
    <div class="admin-login-card">

        <div class="admin-login-card__logo">
            <div class="admin-login-card__icon"><i class="fa fa-robot"></i></div>
            <h1>AI-Solutions Admin</h1>
            <p>Authorised staff only — please sign in</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error" style="margin-bottom:1.25rem;">
                <i class="fa fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username"
                       class="form-control" value="<?= htmlspecialchars($username ?? '') ?>"
                       required autofocus autocomplete="username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div style="position:relative;">
                    <input type="password" id="password" name="password"
                           class="form-control" required autocomplete="current-password"
                           style="padding-right:2.75rem;">
                    <button type="button" onclick="togglePw()"
                            style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--grey-text);font-size:.9rem;"
                            aria-label="Toggle password visibility">
                        <i class="fa fa-eye" id="pwEye"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:.5rem;padding:.85rem;font-size:1rem;">
                <i class="fa fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        <p style="text-align:center;margin-top:1.5rem;font-size:0.82rem;">
            <a href="../index.php" style="color:var(--grey-text);"><i class="fa fa-arrow-left"></i> Back to website</a>
        </p>
    </div>
</div>
<script>
function togglePw() {
    var p = document.getElementById('password'), e = document.getElementById('pwEye');
    p.type = p.type === 'password' ? 'text' : 'password';
    e.className = p.type === 'password' ? 'fa fa-eye' : 'fa fa-eye-slash';
}
</script>
</body>
</html>
