<?php
// Simple script to find Yii and send a test email

// Define possible paths where Yii might be located
$possiblePaths = [
    // Standard paths
    _DIR_ . '/vendor/yiisoft/yii2/Yii.php',
    _DIR_ . '/../vendor/yiisoft/yii2/Yii.php',
    // Yii advanced app possible paths
    _DIR_ . '/../../vendor/yiisoft/yii2/Yii.php',
    // Custom paths - add more if needed
    _DIR_ . '/yiisoft/yii2/Yii.php',
    _DIR_ . '/framework/Yii.php',
    // Try to find in PHP include path
    'yii2/Yii.php',
];

// Try to find Yii.php
$yiiFound = false;
foreach ($possiblePaths as $path) {
    if (file_exists($path)) {
        require($path);
        $yiiFound = true;
        echo "<!-- Found Yii at: {$path} -->\n";
        break;
    }
}

if (!$yiiFound) {
    die("Error: Could not find Yii.php. Please specify the correct path to Yii.php in the script.");
}

// Try to find config file
$possibleConfigPaths = [
    _DIR_ . '/config/web.php',
    _DIR_ . '/../config/web.php',
    _DIR_ . '/protected/config/main.php',
    _DIR_ . '/app/config/web.php',
];

$configFound = false;
foreach ($possibleConfigPaths as $path) {
    if (file_exists($path)) {
        $config = require($path);
        $configFound = true;
        echo "<!-- Found config at: {$path} -->\n";
        break;
    }
}

if (!$configFound) {
    die("Error: Could not find configuration file. Please specify the correct path to your config file in the script.");
}

// Initialize the application
try {
    new yii\web\Application($config);
} catch (Exception $e) {
    die("Error initializing Yii application: " . $e->getMessage());
}

// Now try to send the email
$fromEmail = 'reports@aplustudents.com';
$toEmail = 'umairgilani64@gmail.com';
$subject = 'Test Email from Yii 2.0';
$body = '<h1>Hello!</h1><p>This is a test email sent from Yii 2.0 application.</p><p>Time: ' . date('Y-m-d H:i:s') . '</p>';

// Try to send the email
try {
    if (!isset(Yii::$app->mailer)) {
        throw new Exception("Mailer component is not configured in your application.");
    }
    
    $sent = Yii::$app->mailer->compose()
        ->setFrom([$fromEmail => 'APluStudents Reports'])
        ->setTo($toEmail)
        ->setSubject($subject)
        ->setHtmlBody($body)
        ->send();
    
    $success = $sent;
    $message = $sent ? 'Email successfully sent!' : 'Failed to send email. Check your email configuration.';
} catch (Exception $e) {
    $success = false;
    $message = 'Error: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Email Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
        }
        .header {
            background-color: #f5f5f5;
            padding: 10px 15px;
            margin: -20px -20px 20px;
            border-bottom: 1px solid #ddd;
            border-radius: 5px 5px 0 0;
        }
        .success {
            background-color: #dff0d8;
            border: 1px solid #d6e9c6;
            color: #3c763d;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .error {
            background-color: #f2dede;
            border: 1px solid #ebccd1;
            color: #a94442;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            width: 100px;
        }
        .btn {
            display: inline-block;
            padding: 6px 12px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            cursor: pointer;
            background-image: none;
            border: 1px solid transparent;
            border-radius: 4px;
            color: #fff;
            background-color: #337ab7;
            border-color: #2e6da4;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Email Test Result</h2>
        </div>
        
        <?php if ($success): ?>
            <div class="success">
                <strong>Success!</strong> <?= htmlspecialchars($message) ?>
            </div>
        <?php else: ?>
            <div class="error">
                <strong>Error!</strong> <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <h3>Email Details:</h3>
        <table>
            <tr>
                <th>From:</th>
                <td><?= htmlspecialchars($fromEmail) ?></td>
            </tr>
            <tr>
                <th>To:</th>
                <td><?= htmlspecialchars($toEmail) ?></td>
            </tr>
            <tr>
                <th>Subject:</th>
                <td><?= htmlspecialchars($subject) ?></td>
            </tr>
            <tr>
                <th>Body:</th>
                <td><?= $body ?></td>
            </tr>
        </table>
        
        <div>
            <a href="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="btn">Try Again</a>
        </div>
    </div>
</body>
</html>