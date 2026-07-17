<?php
/**
 * admin/demos.php — Manage demo requests (schedule-demo.php submissions)
 */
$pageTitle  = 'Demo Requests';
$activePage = 'demos';

require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../includes/db.php');

$notice = '';
$error  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete' && !empty($_POST['id'])) {
    try {
        $db->demo_requests->deleteOne(['_id' => new MongoDB\BSON\ObjectId($_POST['id'])]);
        header('Location: demos.php?deleted=1');
        exit;
    } catch (Exception $e) {
        $error = 'Could not delete that request.';
    }
}
if (isset($_GET['deleted'])) $notice = 'Demo request deleted.';

$requests = iterator_to_array($db->demo_requests->find([], ['sort' => ['submitted_at' => -1]]));

require_once(__DIR__ . '/partials/head.php');
?>

<?php if ($notice): ?><div class="alert alert-success" style="margin-bottom:1.5rem;"><i class="fa fa-check-circle"></i> <?= htmlspecialchars($notice) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-error" style="margin-bottom:1.5rem;"><i class="fa fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div><?php endif; ?>

<div class="admin-card">
    <div class="admin-card__header">
        <div class="admin-card__title"><i class="fa fa-calendar-check"></i> Demo Requests (<?= count($requests) ?>)</div>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Name</th><th>Email</th><th>Company</th><th>Interest</th><th>Country</th><th>Submitted</th><th style="text-align:right;">Actions</th></tr></thead>
            <tbody>
            <?php if (empty($requests)): ?>
                <tr><td colspan="7" style="text-align:center;color:var(--grey-text);padding:2.5rem;">No demo requests yet.</td></tr>
            <?php else: foreach ($requests as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['name'] ?? '') ?></td>
                    <td><a href="mailto:<?= htmlspecialchars($r['email'] ?? '') ?>"><?= htmlspecialchars($r['email'] ?? '') ?></a></td>
                    <td><?= htmlspecialchars($r['company'] ?? '') ?></td>
                    <td><span class="badge badge-blue"><?= htmlspecialchars($r['interest_type'] ?? '') ?></span></td>
                    <td><?= htmlspecialchars($r['country'] ?? '') ?></td>
                    <td><?= $r['submitted_at']->toDateTime()->format('d M Y H:i') ?></td>
                    <td style="text-align:right;">
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this request?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $r['_id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once(__DIR__ . '/partials/foot.php'); ?>
