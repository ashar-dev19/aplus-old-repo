<?php
// Direct RBAC role assignment compatible with PHP 5.6
echo "=== DIRECT RBAC ROLE ASSIGNMENT ===\n";

// Database connection
$host = 'srv1772.hstgr.io';
$port = '3306';
$dbname = 'u925491864_betaAplus';
$username = 'u925491864_betaAplusUser';
$password = 'betaAplusUser123';

try {
    $pdo = new PDO("mysql:host={$host};port={$port};dbname={$dbname};charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database connected successfully\n";
    
    // Check if auth_item table exists (stores roles)
    $stmt = $pdo->query("SHOW TABLES LIKE 'auth_item'");
    if (!$stmt->fetch()) {
        echo "❌ auth_item table doesn't exist. RBAC might not be set up.\n";
        exit(1);
    }
    
    // Check if 'user' role exists
    $stmt = $pdo->prepare("SELECT name FROM auth_item WHERE name = 'user' AND type = 1");
    $stmt->execute();
    if (!$stmt->fetch()) {
        echo "Creating 'user' role...\n";
        $stmt = $pdo->prepare("INSERT INTO auth_item (name, type, description, created_at, updated_at) VALUES ('user', 1, 'Regular User', ?, ?)");
        $currentTime = time();
        $stmt->execute(array($currentTime, $currentTime));
        echo "✅ 'user' role created\n";
    } else {
        echo "✅ 'user' role already exists\n";
    }
    
    // Get all users from users1 table
    $stmt = $pdo->query("SELECT id, username, email FROM users1");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($users) . " users to assign roles to...\n";
    
    $assignedCount = 0;
    $skippedCount = 0;
    
    foreach ($users as $user) {
        // Check if user already has the role
        $checkStmt = $pdo->prepare("SELECT item_name FROM auth_assignment WHERE item_name = 'user' AND user_id = ?");
        $checkStmt->execute(array($user['id']));
        
        if ($checkStmt->fetch()) {
            echo "User ID {$user['id']} ({$user['username']}) already has 'user' role - skipping\n";
            $skippedCount++;
            continue;
        }
        
        // Assign the role
        $assignStmt = $pdo->prepare("INSERT INTO auth_assignment (item_name, user_id, created_at) VALUES ('user', ?, ?)");
        $assignStmt->execute(array($user['id'], time()));
        
        echo "✅ Assigned 'user' role to ID {$user['id']} ({$user['username']})\n";
        $assignedCount++;
    }
    
    echo "\n=== RBAC ROLE ASSIGNMENT COMPLETE ===\n";
    echo "✅ Successfully assigned: {$assignedCount} users\n";
    echo "⚠️  Already had role: {$skippedCount} users\n";
    
    // Verify total assignments
    $stmt = $pdo->query("SELECT COUNT(*) FROM auth_assignment WHERE item_name = 'user'");
    $totalAssignments = $stmt->fetchColumn();
    echo "📊 Total 'user' role assignments: {$totalAssignments}\n";
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
