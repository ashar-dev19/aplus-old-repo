<?php
// Direct password migration without full Yii bootstrap
echo "=== DIRECT PASSWORD MIGRATION ===\n";

// Database connection settings - adjust these to match your config
$host = 'localhost';
$dbname = 'u925491864_betaAplus';  // Replace with your actual database name
$username = 'u925491864_betaAplusUser';     // Replace with your actual username  
$password = 'betaAplusUser123';      // Replace with your actual password

try {
    // Direct PDO connection
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
        
        // Hash password using PHP's password_hash (compatible with Yii 2.0)
        $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);
        echo "  Generated hash: " . substr($hashedPassword, 0, 20) . "...\n";
        
        // Update users1 table
        $updateStmt = $pdo->prepare("UPDATE users1 SET password_hash = ? WHERE id = ?");
        $result = $updateStmt->execute([$hashedPassword, $user['id']]);
        
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
