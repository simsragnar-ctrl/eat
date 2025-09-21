<?php
/**
 * Simple test to verify basic PHP and routing
 */

echo "<h1>Time2Eat Test Page</h1>";
echo "<p>If you can see this, PHP is working correctly.</p>";

echo "<h2>Basic Tests</h2>";

// Test 1: Check if we can include files
echo "<h3>1. File Inclusion Test</h3>";
try {
    if (file_exists(__DIR__ . '/bootstrap/app.php')) {
        echo "✅ bootstrap/app.php exists<br>";
        
        // Try to include it
        $app = require_once __DIR__ . '/bootstrap/app.php';
        echo "✅ bootstrap/app.php loaded successfully<br>";
        
        if ($app instanceof Application) {
            echo "✅ Application instance created<br>";
        } else {
            echo "❌ Application instance not created properly<br>";
        }
        
    } else {
        echo "❌ bootstrap/app.php not found<br>";
    }
} catch (Exception $e) {
    echo "❌ Error loading bootstrap: " . $e->getMessage() . "<br>";
}

// Test 2: Check router
echo "<h3>2. Router Test</h3>";
try {
    require_once __DIR__ . '/src/core/EnhancedRouter.php';
    $router = new core\EnhancedRouter('http://localhost/Eat');
    echo "✅ EnhancedRouter created<br>";
    
    // Add a test route
    $router->get('/', function() {
        return "Test route response";
    });
    echo "✅ Test route added<br>";
    
} catch (Exception $e) {
    echo "❌ Router error: " . $e->getMessage() . "<br>";
}

// Test 3: Check controller
echo "<h3>3. Controller Test</h3>";
try {
    require_once __DIR__ . '/src/controllers/HomeController.php';
    echo "✅ HomeController class exists<br>";
    
    if (class_exists('controllers\HomeController')) {
        echo "✅ HomeController class loaded<br>";
    } else {
        echo "❌ HomeController class not found<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Controller error: " . $e->getMessage() . "<br>";
}

// Test 4: Check view
echo "<h3>4. View Test</h3>";
$viewFile = __DIR__ . '/src/views/home/index_enhanced.php';
if (file_exists($viewFile)) {
    echo "✅ Home view exists: $viewFile<br>";
} else {
    echo "❌ Home view not found: $viewFile<br>";
}

$layoutFile = __DIR__ . '/src/views/layouts/app.php';
if (file_exists($layoutFile)) {
    echo "✅ Layout exists: $layoutFile<br>";
} else {
    echo "❌ Layout not found: $layoutFile<br>";
}

echo "<h2>URL Tests</h2>";
echo "<p><a href='/Eat/'>Test main URL: /Eat/</a></p>";
echo "<p><a href='/Eat/index.php'>Test direct index: /Eat/index.php</a></p>";
echo "<p><a href='/Eat/about'>Test route: /Eat/about</a></p>";

echo "<h2>Server Info</h2>";
echo "<pre>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'Not set') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'Not set') . "\n";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'Not set') . "\n";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Not set') . "\n";
echo "</pre>";
?>
