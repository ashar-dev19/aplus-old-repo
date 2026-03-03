<?php



namespace frontend\controllers;



use Yii;

use frontend\models\Topics;



use frontend\models\search\TopicsSearch;

use yii\web\Controller;

use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;



/**

 * TopicsController implements the CRUD actions for Topics model.

 */

class TopicsController extends Controller

{

    /**

     * {@inheritdoc}

     */

    public function behaviors()

    {

        return [

            'verbs' => [

                'class' => VerbFilter::className(),

                'actions' => [

                    'delete' => ['POST'],

                ],

            ],

        ];

    }



    /**

     * Lists all Topics models.

     * @return mixed

     */

    public function actionIndex()

    {

        $topicsSearch = new TopicsSearch();

        $queryParams["TopicsSearch"]["category_id"] = Yii::$app->request->get('id') ;
        // $queryParams["TopicsSearch"]["grade"] = Yii::$app->request->get('id') ;

         $dataProvider = $topicsSearch->search($queryParams);

        $this->layout = '@frontend/views/layouts/_minimal.php';

        return $this->render('index', [

            'searchModel' => $queryParams,

            'dataProvider' => $dataProvider,

        ]);

    }
    
    /**

     * Displays a single Topics model.

     * @param integer $id

     * @return mixed

     * @throws NotFoundHttpException if the model cannot be found

     */

    public function actionView($id)

    {

        $this->layout = '@frontend/views/layouts/_minimal.php';

        return $this->render('view', [

            'model' => $this->findModel($id),

        ]);

    }



    /**

     * Creates a new Topics model.

     * If creation is successful, the browser will be redirected to the 'view' page.

     * @return mixed

     */

    public function actionCreate()

    {

        $model = new Topics();



        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => $model->id]);

        }



        $this->layout = '@frontend/views/layouts/_minimal.php';

        return $this->render('create', [

            'model' => $model,

        ]);

    }



    /**

     * Updates an existing Topics model.

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



        $this->layout = '@frontend/views/layouts/_minimal.php';

        return $this->render('update', [

            'model' => $model,

        ]);

    }



    /**

     * Deletes an existing Topics model.

     * If deletion is successful, the browser will be redirected to the 'index' page.

     * @param integer $id

     * @return mixed

     * @throws NotFoundHttpException if the model cannot be found

     */

    public function actionDelete($id)

    {

        $this->findModel($id)->delete();



        $this->layout = '@frontend/views/layouts/_minimal.php';

        return $this->redirect(['index']);

    }



    /**

     * Finds the Topics model based on its primary key value.

     * If the model is not found, a 404 HTTP exception will be thrown.

     * @param integer $id

     * @return Topics the loaded model

     * @throws NotFoundHttpException if the model cannot be found

     */

    protected function findModel($id)

    {

        if (($model = Topics::findOne($id)) !== null) {

            return $model;

        }



        throw new NotFoundHttpException('The requested page does not exist.');

    }

}



