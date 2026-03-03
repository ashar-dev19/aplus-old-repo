<?php

namespace backend\controllers;

use backend\models\search\TimelineEventSearch;
use webvimark\components\AdminDefaultController;
use Yii;
use yii\web\Controller;

/**
 * Application timeline controller
 */
class TimelineEventController extends AdminDefaultController
{
    public $layout = 'common';

    /**
     * Lists all TimelineEvent models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TimelineEventSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = [
            'defaultOrder' => ['created_at' => SORT_DESC]
        ];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider, 
        ]);
    }

     
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    
    public function actionClear($category = null) {
        $cond = $category ? ['category' => $category] : [];
        \common\models\TimelineEvent::deleteAll($cond);
        Yii::$app->session->setFlash('success', 'Notifications cleared.');
        return $this->redirect(['index']);
    }


    // public function actionClear($category = null)
    // {
    //     $notifier = Yii::$app->notifier;

    //     if ($category) { 
    //         $notifier->markReadByCategory($category); 
    //     } else {
    //         $notifier->markAllRead();  
    //     }

    //     return $this->redirect(['index']);
    // }







}
