<?php
// Direct RBAC role assignment for rbac_auth_assignment table
echo "=== RBAC USER ROLE ASSIGNMENT ===\n";

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
    
    // Check if rbac_auth_item table exists (stores roles)
    $stmt = $pdo->query("SHOW TABLES LIKE 'rbac_auth_item'");
    if (!$stmt->fetch()) {
        echo "❌ rbac_auth_item table doesn't exist. RBAC might not be set up.\n";
        exit(1);
    }
    
    // Check if 'user' role exists in rbac_auth_item
    $stmt = $pdo->prepare("SELECT name FROM rbac_auth_item WHERE name = 'user' AND type = 1");
    $stmt->execute();
    if (!$stmt->fetch()) {
        echo "Creating 'user' role in rbac_auth_item...\n";
        $stmt = $pdo->prepare("INSERT INTO rbac_auth_item (name, type, description, created_at, updated_at) VALUES ('user', 1, 'Regular User', ?, ?)");
        $currentTime = time();
        $stmt->execute(array($currentTime, $currentTime));
        echo "✅ 'user' role created\n";
    } else {
        echo "✅ 'user' role already exists in rbac_auth_item\n";
    }
    
    // Get all users from users1 table
    $stmt = $pdo->query("SELECT id, username, email FROM users1");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($users) . " users in users1 table...\n";
    
    // Check current user role assignments
    $stmt = $pdo->query("SELECT COUNT(*) FROM rbac_auth_assignment WHERE item_name = 'user'");
    $currentAssignments = $stmt->fetchColumn();
    echo "Current 'user' role assignments: {$currentAssignments}\n\n";
    
    $assignedCount = 0;
    $skippedCount = 0;
    $errorCount = 0;
    
    foreach ($users as $user) {
        try {
            // Check if user already has the 'user' role
            $checkStmt = $pdo->prepare("SELECT item_name FROM rbac_auth_assignment WHERE item_name = 'user' AND user_id = ?");
            $checkStmt->execute(array($user['id']));
            
            if ($checkStmt->fetch()) {
                echo "User ID {$user['id']} ({$user['username']}) already has 'user' role - skipping\n";
                $skippedCount++;
                continue;
            }
            
            // Assign the 'user' role
            $assignStmt = $pdo->prepare("INSERT INTO rbac_auth_assignment (item_name, user_id, created_at) VALUES ('user', ?, ?)");
            $assignStmt->execute(array($user['id'], time()));
            
            echo "✅ Assigned 'user' role to ID {$user['id']} ({$user['username']})\n";
            $assignedCount++;
            
        } catch (Exception $e) {
            echo "❌ Error assigning role to user ID {$user['id']}: " . $e->getMessage() . "\n";
            $errorCount++;
        }
    }
    
    echo "\n=== RBAC ROLE ASSIGNMENT COMPLETE ===\n";
    echo "✅ Successfully assigned: {$assignedCount} users\n";
    echo "⚠️  Already had role: {$skippedCount} users\n";
    echo "❌ Errors: {$errorCount} users\n";
    
    // Verify final total assignments
    $stmt = $pdo->query("SELECT COUNT(*) FROM rbac_auth_assignment WHERE item_name = 'user'");
    $finalAssignments = $stmt->fetchColumn();
    echo "📊 Total 'user' role assignments after migration: {$finalAssignments}\n";
    
    // Show summary of all roles
    echo "\n=== ROLE SUMMARY ===\n";
    $stmt = $pdo->query("SELECT item_name, COUNT(*) as count FROM rbac_auth_assignment GROUP BY item_name ORDER BY count DESC");
    $roleCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($roleCounts as $role) {
        echo "Role '{$role['item_name']}': {$role['count']} users\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
