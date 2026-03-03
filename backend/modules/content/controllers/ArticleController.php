<?php

namespace backend\modules\content\controllers;

use backend\modules\content\models\search\ArticleSearch;
use common\models\Article;
use common\models\ArticleCategory;
use common\traits\FormAjaxValidationTrait;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use trntv\filekit\behaviors\UploadBehavior;

class ArticleController extends Controller
{
    use FormAjaxValidationTrait;

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'], // Suggestion: Consider using `delete` via AJAX for better UX.
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = [
            'defaultOrder' => ['published_at' => SORT_DESC], // Suggestion: Allow users to change sorting dynamically.
        ];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    // public function actionCreate()
    // {
    //     $article = new Article();

    //     $this->performAjaxValidation($article); // Suggestion: Handle validation errors in a user-friendly manner.

    //     if ($article->load(Yii::$app->request->post()) && $article->save()) {
    //         Yii::$app->session->setFlash('success', 'Article created successfully.'); // Add success feedback.
    //         return $this->redirect(['index']);
    //     }

    //     return $this->render('create', [
    //         'model' => $article,
    //         'categories' => ArticleCategory::find()->active()->all(), // Suggestion: Add caching for categories.
    //     ]);
    // }
    

    public function actionCreate()
    {
        $model = new Article();
    
        if ($model->load(Yii::$app->request->post())) {
            $thumbnail = UploadedFile::getInstance($model, 'thumbnail');
            
            if ($thumbnail) {
                // Define the upload directory path
                $uploadPath = Yii::getAlias('@backend/web/uploads/thumbnails/');
                
                // Ensure that the directory exists
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);  // Create directory if not exists
                }
                
                // Generate the filename to store (same name as original file)
                $fileName = $thumbnail->baseName . '.' . $thumbnail->extension;
                
                // Save the uploaded file
                if ($thumbnail->saveAs($uploadPath . $fileName)) {
                    // After saving, store the path and URL in the model
                    $model->thumbnail_path = '/uploads/thumbnails/' . $fileName;  // Relative file path
                    $model->thumbnail_base_url = Yii::$app->request->hostInfo . '/uploads/thumbnails/' . $fileName;  // Full URL
    
                    // Save the model
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Article created successfully.');
                        return $this->redirect(['index']);
                    } else {
                        Yii::$app->session->setFlash('error', 'Failed to save the article.');
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to upload the thumbnail.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'No thumbnail image selected.');
            }
        }
    
        return $this->render('create', [
            'model' => $model,
            'categories' => ArticleCategory::find()->all(),
        ]);
    }
    

  
    public function actionUpdate($id)
    {
        $article = $this->findModel($id);

        $this->performAjaxValidation($article);

        if ($article->load(Yii::$app->request->post()) && $article->save()) {
            Yii::$app->session->setFlash('success', 'Article updated successfully.');
            return $this->redirect(['index']);
        }

        // Suggestion: Add timezone handling for `published_at`.
        $article->published_at = date('Y-m-d H:i:s', $article->published_at);

        return $this->render('update', [
            'model' => $article,
            'categories' => ArticleCategory::find()->active()->all(),
        ]);
    }


    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('info', 'Article deleted successfully.');
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.'); // Suggestion: Add logging for not found cases.
    }
}

