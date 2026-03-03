<?php

namespace backend\controllers;
use backend\modules\elearning\models\LessonRead;
use common\models\User;
use backend\modules\student_management\models\Points;
use backend\models\search\TimelineEventSearch;
use backend\modules\student_management\models\Student;
use backend\modules\UserManagement\models\UserProfile;
use webvimark\components\AdminDefaultController;
use Yii;
use yii\web\Controller;

/**
 * Description of DashboardController
 *
 * @author Analyst
 */
class DashboardController extends AdminDefaultController
{

  public function behaviors()
{
    return [
        'ghost-access' => [
            'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
        ],
    ];
}



  public $layout = 'common';
  //    public $freeAccess = true;
  protected $courseArray = array();
  /**
   * Lists all TimelineEvent models.
   * @return mixed
   */
  public function actionIndex()
  {
    $studentReport=[];
    $searchModel = new TimelineEventSearch();
    $studentReport['studentLive']= $this->getLiveStudentReport();
    $studentReport['studentActive'] = $this->getActiveStudent();
    $studentReport['userActive'] = $this->getTotalUsers();
    $studentReport['userPoints'] = $this->getPointsToday();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    $dataProvider->sort = [
      'defaultOrder' => ['created_at' => SORT_DESC]
    ];
    
    return $this->render('index', [
      'searchModel' => $searchModel,
      'dataProvider' => $dataProvider,
      'studentReport' => $studentReport,
    ]);
  }

  public function getLiveStudentReport()
  {
    // get a count of students that read a lesson
    // lesson_read stamps date and studentid and many lesson id
    // where date is the same, show me, students that read a lesson
    // select students from lesson_read where date= today
  //   SELECT COUNT(student_id) FROM `lesson_read` WHERE date="2023-10-17";
    
  //   $count = Yii::$app->db->createCommand('SELECT COUNT(student_id) FROM lesson_read WHERE date=:date')
  //  ->bindValue(':date', '2023-10-17')
  //  ->queryScalar();

    $query = LessonRead::find();
    $query->where(['date' => date("Y-m-d")]); // 2023-10-17
    $count = $query->count('student_id');
    return $count;

  }
  public function getActiveStudent()
  {
    $query = Student::find();
    $query->where(['status'=> 1]);
    $count= $query->count('id');
    return $count;
  }
  public function getTotalUsers()
  {
    $query = User::find();
    $query->where(['status'=> 1]);
    $count= $query->count('id');
    return $count;
  }
  public function getTotalPoints()
  {
    return User::find()->where(['status'=> 1])->count('id');
    // every question is assigned fixed marks
    // raw score is calculated by multiplying question times mark,
    // eg., 10 questions times 20 mark = 200 score
    // to calculate points every test with over 80% success gets 100 points
    // repteated tests upto 3 times get points
    // final chapter test recieve 1000 points
    // final lesson test recieves 500 points
    // first time correct test gets 100 points
    // 2nd and third time test gets 10 points


  }
  public function getPointsToday()
  {
  return Points::find()-> where(["DATE_FORMAT(FROM_UNIXTIME(`created_at`), '%Y-%m-%d')" => date("Y-m-d")])->sum('points');
  
  }
}
