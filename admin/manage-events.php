<?php
/**
 * admin/manage-events.php — Events CMS (create / edit / delete events shown on events.php)
 */
$pageTitle  = 'Manage Events';
$activePage = 'manage-events';

require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../includes/db.php');

$notice = '';
$error  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete' && !empty($_POST['id'])) {
        try {
            $db->events->deleteOne(['_id' => new MongoDB\BSON\ObjectId($_POST['id'])]);
            header('Location: manage-events.php?deleted=1');
            exit;
        } catch (Exception $e) {
            $error = 'Could not delete that event.';
        }
    }

    if ($action === 'save') {
        $id          = $_POST['id'] ?? '';
        $title       = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $day         = trim($_POST['day'] ?? '');
        $month       = trim($_POST['month'] ?? '');
        $location    = trim($_POST['location'] ?? '');
        $time        = trim($_POST['time'] ?? '');
        $img         = trim($_POST['img'] ?? '');
        $sortOrder   = (int) ($_POST['sort_order'] ?? 0);

        if (empty($title) || empty($day) || empty($month)) {
            $error = 'Title, day and month are required.';
        } else {
            $doc = [
                'title'       => $title,
                'description' => $description,
                'day'         => $day,
                'month'       => $month,
                'location'    => $location,
                'time'        => $time,
                'img'         => $img,
                'sort_order'  => $sortOrder,
            ];
            try {
                if ($id) {
                    $db->events->updateOne(['_id' => new MongoDB\BSON\ObjectId($id)], ['$set' => $doc]);
                    header('Location: manage-events.php?saved=1');
                } else {
                    $doc['created_at'] = new MongoDB\BSON\UTCDateTime();
                    $db->events->insertOne($doc);
                    header('Location: manage-events.php?created=1');
                }
                exit;
            } catch (Exception $e) {
                $error = 'Could not save that event.';
            }
        }
    }
}

if (isset($_GET['saved']))   $notice = 'Event updated.';
if (isset($_GET['created'])) $notice = 'Event added.';
if (isset($_GET['deleted'])) $notice = 'Event deleted.';

$mode = 'list';
$editing = null;
if (isset($_GET['new'])) {
    $mode = 'form';
} elseif (!empty($_GET['edit'])) {
    $mode = 'form';
    $editing = $db->events->findOne(['_id' => new MongoDB\BSON\ObjectId($_GET['edit'])]);
}

$events = iterator_to_array($db->events->find([], ['sort' => ['sort_order' => 1, 'created_at' => 1]]));

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
            <div class="admin-card__title"><i class="fa fa-calendar-plus"></i> Events (<?= count($events) ?>)</div>
            <a href="manage-events.php?new=1" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Event</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Preview</th><th>Title</th><th>Date</th><th>Status</th><th>Location</th><th>Sort Order</th><th style="text-align:right;">Actions</th></tr></thead>
                <tbody>
                <?php if (empty($events)): ?>
                    <tr><td colspan="7" style="text-align:center;color:var(--grey-text);padding:2.5rem;">No events yet.</td></tr>
                <?php else: $__today = new DateTime('today'); foreach ($events as $e): ?>
                    <?php
                        $__day = (int)($e['day'] ?? 0); $__month = (string)($e['month'] ?? '');
                        $__d = ($__day && $__month) ? DateTime::createFromFormat('j M Y', "{$__day} {$__month} " . $__today->format('Y')) : false;
                        if ($__d === false) { $__statusLabel = '—'; $__statusColor = 'var(--grey-text)'; }
                        elseif ($__d->format('Y-m-d') === $__today->format('Y-m-d')) { $__statusLabel = 'Today'; $__statusColor = '#16A34A'; }
                        elseif ($__d > $__today) { $__statusLabel = 'Upcoming'; $__statusColor = '#4F46E5'; }
                        else { $__statusLabel = 'Past'; $__statusColor = 'var(--grey-text)'; }
                    ?>
                    <tr>
                        <td><?php if (!empty($e['img'])): ?><img src="<?= htmlspecialchars($e['img']) ?>" alt="" style="width:64px;height:44px;object-fit:cover;border-radius:6px;"><?php endif; ?></td>
                        <td><?= htmlspecialchars($e['title']) ?></td>
                        <td><?= htmlspecialchars(trim(($e['day'] ?? '') . ' ' . ($e['month'] ?? ''))) ?></td>
                        <td><span style="font-size:.75rem;font-weight:600;color:<?= $__statusColor ?>;"><?= $__statusLabel ?></span></td>
                        <td><?= htmlspecialchars($e['location'] ?? '') ?></td>
                        <td><?= (int) ($e['sort_order'] ?? 0) ?></td>
                        <td style="text-align:right;white-space:nowrap;">
                            <a href="manage-events.php?edit=<?= $e['_id'] ?>" class="btn btn-outline btn-sm" title="Edit"><i class="fa fa-pen"></i></a>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this event permanently?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $e['_id'] ?>">
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
            <div class="admin-card__title"><i class="fa fa-pen"></i> <?= $editing ? 'Edit Event' : 'Add Event' ?></div>
            <a href="manage-events.php" class="btn btn-outline btn-sm"><i class="fa fa-arrow-left"></i> Back to List</a>
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
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" rows="3"><?= htmlspecialchars($editing['description'] ?? '') ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="day">Day *</label>
                    <input type="text" id="day" name="day" class="form-control" required placeholder="e.g. 10"
                           value="<?= htmlspecialchars($editing['day'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="month">Month *</label>
                    <input type="text" id="month" name="month" class="form-control" required placeholder="e.g. Jun"
                           value="<?= htmlspecialchars($editing['month'] ?? '') ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" class="form-control"
                           value="<?= htmlspecialchars($editing['location'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="time">Time</label>
                    <input type="text" id="time" name="time" class="form-control" placeholder="e.g. 9:00 AM – 6:00 PM"
                           value="<?= htmlspecialchars($editing['time'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="img">Image URL</label>
                <input type="text" id="img" name="img" class="form-control"
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
                <i class="fa fa-save"></i> <?= $editing ? 'Save Changes' : 'Add Event' ?>
            </button>
        </form>
    </div>

<?php endif; ?>

<?php require_once(__DIR__ . '/partials/foot.php'); ?>
