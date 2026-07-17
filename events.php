<?php
$pageTitle = 'Upcoming Events'; $currentPage = 'events'; $base = '';
require_once(__DIR__ . '/includes/header.php');
require_once(__DIR__ . '/includes/db.php');

$events = iterator_to_array($db->events->find([], ['sort' => ['sort_order' => 1, 'created_at' => 1]]));

$today = new DateTime('today');
$upcoming = []; $ongoing = []; $past = [];
foreach ($events as $e) {
    $day   = (int)($e['day'] ?? 0);
    $month = (string)($e['month'] ?? '');
    $eventDate = ($day && $month) ? DateTime::createFromFormat('j M Y', "{$day} {$month} " . $today->format('Y')) : null;
    if ($eventDate === false) { $eventDate = null; }
    if ($eventDate === null) {
        $upcoming[] = $e;
    } elseif ($eventDate->format('Y-m-d') === $today->format('Y-m-d')) {
        $ongoing[] = $e;
    } elseif ($eventDate > $today) {
        $upcoming[] = $e;
    } else {
        $past[] = $e;
    }
}

function render_event_card($e) {
    $detailUrl = 'event-detail.php?id=' . $e['_id'];
    ?>
    <div class="event-grid-card" style="position:relative;">
        <a href="<?= $detailUrl ?>" class="event-card-overlay" aria-label="View <?= htmlspecialchars($e['title']) ?>"></a>
        <div class="event-grid-card__img">
            <?php if (!empty($e['img'])): ?>
            <img src="<?= htmlspecialchars($e['img']) ?>" alt="<?= htmlspecialchars($e['title']) ?>" loading="lazy">
            <?php endif; ?>
            <?php if (!empty($e['day']) || !empty($e['month'])): ?>
            <div class="event-grid-card__date">
                <span class="event-grid-card__day"><?= htmlspecialchars($e['day'] ?? '') ?></span>
                <span class="event-grid-card__month"><?= htmlspecialchars($e['month'] ?? '') ?></span>
            </div>
            <?php endif; ?>
        </div>
        <div class="event-grid-card__body">
            <h3><?= htmlspecialchars($e['title']) ?></h3>
            <p><?= htmlspecialchars($e['description'] ?? '') ?></p>
            <div class="event-grid-card__meta">
                <?php if (!empty($e['location'])): ?>
                <span><i class="fa fa-map-marker-alt"></i> <?= htmlspecialchars($e['location']) ?></span>
                <?php endif; ?>
                <?php if (!empty($e['time'])): ?>
                <span><i class="fa fa-clock"></i> <?= htmlspecialchars($e['time']) ?></span>
                <?php endif; ?>
            </div>
            <a href="join-events.php" class="btn btn-primary btn-sm" style="position:relative;z-index:1;">Register Now</a>
        </div>
    </div>
    <?php
}
?>
<section class="page-banner"><div class="container">
    <p class="breadcrumb"><a href="index.php">Home</a> <span>/</span> Events</p>
    <h1>Upcoming Events</h1>
    <p>Webinars, meetups, workshops, and conferences — connect with us.</p>
</div></section>

<section class="section"><div class="container">
    <?php if (empty($events)): ?>
    <div style="text-align:center;padding:3rem;color:var(--grey-text);">
        <i class="fa fa-calendar" style="font-size:3rem;margin-bottom:1rem;display:block;color:var(--grey);"></i>
        <h3 style="color:var(--grey-text);">No events scheduled yet</h3>
        <p>Check back soon — exciting events are coming!</p>
    </div>
    <?php else: ?>

    <?php if (!empty($ongoing)): ?>
    <h2 style="margin-bottom:1rem;"><i class="fa fa-circle" style="color:#16A34A;font-size:0.6rem;vertical-align:middle;"></i> Happening Today</h2>
    <div class="grid-3" style="margin-bottom:3rem;">
        <?php foreach ($ongoing as $e) render_event_card($e); ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($upcoming)): ?>
    <h2 style="margin-bottom:1rem;">Upcoming Events</h2>
    <div class="grid-3" style="margin-bottom:3rem;">
        <?php foreach ($upcoming as $e) render_event_card($e); ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($past)): ?>
    <h2 style="margin-bottom:1rem;color:var(--grey-text);">Past Events</h2>
    <div class="grid-3" style="opacity:0.7;">
        <?php foreach ($past as $e) render_event_card($e); ?>
    </div>
    <?php endif; ?>

    <?php endif; ?>
</div></section>
<?php require_once(__DIR__ . '/includes/footer.php'); ?>
