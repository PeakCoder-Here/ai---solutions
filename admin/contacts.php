<?php
/**
 * admin/contacts.php — Manage contact inquiries (contact.php submissions)
 */
$pageTitle  = 'Contact Inquiries';
$activePage = 'contacts';

require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../includes/db.php');

$notice = '';
$error  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete' && !empty($_POST['id'])) {
    try {
        $db->contact_inquiries->deleteOne(['_id' => new MongoDB\BSON\ObjectId($_POST['id'])]);
        header('Location: contacts.php?deleted=1');
        exit;
    } catch (Exception $e) {
        $error = 'Could not delete that inquiry.';
    }
}
if (isset($_GET['deleted'])) $notice = 'Inquiry deleted.';

$inquiries = iterator_to_array($db->contact_inquiries->find([], ['sort' => ['submitted_at' => -1]]));

require_once(__DIR__ . '/partials/head.php');
?>

<?php if ($notice): ?><div class="alert alert-success" style="margin-bottom:1.5rem;"><i class="fa fa-check-circle"></i> <?= htmlspecialchars($notice) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-error" style="margin-bottom:1.5rem;"><i class="fa fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div><?php endif; ?>

<div class="admin-card">
    <div class="admin-card__header">
        <div class="admin-card__title"><i class="fa fa-envelope"></i> Contact Inquiries (<?= count($inquiries) ?>)</div>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Name</th><th>Email</th><th>Company</th><th>Job Title</th><th>Message</th><th>Submitted</th><th style="text-align:right;">Actions</th></tr></thead>
            <tbody>
            <?php if (empty($inquiries)): ?>
                <tr><td colspan="7" style="text-align:center;color:var(--grey-text);padding:2.5rem;">No contact inquiries yet.</td></tr>
            <?php else: foreach ($inquiries as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['name'] ?? '') ?></td>
                    <td><a href="mailto:<?= htmlspecialchars($r['email'] ?? '') ?>"><?= htmlspecialchars($r['email'] ?? '') ?></a></td>
                    <td><?= htmlspecialchars($r['company'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['job_title'] ?? '') ?></td>
                    <td style="max-width:260px;white-space:normal;"><?= htmlspecialchars(mb_strimwidth($r['job_details'] ?? '', 0, 120, '…')) ?></td>
                    <td><?= $r['submitted_at']->toDateTime()->format('d M Y H:i') ?></td>
                    <td style="text-align:right;">
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this inquiry?');">
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
