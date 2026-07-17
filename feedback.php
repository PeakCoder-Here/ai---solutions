<?php
$pageTitle = 'Customer Feedback'; $currentPage = 'feedback'; $base = '';
require_once(__DIR__ . '/includes/header.php');
require_once(__DIR__ . '/includes/db.php');

// Auto-seed approved testimonials if collection is empty
if ($db->feedbacks->countDocuments() === 0) {
    $db->feedbacks->insertMany([
        ['name'=>'Sarah Mitchell','company'=>'NorthCare NHS Trust','role'=>'Head of Digital','stars'=>5,'text'=>'The AI Virtual Assistant transformed our internal helpdesk. Staff satisfaction is up, query resolution time is down, and we saved over £180K in the first year.','status'=>'approved','created_at'=>new MongoDB\BSON\UTCDateTime()],
        ['name'=>'James Thornton','company'=>'PayStream Ltd','role'=>'CPO','stars'=>5,'text'=>'Their rapid prototyping service is exceptional. We tested three product concepts in just 14 days — something that used to take us two months. Game-changing.','status'=>'approved','created_at'=>new MongoDB\BSON\UTCDateTime()],
        ['name'=>'Priya Sharma','company'=>'SteelWorks UK','role'=>'Operations Director','stars'=>4,'text'=>'The custom integration project connected five legacy systems we thought were impossible to unify. The team was professional, responsive, and delivered ahead of schedule.','status'=>'approved','created_at'=>new MongoDB\BSON\UTCDateTime()],
        ['name'=>'David Chen','company'=>'EduTech Solutions','role'=>'CEO','stars'=>5,'text'=>'We engaged AI-Solutions for a digital transformation consultancy. Their roadmap was pragmatic and actionable — not the usual 100-page report that sits on a shelf.','status'=>'approved','created_at'=>new MongoDB\BSON\UTCDateTime()],
        ['name'=>'Emma Williams','company'=>'Vertex Media','role'=>'Marketing Manager','stars'=>4,'text'=>'The chatbot they built for our client-facing portal handles 60% of queries automatically. Our support team can now focus on complex cases that actually need human attention.','status'=>'approved','created_at'=>new MongoDB\BSON\UTCDateTime()],
        ['name'=>'Rajesh Patel','company'=>'GreenEnergy North','role'=>'CTO','stars'=>5,'text'=>'Lochan and the team really understand enterprise AI. They delivered a solution that was secure, scalable, and genuinely useful — not just impressive in a demo.','status'=>'approved','created_at'=>new MongoDB\BSON\UTCDateTime()],
    ]);
}

// Handle form submission
$success = false;
$errors  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf'])) {
        $errors[] = 'Invalid request. Please refresh and try again.';
    } else {
        $name    = trim($_POST['name']    ?? '');
        $company = trim($_POST['company'] ?? '');
        $role    = trim($_POST['role']    ?? '');
        $email   = trim($_POST['email']   ?? '');
        $stars   = (int)($_POST['stars']  ?? 0);
        $text    = trim($_POST['text']    ?? '');
        $product = trim($_POST['product'] ?? '');

        if (!$name)              $errors[] = 'Your name is required.';
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
        if ($stars < 1 || $stars > 5) $errors[] = 'Please select a star rating.';
        if (strlen($text) < 20)  $errors[] = 'Please write at least 20 characters of feedback.';

        if (!$errors) {
            $db->feedbacks->insertOne([
                'name'       => $name,
                'company'    => $company,
                'role'       => $role,
                'email'      => $email,
                'stars'      => $stars,
                'text'       => $text,
                'product'    => $product,
                'status'     => 'pending',
                'created_at' => new MongoDB\BSON\UTCDateTime(),
            ]);
            $success = true;
        }
    }
}

// Fetch approved feedback
$testimonials = iterator_to_array($db->feedbacks->find(
    ['status' => 'approved'],
    ['sort' => ['created_at' => -1]]
));
?>
<section class="page-banner"><div class="container">
    <p class="breadcrumb"><a href="index.php">Home</a> <span>/</span> Feedback</p>
    <h1>Customer Feedback</h1>
    <p>Real experiences from the businesses we work with.</p>
</div></section>

<!-- Approved testimonials -->
<section class="section"><div class="container">
    <?php if (empty($testimonials)): ?>
    <p style="text-align:center;color:var(--grey-text);">No testimonials yet — be the first to share your experience!</p>
    <?php else: ?>
    <div class="grid-3">
    <?php foreach ($testimonials as $t): ?>
        <div class="testimonial-card">
            <div class="testimonial-card__stars">
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <i class="fa fa-star" style="color:<?= $i < (int)$t['stars'] ? '#F5A623' : '#ddd' ?>;"></i>
                <?php endfor; ?>
            </div>
            <?php if (!empty($t['product'])): ?>
            <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--blue);margin-bottom:.5rem;">
                <?= htmlspecialchars($t['product']) ?>
            </div>
            <?php endif; ?>
            <p class="testimonial-card__text">"<?= htmlspecialchars($t['text']) ?>"</p>
            <div class="testimonial-card__author">
                <div class="testimonial-card__avatar">
                    <?= strtoupper(substr((string)$t['name'], 0, 1)) ?>
                </div>
                <div>
                    <div class="testimonial-card__name"><?= htmlspecialchars((string)$t['name']) ?></div>
                    <div class="testimonial-card__company">
                        <?php
                        $parts = array_filter([
                            htmlspecialchars((string)($t['role'] ?? '')),
                            htmlspecialchars((string)($t['company'] ?? '')),
                        ]);
                        echo implode(', ', $parts);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div></section>

