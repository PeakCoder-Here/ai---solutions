<?php
$pageTitle  = 'Dashboard';
$activePage = 'dashboard';
$extraHead  = '<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" integrity="sha384-e6nUZLBkQ86NJ6TVVKAeSaK8jWa3NhkYWZFomE39AvDbQWeie9PlQqM3pmYW5d1g" crossorigin="anonymous"></script>';

require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../includes/db.php');
require_once(__DIR__ . '/partials/head.php');

$demoCount    = $db->demo_requests->countDocuments();
$contactCount = $db->contact_inquiries->countDocuments();
$eventCount   = $db->event_registrations->countDocuments();
$orderCount   = $db->orders->countDocuments();
$totalCount   = $demoCount + $contactCount + $eventCount + $orderCount;
$jobsCount    = $db->contact_inquiries->countDocuments(['job_title' => ['$ne' => '']]);

$barLabels = json_encode(['Orders Placed', 'Demo Requests', 'Contact Inquiries', 'Event Regs', 'Jobs Placed']);
$barData   = json_encode([$orderCount, $demoCount, $contactCount, $eventCount, $jobsCount]);
$pieLabels = json_encode(['Orders Placed', 'Demo Requests', 'Contact Inquiries', 'Event Regs']);
$pieData   = json_encode([$orderCount, $demoCount, $contactCount, $eventCount]);

// Recent submissions across all collections
$recent = [];
foreach ($db->orders->find([], ['sort'=>['created_at'=>-1],'limit'=>5]) as $d)
    $recent[] = ['type'=>'Order',   'name'=>$d['customer_name'], 'email'=>$d['customer_email'], 'company'=>'Order #' . ($d['order_id'] ?? 'N/A'), 'date'=>$d['created_at']];
foreach ($db->demo_requests->find([],    ['sort'=>['submitted_at'=>-1],'limit'=>5]) as $d)
    $recent[] = ['type'=>'Demo',    'name'=>$d['name'], 'email'=>$d['email'], 'company'=>$d['company']??'', 'date'=>$d['submitted_at']];
foreach ($db->contact_inquiries->find([], ['sort'=>['submitted_at'=>-1],'limit'=>5]) as $d)
    $recent[] = ['type'=>'Contact', 'name'=>$d['name'], 'email'=>$d['email'], 'company'=>$d['company']??'', 'date'=>$d['submitted_at']];
foreach ($db->event_registrations->find([],['sort'=>['submitted_at'=>-1],'limit'=>5]) as $d)
    $recent[] = ['type'=>'Event',   'name'=>$d['name'], 'email'=>$d['email'], 'company'=>$d['company']??'', 'date'=>$d['submitted_at']];
usort($recent, fn($a,$b) => $b['date']->toDateTime()->getTimestamp() - $a['date']->toDateTime()->getTimestamp());
$recent = array_slice($recent, 0, 10);
?>

<!-- KPI row -->
<div class="kpi-grid" style="grid-template-columns: repeat(5, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
    <a href="inquiries.php" class="kpi-new kpi-new--clickable">
        <div class="kpi-new__icon" style="background:#EEF2FF;color:#4F46E5;"><i class="fa fa-chart-line"></i></div>
        <div>
            <div class="kpi-new__value" data-count="<?= $totalCount ?>"><?= $totalCount ?></div>
            <div class="kpi-new__label">Total Inquiries</div>
        </div>
    </a>
    <a href="orders.php" class="kpi-new kpi-new--clickable">
        <div class="kpi-new__icon" style="background:#E0F2FE;color:#0284C7;"><i class="fa fa-shopping-cart"></i></div>
        <div>
            <div class="kpi-new__value" data-count="<?= $orderCount ?>"><?= $orderCount ?></div>
            <div class="kpi-new__label">Orders Placed</div>
        </div>
    </a>
    <a href="demos.php" class="kpi-new kpi-new--clickable">
        <div class="kpi-new__icon" style="background:#F0FDF4;color:#16A34A;"><i class="fa fa-calendar-check"></i></div>
        <div>
            <div class="kpi-new__value" data-count="<?= $demoCount ?>"><?= $demoCount ?></div>
            <div class="kpi-new__label">Demo Requests</div>
        </div>
    </a>
    <a href="events-admin.php" class="kpi-new kpi-new--clickable">
        <div class="kpi-new__icon" style="background:#FFF7ED;color:#EA580C;"><i class="fa fa-ticket"></i></div>
        <div>
            <div class="kpi-new__value" data-count="<?= $eventCount ?>"><?= $eventCount ?></div>
            <div class="kpi-new__label">Event Registrations</div>
        </div>
    </a>
    <a href="contacts.php" class="kpi-new kpi-new--clickable">
        <div class="kpi-new__icon" style="background:#FDF2F8;color:#9333EA;"><i class="fa fa-envelope"></i></div>
        <div>
            <div class="kpi-new__value" data-count="<?= $contactCount ?>"><?= $contactCount ?></div>
            <div class="kpi-new__label">Contact Inquiries</div>
        </div>
    </a>
</div>

