<?php
$pageTitle = 'Case Study'; $currentPage = 'cases'; $base = '';
require_once(__DIR__ . '/includes/header.php');
require_once(__DIR__ . '/includes/db.php');

$case = null;
if (!empty($_GET['id'])) {
    try {
        $case = $db->case_studies->findOne(['_id' => new MongoDB\BSON\ObjectId($_GET['id'])]);
    } catch (Exception $e) {
        $case = null;
    }
}

if (!$case) {
    http_response_code(404);
    ?>
    <section class="section"><div class="container" style="text-align:center;padding:3rem;">
        <i class="fa fa-briefcase" style="font-size:3rem;margin-bottom:1rem;display:block;color:var(--grey);"></i>
        <h2>Case study not found</h2>
        <p style="color:var(--grey-text);margin-bottom:1.5rem;">This case study may have been removed or the link is incorrect.</p>
        <a href="case-studies.php" class="btn btn-primary">Back to Case Studies</a>
    </div></section>
    <?php
    require_once(__DIR__ . '/includes/footer.php');
    exit;
}

$pageTitle = htmlspecialchars($case['company']) . ' — Case Study';
?>
<section class="page-banner"><div class="container">
    <p class="breadcrumb"><a href="index.php">Home</a> <span>/</span> <a href="case-studies.php">Case Studies</a> <span>/</span> <?= htmlspecialchars($case['company']) ?></p>
    <h1><?= htmlspecialchars($case['company']) ?></h1>
    <p><?= htmlspecialchars($case['industry']) ?></p>
</div></section>

<section class="section"><div class="container">
    <div class="event-detail-layout">
        <?php if (!empty($case['img'])): ?>
        <div class="event-detail__hero">
            <img src="<?= htmlspecialchars($case['img']) ?>" alt="<?= htmlspecialchars($case['company']) ?>">
        </div>
        <?php endif; ?>

        <?php if (!empty($case['stats'])): ?>
        <div class="event-detail__info-bar">
            <?php foreach ($case['stats'] as $s): ?>
            <div class="event-detail__info-item">
                <i class="fa fa-chart-simple"></i>
                <div><div class="event-detail__info-label"><?= htmlspecialchars($s['label'] ?? '') ?></div><div class="event-detail__info-value"><?= htmlspecialchars($s['value'] ?? '') ?></div></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="event-detail__body">
            <h2>The Challenge</h2>
            <p><?= nl2br(htmlspecialchars($case['challenge'])) ?></p>
            <h2>Our Solution</h2>
            <p><?= nl2br(htmlspecialchars($case['solution'])) ?></p>
        </div>

        <div class="event-detail__cta">
            <a href="contact.php" class="btn btn-primary">Get Results Like This</a>
            <a href="case-studies.php" class="btn btn-secondary">Back to All Case Studies</a>
        </div>
    </div>
</div></section>
<?php require_once(__DIR__ . '/includes/footer.php'); ?>
