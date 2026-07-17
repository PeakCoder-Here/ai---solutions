<?php
/**
 * admin/partials/head.php — Shared admin dashboard shell (opening half)
 *
 * Reconstructed file — this was missing after the project's drive move
 * (referenced by admin/dashboard.php but not present anywhere in the
 * project). Rebuilt to match the .admin-* classes already defined in
 * css/style.css (admin-layout, admin-sidebar, admin-nav, admin-topbar,
 * admin-content, etc.) so no CSS changes were needed.
 *
 * Variables expected from the including page (set BEFORE this require):
 *   $pageTitle    (string)           — e.g. "Dashboard"
 *   $activePage   (string)           — nav highlight key, e.g. "dashboard"
 *   $extraHead    (string, optional) — extra markup injected into <head>
 *
 * Pairs with admin/partials/foot.php, which closes the tags opened here
 * and renders $extraScripts (if set) before </body>.
 */

$pageTitle  = $pageTitle  ?? 'Admin';
$activePage = $activePage ?? '';
$extraHead  = $extraHead  ?? '';

function adminNavClass(string $page, string $current): string
{
    return $page === $current ? 'admin-nav__link active' : 'admin-nav__link';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> — AI-Solutions Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap">

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha384-/o6I2CkkWC//PSjvWC/eYN7l3xM3tJm8ZzVkCOfp//W05QcE3mlGskpoHB6XqI+B" crossorigin="anonymous">

    <?= $extraHead ?>
</head>
<body>
<div class="admin-layout">

    <!-- ═══════════════════════════════════════════════════════════
         SIDEBAR
    ════════════════════════════════════════════════════════════ -->
    <aside class="admin-sidebar" id="adminSidebar">
        <a href="dashboard.php" class="admin-sidebar__logo">
            <span class="logo-icon"><i class="fa fa-robot"></i></span>
            <span>AI<em>-Solutions</em></span>
        </a>

        <nav class="admin-nav">
            <div class="admin-nav__label">Overview</div>
            <a href="dashboard.php" class="<?= adminNavClass('dashboard', $activePage) ?>">
                <i class="fa fa-gauge-high"></i> Dashboard
            </a>
            <a href="inquiries.php" class="<?= adminNavClass('inquiries', $activePage) ?>">
                <i class="fa fa-chart-line"></i> All Inquiries
            </a>

            <div class="admin-nav__label">Content</div>
            <a href="manage-blog.php" class="<?= adminNavClass('blog', $activePage) ?>">
                <i class="fa fa-newspaper"></i> Blog
            </a>
            <a href="manage-events.php" class="<?= adminNavClass('manage-events', $activePage) ?>">
                <i class="fa fa-calendar-plus"></i> Events
            </a>
            <a href="manage-case-studies.php" class="<?= adminNavClass('manage-case-studies', $activePage) ?>">
                <i class="fa fa-briefcase"></i> Case Studies
            </a>
            <a href="manage-gallery.php" class="<?= adminNavClass('gallery', $activePage) ?>">
                <i class="fa fa-images"></i> Gallery
            </a>
            <a href="manage-feedback.php" class="<?= adminNavClass('manage-feedback', $activePage) ?>">
                <i class="fa fa-star"></i> Feedback
            </a>

            <div class="admin-nav__label">Submissions</div>
            <a href="orders.php" class="<?= adminNavClass('orders', $activePage) ?>">
                <i class="fa fa-shopping-cart"></i> Orders
            </a>
            <a href="demos.php" class="<?= adminNavClass('demos', $activePage) ?>">
                <i class="fa fa-calendar-check"></i> Demo Requests
            </a>
            <a href="contacts.php" class="<?= adminNavClass('contacts', $activePage) ?>">
                <i class="fa fa-envelope"></i> Contact Inquiries
            </a>
            <a href="events-admin.php" class="<?= adminNavClass('events-admin', $activePage) ?>">
                <i class="fa fa-ticket"></i> Event Registrations
            </a>
        </nav>

        <div class="admin-sidebar__user">
            <div class="admin-sidebar__avatar"><i class="fa fa-user"></i></div>
            <div style="flex:1;min-width:0;">
                <div class="admin-sidebar__uname"><?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?></div>
                <div class="admin-sidebar__role"><?= htmlspecialchars($_SESSION['admin_role'] ?? 'Administrator') ?></div>
            </div>
            <a href="logout.php" class="admin-sidebar__logout" title="Sign out">
                <i class="fa fa-sign-out-alt"></i>
            </a>
        </div>
    </aside>

    <!-- ═══════════════════════════════════════════════════════════
         MAIN AREA
    ════════════════════════════════════════════════════════════ -->
    <div class="admin-main">
        <header class="admin-topbar">
            <div>
                <div class="admin-topbar__title"><?= htmlspecialchars($pageTitle) ?></div>
                <div class="admin-topbar__meta"><?= date('l, j F Y') ?></div>
            </div>
            <div class="admin-topbar__actions">
                <a href="../index.php" target="_blank" class="btn btn-outline btn-sm">
                    <i class="fa fa-external-link-alt"></i> View Site
                </a>
                <button class="admin-sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
        </header>

        <main class="admin-content">