<!-- Quick links -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">
    <a href="orders.php" class="admin-card" style="text-decoration:none;display:flex;align-items:center;gap:1rem;margin-bottom:0;transition:box-shadow .2s;">
        <div class="kpi-new__icon" style="background:#E0F2FE;color:#0284C7;flex-shrink:0;"><i class="fa fa-shopping-cart"></i></div>
        <div>
            <div style="font-weight:700;color:var(--navy);font-family:var(--font-heading);">Manage Orders</div>
            <div style="font-size:.8rem;color:var(--grey-text);"><?= $orderCount ?> order<?= $orderCount !== 1 ? 's' : '' ?></div>
        </div>
        <i class="fa fa-arrow-right" style="margin-left:auto;color:var(--grey-text);font-size:.85rem;"></i>
    </a>
    <a href="demos.php" class="admin-card" style="text-decoration:none;display:flex;align-items:center;gap:1rem;margin-bottom:0;transition:box-shadow .2s;">
        <div class="kpi-new__icon" style="background:#EEF2FF;color:#4F46E5;flex-shrink:0;"><i class="fa fa-calendar-check"></i></div>
        <div>
            <div style="font-weight:700;color:var(--navy);font-family:var(--font-heading);">Manage Demos</div>
            <div style="font-size:.8rem;color:var(--grey-text);"><?= $demoCount ?> request<?= $demoCount !== 1 ? 's' : '' ?></div>
        </div>
        <i class="fa fa-arrow-right" style="margin-left:auto;color:var(--grey-text);font-size:.85rem;"></i>
    </a>
    <a href="contacts.php" class="admin-card" style="text-decoration:none;display:flex;align-items:center;gap:1rem;margin-bottom:0;transition:box-shadow .2s;">
        <div class="kpi-new__icon" style="background:#FDF2F8;color:#9333EA;flex-shrink:0;"><i class="fa fa-envelope"></i></div>
        <div>
            <div style="font-weight:700;color:var(--navy);font-family:var(--font-heading);">Manage Contacts</div>
            <div style="font-size:.8rem;color:var(--grey-text);"><?= $contactCount ?> inquir<?= $contactCount !== 1 ? 'ies' : 'y' ?></div>
        </div>
        <i class="fa fa-arrow-right" style="margin-left:auto;color:var(--grey-text);font-size:.85rem;"></i>
    </a>
    <a href="events-admin.php" class="admin-card" style="text-decoration:none;display:flex;align-items:center;gap:1rem;margin-bottom:0;transition:box-shadow .2s;">
        <div class="kpi-new__icon" style="background:#F0FDF4;color:#16A34A;flex-shrink:0;"><i class="fa fa-ticket"></i></div>
        <div>
            <div style="font-weight:700;color:var(--navy);font-family:var(--font-heading);">Manage Events</div>
            <div style="font-size:.8rem;color:var(--grey-text);"><?= $eventCount ?> registration<?= $eventCount !== 1 ? 's' : '' ?></div>
        </div>
        <i class="fa fa-arrow-right" style="margin-left:auto;color:var(--grey-text);font-size:.85rem;"></i>
    </a>
</div>

<!-- Charts -->
<div class="grid-2" style="margin-bottom:1.5rem;">
    <div class="admin-card">
        <h2><i class="fa fa-chart-bar"></i> Inquiries by Type</h2>
        <div class="chart-container"><canvas id="barChart"></canvas></div>
    </div>
    <div class="admin-card">
        <h2><i class="fa fa-chart-pie"></i> Inquiry Distribution</h2>
        <div class="chart-container"><canvas id="pieChart"></canvas></div>
    </div>
</div>

<!-- Recent submissions -->
<div class="admin-card">
    <div class="admin-card__header">
        <div class="admin-card__title"><i class="fa fa-clock"></i> Recent Submissions</div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Type</th><th>Name</th><th>Email</th><th>Company</th><th>Date</th></tr>
            </thead>
            <tbody>
            <?php if (empty($recent)): ?>
                <tr><td colspan="5" style="text-align:center;color:var(--grey-text);padding:2.5rem;">
                    No submissions yet. Data will appear here once forms are submitted.
                </td></tr>
            <?php else: foreach ($recent as $r):
                $bc = $r['type']==='Demo' ? 'badge-blue' : ($r['type']==='Contact' ? 'badge-navy' : ($r['type']==='Order' ? 'badge-red' : 'badge-green'));
            ?>
                <tr>
                    <td><span class="badge <?= $bc ?>"><?= $r['type'] ?></span></td>
                    <td><?= htmlspecialchars($r['name']) ?></td>
                    <td><?= htmlspecialchars($r['email']) ?></td>
                    <td><?= htmlspecialchars($r['company']) ?></td>
                    <td><?= $r['date']->toDateTime()->format('d M Y H:i') ?></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$extraScripts = <<<JS
<script>
const indigo = '#4F46E5', violet = '#7C3AED', green = '#16A34A', orange = '#EA580C';

new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: {$barLabels},
        datasets: [{ label: 'Count', data: {$barData},
            backgroundColor: [indigo, violet, green, orange],
            borderRadius: 8, borderSkipped: false }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1, font: { family: 'Inter', size: 12 } }, grid: { color: '#F1F5F9' } },
            x: { ticks: { font: { family: 'Inter', size: 12 } }, grid: { display: false } }
        }
    }
});

new Chart(document.getElementById('pieChart'), {
    type: 'doughnut',
    data: {
        labels: {$pieLabels},
        datasets: [{ data: {$pieData}, backgroundColor: [indigo, violet, green],
            borderWidth: 3, borderColor: '#fff', hoverOffset: 8 }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom',
            labels: { font: { family: 'Inter', size: 13 }, padding: 20, usePointStyle: true } } }
    }
});

document.querySelectorAll('[data-count]').forEach(el => {
    const target = parseInt(el.dataset.count, 10);
    if (!target) return;
    let cur = 0, step = Math.ceil(target / 40);
    const t = setInterval(() => { cur = Math.min(cur + step, target); el.textContent = cur; if (cur >= target) clearInterval(t); }, 40);
});
</script>
JS;

require_once(__DIR__ . '/partials/foot.php');
?>
