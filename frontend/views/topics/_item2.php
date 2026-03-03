<?php
   /**
   
    * @var $this yii\web\View
   
    * @var $model common\models\Student
   
    */
   
   use yii\helpers\Html;
   
   use yii\helpers\HtmlPurifier;
   
   use yii\helpers\StringHelper;
   
   ?>
<a href="/lesson?id=<?php echo $model->id ?>" class="box">


   <div class="inner">
      <div class="left">
         <div class="text">
            <h5>Grade</h5>
            <h4><?php echo Html::encode($model->category->name); ?></h4>
            <?php echo count($model->lesson) ?>

         </div>
         <!-- <div>
            <a href="#">View all Chapters <i class="fa fa-chevron-right"></i></a>
            </div> -->
      </div>

      <div class="right">
         <div class="progress_bar">
            <div class="progress-done" data-done="70">
               70%
            </div>
         </div>

         <div class="text">
            <div class="content">
               <h5>Chapter</h5>
               <h4><?php echo $model->title ?></h4>
            </div>
            

             <div class="single-chart">
                  <svg viewBox="0 0 36 36" class="circular-chart green">
                     <path class="circle-bg"
                        d="M18 2.0845
                        a 15.9155 15.9155 0 0 1 0 31.831
                        a 15.9155 15.9155 0 0 1 0 -31.831"
                        />
                     <path class="circle"
                        stroke-dasharray="60, 100"
                        d="M18 2.0845
                        a 15.9155 15.9155 0 0 1 0 31.831
                        a 15.9155 15.9155 0 0 1 0 -31.831"
                        />
                     <text x="18" y="20.35" class="percentage">60%</text>
                  </svg>
                  <p class="score">Score to beat</p>
             </div>
               
         </div>

     

      </div>
   </div>

</a>
<!-- box -->