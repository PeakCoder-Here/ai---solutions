<?php
/**
 * db.php — MongoDB database connection
 * Provides $db (MongoDB\Database) to any file that includes this.
 */
require_once __DIR__ . '/../vendor/autoload.php';

try {
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
    $db = $mongoClient->ai_solutions;
} catch (Exception $e) {
    die('<div class="alert alert-error"><i class="fa fa-exclamation-circle"></i> Database connection failed: ' . htmlspecialchars($e->getMessage()) . '</div>');
}
