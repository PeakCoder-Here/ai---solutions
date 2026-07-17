<?php
$pageTitle = 'Schedule a Demo'; $currentPage = 'demo'; $base = '';
require_once(__DIR__ . '/includes/header.php');
require_once(__DIR__ . '/includes/db.php');

$success = ''; $errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitise
    $name     = htmlspecialchars(trim($_POST['name']     ?? ''));
    $email    = filter_var(trim($_POST['email']    ?? ''), FILTER_SANITIZE_EMAIL);
    $phone    = htmlspecialchars(trim($_POST['phone']    ?? ''));
    $company  = htmlspecialchars(trim($_POST['company']  ?? ''));
    $country  = htmlspecialchars(trim($_POST['country']  ?? ''));
    $interest = htmlspecialchars(trim($_POST['interest'] ?? ''));

    // Validate
    if (empty($name))     $errors['name']     = 'Full name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Valid email is required.';
    if (empty($company))  $errors['company']  = 'Company name is required.';
    if (empty($country))  $errors['country']  = 'Please select your country.';
    if (empty($interest)) $errors['interest'] = 'Please select an interest type.';

    if (empty($errors)) {
        try {
            $demoData = [
                'name'          => $name,
                'email'         => $email,
                'phone'         => $phone,
                'company'       => $company,
                'country'       => $country,
                'interest_type' => $interest,
                'submitted_at'  => new MongoDB\BSON\UTCDateTime(),
            ];

            $insertResult = $db->demo_requests->insertOne($demoData);

            require_once(__DIR__ . '/includes/queue_manager.php');
            $refId = (string)$insertResult->getInsertedId();
            $emailQueued = QueueManager::queue_demo_confirmation($demoData, $refId);
            QueueManager::queue_admin_notification('demo_request', $demoData, $refId);
            QueueManager::queue_demo_followup($demoData, $refId);

            $success = $emailQueued
                ? 'Thank you! Your demo request has been submitted, and a confirmation email is on the way.'
                : 'Thank you! Your demo request has been submitted. We will be in touch shortly.';
            $name = $email = $phone = $company = $country = $interest = '';
        } catch (Exception $e) {
            $errors['db'] = 'Submission failed. Please try again later.';
        }
    }
}

$countries = ['United Kingdom','United States','Canada','Australia','Germany','France','India','Nepal','China','Japan','Brazil','South Africa','Other'];
$interests = ['AI Virtual Assistant','Rapid Prototyping','Digital Transformation','Custom AI Integration','Other'];
?>

<section class="page-banner"><div class="container">
    <p class="breadcrumb"><a href="index.php">Home</a> <span>/</span> Schedule a Demo</p>
    <h1>Schedule a Demo</h1>
    <p>See our AI solutions in action — book a free personalised demonstration.</p>
</div></section>

<section class="section"><div class="container">
    <div class="form-section">

        <?php if ($success): ?>
            <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?= $success ?></div>
        <?php endif; ?>
        <?php if (!empty($errors['db'])): ?>
            <div class="alert alert-error"><i class="fa fa-exclamation-circle"></i> <?= $errors['db'] ?></div>
        <?php endif; ?>

        <form method="POST" action="" data-validate="true" novalidate>
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Full Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" class="form-control <?= isset($errors['name'])?'is-invalid':'' ?>" value="<?= $name ?? '' ?>" required>
                    <div class="form-error" id="name-error"><?= $errors['name'] ?? '' ?></div>
                </div>
                <div class="form-group">
                    <label for="email">Email Address <span class="required">*</span></label>
                    <input type="email" id="email" name="email" class="form-control <?= isset($errors['email'])?'is-invalid':'' ?>" value="<?= $email ?? '' ?>" required>
                    <div class="form-error" id="email-error"><?= $errors['email'] ?? '' ?></div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="form-control" value="<?= $phone ?? '' ?>">
                </div>
                <div class="form-group">
                    <label for="company">Company Name <span class="required">*</span></label>
                    <input type="text" id="company" name="company" class="form-control <?= isset($errors['company'])?'is-invalid':'' ?>" value="<?= $company ?? '' ?>" required>
                    <div class="form-error" id="company-error"><?= $errors['company'] ?? '' ?></div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="country">Country <span class="required">*</span></label>
                    <select id="country" name="country" class="form-control <?= isset($errors['country'])?'is-invalid':'' ?>" required>
                        <option value="">-- Select Country --</option>
                        <?php foreach ($countries as $c): ?>
                            <option value="<?= $c ?>" <?= ($country ?? '')===$c?'selected':'' ?>><?= $c ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-error" id="country-error"><?= $errors['country'] ?? '' ?></div>
                </div>
                <div class="form-group">
                    <label for="interest">Interest Type <span class="required">*</span></label>
                    <select id="interest" name="interest" class="form-control <?= isset($errors['interest'])?'is-invalid':'' ?>" required>
                        <option value="">-- Select Interest --</option>
                        <?php foreach ($interests as $i): ?>
                            <option value="<?= $i ?>" <?= ($interest ?? '')===$i?'selected':'' ?>><?= $i ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-error" id="interest-error"><?= $errors['interest'] ?? '' ?></div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-lg" style="margin-top:1rem;">
                <i class="fa fa-paper-plane"></i> Submit Demo Request
            </button>
        </form>
    </div>
</div></section>
<?php require_once(__DIR__ . '/includes/footer.php'); ?>
