<?php

declare(strict_types=1);

/**
 * Time2Eat - Bamenda Food Delivery Platform
 * Enhanced main entry point with comprehensive backend architecture
 */

// Bootstrap the application with enhanced features
$app = require_once __DIR__ . '/bootstrap/app.php';

// Run the application with full error handling and routing
$app->run();
