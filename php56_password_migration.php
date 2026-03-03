<?php
// PHP 5.6 compatible password migration
echo "=== PHP 5.6 COMPATIBLE PASSWORD MIGRATION ===\n";

// Database connection settings - UPDATE THESE WITH YOUR ACTUAL CREDENTIALS

// Database connection settings - CORRECT CREDENTIALS
$host = 'srv1772.hstgr.io';
$port = '3306';
$dbname = 'u925491864_betaAplus';
$username = 'u925491864_betaAplusUser';
$password = 'betaAplusUser123';

try {
    // PDO connection
    $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database connected successfully\n";
    
    // Check users1 table
    $stmt = $pdo->query("SELECT COUNT(*) FROM users1");
    $users1Count = $stmt->fetchColumn();
    echo "users1 table has {$users1Count} records\n";
    
    if ($users1Count == 0) {
        echo "❌ users1 table is empty! Please run the SQL migration first.\n";
        exit(1);
    }
    
    // Get users from old table with passwords
    $stmt = $pdo->prepare("
        SELECT id, password 
        FROM users 
        WHERE is_deleted = 0 AND password IS NOT NULL AND password != ''
    ");
    $stmt->execute();
    $oldUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($oldUsers) . " users with passwords in old table\n";
    
    if (empty($oldUsers)) {
        echo "❌ No users with passwords found in old users table!\n";
        exit(1);
    }
    
    $updateCount = 0;
    
    foreach ($oldUsers as $user) {
        echo "Processing user ID: {$user['id']}\n";
        
        // Create a hash compatible with Yii 2.0 (using crypt with blowfish)
        // This is compatible with PHP 5.6 and Yii 2.0's password verification
        $salt = '$2y$13$' . substr(str_replace('+', '.', base64_encode(pack('N4', mt_rand(), mt_rand(), mt_rand(), mt_rand()))), 0, 22);
        $hashedPassword = crypt($user['password'], $salt);
        
        echo "  Generated hash: " . substr($hashedPassword, 0, 20) . "...\n";
        
        // Update users1 table
        $updateStmt = $pdo->prepare("UPDATE users1 SET password_hash = ? WHERE id = ?");
        $result = $updateStmt->execute(array($hashedPassword, $user['id']));
        
        if ($result && $updateStmt->rowCount() > 0) {
            echo "  ✅ Updated successfully\n";
            $updateCount++;
        } else {
            echo "  ❌ Update failed\n";
        }
    }
    
    echo "\n=== MIGRATION COMPLETE ===\n";
    echo "Successfully updated {$updateCount} passwords\n";
    
    // Verify results
    $stmt = $pdo->query("SELECT COUNT(*) FROM users1 WHERE password_hash IS NOT NULL AND password_hash != ''");
    $withPasswords = $stmt->fetchColumn();
    echo "users1 records with password_hash: {$withPasswords}\n";
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
