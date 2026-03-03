<?php
namespace console\controllers;

use yii\console\Controller;
use yii\db\Query;

class MigrateController extends Controller
{
    /**
     * Migrate passwords from old users table to new users1 table with proper Yii 2.0 hashing
     */
    public function actionHashPasswords()
    {
        $this->stdout("Starting password migration from users to users1...\n");
        
        try {
            // Get all users from old table
            $query = new Query();
            $oldUsers = $query->select(['id', 'password'])
                             ->from('users')
                             ->where(['is_deleted' => 0])
                             ->andWhere(['!=', 'password', ''])
                             ->andWhere(['is not', 'password', null])
                             ->all();
            
            $this->stdout("Found " . count($oldUsers) . " users to process...\n");
            
            $updateCount = 0;
            $errorCount = 0;
            
            foreach ($oldUsers as $user) {
                try {
                    // Hash password using Yii 2.0 security component
                    $hashedPassword = \Yii::$app->security->generatePasswordHash($user['password']);
                    
                    // Update users1 table
                    $result = \Yii::$app->db->createCommand()->update('users1', [
                        'password_hash' => $hashedPassword
                    ], ['id' => $user['id']])->execute();
                    
                    if ($result) {
                        $updateCount++;
                        
                        // Progress indicator
                        if ($updateCount % 50 == 0) {
                            $this->stdout("Processed {$updateCount} passwords...\n");
                        }
                    } else {
                        $this->stdout("Warning: Could not update user ID {$user['id']}\n");
                        $errorCount++;
                    }
                    
                } catch (\Exception $e) {
                    $this->stdout("Error processing user ID {$user['id']}: " . $e->getMessage() . "\n");
                    $errorCount++;
                }
            }
            
            $this->stdout("\n=== MIGRATION COMPLETE ===\n");
            $this->stdout("Successfully processed: {$updateCount} passwords\n");
            $this->stdout("Errors encountered: {$errorCount}\n");
            
            if ($errorCount == 0) {
                $this->stdout("✅ All passwords migrated successfully!\n");
            } else {
                $this->stdout("⚠️  Migration completed with {$errorCount} errors. Please review.\n");
            }
            
        } catch (\Exception $e) {
            $this->stdout("❌ Migration failed: " . $e->getMessage() . "\n");
            return 1; // Return error code
        }
        
        return 0; // Success
    }
    
    /**
     * Verify the migration results
     */
    public function actionVerifyMigration()
    {
        $this->stdout("Verifying migration results...\n\n");
        
        // Count original users
        $originalCount = (new Query())->from('users')->where(['is_deleted' => 0])->count();
        $this->stdout("Original users (not deleted): {$originalCount}\n");
        
        // Count new users
        $newCount = (new Query())->from('users1')->count();
        $this->stdout("New users1 records: {$newCount}\n");
        
        // Count users with passwords
        $withPasswords = (new Query())->from('users1')
                                     ->where(['is not', 'password_hash', null])
                                     ->andWhere(['!=', 'password_hash', ''])
                                     ->count();
        $this->stdout("Users1 with password_hash: {$withPasswords}\n");
        
        // Count original members
        $originalMembers = (new Query())
            ->from('members m')
            ->innerJoin('users u', 'm.login_id = u.id')
            ->where(['u.is_deleted' => 0])
            ->count();
        $this->stdout("Original members (with active users): {$originalMembers}\n");
        
        // Count new students
        $newStudents = (new Query())->from('student')->count();
        $this->stdout("New student records: {$newStudents}\n");
        
        // Check for orphaned students
        $orphanedStudents = (new Query())
            ->from('student s')
            ->leftJoin('users1 u', 's.parent_id = u.id')
            ->where(['u.id' => null])
            ->count();
        $this->stdout("Orphaned students (should be 0): {$orphanedStudents}\n");
        
        $this->stdout("\n=== VERIFICATION COMPLETE ===\n");
        
        if ($orphanedStudents == 0 && $newCount > 0 && $newStudents > 0) {
            $this->stdout("✅ Migration appears successful!\n");
        } else {
            $this->stdout("⚠️  Please review the results above.\n");
        }
    }
}