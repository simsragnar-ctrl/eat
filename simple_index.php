<?php
/**
 * Simple index for testing
 */

echo "<h1>Simple Index Test</h1>";
echo "<p>This is a basic test to see if URL rewriting works.</p>";

echo "<h2>Request Information</h2>";
echo "<pre>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'Not set') . "\n";
echo "REQUEST_METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'Not set') . "\n";
echo "QUERY_STRING: " . ($_SERVER['QUERY_STRING'] ?? 'Not set') . "\n";
echo "PATH_INFO: " . ($_SERVER['PATH_INFO'] ?? 'Not set') . "\n";
echo "</pre>";

// Simple routing test
$uri = $_SERVER['REQUEST_URI'] ?? '';
$path = parse_url($uri, PHP_URL_PATH);

// Remove base path
$basePath = '/Eat';
if (strpos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath));
}

if (empty($path) || $path === '/') {
    echo "<h2>✅ Home Route Matched</h2>";
    echo "<p>You are on the home page!</p>";
} elseif ($path === '/about') {
    echo "<h2>✅ About Route Matched</h2>";
    echo "<p>You are on the about page!</p>";
} else {
    echo "<h2>❌ No Route Matched</h2>";
    echo "<p>Path: $path</p>";
    echo "<p>This would normally show a 404 page.</p>";
}

echo "<h2>Test Links</h2>";
echo "<p><a href='/Eat/'>Home</a></p>";
echo "<p><a href='/Eat/about'>About</a></p>";
echo "<p><a href='/Eat/contact'>Contact (should 404)</a></p>";
?>
