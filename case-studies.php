<?php
$pageTitle = 'Case Studies'; $currentPage = 'cases'; $base = '';
require_once(__DIR__ . '/includes/header.php');
require_once(__DIR__ . '/includes/db.php');

// Auto-seed if collection is empty (matches the pattern used by gallery/feedback)
if ($db->case_studies->countDocuments() === 0) {
    $db->case_studies->insertMany([
        [
            'industry' => 'Healthcare', 'company' => 'NorthCare NHS Trust',
            'challenge' => '12,000+ repetitive HR queries per month overwhelming the internal helpdesk.',
            'solution' => "Deployed AI Virtual Assistant integrated with the trust's intranet and Microsoft Teams, trained on 500+ HR policy documents.",
            'img' => 'https://images.unsplash.com/photo-1584982751601-97dcc096659c?w=600&h=160&fit=crop&auto=format',
            'stats' => [['value'=>'73%','label'=>'Query Deflection'],['value'=>'£180K','label'=>'Annual Saving'],['value'=>'4.6★','label'=>'Staff Rating']],
            'sort_order' => 1, 'created_at' => new MongoDB\BSON\UTCDateTime(),
        ],
        [
            'industry' => 'Fintech', 'company' => 'PayStream Ltd',
            'challenge' => 'Slow 8-week product validation cycle causing missed market windows.',
            'solution' => 'Rapid Prototyping engagement delivering 3 interactive product prototypes in 14 days, tested with 200+ users.',
            'img' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?w=600&h=160&fit=crop&auto=format',
            'stats' => [['value'=>'14','label'=>'Days to Prototype'],['value'=>'3x','label'=>'Faster Validation'],['value'=>'92%','label'=>'User Approval']],
            'sort_order' => 2, 'created_at' => new MongoDB\BSON\UTCDateTime(),
        ],
        [
            'industry' => 'Manufacturing', 'company' => 'SteelWorks UK',
            'challenge' => 'Fragmented legacy systems with no cross-platform data visibility for managers.',
            'solution' => 'Custom AI Integration connecting ERP, HRMS, and quality databases into a unified intelligent dashboard.',
            'img' => 'https://images.unsplash.com/photo-1565043666747-69f6646db940?w=600&h=160&fit=crop&auto=format',
            'stats' => [['value'=>'5','label'=>'Systems Unified'],['value'=>'40%','label'=>'Time Saved'],['value'=>'ROI+','label'=>'Within 6 Months']],
            'sort_order' => 3, 'created_at' => new MongoDB\BSON\UTCDateTime(),
        ],
    ]);
}

$cases = iterator_to_array($db->case_studies->find([], ['sort' => ['sort_order' => 1, 'created_at' => 1]]));
?>
<section class="page-banner"><div class="container">
    <p class="breadcrumb"><a href="index.php">Home</a> <span>/</span> Case Studies</p>
    <h1>Past Solutions & Case Studies</h1>
    <p>Real results we've delivered for clients across industries.</p>
</div></section>

<section class="section"><div class="container">
    <div class="section__header"><h2>Featured Projects</h2>
        <p>From healthcare to fintech — here's how our AI solutions created measurable impact.</p></div>
    <?php if (empty($cases)): ?>
    <div style="text-align:center;padding:3rem;color:var(--grey-text);">
        <i class="fa fa-briefcase" style="font-size:3rem;margin-bottom:1rem;display:block;color:var(--grey);"></i>
        <h3 style="color:var(--grey-text);">Case studies coming soon</h3>
    </div>
    <?php else: ?>
    <div class="grid-3">
    <?php foreach ($cases as $c): ?>
        <div class="case-card" style="position:relative;cursor:pointer;">
            <a href="case-study-detail.php?id=<?= $c['_id'] ?>" class="event-card-overlay" aria-label="View <?= htmlspecialchars($c['company']) ?> case study"></a>
            <?php if (!empty($c['img'])): ?>
            <div class="case-card__img">
                <img src="<?= htmlspecialchars($c['img']) ?>" alt="<?= htmlspecialchars($c['industry'] . ' - ' . $c['company']) ?>" loading="lazy">
            </div>
            <?php endif; ?>
            <div class="case-card__header"><span class="case-card__industry"><?= htmlspecialchars($c['industry']) ?></span><h3><?= htmlspecialchars($c['company']) ?></h3></div>
            <div class="case-card__body">
                <p><strong>Challenge:</strong> <?= htmlspecialchars($c['challenge']) ?></p>
                <p><strong>Solution:</strong> <?= htmlspecialchars($c['solution']) ?></p>
                <?php if (!empty($c['stats'])): ?>
                <div class="case-card__results">
                    <?php foreach ($c['stats'] as $s): ?>
                    <div class="case-card__stat"><div class="case-card__stat-value"><?= htmlspecialchars($s['value'] ?? '') ?></div><div class="case-card__stat-label"><?= htmlspecialchars($s['label'] ?? '') ?></div></div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <a href="case-study-detail.php?id=<?= $c['_id'] ?>" class="btn btn-primary btn-sm" style="position:relative;z-index:1;margin-top:1rem;">Read Full Story &rarr;</a>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div></section>

<section class="section section--grey"><div class="container text-center">
    <h2>Want results like these?</h2>
    <p style="margin-bottom:2rem;">Let's discuss how we can tailor a solution for your organisation.</p>
    <a href="contact.php" class="btn btn-primary btn-lg">Contact Us</a>
</div></section>
<?php require_once(__DIR__ . '/includes/footer.php'); ?>
