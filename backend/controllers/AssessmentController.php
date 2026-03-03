<?php
namespace backend\controllers;

use Yii;
use frontend\models\Assessment;
use frontend\models\search\AssessmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter; 


class AssessmentController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],   // delete must be POST
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel  = new AssessmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
 
        $dataProvider->sort = [
            'defaultOrder' => ['created_at' => SORT_DESC],
        ];

         
        \common\models\TimelineEvent::updateAll(
            ['is_read' => 1],
            ['category' => 'assessment', 'is_read' => 0]
        );


        return $this->render('index', compact('searchModel','dataProvider'));
    }


    public function actionView($id)
    {
        // mark related timeline events as read when opened in backend
        \common\models\TimelineEvent::updateAll(
            ['is_read' => 1],
            ['assessment_id' => (int)$id, 'category' => 'assessment', 'is_read' => 0]
        );
        
        $model = \frontend\models\Assessment::findOne((int)$id);
            if (!$model) {
                throw new \yii\web\NotFoundHttpException('Assessment not found.');
            }



        return $this->render('view', compact('model'));
    }

    
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();                       // hard delete (see note below)

        Yii::$app->session->setFlash('alert', [
            'body' => 'Assessment deleted.',
            'options' => ['class' => 'alert-success']
        ]);

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($m = Assessment::findOne((int)$id)) !== null) {
            return $m;
        }
        throw new NotFoundHttpException('Assessment not found.');
    }

    
}
