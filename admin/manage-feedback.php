<?php
/**
 * admin/manage-feedback.php — Moderate customer feedback submissions
 * (approve / reject / delete testimonials submitted via feedback.php)
 */
$pageTitle  = 'Manage Feedback';
$activePage = 'manage-feedback';

require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../includes/db.php');

$notice = '';
$error  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id     = $_POST['id'] ?? '';

    if ($id && in_array($action, ['approve', 'reject', 'delete'], true)) {
        try {
            $oid = new MongoDB\BSON\ObjectId($id);
            if ($action === 'delete') {
                $db->feedbacks->deleteOne(['_id' => $oid]);
                header('Location: manage-feedback.php?deleted=1');
            } else {
                $status = $action === 'approve' ? 'approved' : 'rejected';
                $db->feedbacks->updateOne(['_id' => $oid], ['$set' => ['status' => $status]]);
                header('Location: manage-feedback.php?updated=1');
            }
            exit;
        } catch (Exception $e) {
            $error = 'Could not update that feedback entry.';
        }
    }
}

if (isset($_GET['updated'])) $notice = 'Feedback status updated.';
if (isset($_GET['deleted'])) $notice = 'Feedback deleted.';

$filter = $_GET['status'] ?? 'pending';
$query = in_array($filter, ['pending', 'approved', 'rejected'], true) ? ['status' => $filter] : [];
$items = iterator_to_array($db->feedbacks->find($query, ['sort' => ['created_at' => -1]]));

$counts = [
    'pending'  => $db->feedbacks->countDocuments(['status' => 'pending']),
    'approved' => $db->feedbacks->countDocuments(['status' => 'approved']),
    'rejected' => $db->feedbacks->countDocuments(['status' => 'rejected']),
];

require_once(__DIR__ . '/partials/head.php');
?>

<?php if ($notice): ?>
    <div class="alert alert-success" style="margin-bottom:1.5rem;"><i class="fa fa-check-circle"></i> <?= htmlspecialchars($notice) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-error" style="margin-bottom:1.5rem;"><i class="fa fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="admin-card">
    <div class="admin-card__header">
        <div class="admin-card__title"><i class="fa fa-star"></i> Customer Feedback</div>
        <div style="display:flex;gap:.4rem;">
            <a href="?status=pending"  class="btn btn-sm <?= $filter==='pending'  ? 'btn-primary' : 'btn-outline' ?>">Pending (<?= $counts['pending'] ?>)</a>
            <a href="?status=approved" class="btn btn-sm <?= $filter==='approved' ? 'btn-primary' : 'btn-outline' ?>">Approved (<?= $counts['approved'] ?>)</a>
            <a href="?status=rejected" class="btn btn-sm <?= $filter==='rejected' ? 'btn-primary' : 'btn-outline' ?>">Rejected (<?= $counts['rejected'] ?>)</a>
        </div>
    </div>

    <?php if (empty($items)): ?>
        <p style="text-align:center;color:var(--grey-text);padding:2.5rem;">No <?= htmlspecialchars($filter) ?> feedback.</p>
    <?php else: ?>
    <div style="display:grid;gap:1rem;">
        <?php foreach ($items as $f): ?>
        <div style="border:1px solid var(--grey);border-radius:var(--radius-lg);padding:1.25rem;background:var(--white);">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap;">
                <div>
                    <strong><?= htmlspecialchars($f['name'] ?? '') ?></strong>
                    <?php if (!empty($f['role']) || !empty($f['company'])): ?>
                    <span style="color:var(--grey-text);font-size:.85rem;"> — <?= htmlspecialchars(trim(($f['role'] ?? '') . ' at ' . ($f['company'] ?? ''), ' at ')) ?></span>
                    <?php endif; ?>
                    <div style="color:#F59E0B;font-size:.85rem;margin-top:2px;">
                        <?= str_repeat('★', (int)($f['stars'] ?? 0)) . str_repeat('☆', 5 - (int)($f['stars'] ?? 0)) ?>
                    </div>
                </div>
                <div style="font-size:.78rem;color:var(--grey-text);white-space:nowrap;">
                    <?= isset($f['created_at']) ? htmlspecialchars($f['created_at']->toDateTime()->format('d M Y, H:i')) : '' ?>
                </div>
            </div>
            <p style="margin:.75rem 0;line-height:1.5;"><?= nl2br(htmlspecialchars($f['text'] ?? '')) ?></p>
            <?php if (!empty($f['email'])): ?>
            <p style="font-size:.78rem;color:var(--grey-text);margin-bottom:.75rem;"><i class="fa fa-envelope"></i> <?= htmlspecialchars($f['email']) ?></p>
            <?php endif; ?>
            <div style="display:flex;gap:.5rem;">
                <?php if (($f['status'] ?? '') !== 'approved'): ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="approve">
                    <input type="hidden" name="id" value="<?= $f['_id'] ?>">
                    <button type="submit" class="btn btn-sm" style="background:#DCFCE7;color:#16A34A;"><i class="fa fa-check"></i> Approve</button>
                </form>
                <?php endif; ?>
                <?php if (($f['status'] ?? '') !== 'rejected'): ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="reject">
                    <input type="hidden" name="id" value="<?= $f['_id'] ?>">
                    <button type="submit" class="btn btn-sm" style="background:#FEF3C7;color:#B45309;"><i class="fa fa-eye-slash"></i> Reject</button>
                </form>
                <?php endif; ?>
                <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this feedback permanently?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $f['_id'] ?>">
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php require_once(__DIR__ . '/partials/foot.php'); ?>
