<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\student_management\models\search\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
 
 <div class="course_page math">
   <div class="container">
      <div class="row align-items-center">
         <div class="col-md-12">
            <h2 class="heading">Select Grade</h2>
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
                    

                     


</div> <!-- boxes -->
         </div><!-- col-md-12 --> 
        </div><!-- row --> 
   </div>
</div>