<!-- Submit feedback form -->
<section class="section section--grey" id="leave-feedback"><div class="container">
    <div style="max-width:680px;margin:0 auto;">
        <div class="section__header" style="text-align:left;">
            <h2>Share Your Experience</h2>
            <p>Used one of our products or services? We'd love to hear what you think. Approved reviews appear on this page.</p>
        </div>

        <?php if ($success): ?>
        <div class="alert alert-success" style="display:flex;align-items:center;gap:.75rem;padding:1.1rem 1.4rem;background:#D1FAE5;border:1px solid #6EE7B7;border-radius:var(--radius-md);color:#065F46;margin-bottom:1.5rem;">
            <i class="fa fa-circle-check" style="font-size:1.2rem;"></i>
            <div><strong>Thank you!</strong> Your feedback has been submitted and will appear here once reviewed.</div>
        </div>
        <?php endif; ?>

        <?php if ($errors): ?>
        <div class="alert alert-error" style="padding:1rem 1.4rem;background:#FEE2E2;border:1px solid #FCA5A5;border-radius:var(--radius-md);color:#991B1B;margin-bottom:1.5rem;">
            <strong>Please fix the following:</strong>
            <ul style="margin:.5rem 0 0 1.2rem;">
                <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <form method="POST" class="card" style="padding:2rem;">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

            <!-- Star rating -->
            <div class="form-group" style="margin-bottom:1.5rem;">
                <label style="display:block;font-weight:700;margin-bottom:.6rem;color:var(--navy);">Your Rating <span style="color:#E53E3E;">*</span></label>
                <div class="star-picker" id="starPicker">
                    <?php for ($s = 1; $s <= 5; $s++): ?>
                    <label class="star-lbl">
                        <input type="radio" name="stars" value="<?= $s ?>" <?= (($_POST['stars'] ?? 0) == $s) ? 'checked' : '' ?>>
                        <i class="fa fa-star"></i>
                    </label>
                    <?php endfor; ?>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div class="form-group">
                    <label>Your Name <span style="color:#E53E3E;">*</span></label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" placeholder="Jane Smith" required>
                </div>
                <div class="form-group">
                    <label>Email Address <span style="color:#E53E3E;">*</span></label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="jane@company.com" required>
                    <small style="color:var(--grey-text);font-size:.8rem;">Not published publicly.</small>
                </div>
                <div class="form-group">
                    <label>Company / Organisation</label>
                    <input type="text" name="company" class="form-control" value="<?= htmlspecialchars($_POST['company'] ?? '') ?>" placeholder="Acme Ltd">
                </div>
                <div class="form-group">
                    <label>Your Role / Title</label>
                    <input type="text" name="role" class="form-control" value="<?= htmlspecialchars($_POST['role'] ?? '') ?>" placeholder="Operations Manager">
                </div>
            </div>

            <div class="form-group" style="margin-top:.5rem;">
                <label>Product / Service Used</label>
                <select name="product" class="form-control">
                    <option value="">— Select a product (optional) —</option>
                    <option value="AI Virtual Assistant" <?= ($_POST['product'] ?? '') === 'AI Virtual Assistant' ? 'selected' : '' ?>>AI Virtual Assistant</option>
                    <option value="Rapid Prototyping" <?= ($_POST['product'] ?? '') === 'Rapid Prototyping' ? 'selected' : '' ?>>Rapid Prototyping</option>
                    <option value="Digital Transformation" <?= ($_POST['product'] ?? '') === 'Digital Transformation' ? 'selected' : '' ?>>Digital Transformation</option>
                    <option value="Custom AI Integration" <?= ($_POST['product'] ?? '') === 'Custom AI Integration' ? 'selected' : '' ?>>Custom AI Integration</option>
                </select>
            </div>

            <div class="form-group" style="margin-top:.5rem;">
                <label>Your Feedback <span style="color:#E53E3E;">*</span></label>
                <textarea name="text" class="form-control" rows="5" placeholder="Tell us about your experience — what problem did we solve, what results did you see?" required><?= htmlspecialchars($_POST['text'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-lg" style="width:100%;margin-top:1rem;">
                <i class="fa fa-paper-plane"></i> Submit Feedback
            </button>
        </form>
        <?php endif; ?>
    </div>
</div></section>

<style>
.star-picker { display:flex; gap:.25rem; }
.star-lbl { cursor:pointer; font-size:1.8rem; color:#D1D5DB; transition:color .15s; }
.star-lbl input { display:none; }
.star-lbl:hover,
.star-lbl:hover ~ .star-lbl { color:#F5A623; }
.star-picker:hover .star-lbl { color:#F5A623; }
.star-picker .star-lbl:hover ~ .star-lbl { color:#D1D5DB; }
/* JS-driven selected state handled below */
.star-lbl.selected { color:#F5A623; }
</style>
<script>
(function(){
    var stars = document.querySelectorAll('.star-lbl');
    function highlight(n){
        stars.forEach(function(s,i){ s.classList.toggle('selected', i < n); });
    }
    stars.forEach(function(s,i){
        s.addEventListener('mouseenter', function(){ highlight(i+1); });
        s.addEventListener('click',      function(){ highlight(i+1); });
    });
    document.getElementById('starPicker').addEventListener('mouseleave', function(){
        var checked = document.querySelector('input[name="stars"]:checked');
        highlight(checked ? parseInt(checked.value) : 0);
    });
    // Restore if coming back with POST errors
    var checked = document.querySelector('input[name="stars"]:checked');
    if (checked) highlight(parseInt(checked.value));
})();
</script>

<?php require_once(__DIR__ . '/includes/footer.php'); ?>
