<?php
$pageTitle = 'Checkout'; $currentPage = 'solutions'; $base = '';
// config.php only (not header.php) so we can still send a redirect header
// below if the order submits successfully — header.php echoes HTML.
require_once(__DIR__ . '/includes/config.php');
require_once(__DIR__ . '/includes/db.php');
require_once(__DIR__ . '/includes/queue_manager.php');

// ── Re-derive plan + price server-side (never trust a client-supplied total) ─
$Q_LABEL = ['500/day','1,000/day','2,000/day','5,000/day','10,000/day','Unlimited'];
$Q_PRICE = [0, 50, 100, 150, 200, 300];

$users   = max(1, (int)($_GET['users'] ?? 25));
$qIdx    = max(0, min(5, (int)($_GET['q'] ?? 0)));
$annual  = !empty($_GET['annual']);
$slack   = !empty($_GET['slack']);
$teams   = !empty($_GET['teams']);
$brand   = !empty($_GET['brand']);
$analy   = !empty($_GET['analy']);
$api     = !empty($_GET['api']);
$train   = !empty($_GET['train']);
$erp     = !empty($_GET['erp']);
$support = !empty($_GET['support']);

if ($users >= 200 || ($api && $erp)) {
    // Enterprise tier has no self-serve checkout — send them to sales.
    header('Location: contact.php');
    exit;
}

$base_    = 299;
$extraU   = max(0, $users - 25) * 6;
$qCost    = $Q_PRICE[$qIdx];
$slackC   = $slack   ? 79  : 0;
$teamsC   = $teams   ? 79  : 0;
$brandC   = $brand   ? 49  : 0;
$analyC   = $analy   ? 79  : 0;
$apiC     = $api     ? 149 : 0;
$trainC   = $train   ? 199 : 0;
$erpC     = $erp     ? 249 : 0;
$suppC    = $support ? 199 : 0;
$subtotal = $base_ + $extraU + $qCost + $slackC + $teamsC + $brandC + $analyC + $apiC + $trainC + $erpC + $suppC;
$discount = $annual ? round($subtotal * 0.15) : 0;
$monthlyTotal = $subtotal - $discount;

$tier = 'Starter';
if ($users > 25 || $qIdx > 0 || $slack || $teams || $brand || $analy) $tier = 'Business';
if ($api || $train || $erp || $support) $tier = 'Enterprise';

$lineItems = [['label' => "AI Virtual Assistant — {$tier} (base, 25 users)", 'amount' => $base_]];
if ($extraU)  $lineItems[] = ['label' => "Extra users ({$users}-25 × £6/mo)", 'amount' => $extraU];
if ($qCost)   $lineItems[] = ['label' => 'Queries: ' . $Q_LABEL[$qIdx], 'amount' => $qCost];
if ($slackC)  $lineItems[] = ['label' => 'Slack integration', 'amount' => $slackC];
if ($teamsC)  $lineItems[] = ['label' => 'Microsoft Teams', 'amount' => $teamsC];
if ($brandC)  $lineItems[] = ['label' => 'Custom branding', 'amount' => $brandC];
if ($analyC)  $lineItems[] = ['label' => 'Advanced analytics', 'amount' => $analyC];
if ($apiC)    $lineItems[] = ['label' => 'API access', 'amount' => $apiC];
if ($trainC)  $lineItems[] = ['label' => 'Custom AI training', 'amount' => $trainC];
if ($erpC)    $lineItems[] = ['label' => 'ERP / CRM integration', 'amount' => $erpC];
if ($suppC)   $lineItems[] = ['label' => 'Dedicated support', 'amount' => $suppC];

