<?php
/**
 * setup_db.php — One-time database setup
 * Run once at: http://localhost/ai-solutions/setup_db.php
 * Then DELETE this file immediately.
 */
require_once(__DIR__ . '/includes/db.php');

echo "<h2>AI-Solutions — Database Setup</h2>";

// Create collections
$collections = ['demo_requests', 'contact_inquiries', 'event_registrations', 'admin_users', 'orders', 'email_queue'];
foreach ($collections as $col) {
    try {
        $db->createCollection($col);
        echo "<p>✅ Created collection: <strong>$col</strong></p>";
    } catch (Exception $e) {
        echo "<p>ℹ️ Collection <strong>$col</strong> already exists.</p>";
    }
}

// Create indexes for email_queue
try {
    $db->email_queue->createIndex(['status' => 1, 'scheduled_at' => 1]);
    $db->email_queue->createIndex(['email_type' => 1, 'order_id' => 1]);
    $db->email_queue->createIndex(['reference_type' => 1, 'reference_id' => 1, 'email_type' => 1]);
    echo "<p>✅ Created indexes for <strong>email_queue</strong></p>";
} catch (Exception $e) {
    echo "<p>❌ Failed to create indexes: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Seed admin user
$existing = $db->admin_users->findOne(['username' => 'admin']);
if (!$existing) {
    $db->admin_users->insertOne([
        'username'      => 'admin',
        'password_hash' => password_hash('Admin@123', PASSWORD_BCRYPT),
        'role'          => 'superadmin',
        'created_at'    => new MongoDB\BSON\UTCDateTime(),
    ]);
    echo "<p>✅ Admin user created — <strong>admin / Admin@123</strong></p>";
} else {
    echo "<p>ℹ️ Admin user already exists.</p>";
}

// Seed sample data for dashboard demo
$sampleDemos = [
    ['name'=>'Alice Cooper','email'=>'alice@example.com','phone'=>'+44 7700 100001','company'=>'TechNova Ltd','country'=>'United Kingdom','interest_type'=>'AI Virtual Assistant'],
    ['name'=>'Bob Zhang','email'=>'bob@example.com','phone'=>'+1 555 0102','company'=>'DataCorp','country'=>'United States','interest_type'=>'Rapid Prototyping'],
    ['name'=>'Carla Rossi','email'=>'carla@example.com','phone'=>'+39 333 1234567','company'=>'InnovaIT','country'=>'Germany','interest_type'=>'Digital Transformation'],
];
$sampleContacts = [
    ['name'=>'David Kim','email'=>'david@example.com','phone'=>'+82 10 1234 5678','company'=>'SoftBridge','country'=>'United Kingdom','job_title'=>'Software Engineer','job_details'=>'Interested in a backend developer role.'],
    ['name'=>'Emily Foster','email'=>'emily@example.com','phone'=>'+44 7700 200002','company'=>'Vertex Media','country'=>'United Kingdom','job_title'=>'Marketing Manager','job_details'=>'Partnership inquiry for co-branded event.'],
];
$sampleEvents = [
    ['name'=>'Frank Adams','email'=>'frank@example.com','phone'=>'+44 7700 300003','company'=>'NorthCare NHS','country'=>'United Kingdom','event_name'=>'AI in the Workplace — Webinar (15 Jun)'],
    ['name'=>'Grace Obi','email'=>'grace@example.com','phone'=>'+234 801 123 4567','company'=>'AfriTech','country'=>'Other','event_name'=>'Digital Transformation Summit 2026 (18 Jul)'],
    ['name'=>'Hiro Tanaka','email'=>'hiro@example.com','phone'=>'+81 90 1234 5678','company'=>'Zenith Corp','country'=>'Japan','event_name'=>'Prototype Sprint Workshop (5 Jul)'],
    ['name'=>'Isabel Martinez','email'=>'isabel@example.com','phone'=>'+34 612 345 678','company'=>'Solaris','country'=>'Other','event_name'=>'Sunderland Tech Meetup (22 Jun)'],
];

if ($db->demo_requests->countDocuments() === 0) {
    foreach ($sampleDemos as $d) { $d['submitted_at'] = new MongoDB\BSON\UTCDateTime(); $db->demo_requests->insertOne($d); }
    echo "<p>✅ Seeded " . count($sampleDemos) . " sample demo requests.</p>";
}
if ($db->contact_inquiries->countDocuments() === 0) {
    foreach ($sampleContacts as $d) { $d['submitted_at'] = new MongoDB\BSON\UTCDateTime(); $db->contact_inquiries->insertOne($d); }
    echo "<p>✅ Seeded " . count($sampleContacts) . " sample contact inquiries.</p>";
}
if ($db->event_registrations->countDocuments() === 0) {
    foreach ($sampleEvents as $d) { $d['submitted_at'] = new MongoDB\BSON\UTCDateTime(); $db->event_registrations->insertOne($d); }
    echo "<p>✅ Seeded " . count($sampleEvents) . " sample event registrations.</p>";
}

echo "<hr><p><strong>🎉 Setup complete!</strong> <a href='admin/login.php'>Go to Admin Login</a> | <a href='index.php'>Go to Home</a></p>";
echo "<p style='color:red;font-weight:bold;'>⚠️ DELETE THIS FILE NOW — never leave setup scripts accessible.</p>";
