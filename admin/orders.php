<?php
/**
 * admin/orders.php — Orders placed via checkout.php
 */
$pageTitle  = 'Orders';
$activePage = 'orders';

require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../includes/db.php');

$orders = iterator_to_array($db->orders->find([], ['sort' => ['created_at' => -1]]));
$totalRevenue = 0.0;
foreach ($orders as $o) { $totalRevenue += (float)($o['grand_total'] ?? 0); }

require_once(__DIR__ . '/partials/head.php');
?>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem;">
    <div class="admin-card" style="padding:1.25rem;">
        <div style="font-size:.78rem;color:var(--grey-text);">Total Orders</div>
        <div style="font-size:1.6rem;font-weight:700;"><?= count($orders) ?></div>
    </div>
    <div class="admin-card" style="padding:1.25rem;">
        <div style="font-size:.78rem;color:var(--grey-text);">Total Revenue</div>
        <div style="font-size:1.6rem;font-weight:700;">£<?= number_format($totalRevenue, 0) ?></div>
    </div>
</div>

<?php if (empty($orders)): ?>
<div class="admin-card">
    <div class="admin-card__header">
        <div class="admin-card__title"><i class="fa fa-shopping-cart"></i> Orders (0)</div>
    </div>
    <div style="text-align:center;padding:3rem;color:var(--grey-text);">
        <i class="fa fa-shopping-cart" style="font-size:3rem;margin-bottom:1rem;display:block;color:var(--grey);"></i>
        <h3 style="color:var(--grey-text);">No orders yet</h3>
        <p>Orders placed through the Solutions page checkout will appear here.</p>
    </div>
</div>
<?php else: ?>
<div class="admin-card">
    <div class="admin-card__header">
        <div class="admin-card__title"><i class="fa fa-shopping-cart"></i> Orders (<?= count($orders) ?>)</div>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Order #</th><th>Customer</th><th>Email</th><th>Plan</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
            <?php foreach ($orders as $o): ?>
                <tr>
                    <td><?= htmlspecialchars($o['order_id'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($o['customer_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($o['customer_email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($o['plan'] ?? '') ?> <span style="color:var(--grey-text);font-size:.78rem;">(<?= htmlspecialchars($o['billing_cycle'] ?? '') ?>)</span></td>
                    <td>£<?= number_format((float)($o['grand_total'] ?? 0), 0) ?></td>
                    <td><?= htmlspecialchars($o['payment_method'] ?? '') ?></td>
                    <td><span class="badge badge-blue"><?= htmlspecialchars($o['order_status'] ?? '') ?></span></td>
                    <td><?= isset($o['created_at']) ? $o['created_at']->toDateTime()->format('d M Y H:i') : '' ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php require_once(__DIR__ . '/partials/foot.php'); ?>
