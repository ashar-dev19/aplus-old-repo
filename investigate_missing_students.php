<?php
// Investigate missing students from member_points
echo "=== INVESTIGATE MISSING STUDENTS ===\n";

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
    
    // 1. Check if original members table still exists
    echo "\n=== CHECKING ORIGINAL TABLES ===\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'members'");
    $membersExists = $stmt->rowCount() > 0;
    echo "Original 'members' table exists: " . ($membersExists ? "YES" : "NO") . "\n";
    
    if ($membersExists) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM members");
        $membersCount = $stmt->fetchColumn();
        echo "Total members in original table: {$membersCount}\n";
    }
    
    // 2. Check ID ranges
    echo "\n=== ID RANGE ANALYSIS ===\n";
    $stmt = $pdo->query("SELECT MIN(member_id) as min_id, MAX(member_id) as max_id FROM member_points");
    $pointsRange = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "member_points ID range: {$pointsRange['min_id']} to {$pointsRange['max_id']}\n";
    
    $stmt = $pdo->query("SELECT MIN(id) as min_id, MAX(id) as max_id FROM student");
    $studentRange = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "student ID range: {$studentRange['min_id']} to {$studentRange['max_id']}\n";
    
    // 3. Show sample of missing member_ids
    echo "\n=== SAMPLE MISSING MEMBER IDs ===\n";
    $stmt = $pdo->query("
        SELECT mp.member_id, mp.points, COUNT(*) as points_count
        FROM member_points mp
        LEFT JOIN student s ON mp.member_id = s.id
        WHERE s.id IS NULL
        GROUP BY mp.member_id
        ORDER BY points_count DESC, mp.member_id
        LIMIT 15
    ");
    $missing = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($missing as $miss) {
        echo "Missing member_id {$miss['member_id']}: {$miss['points_count']} point records\n";
    }
    
    // 4. If original members table exists, check what happened to those members
    if ($membersExists) {
        echo "\n=== CHECKING ORIGINAL MEMBERS STATUS ===\n";
        $stmt = $pdo->query("
            SELECT 
                COUNT(*) as total_missing_members,
                SUM(CASE WHEN m.id IS NOT NULL THEN 1 ELSE 0 END) as exist_in_members,
                SUM(CASE WHEN m.id IS NULL THEN 1 ELSE 0 END) as not_in_members
            FROM (
                SELECT DISTINCT mp.member_id
                FROM member_points mp
                LEFT JOIN student s ON mp.member_id = s.id
                WHERE s.id IS NULL
            ) missing_ids
            LEFT JOIN members m ON missing_ids.member_id = m.id
        ");
        $memberStatus = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "Missing members analysis:\n";
        echo "- Total unique missing member IDs: {$memberStatus['total_missing_members']}\n";
        echo "- Still exist in original members table: {$memberStatus['exist_in_members']}\n";
        echo "- Don't exist in original members table: {$memberStatus['not_in_members']}\n";
        
        // Show sample of members that exist but weren't migrated
        echo "\n=== MEMBERS NOT MIGRATED TO STUDENTS ===\n";
        $stmt = $pdo->query("
            SELECT m.id, m.name, m.status, u.email, u.is_deleted
            FROM members m
            INNER JOIN users u ON m.login_id = u.id
            LEFT JOIN student s ON m.id = s.id
            WHERE s.id IS NULL
            ORDER BY m.id
            LIMIT 10
        ");
        $notMigrated = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($notMigrated as $member) {
            $userStatus = $member['is_deleted'] ? 'DELETED' : 'ACTIVE';
            echo "Member ID {$member['id']}: '{$member['name']}' (status: {$member['status']}, user: {$userStatus})\n";
        }
    }
    
    // 5. Check if there's a pattern in the missing IDs
    echo "\n=== MISSING ID PATTERNS ===\n";
    $stmt = $pdo->query("
        SELECT 
            CASE 
                WHEN mp.member_id < 100 THEN 'Under 100'
                WHEN mp.member_id < 1000 THEN '100-999'
                WHEN mp.member_id < 10000 THEN '1000-9999'
                ELSE 'Over 10000'
            END as id_range,
            COUNT(DISTINCT mp.member_id) as missing_count
        FROM member_points mp
        LEFT JOIN student s ON mp.member_id = s.id
        WHERE s.id IS NULL
        GROUP BY 
            CASE 
                WHEN mp.member_id < 100 THEN 'Under 100'
                WHEN mp.member_id < 1000 THEN '100-999'
                WHEN mp.member_id < 10000 THEN '1000-9999'
                ELSE 'Over 10000'
            END
        ORDER BY missing_count DESC
    ");
    $patterns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($patterns as $pattern) {
        echo "ID range {$pattern['id_range']}: {$pattern['missing_count']} missing members\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