$billingCycle = $annual ? 'Annual' : 'Monthly';
$grandTotal   = $annual ? $monthlyTotal * 12 : $monthlyTotal;

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf'] ?? '')) {
        $errors[] = 'Invalid request. Please refresh and try again.';
    } else {
        $name    = trim($_POST['name'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $company = trim($_POST['company'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $payment = trim($_POST['payment_method'] ?? '');

        if (!$name) $errors[] = 'Full name is required.';
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
        if (!$address) $errors[] = 'Billing address is required.';
        if (!in_array($payment, ['Credit Card', 'PayPal', 'Bank Transfer'], true)) $errors[] = 'Please choose a payment method.';

        if (!$errors) {
            $orderId = 'ORD-' . date('ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
            $order = [
                'order_id'       => $orderId,
                'customer_name'  => $name,
                'customer_email' => $email,
                'company'        => $company,
                'plan'           => $tier,
                'users'          => $users,
                'billing_cycle'  => $billingCycle,
                'line_items'     => $lineItems,
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'grand_total'    => $grandTotal,
                'payment_method' => $payment,
                'billing_address'=> $address,
                'order_status'   => 'Processing',
                'created_at'     => new MongoDB\BSON\UTCDateTime(),
            ];
            $db->orders->insertOne($order);

            QueueManager::queue_order_confirmation($order, $orderId);
            QueueManager::queue_admin_notification('order', $order, $orderId);

            header('Location: order-success.php?order_id=' . urlencode($orderId));
            exit;
        }
    }
}

require_once(__DIR__ . '/includes/header.php');
?>
<section class="page-banner"><div class="container">
    <p class="breadcrumb"><a href="index.php">Home</a> <span>/</span> <a href="solutions.php">Solutions</a> <span>/</span> Checkout</p>
    <h1>Checkout</h1>
    <p>Review your plan and complete your order.</p>
</div></section>

<section class="section"><div class="container">
    <?php if ($errors): ?>
    <div class="alert alert-error" style="margin-bottom:1.5rem;">
        <i class="fa fa-exclamation-circle"></i>
        <ul style="margin:0;padding-left:1.2rem;">
            <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:1.2fr 1fr;gap:2rem;align-items:start;">
        <div class="card" style="padding:2rem;">
            <h2 style="margin-top:0;">Billing Details</h2>
            <form method="POST" action="">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                <div class="form-group">
                    <label for="name">Full Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address <span class="required">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="company">Company</label>
                    <input type="text" id="company" name="company" class="form-control" value="<?= htmlspecialchars($_POST['company'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="address">Billing Address <span class="required">*</span></label>
                    <textarea id="address" name="address" class="form-control" rows="3" required><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label for="payment_method">Payment Method <span class="required">*</span></label>
                    <select id="payment_method" name="payment_method" class="form-control" required>
                        <option value="">Select a payment method</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="PayPal">PayPal</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                    </select>
                </div>
                <p style="font-size:.8rem;color:var(--grey-text);margin-bottom:1.25rem;">
                    <i class="fa fa-circle-info"></i> This is a demonstration checkout — no real payment is processed.
                </p>
                <button type="submit" class="btn btn-primary btn-lg" style="width:100%;">
                    <i class="fa fa-lock"></i> Confirm Order — £<?= number_format($grandTotal, 0) ?>
                </button>
            </form>
        </div>

        <div class="card" style="padding:2rem;background:var(--grey-light);">
            <h2 style="margin-top:0;"><?= htmlspecialchars($tier) ?> Plan</h2>
            <?php foreach ($lineItems as $li): ?>
            <div style="display:flex;justify-content:space-between;padding:.4rem 0;font-size:.9rem;">
                <span><?= htmlspecialchars($li['label']) ?></span><span>£<?= number_format($li['amount'], 0) ?></span>
            </div>
            <?php endforeach; ?>
            <?php if ($discount): ?>
            <div style="display:flex;justify-content:space-between;padding:.4rem 0;font-size:.9rem;color:#16A34A;">
                <span>Annual discount (15%)</span><span>-£<?= number_format($discount, 0) ?></span>
            </div>
            <?php endif; ?>
            <hr style="border:none;border-top:1px solid var(--grey);margin:1rem 0;">
            <div style="display:flex;justify-content:space-between;font-weight:700;font-size:1.1rem;">
                <span><?= $billingCycle ?> Total</span><span>£<?= number_format($grandTotal, 0) ?></span>
            </div>
        </div>
    </div>
</div></section>
<?php require_once(__DIR__ . '/includes/footer.php'); ?>
