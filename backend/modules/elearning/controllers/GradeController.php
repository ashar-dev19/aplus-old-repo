<?php

namespace backend\modules\elearning\controllers;

use Yii;
use backend\modules\elearning\models\Grade;
use backend\modules\elearning\models\Subject;
use backend\modules\elearning\models\search\GradeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GradeController implements the CRUD actions for Grade model.
 */
class GradeController extends Controller
{

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Grade models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GradeSearch();
        $queryParams = Yii::$app->request->queryParams;

        // Subject ID ko URL se fetch karna
        $subjectId = Yii::$app->request->get('subjectid');

        // Agar subject ID available hai toh uske mutabiq filter karein
        if ($subjectId) {
            $queryParams['GradeSearch']['subject_id'] = $subjectId;
        }

        $dataProvider = $searchModel->search($queryParams);

        // Get list of subjects for dropdown
        // $subjects = Subject::find()->select(['id', 'title'])->indexBy('id')->column();
        // $subjects = Subject::find()->select(['id', 'title'])->asArray()->all();
        $subjects = Subject::find()
        ->select(['id', 'title'])
        ->where(['status' => 1]) // Filter only active subjects
        ->asArray()
        ->all();


        

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'subjects' => $subjects,
            'selectedSubject' => $subjectId, // Pass selected subject ID to view
        ]);
    }

    
    

    /**
     * Displays a single Grade model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Grade model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Grade();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Grade model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
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
     * Deletes an existing Grade model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Grade model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Grade the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Grade::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
