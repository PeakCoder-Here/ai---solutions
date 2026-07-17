<?php
/**
 * config.php — Global configuration for AI-Solutions website
 * Include this first in every page via: require_once 'includes/config.php';
 */

// ── Site Settings ─────────────────────────────────────────────────────────
define('SITE_NAME',    'AI-Solutions');
define('SITE_TAGLINE', 'Innovate. Promote. Deliver. The Future of Digital Employee Experience.');
define('SITE_URL',     'http://localhost/ai-solutions/');

// ── Error Reporting (set to 0 before submission/demo) ────────────────────
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ── SMTP (loaded from .env; queue_manager.php falls back to "off" if unset) ─
$__envFile = __DIR__ . '/../.env';
if (is_readable($__envFile)) {
    foreach (file($__envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $__line) {
        $__line = trim($__line);
        if ($__line === '' || $__line[0] === '#' || !str_contains($__line, '=')) continue;
        [$__k, $__v] = explode('=', $__line, 2);
        $_ENV[trim($__k)] = trim($__v, " \t\"'");
    }
}
if (!empty($_ENV['SMTP_HOST'])) {
    define('SMTP_HOST', $_ENV['SMTP_HOST']);
    define('SMTP_PORT', (int) ($_ENV['SMTP_PORT'] ?? 587));
    define('SMTP_USER', $_ENV['SMTP_USER'] ?? '');
    define('SMTP_PASS', $_ENV['SMTP_PASS'] ?? '');
    define('SMTP_FROM', $_ENV['SMTP_FROM'] ?? ($_ENV['SMTP_USER'] ?? ''));
    define('SMTP_FROM_NAME', $_ENV['SMTP_FROM_NAME'] ?? 'AI-Solutions');
    define('ADMIN_NOTIFY_EMAIL', $_ENV['ADMIN_NOTIFY_EMAIL'] ?? ($_ENV['SMTP_USER'] ?? ''));
}

// ── Session (start once globally) ────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

// ── Base path helper ──────────────────────────────────────────────────────
// $base is set PER PAGE before including header.php:
//   Root pages  → $base = '';
//   Admin pages → $base = '../';
// This ensures CSS/JS/image paths resolve correctly from subdirectories.
