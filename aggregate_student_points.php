<?php
// Aggregate student points from lesson_test_attempt to points table
echo "=== AGGREGATE STUDENT POINTS ===\n";

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
    
    // Check current data
    echo "\n=== CURRENT DATA ANALYSIS ===\n";
    $stmt = $pdo->query("SELECT COUNT(*) FROM lesson_test_attempt");
    $attemptCount = $stmt->fetchColumn();
    echo "lesson_test_attempt records: {$attemptCount}\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM points");
    $pointsCount = $stmt->fetchColumn();
    echo "points records: {$pointsCount}\n";
    
    $stmt = $pdo->query("SELECT COUNT(DISTINCT student_id) FROM lesson_test_attempt");
    $uniqueStudents = $stmt->fetchColumn();
    echo "Unique students in lesson_test_attempt: {$uniqueStudents}\n";
    
    if ($attemptCount == 0) {
        echo "❌ No lesson test attempts found!\n";
        exit(1);
    }
    
    // Analyze points to aggregate
    echo "\n=== POINTS AGGREGATION PREVIEW ===\n";
    $stmt = $pdo->query("
        SELECT 
            lta.student_id,
            s.full_name,
            COUNT(*) as total_attempts,
            SUM(lta.points_earned) as total_points,
            AVG(lta.points_earned) as avg_points
        FROM lesson_test_attempt lta
        INNER JOIN student s ON lta.student_id = s.id
        WHERE lta.points_earned > 0
        GROUP BY lta.student_id, s.full_name
        ORDER BY total_points DESC
        LIMIT 10
    ");
    $preview = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $totalPointsToAggregate = 0;
    foreach ($preview as $student) {
        echo "Student '{$student['full_name']}': {$student['total_attempts']} attempts, {$student['total_points']} total points\n";
        $totalPointsToAggregate += $student['total_points'];
    }
    
    // Get full statistics
    $stmt = $pdo->query("
        SELECT 
            COUNT(DISTINCT student_id) as students_with_points,
            SUM(points_earned) as total_points_to_aggregate
        FROM lesson_test_attempt
        WHERE points_earned > 0
    ");
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "\n📊 AGGREGATION SUMMARY:\n";
    echo "Students with points: {$stats['students_with_points']}\n";
    echo "Total points to aggregate: {$stats['total_points_to_aggregate']}\n";
    
    // Check for existing aggregated records
    $stmt = $pdo->query("
        SELECT COUNT(*) 
        FROM points 
        WHERE details LIKE '%lesson test attempts%'
    ");
    $existingAggregated = $stmt->fetchColumn();
    echo "Existing aggregated records: {$existingAggregated}\n";
    
    if ($existingAggregated > 0) {
        echo "⚠️  Warning: There are already {$existingAggregated} aggregated records. This script will skip duplicate students.\n";
    }
    
    echo "\n🚨 Ready to aggregate points for {$stats['students_with_points']} students\n";
    echo "🚨 TO PERFORM AGGREGATION, uncomment the aggregation code below\n\n";
    
    // AGGREGATION CODE - Uncomment to execute
    /*
    echo "=== PERFORMING AGGREGATION ===\n";
    
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
        SELECT 
            lta.student_id,
            s.full_name,
            SUM(lta.points_earned) as total_points,
            COUNT(*) as total_attempts
        FROM lesson_test_attempt lta
        INNER JOIN student s ON lta.student_id = s.id
        WHERE lta.points_earned > 0
        AND lta.student_id NOT IN (
            SELECT DISTINCT student_id 
            FROM points 
            WHERE details LIKE '%lesson test attempts%'
        )
        GROUP BY lta.student_id, s.full_name
        HAVING SUM(lta.points_earned) > 0
        ORDER BY total_points DESC
    ");
    
    $aggregatedCount = 0;
    $totalPointsInserted = 0;
    $currentTime = time();
    
    while ($student = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $details = "Total points from {$student['total_attempts']} lesson test attempts. Aggregated on " . date('Y-m-d');
        
        $insertStmt->execute([
            $student['total_points'],
            $student['student_id'],
            $details,
            $currentTime,
            $currentTime
        ]);
        
        echo "✅ {$student['full_name']}: {$student['total_points']} points from {$student['total_attempts']} attempts\n";
        
        $aggregatedCount++;
        $totalPointsInserted += $student['total_points'];
    }
    
    echo "\n=== AGGREGATION COMPLETE ===\n";
    echo "✅ Aggregated points for {$aggregatedCount} students\n";
    echo "📊 Total points inserted: {$totalPointsInserted}\n";
    
    // Verification
    $stmt = $pdo->query("SELECT COUNT(*) FROM points WHERE details LIKE '%lesson test attempts%'");
    $finalAggregated = $stmt->fetchColumn();
    echo "📊 Total aggregated records in database: {$finalAggregated}\n";
    
    // Accuracy check
    $stmt = $pdo->query("
        SELECT 
            SUM(points_earned) as original_total
        FROM lesson_test_attempt
        WHERE points_earned > 0
    ");
    $originalTotal = $stmt->fetchColumn();
    
    $stmt = $pdo->query("
        SELECT 
            SUM(points) as aggregated_total
        FROM points
        WHERE details LIKE '%lesson test attempts%'
    ");
    $aggregatedTotal = $stmt->fetchColumn();
    
    echo "🔍 ACCURACY CHECK:\n";
    echo "Original total from lesson_test_attempt: {$originalTotal}\n";
    echo "Aggregated total in points table: {$aggregatedTotal}\n";
    echo "Match: " . ($originalTotal == $aggregatedTotal ? "✅ YES" : "❌ NO") . "\n";
    */
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
