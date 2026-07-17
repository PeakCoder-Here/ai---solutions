<?php
$pageTitle = 'Join Our Events'; $currentPage = 'events'; $base = '';
require_once(__DIR__ . '/includes/header.php');
require_once(__DIR__ . '/includes/db.php');

$success = ''; $errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name      = htmlspecialchars(trim($_POST['name']       ?? ''));
    $email     = filter_var(trim($_POST['email']      ?? ''), FILTER_SANITIZE_EMAIL);
    $phone     = htmlspecialchars(trim($_POST['phone']      ?? ''));
    $company   = htmlspecialchars(trim($_POST['company']    ?? ''));
    $country   = htmlspecialchars(trim($_POST['country']    ?? ''));
    $eventName = htmlspecialchars(trim($_POST['event_name'] ?? ''));

    if (empty($name))      $errors['name']       = 'Full name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Valid email is required.';
    if (empty($country))   $errors['country']    = 'Please select your country.';
    if (empty($eventName)) $errors['event_name'] = 'Please select an event.';

    if (empty($errors)) {
        try {
            $registrationData = [
                'name'         => $name,
                'email'        => $email,
                'phone'        => $phone,
                'company'      => $company,
                'country'      => $country,
                'event_name'   => $eventName,
                'submitted_at' => new MongoDB\BSON\UTCDateTime(),
            ];

            $insertResult = $db->event_registrations->insertOne($registrationData);

            require_once(__DIR__ . '/includes/queue_manager.php');
            $refId = (string)$insertResult->getInsertedId();
            $emailQueued = QueueManager::queue_event_registration_confirmation($registrationData, $refId);
            QueueManager::queue_admin_notification('event_registration', $registrationData, $refId);
            QueueManager::queue_event_follow_emails($registrationData, $refId);

            $success = $emailQueued
                ? 'You are registered! A confirmation email is on the way.'
                : 'You are registered! Our team has received your details.';
            $name = $email = $phone = $company = $country = $eventName = '';
        } catch (Exception $e) {
            $errors['db'] = 'Registration failed. Please try again later.';
        }
    }
}
$countries = ['United Kingdom','United States','Canada','Australia','Germany','France','India','Nepal','China','Japan','Brazil','South Africa','Other'];
$events = ['AI in the Workplace — Webinar (15 Jun)','Sunderland Tech Meetup (22 Jun)','Prototype Sprint Workshop (5 Jul)','Digital Transformation Summit 2026 (18 Jul)','STEM Outreach — Schools Programme (2 Aug)'];
?>
<section class="page-banner"><div class="container">
    <p class="breadcrumb"><a href="index.php">Home</a> <span>/</span> <a href="events.php">Events</a> <span>/</span> Register</p>
    <h1>Join Our Events</h1>
    <p>Register for an upcoming event — webinars, workshops, and conferences.</p>
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
            <div class="form-group">
                <label for="event_name">Select Event <span class="required">*</span></label>
                <select id="event_name" name="event_name" class="form-control <?= isset($errors['event_name'])?'is-invalid':'' ?>" required>
                    <option value="">-- Select an Event --</option>
                    <?php foreach ($events as $ev): ?>
                        <option value="<?= $ev ?>" <?= ($eventName ?? '')===$ev?'selected':'' ?>><?= $ev ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="form-error" id="event_name-error"><?= $errors['event_name'] ?? '' ?></div>
            </div>
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
            <button type="submit" class="btn btn-primary btn-lg" style="margin-top:1rem;">
                <i class="fa fa-calendar-check"></i> Register for Event
            </button>
        </form>
    </div>
</div></section>
<?php require_once(__DIR__ . '/includes/footer.php'); ?>
