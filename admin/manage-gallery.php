<?php
/**
 * admin/manage-gallery.php — Gallery CMS (create / edit / delete gallery photos)
 */
$pageTitle  = 'Manage Gallery';
$activePage = 'gallery';

require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../includes/db.php');

$notice = '';
$error  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete' && !empty($_POST['id'])) {
        try {
            $db->gallery->deleteOne(['_id' => new MongoDB\BSON\ObjectId($_POST['id'])]);
            header('Location: manage-gallery.php?deleted=1');
            exit;
        } catch (Exception $e) {
            $error = 'Could not delete that photo.';
        }
    }

    if ($action === 'save') {
        $id        = $_POST['id'] ?? '';
        $title     = trim($_POST['title'] ?? '');
        $img       = trim($_POST['img'] ?? '');
        $sortOrder = (int) ($_POST['sort_order'] ?? 0);

        if (empty($title) || empty($img)) {
            $error = 'Title and image URL are both required.';
        } else {
            $doc = ['title' => $title, 'img' => $img, 'sort_order' => $sortOrder];
            try {
                if ($id) {
                    $db->gallery->updateOne(['_id' => new MongoDB\BSON\ObjectId($id)], ['$set' => $doc]);
                    header('Location: manage-gallery.php?saved=1');
                } else {
                    $doc['created_at'] = new MongoDB\BSON\UTCDateTime();
                    $db->gallery->insertOne($doc);
                    header('Location: manage-gallery.php?created=1');
                }
                exit;
            } catch (Exception $e) {
                $error = 'Could not save that photo.';
            }
        }
    }
}

if (isset($_GET['saved']))   $notice = 'Photo updated.';
if (isset($_GET['created'])) $notice = 'Photo added.';
if (isset($_GET['deleted'])) $notice = 'Photo deleted.';

$mode = 'list';
$editing = null;
if (isset($_GET['new'])) {
    $mode = 'form';
} elseif (!empty($_GET['edit'])) {
    $mode = 'form';
    $editing = $db->gallery->findOne(['_id' => new MongoDB\BSON\ObjectId($_GET['edit'])]);
}

$photos = iterator_to_array($db->gallery->find([], ['sort' => ['sort_order' => 1, 'created_at' => 1]]));

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
            <div class="admin-card__title"><i class="fa fa-images"></i> Gallery Photos (<?= count($photos) ?>)</div>
            <a href="manage-gallery.php?new=1" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Photo</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Preview</th><th>Title</th><th>Sort Order</th><th style="text-align:right;">Actions</th></tr></thead>
                <tbody>
                <?php if (empty($photos)): ?>
                    <tr><td colspan="4" style="text-align:center;color:var(--grey-text);padding:2.5rem;">No photos yet.</td></tr>
                <?php else: foreach ($photos as $p): ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($p['img']) ?>" alt="" style="width:64px;height:44px;object-fit:cover;border-radius:6px;"></td>
                        <td><?= htmlspecialchars($p['title']) ?></td>
                        <td><?= (int) ($p['sort_order'] ?? 0) ?></td>
                        <td style="text-align:right;white-space:nowrap;">
                            <a href="manage-gallery.php?edit=<?= $p['_id'] ?>" class="btn btn-outline btn-sm" title="Edit"><i class="fa fa-pen"></i></a>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this photo permanently?');">
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
            <div class="admin-card__title"><i class="fa fa-pen"></i> <?= $editing ? 'Edit Photo' : 'Add Photo' ?></div>
            <a href="manage-gallery.php" class="btn btn-outline btn-sm"><i class="fa fa-arrow-left"></i> Back to List</a>
        </div>

        <form method="POST">
            <input type="hidden" name="action" value="save">
            <?php if ($editing): ?><input type="hidden" name="id" value="<?= $editing['_id'] ?>"><?php endif; ?>

            <div class="form-group">
                <label for="title">Title *</label>
                <input type="text" id="title" name="title" class="form-control" required
                       value="<?= htmlspecialchars($editing['title'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="img">Image URL *</label>
                <input type="text" id="img" name="img" class="form-control" required
                       value="<?= htmlspecialchars($editing['img'] ?? '') ?>" placeholder="https://...">
            </div>

            <div class="form-group">
                <label for="sort_order">Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" class="form-control"
                       value="<?= (int) ($editing['sort_order'] ?? 0) ?>">
            </div>

            <?php if (!empty($editing['img'])): ?>
                <img src="<?= htmlspecialchars($editing['img']) ?>" alt="" style="max-width:240px;border-radius:8px;margin-bottom:1rem;display:block;">
            <?php endif; ?>

            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fa fa-save"></i> <?= $editing ? 'Save Changes' : 'Add Photo' ?>
            </button>
        </form>
    </div>

<?php endif; ?>

<?php require_once(__DIR__ . '/partials/foot.php'); ?>
