<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use frontend\models\UserImported;
use webvimark\modules\UserManagement\models\User;
use backend\modules\UserManagement\models\UserProfile;

class ImportController extends Controller
{
    /**
     * Import or update **all** users from `user_imported` in one go.
     */
    public function actionAll(): int
    {
        // allow the script to run indefinitely
        set_time_limit(0);

        // fetch every un-processed row
        $rows = UserImported::find()
            ->where(['processed' => 0])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        if (empty($rows)) {
            $this->stdout("✅ Nothing left to import.\n");
            return self::EXIT_CODE_NORMAL;
        }

        $imported = $updated = $failed = [];

        foreach ($rows as $r) {
            // try to find an existing user by email
            $u = User::findOne(['email' => $r->email]);
            if ($u) {
                // --- UPDATE existing user ---
                $u->password_hash = Yii::$app->security->generatePasswordHash($r->password);
                if (!$u->save(false)) {
                    $failed[$r->email] = $u->getErrors();
                    continue;
                }
                // ensure they have the “user” role
                User::assignRole($u->id, 'user');

                // load or create their profile
                $p = UserProfile::findOne(['user_id' => $u->id])
                   ?: new UserProfile(['user_id' => $u->id]);
                if (empty($p->firstname)) {
                    $p->firstname = $r->fname;
                }
                if (empty($p->lastname)) {
                    $p->lastname = $r->lname;
                }
                $p->save(false);

                $updated[] = $r->email;
            } else {
                // --- CREATE new user ---
                $u = new User([
                    'username'        => $r->email,
                    'email'           => $r->email,
                    'address'         => mb_strimwidth($r->address, 0, 255, '', 'UTF-8'),
                    'phone'           => $r->phone_number,
                    'status'          => User::STATUS_ACTIVE,
                    'email_confirmed' => 1,
                    'auth_key'        => Yii::$app->security->generateRandomString(),
                    'password_hash'   => Yii::$app->security->generatePasswordHash($r->password),
                ]);
                if (!$u->save(false)) {
                    $failed[$r->email] = $u->getErrors();
                    continue;
                }
                User::assignRole($u->id, 'user');

                $p = new UserProfile([
                    'user_id'   => $u->id,
                    'firstname' => $r->fname,
                    'lastname'  => $r->lname,
                ]);
                $p->save(false);

                $imported[] = $r->email;
            }

            // mark this row processed (but do NOT delete it)
            $r->processed    = 1;
            $r->processed_at = date('Y-m-d H:i:s');
            $r->save(false);
        }

        // report
        if ($imported) {
            $this->stdout("➕ Imported:\n");
            foreach ($imported as $e) {
                $this->stdout("    • {$e}\n");
            }
        }
        if ($updated) {
            $this->stdout("🔄 Updated:\n");
            foreach ($updated as $e) {
                $this->stdout("    • {$e}\n");
            }
        }
        if ($failed) {
            $this->stdout("❌ Failed:\n");
            foreach ($failed as $e => $errs) {
                $this->stdout("    • {$e} – " . json_encode($errs) . "\n");
            }
        }

        $this->stdout("\n✅ Done importing all remaining rows.\n");
        return self::EXIT_CODE_NORMAL;
    }
}
