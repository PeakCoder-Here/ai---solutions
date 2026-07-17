<?php
$pageTitle = 'Blog'; $currentPage = 'blog'; $base = '';
require_once(__DIR__ . '/includes/header.php');
require_once(__DIR__ . '/includes/db.php');

// Auto-seed blog posts on first run (mirrors gallery.php's seeding pattern)
if ($db->blog_posts->countDocuments() === 0) {
    require_once(__DIR__ . '/includes/blog-data.php');
    foreach ($blogSeed as $post) {
        $post['published_at'] = new MongoDB\BSON\UTCDateTime(strtotime($post['published_at']) * 1000);
        $post['created_at']   = new MongoDB\BSON\UTCDateTime();
        $db->blog_posts->insertOne($post);
    }
}

$posts = iterator_to_array($db->blog_posts->find([], ['sort' => ['published_at' => -1]]));
$articles = array_map(function ($p) {
    return [
        'slug'    => $p['slug'],
        'title'   => $p['title'],
        'excerpt' => $p['excerpt'],
        'tag'     => $p['tag'],
        'author'  => $p['author'],
        'img'     => $p['img'],
        'date'    => $p['published_at']->toDateTime()->format('j M Y'),
    ];
}, $posts);
?>
<section class="page-banner"><div class="container">
    <p class="breadcrumb"><a href="index.php">Home</a> <span>/</span> Blog</p>
    <h1>Articles & Insights</h1>
    <p>Thought leadership, technical deep dives, and industry analysis.</p>
</div></section>

<section class="section"><div class="container">
    <div class="grid-3">
    <?php foreach ($articles as $a): ?>
        <div class="blog-card">
            <div class="blog-card__img">
                <a href="blog-post.php?slug=<?= urlencode($a['slug']) ?>">
                    <img src="<?= htmlspecialchars($a['img']) ?>" alt="<?= htmlspecialchars($a['title']) ?>" loading="lazy">
                </a>
            </div>
            <div class="blog-card__body">
                <span class="blog-card__tag"><?= htmlspecialchars($a['tag']) ?></span>
                <div class="blog-card__meta">
                    <span><i class="fa fa-calendar"></i> <?= htmlspecialchars($a['date']) ?></span>
                    <span><i class="fa fa-user"></i> <?= htmlspecialchars($a['author']) ?></span>
                </div>
                <h3><a href="blog-post.php?slug=<?= urlencode($a['slug']) ?>" style="color:inherit;text-decoration:none;"><?= htmlspecialchars($a['title']) ?></a></h3>
                <p><?= htmlspecialchars($a['excerpt']) ?></p>
                <a href="blog-post.php?slug=<?= urlencode($a['slug']) ?>" class="btn btn-primary btn-sm">Read More</a>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
</div></section>
<?php require_once(__DIR__ . '/includes/footer.php'); ?>
