<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/common/config/bootstrap.php';
require __DIR__ . '/console/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/common/config/main.php',
    require __DIR__ . '/common/config/main-local.php',
    require __DIR__ . '/console/config/main.php',
    require __DIR__ . '/console/config/main-local.php'
);

new yii\console\Application($config);

echo "=== PASSWORD MIGRATION SCRIPT ===\n";

try {
    // Check users1 table
    $users1Count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM users1")->queryScalar();
    echo "users1 table has {$users1Count} records\n";
    
    if ($users1Count == 0) {
        echo "ERROR: users1 table is empty! Please run the SQL migration first.\n";
        exit(1);
    }
    
    // Get users from old table with passwords
    $oldUsers = Yii::$app->db->createCommand("
        SELECT id, password 
        FROM users 
        WHERE is_deleted = 0 AND password IS NOT NULL AND password != ''
    ")->queryAll();
    
    echo "Found " . count($oldUsers) . " users with passwords in old table\n";
    
    if (empty($oldUsers)) {
        echo "ERROR: No users with passwords found in old users table!\n";
        exit(1);
    }
    
    $updateCount = 0;
    
    foreach ($oldUsers as $user) {
        echo "Processing user ID: {$user['id']}\n";
        
        // Hash password
        $hashedPassword = Yii::$app->security->generatePasswordHash($user['password']);
        echo "  Generated hash: " . substr($hashedPassword, 0, 20) . "...\n";
        
        // Update users1 table
        $result = Yii::$app->db->createCommand()->update('users1', [
            'password_hash' => $hashedPassword
        ], ['id' => $user['id']])->execute();
        
        if ($result) {
            echo "  ✅ Updated successfully\n";
            $updateCount++;
        } else {
            echo "  ❌ Update failed\n";
        }
    }
    
    echo "\n=== MIGRATION COMPLETE ===\n";
    echo "Successfully updated {$updateCount} passwords\n";
    
    // Verify results
    $withPasswords = Yii::$app->db->createCommand("SELECT COUNT(*) FROM users1 WHERE password_hash IS NOT NULL AND password_hash != ''")->queryScalar();
    echo "users1 records with password_hash: {$withPasswords}\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
