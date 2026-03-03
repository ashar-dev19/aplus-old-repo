<?php



use yii\helpers\Html;

use yii\grid\GridView;





/* @var $this yii\web\View */

/* @var $searchModel backend\modules\student_management\models\search\TopicsSearch */

/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = 'Topics';

$this->params['breadcrumbs'][] = $this->title;

?>







    



    <!-- <p>

        //Html::a('Create Topics', ['create'], ['class' => 'btn btn-success']);

    </p> -->



    <div class="topic_page ">

        <div class="container">

            <div class="row align-items-center">

                <div class="col-md-12">

                    <h2 class="heading">Gradessss</h2>

                    <!-- boxes -->

                    <div class="boxes">

                        <?= \yii\widgets\ListView::widget([

                        'dataProvider' => $dataProvider,

                        'pager' => [

                        'hideOnSinglePage' => true,

                        ],



                        'itemView' => '_item', // Make sure you have this view defined

                        'layout' => '{items}',



                        ]) ?>

                    </div>

                </div>

            </div>



            <div class="row">

                <div class="col-md-12">

                    <div class="btns">

                    <a href="/category" class="theme_btn"><i class="fa fa-chevron-left"></i> My Study room </a>

                    <!-- <a href="#" class="theme_btn2">Get assessment <i class="fa fa-chevron-right"></i></a> -->

                    </div>

                </div>

            </div>



        </div>

    </div>               





   













