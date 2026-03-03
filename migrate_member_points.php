<?php
// Migrate member_points to points table
echo "=== MIGRATE MEMBER_POINTS TO POINTS ===\n";

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
    
    // Check current counts
    echo "\n=== CURRENT DATA ===\n";
    $stmt = $pdo->query("SELECT COUNT(*) FROM member_points");
    $memberPointsCount = $stmt->fetchColumn();
    echo "member_points records: {$memberPointsCount}\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM points");
    $pointsCount = $stmt->fetchColumn();
    echo "points records: {$pointsCount}\n";
    
    if ($memberPointsCount == 0) {
        echo "❌ No member_points records to migrate!\n";
        exit(1);
    }
    
    // Check mapping between member_id and student_id
    echo "\n=== CHECKING ID MAPPING ===\n";
    $stmt = $pdo->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN s.id IS NOT NULL THEN 1 ELSE 0 END) as with_student,
            SUM(CASE WHEN s.id IS NULL THEN 1 ELSE 0 END) as without_student
        FROM member_points mp
        LEFT JOIN student s ON mp.member_id = s.id
    ");
    $mapping = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Total member_points: {$mapping['total']}\n";
    echo "With matching student: {$mapping['with_student']}\n";
    echo "Without matching student: {$mapping['without_student']}\n";
    
    if ($mapping['without_student'] > 0) {
        echo "⚠️  Warning: {$mapping['without_student']} member_points have no matching student\n";
        echo "These will be skipped during migration\n";
    }
    
    // Show sample data
    echo "\n=== SAMPLE DATA TO MIGRATE ===\n";
    $stmt = $pdo->query("
        SELECT mp.id, mp.member_id, mp.points, s.full_name
        FROM member_points mp
        INNER JOIN student s ON mp.member_id = s.id
        ORDER BY mp.id
        LIMIT 5
    ");
    $samples = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($samples as $sample) {
        echo "ID {$sample['id']}: Member {$sample['member_id']} → Student '{$sample['full_name']}' ({$sample['points']} points)\n";
    }
    
    echo "\n🚨 Ready to migrate {$mapping['with_student']} records\n";
    echo "🚨 TO PERFORM MIGRATION, uncomment the migration code below\n\n";
    
    // MIGRATION CODE - Uncomment to execute
    /*
    echo "=== PERFORMING MIGRATION ===\n";
    
    $insertStmt = $pdo->prepare("
        INSERT INTO points (
            points,
            student_id,
            details,
            status,
            created_at,
            update_at
        ) VALUES (?, ?, ?, 1, ?, ?)
    ");
    
    $stmt = $pdo->query("
        SELECT mp.id, mp.member_id, mp.points, s.full_name
        FROM member_points mp
        INNER JOIN student s ON mp.member_id = s.id
        WHERE mp.points IS NOT NULL
        ORDER BY mp.id
    ");
    
    $migratedCount = 0;
    $currentTime = time();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $details = "Migrated from member_points ID: {$row['id']}";
        
        $insertStmt->execute([
            $row['points'],
            $row['member_id'], // This becomes student_id
            $details,
            $currentTime,
            $currentTime
        ]);
        
        $migratedCount++;
        
        if ($migratedCount % 50 == 0) {
            echo "Migrated {$migratedCount} records...\n";
        }
    }
    
    echo "✅ Successfully migrated {$migratedCount} records\n";
    
    // Verify migration
    $stmt = $pdo->query("SELECT COUNT(*) FROM points WHERE details LIKE 'Migrated from member_points%'");
    $migratedInDb = $stmt->fetchColumn();
    echo "📊 Migrated records in database: {$migratedInDb}\n";
    */
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
