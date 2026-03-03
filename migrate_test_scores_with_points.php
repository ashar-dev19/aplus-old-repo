<?php
// Migrate topic_index_test_score to lesson_test_attempt with points calculation
echo "=== MIGRATE TEST SCORES WITH POINTS CALCULATION ===\n";

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
    echo "\n=== CURRENT DATA ANALYSIS ===\n";
    $stmt = $pdo->query("SELECT COUNT(*) FROM topic_index_test_score");
    $oldCount = $stmt->fetchColumn();
    echo "topic_index_test_score records: {$oldCount}\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM lesson_test_attempt");
    $newCount = $stmt->fetchColumn();
    echo "lesson_test_attempt records: {$newCount}\n";
    
    if ($oldCount == 0) {
        echo "❌ No records to migrate!\n";
        exit(1);
    }
    
    // Check student mapping
    echo "\n=== STUDENT MAPPING ANALYSIS ===\n";
    $stmt = $pdo->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN s.id IS NOT NULL THEN 1 ELSE 0 END) as with_student,
            SUM(CASE WHEN s.id IS NULL THEN 1 ELSE 0 END) as without_student
        FROM topic_index_test_score tits
        LEFT JOIN student s ON tits.member_id = s.id
    ");
    $mapping = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Total test scores: {$mapping['total']}\n";
    echo "With matching student: {$mapping['with_student']}\n";
    echo "Without matching student: {$mapping['without_student']}\n";
    
    if ($mapping['without_student'] > 0) {
        echo "⚠️  Warning: {$mapping['without_student']} test scores have no matching student\n";
    }
    
    // Points calculation analysis
    echo "\n=== POINTS CALCULATION PREVIEW ===\n";
    $stmt = $pdo->query("
        SELECT 
            attempt,
            COUNT(*) as total_attempts,
            SUM(CASE WHEN (score / total_score) >= 0.8 THEN 1 ELSE 0 END) as passing_attempts,
            SUM(CASE 
                WHEN (score / total_score) >= 0.8 AND attempt <= 2 THEN 100
                WHEN (score / total_score) >= 0.8 AND attempt = 3 THEN 50
                WHEN (score / total_score) >= 0.8 AND attempt > 3 THEN 10
                ELSE 0
            END) as total_points
        FROM topic_index_test_score
        WHERE total_score > 0
        GROUP BY attempt
        ORDER BY attempt
    ");
    $pointsPreview = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $totalPointsToAward = 0;
    foreach ($pointsPreview as $preview) {
        echo "Attempt {$preview['attempt']}: {$preview['total_attempts']} total, {$preview['passing_attempts']} passing (≥80%), {$preview['total_points']} points to award\n";
        $totalPointsToAward += $preview['total_points'];
    }
    echo "📊 Total points to be awarded: {$totalPointsToAward}\n";
    
    // Sample data preview
    echo "\n=== SAMPLE DATA PREVIEW ===\n";
    $stmt = $pdo->query("
        SELECT 
            tits.id,
            tits.topic_index_id,
            tits.member_id,
            s.full_name,
            tits.attempt,
            tits.score,
            tits.total_score,
            ROUND((tits.score / tits.total_score) * 100, 2) as percentage,
            CASE 
                WHEN (tits.score / tits.total_score) >= 0.8 AND tits.attempt <= 2 THEN 100
                WHEN (tits.score / tits.total_score) >= 0.8 AND tits.attempt = 3 THEN 50
                WHEN (tits.score / tits.total_score) >= 0.8 AND tits.attempt > 3 THEN 10
                ELSE 0
            END as points_earned
        FROM topic_index_test_score tits
        INNER JOIN student s ON tits.member_id = s.id
        WHERE tits.total_score > 0
        ORDER BY tits.member_id, tits.topic_index_id, tits.attempt
        LIMIT 10
    ");
    $samples = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($samples as $sample) {
        echo "Student '{$sample['full_name']}', Attempt {$sample['attempt']}: {$sample['score']}/{$sample['total_score']} ({$sample['percentage']}%) → {$sample['points_earned']} points\n";
    }
    
    echo "\n🚨 Ready to migrate {$mapping['with_student']} records\n";
    echo "🚨 TO PERFORM MIGRATION, uncomment the migration code below\n\n";
    
    // MIGRATION CODE - Uncomment to execute

    echo "=== PERFORMING MIGRATION ===\n";
    
    $insertStmt = $pdo->prepare("
        INSERT INTO lesson_test_attempt (
            lesson_test_id,
            student_id,
            attempt,
            score,
            total_score,
            points_earned,
            status,
            created_at,
            updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt = $pdo->query("
        SELECT 
            tits.*,
            CASE 
                WHEN (tits.score / tits.total_score) >= 0.8 AND tits.attempt <= 2 THEN 100
                WHEN (tits.score / tits.total_score) >= 0.8 AND tits.attempt = 3 THEN 50
                WHEN (tits.score / tits.total_score) >= 0.8 AND tits.attempt > 3 THEN 10
                ELSE 0
            END as calculated_points
        FROM topic_index_test_score tits
        INNER JOIN student s ON tits.member_id = s.id
        WHERE tits.score IS NOT NULL 
        AND tits.total_score IS NOT NULL 
        AND tits.total_score > 0
        ORDER BY tits.id
    ");
    
    $migratedCount = 0;
    $totalPointsAwarded = 0;
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $createdAt = $row['date_started'] ? strtotime($row['date_started']) : time();
        $updatedAt = $row['date_completed'] ? strtotime($row['date_completed']) : time();
        
        $insertStmt->execute([
            $row['topic_index_id'],
            $row['member_id'],
            $row['attempt'],
            $row['score'],
            $row['total_score'],
            $row['calculated_points'],
            $row['status'],
            $createdAt,
            $updatedAt
        ]);
        
        $migratedCount++;
        $totalPointsAwarded += $row['calculated_points'];
        
        if ($migratedCount % 100 == 0) {
            echo "Migrated {$migratedCount} records...\n";
        }
    }
    
    echo "✅ Successfully migrated {$migratedCount} records\n";
    echo "📊 Total points awarded: {$totalPointsAwarded}\n";
    
    // Verification
    $stmt = $pdo->query("SELECT COUNT(*) FROM lesson_test_attempt");
    $finalCount = $stmt->fetchColumn();
    echo "📊 Final lesson_test_attempt count: {$finalCount}\n";

    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
