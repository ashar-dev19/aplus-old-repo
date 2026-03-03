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

echo "=== DEBUG MIGRATION ===\n";

try {
    // Check if tables exist
    $tables = ['users', 'members', 'users1', 'student'];
    foreach ($tables as $table) {
        $exists = Yii::$app->db->schema->getTableSchema($table) !== null;
        echo "Table '{$table}': " . ($exists ? "EXISTS" : "MISSING") . "\n";
        
        if ($exists) {
            $count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM {$table}")->queryScalar();
            echo "  - Records: {$count}\n";
        }
    }
    
    // Check users1 table specifically
    if (Yii::$app->db->schema->getTableSchema('users1') !== null) {
        $withPasswords = Yii::$app->db->createCommand("SELECT COUNT(*) FROM users1 WHERE password_hash IS NOT NULL AND password_hash != ''")->queryScalar();
        $withoutPasswords = Yii::$app->db->createCommand("SELECT COUNT(*) FROM users1 WHERE password_hash IS NULL OR password_hash = ''")->queryScalar();
        echo "Users1 with passwords: {$withPasswords}\n";
        echo "Users1 without passwords: {$withoutPasswords}\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
