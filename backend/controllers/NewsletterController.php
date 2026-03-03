<?php

namespace backend\controllers;

use Yii;
use backend\models\Newsletter;
use backend\models\search\NewsletterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NewsletterController implements the CRUD actions for Newsletter model.
 */
class NewsletterController extends Controller
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
     * Lists all Newsletter models.
     * @return mixed
     */
    

    public function actionIndex()
    {
         
        
        $searchModel  = new NewsletterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Newsletter index action ke end me:
        \common\models\TimelineEvent::updateAll(
            ['is_read' => 1],
            ['category' => 'newsletter', 'is_read' => 0]
        );


        return $this->render('index', compact('searchModel','dataProvider'));
    }



   public function actionView($id)
    {
        \common\models\TimelineEvent::updateAll(
            ['is_read' => 1],
            ['category' => 'newsletter', 'newsletter_id' => (int)$id, 'is_read' => 0]
        );

        return $this->render('view', ['model' => $this->findModel($id)]);
    }


    /**
     * Creates a new Newsletter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Newsletter();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Newsletter model.
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
     * Deletes an existing Newsletter model.
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
     * Finds the Newsletter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Newsletter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Newsletter::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
