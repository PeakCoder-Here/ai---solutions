<?php
/**
 * admin/manage-blog.php — Blog CMS (create / edit / delete blog_posts)
 */
$pageTitle  = 'Manage Blog';
$activePage = 'blog';

require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../includes/db.php');

$notice = '';
$error  = '';

function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-') ?: 'post-' . substr(md5((string) microtime()), 0, 8);
}

// ── Handle POST actions (save / delete) ──────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete' && !empty($_POST['id'])) {
        try {
            $db->blog_posts->deleteOne(['_id' => new MongoDB\BSON\ObjectId($_POST['id'])]);
            header('Location: manage-blog.php?deleted=1');
            exit;
        } catch (Exception $e) {
            $error = 'Could not delete that post.';
        }
    }

    if ($action === 'save') {
        $id      = $_POST['id'] ?? '';
        $title   = trim($_POST['title'] ?? '');
        $slug    = trim($_POST['slug'] ?? '') ?: slugify($title);
        $excerpt = trim($_POST['excerpt'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $tag     = trim($_POST['tag'] ?? '');
        $author  = trim($_POST['author'] ?? '');
        $img     = trim($_POST['img'] ?? '');
        $pubDate = trim($_POST['published_at'] ?? '');

        if (empty($title) || empty($excerpt) || empty($content) || empty($pubDate)) {
            $error = 'Title, excerpt, content, and publish date are all required.';
        } else {
            $doc = [
                'title'        => $title,
                'slug'         => $slug,
                'excerpt'      => $excerpt,
                'content'      => $content,
                'tag'          => $tag ?: 'General',
                'author'       => $author ?: ($_SESSION['admin_username'] ?? 'AI-Solutions Team'),
                'img'          => $img ?: 'https://images.unsplash.com/photo-1531746790731-6c087fecd65a?w=600&h=400&fit=crop&auto=format',
                'published_at' => new MongoDB\BSON\UTCDateTime(strtotime($pubDate) * 1000),
            ];

            try {
                if ($id) {
                    $db->blog_posts->updateOne(['_id' => new MongoDB\BSON\ObjectId($id)], ['$set' => $doc]);
                    header('Location: manage-blog.php?saved=1');
                } else {
                    $doc['created_at'] = new MongoDB\BSON\UTCDateTime();
                    $db->blog_posts->insertOne($doc);
                    header('Location: manage-blog.php?created=1');
                }
                exit;
            } catch (Exception $e) {
                $error = 'Could not save that post — check the slug isn\'t already in use.';
            }
        }
    }
}

if (isset($_GET['saved']))   $notice = 'Post updated.';
if (isset($_GET['created'])) $notice = 'Post created.';
if (isset($_GET['deleted'])) $notice = 'Post deleted.';

// ── Determine view mode: list / new / edit ────────────────────────────
$mode = 'list';
$editing = null;
if (isset($_GET['new'])) {
    $mode = 'form';
} elseif (!empty($_GET['edit'])) {
    $mode = 'form';
    $editing = $db->blog_posts->findOne(['_id' => new MongoDB\BSON\ObjectId($_GET['edit'])]);
}

$posts = iterator_to_array($db->blog_posts->find([], ['sort' => ['published_at' => -1]]));

require_once(__DIR__ . '/partials/head.php');
?>

<?php if ($notice): ?>
    <div class="alert alert-success" style="margin-bottom:1.5rem;"><i class="fa fa-check-circle"></i> <?= htmlspecialchars($notice) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-error" style="margin-bottom:1.5rem;"><i class="fa fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($mode === 'list'): ?>

    <div class="admin-card">
        <div class="admin-card__header">
            <div class="admin-card__title"><i class="fa fa-newspaper"></i> Blog Posts (<?= count($posts) ?>)</div>
            <a href="manage-blog.php?new=1" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> New Post</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Title</th><th>Tag</th><th>Author</th><th>Published</th><th style="text-align:right;">Actions</th></tr></thead>
                <tbody>
                <?php if (empty($posts)): ?>
                    <tr><td colspan="5" style="text-align:center;color:var(--grey-text);padding:2.5rem;">No blog posts yet.</td></tr>
                <?php else: foreach ($posts as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['title']) ?></td>
                        <td><span class="badge badge-blue"><?= htmlspecialchars($p['tag']) ?></span></td>
                        <td><?= htmlspecialchars($p['author']) ?></td>
                        <td><?= $p['published_at']->toDateTime()->format('d M Y') ?></td>
                        <td style="text-align:right;white-space:nowrap;">
                            <a href="../blog-post.php?slug=<?= urlencode($p['slug']) ?>" target="_blank" class="btn btn-outline btn-sm" title="View live"><i class="fa fa-eye"></i></a>
                            <a href="manage-blog.php?edit=<?= $p['_id'] ?>" class="btn btn-outline btn-sm" title="Edit"><i class="fa fa-pen"></i></a>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this post permanently?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $p['_id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php else: ?>

    <div class="admin-card">
        <div class="admin-card__header">
            <div class="admin-card__title">
                <i class="fa fa-pen"></i> <?= $editing ? 'Edit Post' : 'New Post' ?>
            </div>
            <a href="manage-blog.php" class="btn btn-outline btn-sm"><i class="fa fa-arrow-left"></i> Back to List</a>
        </div>

        <form method="POST">
            <input type="hidden" name="action" value="save">
            <?php if ($editing): ?><input type="hidden" name="id" value="<?= $editing['_id'] ?>"><?php endif; ?>

            <div class="form-row">
                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" class="form-control" required
                           value="<?= htmlspecialchars($editing['title'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="slug">URL Slug (auto-generated if left blank)</label>
                    <input type="text" id="slug" name="slug" class="form-control"
                           value="<?= htmlspecialchars($editing['slug'] ?? '') ?>" placeholder="my-article-title">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tag">Tag / Category</label>
                    <input type="text" id="tag" name="tag" class="form-control"
                           value="<?= htmlspecialchars($editing['tag'] ?? '') ?>" placeholder="AI Strategy">
                </div>
                <div class="form-group">
                    <label for="author">Author</label>
                    <input type="text" id="author" name="author" class="form-control"
                           value="<?= htmlspecialchars($editing['author'] ?? '') ?>" placeholder="AI-Solutions Team">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="published_at">Publish Date *</label>
                    <input type="date" id="published_at" name="published_at" class="form-control" required
                           value="<?= isset($editing['published_at']) ? $editing['published_at']->toDateTime()->format('Y-m-d') : date('Y-m-d') ?>">
                </div>
                <div class="form-group">
                    <label for="img">Cover Image URL</label>
                    <input type="text" id="img" name="img" class="form-control"
                           value="<?= htmlspecialchars($editing['img'] ?? '') ?>" placeholder="https://...">
                </div>
            </div>

            <div class="form-group">
                <label for="excerpt">Excerpt *</label>
                <textarea id="excerpt" name="excerpt" class="form-control" rows="2" required><?= htmlspecialchars($editing['excerpt'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="content">Full Article Content * <span style="font-weight:400;color:var(--grey-text);">(HTML supported, e.g. &lt;p&gt;...&lt;/p&gt;)</span></label>
                <textarea id="content" name="content" class="form-control" rows="12" required style="font-family:monospace;font-size:0.85rem;"><?= htmlspecialchars($editing['content'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fa fa-save"></i> <?= $editing ? 'Save Changes' : 'Publish Post' ?>
            </button>
        </form>
    </div>

<?php endif; ?>

<?php require_once(__DIR__ . '/partials/foot.php'); ?>
