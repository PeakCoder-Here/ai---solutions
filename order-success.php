<?php
$pageTitle = 'Order Confirmed'; $currentPage = 'solutions'; $base = '';
require_once(__DIR__ . '/includes/header.php');
require_once(__DIR__ . '/includes/db.php');

$order = null;
if (!empty($_GET['order_id'])) {
    $order = $db->orders->findOne(['order_id' => $_GET['order_id']]);
}
?>
<section class="page-banner"><div class="container">
    <p class="breadcrumb"><a href="index.php">Home</a> <span>/</span> Order Confirmed</p>
    <h1>Order Confirmed</h1>
</div></section>

<section class="section"><div class="container" style="max-width:600px;">
    <?php if (!$order): ?>
    <div style="text-align:center;padding:2rem;">
        <i class="fa fa-circle-question" style="font-size:3rem;color:var(--grey);margin-bottom:1rem;display:block;"></i>
        <h2>Order not found</h2>
        <a href="solutions.php" class="btn btn-primary" style="margin-top:1rem;">Back to Solutions</a>
    </div>
    <?php else: ?>
    <div class="card" style="padding:2.5rem;text-align:center;">
        <i class="fa fa-circle-check" style="font-size:3.5rem;color:#16A34A;margin-bottom:1rem;display:block;"></i>
        <h2 style="margin-top:0;">Thank you, <?= htmlspecialchars($order['customer_name']) ?>!</h2>
        <p style="color:var(--grey-text);">Your order <strong><?= htmlspecialchars($order['order_id']) ?></strong> has been received.</p>
        <div style="text-align:left;background:var(--grey-light);border-radius:var(--radius-lg);padding:1.5rem;margin:1.5rem 0;">
            <p><strong>Plan:</strong> <?= htmlspecialchars($order['plan']) ?></p>
            <p><strong>Billing:</strong> <?= htmlspecialchars($order['billing_cycle']) ?></p>
            <p><strong>Total:</strong> £<?= number_format((float)$order['grand_total'], 0) ?></p>
            <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
            <p style="margin-bottom:0;"><strong>Status:</strong> <?= htmlspecialchars($order['order_status']) ?></p>
        </div>
        <p style="font-size:.9rem;color:var(--grey-text);">A confirmation email has been sent to <?= htmlspecialchars($order['customer_email']) ?>. Our team will be in touch shortly.</p>
        <a href="index.php" class="btn btn-primary" style="margin-top:1rem;">Back to Home</a>
    </div>
    <?php endif; ?>
</div></section>
<?php require_once(__DIR__ . '/includes/footer.php'); ?>
