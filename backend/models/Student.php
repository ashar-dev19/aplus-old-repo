<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use backend\models\User;
use backend\models\Notes;
use frontend\models\Grade;
use frontend\models\LessonTestAttempt;
use frontend\models\Points;

use yii\db\Query;
use yii\db\Expression;

/**
 * This is the model class for table "student".
 *
 * @property int         $id
 * @property int         $parent_id
 * @property string      $full_name
 * @property string|null $email
 * @property string|null $details      // profile image path
 * @property int|null    $grade_id
 * @property string|null $gender
 * @property string|null $dob
 * @property int|null    $live_support
 * @property int         $status
 * @property int|null    $created_by
 * @property int|null    $updated_by
 * @property int|null    $updated_at
 * @property int|null    $created_at
 *
 * @property-read Grade|null   $grade
 * @property-read string       $gradeName
 * @property-read User|null    $parent
 * @property-read string|null  $parentName
 * @property-read Notes[]      $notes
 * @property-read int          $totalPoints
 */
class Student extends ActiveRecord
{
    /** {@inheritdoc} */
    public static function tableName()
    {
        return 'student';
    }

    /** {@inheritdoc} */
    public function rules()
    {
        return [
            [['parent_id', 'full_name', 'status'], 'required'],
            [['parent_id', 'grade_id', 'live_support', 'status', 'created_by', 'updated_by', 'updated_at', 'created_at'], 'integer'],
           
        ['dob', 'date', 'format' => 'php:Y-m-d'],
            [['full_name'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 50],
            [['gender'], 'string', 'max' => 25],
            // ✅ treat "details" as an optional image upload
            [
                'details',
                'file',
                'extensions' => ['png','jpg','jpeg','webp'],
                'maxSize' => 2 * 1024 * 1024,              // 2MB
                'checkExtensionByMimeType' => true,
                'skipOnEmpty' => true,                   
            ],
        ];
    }

    /** {@inheritdoc} */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'parent_id'    => 'Parent ID',
            'full_name'    => 'Full Name',
            'email'        => 'Email',
            'details'      => 'Profile Image',
            'grade_id'     => 'Grade ID',
            'gender'       => 'Gender',
            'dob'          => 'Date of Birth',
            'live_support' => 'Live Support',
            'status'       => 'Status',
            'created_by'   => 'Created By',
            'updated_by'   => 'Updated By',
            'updated_at'   => 'Updated At',
            'created_at'   => 'Created At',
        ];
    }

    /**
     * Clean up dependant data (attempts) when a student is deleted.
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            LessonTestAttempt::deleteAll(['student_id' => $this->id]);
            return true;
        }
        return false;
    }

    /* =========================
     *         RELATIONS
     * ========================= */

    

    public function getParentName()
    {
        return $this->parent ? $this->parent->username : null;
    }

    public function getTestAttempts()
    {
        return $this->hasMany(LessonTestAttempt::class, ['student_id' => 'id']);
    }

    public function getGrade()
    {
        return $this->hasOne(Grade::class, ['id' => 'grade_id']);
    }

    public function getGradeName()
    {
        return $this->grade ? $this->grade->title : 'Not Assigned';
    }

    public function getNotes()
    {
        return $this->hasMany(Notes::class, ['student_id' => 'id']);
    }
    // public function getParent()
    // {
    //     // give the relation a stable alias so we can filter/sort on it
    //     return $this->hasOne(\backend\models\User::class, ['id' => 'parent_id'])->alias('parentUser');
    // }

    public function getParent()
    {
        // Webvimark User model + stable alias
        return $this->hasOne(\webvimark\modules\UserManagement\models\User::class, ['id' => 'parent_id'])
            ->alias('parentUser');
    }

     


    /* =========================
     *     POINTS CALCULATION
     * ========================= */

    /**
     * FRONTEND logic parity:
     * - Earned rows: points where is_redempt IS NULL
     * - Redeemed rows: points where is_redempt = 1
     * Total = earned - redeemed
     */
    // public function getTotalPointsAccurate(): int
    // {
    //     $earned = (int) Points::find()
    //         ->where(['student_id' => $this->id])
    //         ->andWhere(['is_redempt' => null])
    //         ->sum('points');

    //     $redeemed = (int) Points::find()
    //         ->where(['student_id' => $this->id, 'is_redempt' => 1])
    //         ->sum('points');

    //     return max(0, $earned - $redeemed);
    // }

    // public function getTotalPointsAccurate(): int
    // {
    //     // Earned = (is_redempt IS NULL) OR (is_redempt = 0)  ← bonus 5000 rows yahan count hongi
    //     $earned = (int) \frontend\models\Points::find()
    //         ->where(['student_id' => $this->id])
    //         ->andWhere(['or', ['is_redempt' => null], ['is_redempt' => 0]])
    //         ->sum('points');

    //     // Redeemed = is_redempt = 1
    //     $redeemed = (int) \frontend\models\Points::find()
    //         ->where(['student_id' => $this->id, 'is_redempt' => 1])
    //         ->sum('points');

    //     return max(0, $earned - $redeemed);
    // }

    public function getTotalPointsAccurate(): int
    {
        // Net = SUM(earn/bonus) − SUM(deductions/redemptions)
        $expr = new Expression("
            COALESCE(SUM(CASE WHEN is_redempt = 1 THEN -points ELSE points END), 0)
        ");

        return (int) (new Query())
            ->from(\frontend\models\Points::tableName())
            ->select($expr)
            ->where(['student_id' => $this->id, 'status' => 1]) // status filter optional
            ->scalar();
    }


    

    /**
     * Fallback: Completed attempts ka points_earned sum.
     */
    public function getTotalPointsFromAttempts(): int
    {
        return (int) LessonTestAttempt::find()
            ->where([
                'student_id' => $this->id,
                'status'     => LessonTestAttempt::STATUS_COMPLETE,
            ])
            ->sum('points_earned');
    }

    /**
     * Grid/view me dikhane wali final value.
     * Pehle points table se exact total, warna attempts se.
     */
    public function getTotalPoints(): int
    {
        $total = $this->getTotalPointsAccurate();
        if ($total === 0) {
            $total = $this->getTotalPointsFromAttempts();
        }
        return $total;
    }
}
