<?php
/**
 * admin/inquiries.php — Combined view across all submission types
 * (Orders + Demo Requests + Contact Inquiries + Event Registrations)
 * Read-only overview; manage/delete each type from its own page.
 */
$pageTitle  = 'All Inquiries';
$activePage = 'inquiries';

require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../includes/db.php');

$all = [];
foreach ($db->orders->find([], ['sort' => ['created_at' => -1]]) as $d)
    $all[] = ['type' => 'Order', 'name' => $d['customer_name'] ?? '', 'email' => $d['customer_email'] ?? '', 'detail' => 'Order #' . ($d['order_id'] ?? 'N/A'), 'date' => $d['created_at'], 'link' => 'orders.php'];
foreach ($db->demo_requests->find([], ['sort' => ['submitted_at' => -1]]) as $d)
    $all[] = ['type' => 'Demo', 'name' => $d['name'] ?? '', 'email' => $d['email'] ?? '', 'detail' => $d['interest_type'] ?? '', 'date' => $d['submitted_at'], 'link' => 'demos.php'];
foreach ($db->contact_inquiries->find([], ['sort' => ['submitted_at' => -1]]) as $d)
    $all[] = ['type' => 'Contact', 'name' => $d['name'] ?? '', 'email' => $d['email'] ?? '', 'detail' => $d['job_title'] ?? '', 'date' => $d['submitted_at'], 'link' => 'contacts.php'];
foreach ($db->event_registrations->find([], ['sort' => ['submitted_at' => -1]]) as $d)
    $all[] = ['type' => 'Event', 'name' => $d['name'] ?? '', 'email' => $d['email'] ?? '', 'detail' => $d['event_name'] ?? '', 'date' => $d['submitted_at'], 'link' => 'events-admin.php'];

usort($all, fn($a, $b) => $b['date']->toDateTime()->getTimestamp() - $a['date']->toDateTime()->getTimestamp());

require_once(__DIR__ . '/partials/head.php');
?>

<div class="admin-card">
    <div class="admin-card__header">
        <div class="admin-card__title"><i class="fa fa-chart-line"></i> All Inquiries (<?= count($all) ?>)</div>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Type</th><th>Name</th><th>Email</th><th>Detail</th><th>Date</th><th></th></tr></thead>
            <tbody>
            <?php if (empty($all)): ?>
                <tr><td colspan="6" style="text-align:center;color:var(--grey-text);padding:2.5rem;">No submissions yet. Data will appear here once forms are submitted.</td></tr>
            <?php else: foreach ($all as $r):
                $bc = $r['type']==='Demo' ? 'badge-blue' : ($r['type']==='Contact' ? 'badge-navy' : ($r['type']==='Order' ? 'badge-red' : 'badge-green'));
            ?>
                <tr>
                    <td><span class="badge <?= $bc ?>"><?= htmlspecialchars($r['type']) ?></span></td>
                    <td><?= htmlspecialchars($r['name']) ?></td>
                    <td><?= htmlspecialchars($r['email']) ?></td>
                    <td><?= htmlspecialchars($r['detail']) ?></td>
                    <td><?= $r['date']->toDateTime()->format('d M Y H:i') ?></td>
                    <td><a href="<?= $r['link'] ?>" class="btn btn-outline btn-sm">Manage</a></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once(__DIR__ . '/partials/foot.php'); ?>
