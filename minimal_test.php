<?php
echo "=== MINIMAL TEST SCRIPT ===\n";

// Test 1: Basic PHP
echo "✅ PHP is working\n";

// Test 2: Check if autoload exists
echo "Checking autoload...\n";
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "✅ vendor/autoload.php exists\n";
    require __DIR__ . '/vendor/autoload.php';
    echo "✅ autoload loaded successfully\n";
} else {
    echo "❌ vendor/autoload.php NOT FOUND\n";
    exit(1);
}

// Test 3: Check if Yii.php exists
echo "Checking Yii.php...\n";
if (file_exists(__DIR__ . '/vendor/yiisoft/yii2/Yii.php')) {
    echo "✅ Yii.php exists\n";
    require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
    echo "✅ Yii.php loaded successfully\n";
} else {
    echo "❌ Yii.php NOT FOUND\n";
    exit(1);
}

// Test 4: Check config files
$configFiles = [
    '/common/config/bootstrap.php',
    '/console/config/bootstrap.php',
    '/common/config/main.php',
    '/console/config/main.php'
];

foreach ($configFiles as $file) {
    $fullPath = __DIR__ . $file;
    if (file_exists($fullPath)) {
        echo "✅ {$file} exists\n";
    } else {
        echo "❌ {$file} NOT FOUND\n";
        exit(1);
    }
}

echo "=== ALL BASIC CHECKS PASSED ===\n";
