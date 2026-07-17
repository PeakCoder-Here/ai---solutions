<?php
/**
 * blog-post.php — Single article view
 *
 * Reconstructed file — this was never actually built in this project
 * (confirmed against the D: backup copy, which also lacks it), even
 * though css/style.css already defines the full .blog-post__* styling
 * for it. Reads a post from the blog_posts collection by ?slug=.
 */
require_once(__DIR__ . '/includes/db.php');

$slug = trim($_GET['slug'] ?? '');
$post = $slug ? $db->blog_posts->findOne(['slug' => $slug]) : null;

$pageTitle   = $post ? $post['title'] : 'Article Not Found';
$currentPage = 'blog';
$base        = '';
require_once(__DIR__ . '/includes/header.php');

if (!$post): ?>
<section class="page-banner"><div class="container">
    <p class="breadcrumb"><a href="index.php">Home</a> <span>/</span> <a href="blog.php">Blog</a> <span>/</span> Not Found</p>
    <h1>Article Not Found</h1>
</div></section>
<section class="section"><div class="container text-center">
    <p style="margin-bottom:2rem;">We couldn't find the article you were looking for.</p>
    <a href="blog.php" class="btn btn-primary btn-lg">Back to Blog</a>
</div></section>
<?php else:
    // Prev/next navigation, ordered the same way blog.php lists posts
    $all  = iterator_to_array($db->blog_posts->find([], ['sort' => ['published_at' => -1]]));
    $ids  = array_map(fn($p) => (string) $p['_id'], $all);
    $pos  = array_search((string) $post['_id'], $ids, true);
    $prev = $pos !== false && isset($all[$pos + 1]) ? $all[$pos + 1] : null;
    $next = $pos !== false && $pos > 0 ? $all[$pos - 1] : null;
?>
<section class="page-banner"><div class="container">
    <p class="breadcrumb"><a href="index.php">Home</a> <span>/</span> <a href="blog.php">Blog</a> <span>/</span> <?= htmlspecialchars($post['title']) ?></p>
    <h1><?= htmlspecialchars($post['title']) ?></h1>
</div></section>

<section class="section"><div class="container">
    <div class="blog-post-layout">
        <div class="blog-post__hero">
            <img src="<?= htmlspecialchars($post['img']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
        </div>

        <div class="blog-post__body">
            <?= $post['content'] ?>
        </div>

        <div class="blog-post__author">
            <div class="admin-sidebar__avatar" style="background:linear-gradient(135deg,var(--blue),var(--navy));">
                <i class="fa fa-user"></i>
            </div>
            <div>
                <div style="font-weight:700;color:var(--navy);"><?= htmlspecialchars($post['author']) ?></div>
                <div style="font-size:0.85rem;color:var(--grey-text);">
                    <?= htmlspecialchars($post['tag']) ?> &middot; <?= $post['published_at']->toDateTime()->format('j F Y') ?>
                </div>
            </div>
        </div>

        <div class="blog-post__nav">
            <?php if ($prev): ?>
            <a href="blog-post.php?slug=<?= urlencode($prev['slug']) ?>" class="blog-post__nav-link">
                <span class="blog-post__nav-dir"><i class="fa fa-arrow-left"></i> Previous</span>
                <span class="blog-post__nav-title"><?= htmlspecialchars($prev['title']) ?></span>
            </a>
            <?php else: ?><div></div><?php endif; ?>

            <?php if ($next): ?>
            <a href="blog-post.php?slug=<?= urlencode($next['slug']) ?>" class="blog-post__nav-link blog-post__nav-link--next">
                <span class="blog-post__nav-dir">Next <i class="fa fa-arrow-right"></i></span>
                <span class="blog-post__nav-title"><?= htmlspecialchars($next['title']) ?></span>
            </a>
            <?php else: ?><div></div><?php endif; ?>
        </div>

        <div class="text-center">
            <a href="blog.php" class="btn btn-outline"><i class="fa fa-arrow-left"></i> Back to All Articles</a>
        </div>
    </div>
</div></section>
<?php endif; ?>

<?php require_once(__DIR__ . '/includes/footer.php'); ?>
