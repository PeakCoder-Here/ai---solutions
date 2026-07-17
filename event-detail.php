<?php
$pageTitle = 'Event Details'; $currentPage = 'events'; $base = '';
require_once(__DIR__ . '/includes/header.php');
require_once(__DIR__ . '/includes/db.php');

$event = null;
if (!empty($_GET['id'])) {
    try {
        $event = $db->events->findOne(['_id' => new MongoDB\BSON\ObjectId($_GET['id'])]);
    } catch (Exception $e) {
        $event = null;
    }
}

if (!$event) {
    http_response_code(404);
    ?>
    <section class="section"><div class="container" style="text-align:center;padding:3rem;">
        <i class="fa fa-calendar-xmark" style="font-size:3rem;margin-bottom:1rem;display:block;color:var(--grey);"></i>
        <h2>Event not found</h2>
        <p style="color:var(--grey-text);margin-bottom:1.5rem;">This event may have been removed or the link is incorrect.</p>
        <a href="events.php" class="btn btn-primary">Back to Events</a>
    </div></section>
    <?php
    require_once(__DIR__ . '/includes/footer.php');
    exit;
}

$pageTitle = htmlspecialchars($event['title']) . ' — Events';
?>
<section class="page-banner"><div class="container">
    <p class="breadcrumb"><a href="index.php">Home</a> <span>/</span> <a href="events.php">Events</a> <span>/</span> <?= htmlspecialchars($event['title']) ?></p>
    <h1><?= htmlspecialchars($event['title']) ?></h1>
</div></section>

<section class="section"><div class="container">
    <div class="event-detail-layout">
        <div class="event-detail__hero">
            <?php if (!empty($event['img'])): ?>
            <img src="<?= htmlspecialchars($event['img']) ?>" alt="<?= htmlspecialchars($event['title']) ?>">
            <?php endif; ?>
            <?php if (!empty($event['day']) || !empty($event['month'])): ?>
            <div class="event-detail__date-badge">
                <div style="font-size:1.3rem;font-weight:700;line-height:1;"><?= htmlspecialchars($event['day'] ?? '') ?></div>
                <div style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.05em;"><?= htmlspecialchars($event['month'] ?? '') ?></div>
            </div>
            <?php endif; ?>
        </div>

        <div class="event-detail__info-bar">
            <?php if (!empty($event['location'])): ?>
            <div class="event-detail__info-item">
                <i class="fa fa-map-marker-alt"></i>
                <div><div class="event-detail__info-label">Location</div><div class="event-detail__info-value"><?= htmlspecialchars($event['location']) ?></div></div>
            </div>
            <?php endif; ?>
            <?php if (!empty($event['time'])): ?>
            <div class="event-detail__info-item">
                <i class="fa fa-clock"></i>
                <div><div class="event-detail__info-label">Time</div><div class="event-detail__info-value"><?= htmlspecialchars($event['time']) ?></div></div>
            </div>
            <?php endif; ?>
            <?php if (!empty($event['day']) || !empty($event['month'])): ?>
            <div class="event-detail__info-item">
                <i class="fa fa-calendar"></i>
                <div><div class="event-detail__info-label">Date</div><div class="event-detail__info-value"><?= htmlspecialchars(trim(($event['day'] ?? '') . ' ' . ($event['month'] ?? ''))) ?></div></div>
            </div>
            <?php endif; ?>
        </div>

        <div class="event-detail__body">
            <h2>About This Event</h2>
            <p><?= nl2br(htmlspecialchars($event['description'] ?? 'More details coming soon.')) ?></p>
        </div>

        <div class="event-detail__cta">
            <a href="join-events.php?event=<?= urlencode($event['title']) ?>" class="btn btn-primary">Register Now</a>
            <a href="events.php" class="btn btn-secondary">Back to All Events</a>
        </div>
    </div>
</div></section>
<?php require_once(__DIR__ . '/includes/footer.php'); ?>
