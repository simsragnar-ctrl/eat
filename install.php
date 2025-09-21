<?php
/**
 * Time2Eat Installation Script
 * Self-deleting installer for any hosting environment
 * Compatible with shared hosting, VPS, and cloud platforms
 */

// Prevent direct access after installation
if (file_exists('.env') && file_exists('config/installed.lock')) {
    die('Time2Eat is already installed. Delete config/installed.lock to reinstall.');
}

// Start session for installer state
session_start();

// Installation configuration
$config = [
    'min_php_version' => '8.0.0',
    'required_extensions' => ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'json', 'curl', 'gd'],
    'required_directories' => ['public/uploads', 'logs', 'cache', 'storage'],
    'database_file' => 'database/data.sql',
    'sample_data_file' => 'database/sample_data.sql'
];

// Installation steps
$steps = [
    1 => 'System Requirements Check',
    2 => 'Database Configuration',
    3 => 'Database Setup',
    4 => 'Admin Account Creation',
    5 => 'Final Configuration',
    6 => 'Installation Complete'
];

$current_step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$errors = [];
$success = [];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($current_step) {
        case 2:
            $current_step = handleDatabaseConfig();
            break;
        case 3:
            $current_step = handleDatabaseSetup();
            break;
        case 4:
            $current_step = handleAdminCreation();
            break;
        case 5:
            $current_step = handleFinalConfiguration();
            break;
    }
}

/**
 * Handle database configuration
 */
function handleDatabaseConfig() {
    global $errors, $success;
    
    $db_host = trim($_POST['db_host'] ?? '');
    $db_name = trim($_POST['db_name'] ?? '');
    $db_user = trim($_POST['db_user'] ?? '');
    $db_pass = $_POST['db_pass'] ?? '';
    $db_port = (int)($_POST['db_port'] ?? 3306);
    
    // Validate inputs
    if (empty($db_host)) $errors[] = 'Database host is required';
    if (empty($db_name)) $errors[] = 'Database name is required';
    if (empty($db_user)) $errors[] = 'Database username is required';
    
    if (empty($errors)) {
        // Test database connection
        try {
            $dsn = "mysql:host={$db_host};port={$db_port};charset=utf8mb4";
            $pdo = new PDO($dsn, $db_user, $db_pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            
            // Check if database exists, create if not
            $stmt = $pdo->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?");
            $stmt->execute([$db_name]);
            
            if (!$stmt->fetch()) {
                $pdo->exec("CREATE DATABASE `{$db_name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $success[] = "Database '{$db_name}' created successfully";
            }
            
            // Store database config in session
            $_SESSION['db_config'] = [
                'host' => $db_host,
                'name' => $db_name,
                'user' => $db_user,
                'pass' => $db_pass,
                'port' => $db_port
            ];
            
            $success[] = 'Database connection successful';
            return 3; // Next step
            
        } catch (PDOException $e) {
            $errors[] = 'Database connection failed: ' . $e->getMessage();
        }
    }
    
    return 2; // Stay on current step
}

/**
 * Handle database setup
 */
function handleDatabaseSetup() {
    global $errors, $success, $config;
    
    if (!isset($_SESSION['db_config'])) {
        $errors[] = 'Database configuration not found. Please go back to step 2.';
        return 2;
    }
    
    $db_config = $_SESSION['db_config'];
    
    try {
        $dsn = "mysql:host={$db_config['host']};port={$db_config['port']};dbname={$db_config['name']};charset=utf8mb4";
        $pdo = new PDO($dsn, $db_config['user'], $db_config['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        
        // Import main database schema
        if (file_exists($config['database_file'])) {
            $sql = file_get_contents($config['database_file']);
            $statements = explode(';', $sql);
            
            $pdo->beginTransaction();
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    $pdo->exec($statement);
                }
            }
            
            $pdo->commit();
            $success[] = 'Database schema imported successfully';
        } else {
            $errors[] = 'Database schema file not found: ' . $config['database_file'];
        }
        
        // Import sample data if requested
        if (isset($_POST['import_sample_data']) && file_exists($config['sample_data_file'])) {
            $sql = file_get_contents($config['sample_data_file']);
            $statements = explode(';', $sql);
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    $pdo->exec($statement);
                }
            }
            
            $success[] = 'Sample data imported successfully';
        }
        
        return 4; // Next step
        
    } catch (PDOException $e) {
        $errors[] = 'Database setup failed: ' . $e->getMessage();
        return 3; // Stay on current step
    }
}

