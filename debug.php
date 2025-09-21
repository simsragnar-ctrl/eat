<?php
/**
 * Debug script to diagnose routing issues
 */

echo "<h1>Time2Eat Debug Information</h1>";

echo "<h2>Server Information</h2>";
echo "<pre>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'Not set') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'Not set') . "\n";
echo "PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'Not set') . "\n";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'Not set') . "\n";
echo "SERVER_NAME: " . ($_SERVER['SERVER_NAME'] ?? 'Not set') . "\n";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Not set') . "\n";
echo "REQUEST_METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'Not set') . "\n";
echo "</pre>";

echo "<h2>Path Information</h2>";
echo "<pre>";
$uri = $_SERVER['REQUEST_URI'] ?? '';
$parsedUri = parse_url($uri, PHP_URL_PATH);
echo "Raw URI: $uri\n";
echo "Parsed Path: $parsedUri\n";
echo "Current Directory: " . __DIR__ . "\n";
echo "Root Path: " . dirname(__DIR__) . "\n";
echo "</pre>";

echo "<h2>File Checks</h2>";
echo "<pre>";
echo "index.php exists: " . (file_exists(__DIR__ . '/index.php') ? 'YES' : 'NO') . "\n";
echo "bootstrap/app.php exists: " . (file_exists(__DIR__ . '/bootstrap/app.php') ? 'YES' : 'NO') . "\n";
echo "routes/web.php exists: " . (file_exists(__DIR__ . '/routes/web.php') ? 'YES' : 'NO') . "\n";
echo "src/core/EnhancedRouter.php exists: " . (file_exists(__DIR__ . '/src/core/EnhancedRouter.php') ? 'YES' : 'NO') . "\n";
echo ".htaccess exists: " . (file_exists(__DIR__ . '/.htaccess') ? 'YES' : 'NO') . "\n";
echo "</pre>";

echo "<h2>Apache Modules (if available)</h2>";
echo "<pre>";
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    echo "mod_rewrite: " . (in_array('mod_rewrite', $modules) ? 'ENABLED' : 'DISABLED') . "\n";
    echo "mod_headers: " . (in_array('mod_headers', $modules) ? 'ENABLED' : 'DISABLED') . "\n";
    echo "mod_expires: " . (in_array('mod_expires', $modules) ? 'ENABLED' : 'DISABLED') . "\n";
} else {
    echo "apache_get_modules() not available\n";
}
echo "</pre>";

echo "<h2>Test Router</h2>";
echo "<pre>";
try {
    // Try to load the application
    require_once __DIR__ . '/bootstrap/app.php';
    echo "Application bootstrap: SUCCESS\n";
    
    // Test router creation
    require_once __DIR__ . '/src/core/EnhancedRouter.php';
    $router = new core\EnhancedRouter();
    echo "Router creation: SUCCESS\n";
    
    // Test route definition
    $router->get('/', function() {
        return "Test route works!";
    });
    echo "Route definition: SUCCESS\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
echo "</pre>";

echo "<h2>Recommendations</h2>";
echo "<ul>";

// Check if we're in a subdirectory
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
if (strpos($scriptName, '/Eat/') !== false || strpos($scriptName, '/eat/') !== false) {
    echo "<li><strong>Subdirectory detected:</strong> Your application is in a subdirectory. This requires special configuration.</li>";
}

// Check .htaccess
if (!file_exists(__DIR__ . '/.htaccess')) {
    echo "<li><strong>Missing .htaccess:</strong> The .htaccess file is missing.</li>";
} else {
    $htaccess = file_get_contents(__DIR__ . '/.htaccess');
    if (strpos($htaccess, 'RewriteEngine On') === false) {
        echo "<li><strong>.htaccess issue:</strong> RewriteEngine is not enabled in .htaccess.</li>";
    }
}

echo "</ul>";

echo "<p><a href='/eat/'>Try accessing /eat/ again</a></p>";
echo "<p><a href='/eat/index.php'>Try accessing /eat/index.php directly</a></p>";
?>
