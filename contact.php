<?php
$pageTitle = 'Contact Us'; $currentPage = 'contact'; $base = '';
require_once(__DIR__ . '/includes/header.php');
require_once(__DIR__ . '/includes/db.php');

$success = ''; $errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = htmlspecialchars(trim($_POST['name']       ?? ''));
    $email      = filter_var(trim($_POST['email']      ?? ''), FILTER_SANITIZE_EMAIL);
    $phone      = htmlspecialchars(trim($_POST['phone']      ?? ''));
    $company    = htmlspecialchars(trim($_POST['company']    ?? ''));
    $country    = htmlspecialchars(trim($_POST['country']    ?? ''));
    $job_title  = htmlspecialchars(trim($_POST['job_title']  ?? ''));
    $job_details= htmlspecialchars(trim($_POST['job_details']?? ''));

    if (empty($name))       $errors['name']       = 'Full name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Valid email is required.';
    if (empty($country))    $errors['country']    = 'Please select your country.';
    if (empty($job_title))  $errors['job_title']  = 'Job title is required.';
    if (empty($job_details))$errors['job_details']= 'Please provide details about your enquiry.';

    if (empty($errors)) {
        try {
            $inquiryData = [
                'name'         => $name,
                'email'        => $email,
                'phone'        => $phone,
                'company'      => $company,
                'country'      => $country,
                'job_title'    => $job_title,
                'job_details'  => $job_details,
                'submitted_at' => new MongoDB\BSON\UTCDateTime(),
            ];

            $insertResult = $db->contact_inquiries->insertOne($inquiryData);

            require_once(__DIR__ . '/includes/queue_manager.php');
            $refId = (string)$insertResult->getInsertedId();
            $emailQueued = QueueManager::queue_contact_acknowledgement($inquiryData, $refId);
            QueueManager::queue_admin_notification('contact_inquiry', $inquiryData, $refId);

            $success = $emailQueued
                ? 'Thank you for reaching out! We will respond within 24 hours, and a confirmation email is on the way.'
                : 'Thank you for reaching out! We will respond within 24 hours.';
            $name = $email = $phone = $company = $country = $job_title = $job_details = '';
            unset($refId);
        } catch (Exception $e) {
            $errors['db'] = 'Submission failed. Please try again later.';
        }
    }
}
$countries = ['United Kingdom','United States','Canada','Australia','Germany','France','India','Nepal','China','Japan','Brazil','South Africa','Other'];
?>
<section class="page-banner"><div class="container">
    <p class="breadcrumb"><a href="index.php">Home</a> <span>/</span> Contact</p>
    <h1>Contact Us</h1>
    <p>Have a question, partnership idea, or job enquiry? We'd love to hear from you.</p>
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
                    <label for="company">Company Name</label>
                    <input type="text" id="company" name="company" class="form-control" value="<?= $company ?? '' ?>">
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
                    <label for="job_title">Job Title <span class="required">*</span></label>
                    <input type="text" id="job_title" name="job_title" class="form-control <?= isset($errors['job_title'])?'is-invalid':'' ?>" value="<?= $job_title ?? '' ?>" required>
                    <div class="form-error" id="job_title-error"><?= $errors['job_title'] ?? '' ?></div>
                </div>
            </div>
            <div class="form-group">
                <label for="job_details">Job Details / Message <span class="required">*</span></label>
                <textarea id="job_details" name="job_details" class="form-control <?= isset($errors['job_details'])?'is-invalid':'' ?>" rows="5" required><?= $job_details ?? '' ?></textarea>
                <div class="form-error" id="job_details-error"><?= $errors['job_details'] ?? '' ?></div>
            </div>
            <button type="submit" class="btn btn-primary btn-lg" style="margin-top:1rem;">
                <i class="fa fa-paper-plane"></i> Send Message
            </button>
        </form>
    </div>
</div></section>
<?php require_once(__DIR__ . '/includes/footer.php'); ?>
