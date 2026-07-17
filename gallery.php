<?php
$pageTitle = 'Photo Gallery'; $currentPage = 'gallery'; $base = '';
require_once(__DIR__ . '/includes/header.php');
require_once(__DIR__ . '/includes/db.php');

// Restore any missing seed images
$_seed = [
    ['title'=>'AI Summit 2025 — Keynote',            'img'=>'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=600&h=400&fit=crop&auto=format','sort_order'=>1],
    ['title'=>'Team Hackathon — Innovation Day',      'img'=>'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=600&h=400&fit=crop&auto=format','sort_order'=>2],
    ['title'=>'Client Workshop — NorthCare NHS',      'img'=>'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=600&h=400&fit=crop&auto=format','sort_order'=>3],
    ['title'=>'Office — Sunderland HQ',               'img'=>'https://images.unsplash.com/photo-1497366216548-37526070297c?w=600&h=400&fit=crop&auto=format','sort_order'=>4],
    ['title'=>'Prototype Sprint — PayStream',         'img'=>'https://images.unsplash.com/photo-1581291518633-83b4ebd1d83e?w=600&h=400&fit=crop&auto=format','sort_order'=>5],
    ['title'=>'Annual Company Retreat 2025',           'img'=>'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=600&h=400&fit=crop&auto=format','sort_order'=>6],
    ['title'=>'AI Demo Day — Investor Showcase',      'img'=>'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=600&h=400&fit=crop&auto=format','sort_order'=>7],
    ['title'=>'Community Outreach — STEM Workshop',   'img'=>'https://images.unsplash.com/photo-1588072432836-e10032774350?w=600&h=400&fit=crop&auto=format','sort_order'=>8],
    ['title'=>'Product Launch — Virtual Assistant v3','img'=>'https://images.unsplash.com/photo-1677442135703-1787eea5ce01?w=600&h=400&fit=crop&auto=format','sort_order'=>9],
];
if ($db->gallery->countDocuments() < count($_seed)) {
    foreach ($_seed as $_item) {
        if ($db->gallery->countDocuments(['title' => $_item['title']]) === 0) {
            $_item['created_at'] = new MongoDB\BSON\UTCDateTime();
            $db->gallery->insertOne($_item);
        }
    }
}

$photos = iterator_to_array($db->gallery->find([], ['sort' => ['sort_order' => 1, 'created_at' => 1]]));
?>
<section class="page-banner"><div class="container">
    <p class="breadcrumb"><a href="index.php">Home</a> <span>/</span> Gallery</p>
    <h1>Photo Gallery</h1>
    <p>A visual journey through our events, team, and projects.</p>
</div></section>

<section class="section"><div class="container">
    <?php if (empty($photos)): ?>
    <div style="text-align:center;padding:3rem;color:var(--grey-text);">
        <i class="fa fa-images" style="font-size:3rem;margin-bottom:1rem;display:block;color:var(--grey);"></i>
        <h3 style="color:var(--grey-text);">Gallery coming soon</h3>
        <p>Images will appear here once added by the team.</p>
    </div>
    <?php else: ?>
    <div class="gallery-grid">
    <?php foreach ($photos as $index => $photo): ?>
        <div class="gallery-item" style="cursor: pointer;" data-lightbox="true" data-index="<?= $index ?>" data-src="<?= htmlspecialchars($photo['img']) ?>" data-title="<?= htmlspecialchars($photo['title']) ?>">
            <img src="<?= htmlspecialchars($photo['img']) ?>" alt="<?= htmlspecialchars($photo['title']) ?>" loading="lazy">
            <div class="gallery-item__overlay">
                <div class="gallery-item__info">
                    <p class="gallery-item__title"><?= htmlspecialchars($photo['title']) ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div></section>
<?php require_once(__DIR__ . '/includes/footer.php'); ?>
