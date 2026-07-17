<?php
/**
 * header.php — Shared page header & navigation
 *
 * Variables expected from the including page:
 *   $pageTitle   (string) — browser tab title, e.g. "Home"
 *   $currentPage (string) — nav highlight key, e.g. "home", "solutions"
 *   $base        (string) — path prefix: '' for root, '../' for admin/
 */

require_once __DIR__ . '/config.php';

$pageTitle   = $pageTitle   ?? 'AI-Solutions';
$currentPage = $currentPage ?? '';
$base        = $base        ?? '';

// Helper: mark active nav link
function navClass(string $page, string $current): string {
    return $page === $current ? 'nav__link nav__link--active' : 'nav__link';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AI-Solutions — AI-powered virtual assistant and affordable prototyping solutions.">
    <title><?= htmlspecialchars($pageTitle) ?> — AI-Solutions</title>

    <!-- Fonts: Space Grotesk (headings) + Inter (body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap">

    <!-- Global Stylesheet -->
    <link rel="stylesheet" href="<?= $base ?>css/style.css">

    <!-- Font Awesome (icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha384-/o6I2CkkWC//PSjvWC/eYN7l3xM3tJm8ZzVkCOfp//W05QcE3mlGskpoHB6XqI+B" crossorigin="anonymous">
</head>
<body>

<!-- ═══════════════════════════════════════════════════════════════
     TOP BAR
════════════════════════════════════════════════════════════════ -->
<div class="topbar">
    <div class="container topbar__inner">
        <span><i class="fa fa-envelope"></i> info@ai-solutions.co.uk</span>
        <span><i class="fa fa-phone"></i> +44 191 000 0000</span>
        <span><i class="fa fa-map-marker-alt"></i> Sunderland, UK</span>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════
     MAIN NAVIGATION
════════════════════════════════════════════════════════════════ -->
<header class="site-header" id="site-header">
    <nav class="navbar container" role="navigation" aria-label="Main navigation">

        <!-- Logo -->
        <a href="<?= $base ?>index.php" class="navbar__logo" aria-label="AI-Solutions home">
            <span class="logo__icon"><i class="fa fa-robot"></i></span>
            <span class="logo__text">AI<span class="logo__accent">-Solutions</span></span>
        </a>

        <!-- Hamburger (mobile) -->
        <button class="navbar__toggle" id="navToggle" aria-label="Toggle navigation" aria-expanded="false" aria-controls="navMenu">
            <span class="hamburger"></span>
            <span class="hamburger"></span>
            <span class="hamburger"></span>
        </button>

        <!-- Nav links -->
        <ul class="navbar__menu" id="navMenu" role="list">
            <li><a href="<?= $base ?>index.php"         class="<?= navClass('home',       $currentPage) ?>">Home</a></li>
            <li><a href="<?= $base ?>solutions.php"     class="<?= navClass('solutions',  $currentPage) ?>">Solutions</a></li>
            <li><a href="<?= $base ?>case-studies.php"  class="<?= navClass('cases',      $currentPage) ?>">Case Studies</a></li>
            <li><a href="<?= $base ?>gallery.php"       class="<?= navClass('gallery',    $currentPage) ?>">Gallery</a></li>
            <li><a href="<?= $base ?>events.php"        class="<?= navClass('events',     $currentPage) ?>">Events</a></li>
            <li><a href="<?= $base ?>blog.php"          class="<?= navClass('blog',       $currentPage) ?>">Blog</a></li>
            <li><a href="<?= $base ?>feedback.php"      class="<?= navClass('feedback',   $currentPage) ?>">Feedback</a></li>
            <li><a href="<?= $base ?>contact.php"       class="<?= navClass('contact',    $currentPage) ?> nav__link--outline">Contact</a></li>
            <li><a href="<?= $base ?>schedule-demo.php" class="<?= navClass('demo',       $currentPage) ?> nav__link--cta">Schedule Demo</a></li>
        </ul>

    </nav>
</header>

<!-- ═══════════════════════════════════════════════════════════════
     PAGE CONTENT BEGINS BELOW (each page adds its own sections)
════════════════════════════════════════════════════════════════ -->
<main id="main-content">
