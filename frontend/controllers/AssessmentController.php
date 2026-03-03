<?php
namespace frontend\controllers;

use Yii;
use frontend\models\Assessment;
use frontend\models\search\AssessmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * AssessmentController implements the CRUD actions for Assessment model.
 */
class AssessmentController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public $freeAccess = true;  
    
    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        if ($action->id !== 'create') {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return parent::beforeAction($action);
    }
    

    /**
     * Lists all Assessment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AssessmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (!Yii::$app->user->isGuest) {
            return Yii::$app->response->redirect(['student/current']);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Assessment model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Assessment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function actionCreate()
    // {
    //     $model = new Assessment();

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         // return $this->redirect(['view', 'id' => $model->id]);
    //         return $this->redirect(['site/index']);
    //     }

    //     if (!Yii::$app->user->isGuest) {
    //         return Yii::$app->response->redirect(['student/current']);
    //     }

    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }

    // public function actionCreate()
    // {
    //     $model = new Assessment();
    
    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {

    //         Yii::$app->session->setFlash('success', 'Assessment booked successfully!');
    //         $model = new Assessment();
            
    //     } else if ($model->hasErrors()) {
            
    //         Yii::$app->session->setFlash('error', 'There was an error booking your assessment. Please try again.');
    //     }
    
    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }

    public function actionCreate()
{
    $model = new Assessment();

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
        Yii::$app->session->setFlash('success', 'Assessment booked successfully!');

        // --- SEND EMAILS ---
        /* You'll need to have configured Yii::$app->mailer in your application config
           (transport, host, username, password, etc.). */

        // Prepare a little context
        $fullName = trim($model->first_name . ' ' . $model->last_name);
        $date     = Yii::$app->formatter->asDatetime($model->assessment_datetime, 'php:F j, Y \a\t g:ia');

        // Compose a simple HTML body
        $body = "<p>Hi {$fullName},</p>"
              . "<p>Thanks for booking your assessment on <strong>{$date}</strong>.</p>"
              . "<p>We look forward to seeing you!</p>"
              . "<hr><p>— The A+ Students Team</p>";

        // 1) Send to the student
        Yii::$app->mailer->compose()
            // ->setFrom([Yii::$app->params['supportEmail'] => 'A+ Students'])
            ->setFrom(['noreply@aplus.com' => 'A+ Students'])
            ->setTo($model->email)
            ->setSubject('Your assessment booking confirmation')
            ->setHtmlBody($body)
            ->send();

        // 2) Send an admin copy
        Yii::$app->mailer->compose()
            // ->setFrom([Yii::$app->params['supportEmail'] => 'A+ Students'])
            ->setFrom(['noreply@aplus.com' => 'A+ Students'])
            ->setTo('umairgilani64@gmail.com')
            ->setSubject('New assessment booked: ' . $fullName)
            ->setHtmlBody(
                "<p>A new assessment was just booked:</p>"
              . "<ul>"
              . "<li><strong>Name:</strong> {$fullName}</li>"
              . "<li><strong>Email:</strong> {$model->email}</li>"
              . "<li><strong>Phone:</strong> {$model->phone}</li>"
              . "<li><strong>Date/Time:</strong> {$date}</li>"
              . "</ul>"
            )
            ->send();
        // -------------------

        // reset form
        $model = new Assessment();
    } elseif ($model->hasErrors()) {
        Yii::$app->session->setFlash('error', 'There was an error booking your assessment. Please try again.');
    }

    return $this->render('create', [
        'model' => $model,
    ]);
}

    


    /**
     * Updates an existing Assessment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Assessment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Assessment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Assessment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Assessment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
