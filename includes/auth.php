<?php
/**
 * auth.php — Session authentication guard
 * Include at the top of any admin-only page.
 * Redirects unauthenticated users to login.php.
 */
require_once __DIR__ . '/config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