/**
 * Handle admin account creation
 */
function handleAdminCreation() {
    global $errors, $success;
    
    $admin_email = trim($_POST['admin_email'] ?? '');
    $admin_password = $_POST['admin_password'] ?? '';
    $admin_confirm = $_POST['admin_confirm'] ?? '';
    $admin_name = trim($_POST['admin_name'] ?? '');
    
    // Validate inputs
    if (empty($admin_email) || !filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid admin email is required';
    }
    if (empty($admin_password) || strlen($admin_password) < 8) {
        $errors[] = 'Admin password must be at least 8 characters';
    }
    if ($admin_password !== $admin_confirm) {
        $errors[] = 'Password confirmation does not match';
    }
    if (empty($admin_name)) {
        $errors[] = 'Admin name is required';
    }
    
    if (empty($errors)) {
        try {
            $db_config = $_SESSION['db_config'];
            $dsn = "mysql:host={$db_config['host']};port={$db_config['port']};dbname={$db_config['name']};charset=utf8mb4";
            $pdo = new PDO($dsn, $db_config['user'], $db_config['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            
            // Create admin user
            $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
            $username = 'admin_' . uniqid();
            
            $stmt = $pdo->prepare("
                INSERT INTO users (username, email, password, first_name, last_name, phone, role, status, email_verified_at) 
                VALUES (?, ?, ?, ?, 'Administrator', '+237000000000', 'admin', 'active', NOW())
            ");
            
            $stmt->execute([$username, $admin_email, $hashed_password, $admin_name]);
            
            $_SESSION['admin_created'] = true;
            $success[] = 'Admin account created successfully';
            return 5; // Next step
            
        } catch (PDOException $e) {
            $errors[] = 'Failed to create admin account: ' . $e->getMessage();
        }
    }
    
    return 4; // Stay on current step
}

/**
 * Handle final configuration
 */
function handleFinalConfiguration() {
    global $errors, $success;
    
    $app_name = trim($_POST['app_name'] ?? 'Time2Eat');
    $app_url = trim($_POST['app_url'] ?? '');
    $app_env = $_POST['app_env'] ?? 'production';
    
    // Generate secure keys
    $app_key = bin2hex(random_bytes(32));
    $jwt_secret = bin2hex(random_bytes(32));
    
    // Create .env file
    $env_content = generateEnvFile($_SESSION['db_config'], [
        'app_name' => $app_name,
        'app_url' => $app_url,
        'app_env' => $app_env,
        'app_key' => $app_key,
        'jwt_secret' => $jwt_secret
    ]);
    
    if (file_put_contents('.env', $env_content)) {
        $success[] = 'Environment configuration created';
    } else {
        $errors[] = 'Failed to create .env file. Please check file permissions.';
    }
    
    // Create required directories
    createRequiredDirectories();
    
    // Create installation lock file
    if (file_put_contents('config/installed.lock', date('Y-m-d H:i:s'))) {
        $success[] = 'Installation lock created';
    }
    
    if (empty($errors)) {
        return 6; // Installation complete
    }
    
    return 5; // Stay on current step
}

/**
 * Generate .env file content
 */
function generateEnvFile($db_config, $app_config) {
    return "# Time2Eat Environment Configuration
# Generated on " . date('Y-m-d H:i:s') . "

# Application
APP_NAME=\"{$app_config['app_name']}\"
APP_URL={$app_config['app_url']}
APP_ENV={$app_config['app_env']}
APP_DEBUG=" . ($app_config['app_env'] === 'development' ? 'true' : 'false') . "
APP_KEY={$app_config['app_key']}

# Database
DB_HOST={$db_config['host']}
DB_PORT={$db_config['port']}
DB_NAME={$db_config['name']}
DB_USER={$db_config['user']}
DB_PASS={$db_config['pass']}
DB_CHARSET=utf8mb4

# Security
JWT_SECRET={$app_config['jwt_secret']}

# Mail Configuration (Update with your settings)
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@time2eat.com
MAIL_FROM_NAME=\"Time2Eat\"

# Payment Gateways (Update with your credentials)
STRIPE_PUBLIC_KEY=pk_test_your_stripe_public_key
STRIPE_SECRET_KEY=sk_test_your_stripe_secret_key
PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_CLIENT_SECRET=your_paypal_client_secret
TRANZAK_API_KEY=your_tranzak_api_key

# SMS Configuration (Twilio)
TWILIO_SID=your_twilio_sid
TWILIO_TOKEN=your_twilio_token
TWILIO_FROM=+1234567890

# Maps API
MAP_API_KEY=your_google_maps_api_key
MAP_PROVIDER=google

# File Storage
STORAGE_DRIVER=local
STORAGE_PATH=storage/

# Cache
CACHE_DRIVER=file
CACHE_PATH=cache/

# Session
SESSION_DRIVER=file
SESSION_LIFETIME=7200

# Timezone
APP_TIMEZONE=Africa/Douala
";
}

/**
 * Create required directories
 */
function createRequiredDirectories() {
    global $config, $success, $errors;
    
    foreach ($config['required_directories'] as $dir) {
        if (!is_dir($dir)) {
            if (mkdir($dir, 0755, true)) {
                $success[] = "Created directory: {$dir}";
            } else {
                $errors[] = "Failed to create directory: {$dir}";
            }
        }
        
        // Create .htaccess for security
        if (in_array($dir, ['logs', 'cache', 'storage'])) {
            $htaccess = $dir . '/.htaccess';
            if (!file_exists($htaccess)) {
                file_put_contents($htaccess, "Deny from all\n");
            }
        }
    }
}

/**
 * Check system requirements
 */
function checkSystemRequirements() {
    global $config;
    
    $requirements = [];
    
    // PHP Version
    $requirements['php_version'] = [
        'name' => 'PHP Version (' . $config['min_php_version'] . '+)',
        'status' => version_compare(PHP_VERSION, $config['min_php_version'], '>='),
        'current' => PHP_VERSION
    ];
    
    // PHP Extensions
    foreach ($config['required_extensions'] as $ext) {
        $requirements['ext_' . $ext] = [
            'name' => "PHP Extension: {$ext}",
            'status' => extension_loaded($ext),
            'current' => extension_loaded($ext) ? 'Loaded' : 'Not loaded'
        ];
    }
    
    // File Permissions
    $requirements['writable_root'] = [
        'name' => 'Root Directory Writable',
        'status' => is_writable('.'),
        'current' => is_writable('.') ? 'Writable' : 'Not writable'
    ];
    
    return $requirements;
}

// Handle step 1 (requirements check)
if ($current_step === 1 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $requirements = checkSystemRequirements();
    $all_passed = true;
    
    foreach ($requirements as $req) {
        if (!$req['status']) {
            $all_passed = false;
            break;
        }
    }
    
    if ($all_passed) {
        $current_step = 2;
    } else {
        $errors[] = 'Please fix the system requirements before continuing.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time2Eat Installation</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .step-active { @apply bg-red-500 text-white; }
        .step-completed { @apply bg-green-500 text-white; }
        .step-pending { @apply bg-gray-300 text-gray-600; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Time2Eat Installation</h1>
            <p class="text-gray-600">Welcome to the Time2Eat food delivery platform installer</p>
        </div>

        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex flex-wrap justify-center space-x-2 mb-4">
                <?php foreach ($steps as $step_num => $step_name): ?>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold
                            <?= $step_num < $current_step ? 'step-completed' : ($step_num === $current_step ? 'step-active' : 'step-pending') ?>">
                            <?= $step_num ?>
                        </div>
                        <?php if ($step_num < count($steps)): ?>
                            <div class="w-8 h-0.5 bg-gray-300 mx-2"></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center">
                <h2 class="text-xl font-semibold text-gray-800"><?= $steps[$current_step] ?></h2>
            </div>
        </div>

        <!-- Messages -->
        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    <?php foreach ($success as $message): ?>
                        <li><?= htmlspecialchars($message) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Installation Steps Content -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <?php
            // Include the appropriate step content
            switch ($current_step) {
                case 1:
                    include 'install_steps/step1_requirements.php';
                    break;
                case 2:
                    include 'install_steps/step2_database.php';
                    break;
                case 3:
                    include 'install_steps/step3_setup.php';
                    break;
                case 4:
                    include 'install_steps/step4_admin.php';
                    break;
                case 5:
                    include 'install_steps/step5_config.php';
                    break;
                case 6:
                    include 'install_steps/step6_complete.php';
                    break;
            }
            ?>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-gray-500">
            <p>&copy; <?= date('Y') ?> Time2Eat. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
