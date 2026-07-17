<?php
$pageTitle = 'Invoice'; $currentPage = ''; $base = '';
require_once(__DIR__ . '/includes/header.php');
require_once(__DIR__ . '/includes/db.php');

$order = null;
if (!empty($_GET['order_id'])) {
    $order = $db->orders->findOne(['order_id' => $_GET['order_id']]);
}
?>
<style>
@media print {
    .site-header, .topbar, .site-footer, #ab-wrap, #aw, .no-print { display: none !important; }
    body { background: #fff !important; }
}
.invoice-box { max-width: 720px; margin: 2rem auto; background: #fff; border: 1px solid var(--grey); border-radius: var(--radius-lg); padding: 2.5rem; box-shadow: var(--shadow-sm); }
.invoice-head { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px solid #4F46E5; padding-bottom: 1.5rem; margin-bottom: 1.5rem; }
.invoice-head h1 { margin: 0 0 .25rem; font-size: 1.5rem; }
.invoice-meta { text-align: right; font-size: .85rem; color: var(--grey-text); }
.invoice-parties { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem; }
.invoice-parties h4 { font-size: .72rem; text-transform: uppercase; letter-spacing: .05em; color: #4F46E5; margin-bottom: .4rem; }
.invoice-table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; }
.invoice-table th { text-align: left; font-size: .75rem; text-transform: uppercase; color: var(--grey-text); border-bottom: 2px solid var(--grey); padding: .5rem; }
.invoice-table td { padding: .6rem .5rem; border-bottom: 1px solid var(--grey-light); font-size: .92rem; }
.invoice-table td:last-child, .invoice-table th:last-child { text-align: right; }
.invoice-totals { margin-left: auto; width: 260px; }
.invoice-totals div { display: flex; justify-content: space-between; padding: .3rem 0; font-size: .92rem; }
.invoice-totals .grand { font-weight: 700; font-size: 1.1rem; border-top: 2px solid var(--grey); padding-top: .6rem; margin-top: .3rem; }
</style>

<section class="section"><div class="container">
    <?php if (!$order): ?>
    <div style="text-align:center;padding:3rem;">
        <i class="fa fa-file-invoice" style="font-size:3rem;color:var(--grey);margin-bottom:1rem;display:block;"></i>
        <h2>Invoice not found</h2>
        <a href="index.php" class="btn btn-primary" style="margin-top:1rem;">Back to Home</a>
    </div>
    <?php else: ?>
    <div class="invoice-box">
        <div class="invoice-head">
            <div>
                <h1><i class="fa fa-robot"></i> AI-Solutions</h1>
                <p style="margin:0;font-size:.85rem;color:var(--grey-text);">Sunderland, UK<br>info@ai-solutions.co.uk</p>
            </div>
            <div class="invoice-meta">
                <strong style="font-size:1.1rem;color:var(--navy);">INVOICE</strong><br>
                #<?= htmlspecialchars($order['order_id']) ?><br>
                <?= isset($order['created_at']) ? htmlspecialchars($order['created_at']->toDateTime()->format('d M Y')) : '' ?>
            </div>
        </div>

        <div class="invoice-parties">
            <div>
                <h4>Billed To</h4>
                <strong><?= htmlspecialchars($order['customer_name']) ?></strong><br>
                <?php if (!empty($order['company'])): ?><?= htmlspecialchars($order['company']) ?><br><?php endif; ?>
                <?= htmlspecialchars($order['customer_email']) ?><br>
                <span style="white-space:pre-wrap;"><?= htmlspecialchars($order['billing_address']) ?></span>
            </div>
            <div>
                <h4>Payment Details</h4>
                <strong>Plan:</strong> <?= htmlspecialchars($order['plan']) ?><br>
                <strong>Billing:</strong> <?= htmlspecialchars($order['billing_cycle']) ?><br>
                <strong>Method:</strong> <?= htmlspecialchars($order['payment_method']) ?><br>
                <strong>Status:</strong> <?= htmlspecialchars($order['order_status']) ?>
            </div>
        </div>

        <table class="invoice-table">
            <thead><tr><th>Description</th><th>Amount</th></tr></thead>
            <tbody>
            <?php foreach ($order['line_items'] ?? [] as $li): ?>
                <tr><td><?= htmlspecialchars($li['label'] ?? '') ?></td><td>£<?= number_format((float)($li['amount'] ?? 0), 2) ?></td></tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="invoice-totals">
            <div><span>Subtotal</span><span>£<?= number_format((float)($order['subtotal'] ?? 0), 2) ?></span></div>
            <?php if (!empty($order['discount'])): ?>
            <div style="color:#16A34A;"><span>Annual discount</span><span>-£<?= number_format((float)$order['discount'], 2) ?></span></div>
            <?php endif; ?>
            <div class="grand"><span><?= htmlspecialchars($order['billing_cycle']) ?> Total</span><span>£<?= number_format((float)($order['grand_total'] ?? 0), 2) ?></span></div>
        </div>

        <p style="text-align:center;font-size:.8rem;color:var(--grey-text);margin-top:2rem;">Thank you for your business. Questions? Contact support@ai-solutions.co.uk</p>

        <div class="no-print" style="text-align:center;margin-top:1.5rem;">
            <button onclick="window.print()" class="btn btn-primary"><i class="fa fa-print"></i> Print / Save as PDF</button>
        </div>
    </div>
    <?php endif; ?>
</div></section>
<?php require_once(__DIR__ . '/includes/footer.php'); ?>
