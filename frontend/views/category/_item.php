<?php

/**

 * @var $this yii\web\View

 * @var $model common\models\Student

 */

use yii\helpers\Html;

use yii\helpers\HtmlPurifier;

use yii\helpers\StringHelper;

?>
<?php 
$grade_title = $model->name ;
$firstCharacter = substr($grade_title, 0, 2);



?>
 

 <!-- <?php //echo Html::a(
                    //Html::encode($model->name), ['/topics/view', 'category_id'=>$model->id], ['class' => ['title']]
                    
               // ) ?> -->

               <div class="box">
                  <div class="inner">
                     <!-- <i class="fa fa-eercast"></i> -->
                     <a href="/topics?id=<?php echo $model->id ?>" class="title">
                        <h2><?php echo $model->name ?></h2>
                        <p> <?php echo $model->description ?>
                        </p>
                     </a>
                     <div class="skils">
                        <div class="sk_title">
                           <!--<h4>Math</h4>-->
                           <h4>Lessons Available</h4>
                        </div>
                        <div class="sk_link">
                           <a href="#">165</a>
                        </div>
                        <div class="sk_title">
                           <!--<h4>Language arts</h4>-->
                           <h4>In Progress</h4>
                        </div>
                        <div class="sk_link">
                           <a href="#">3</a>
                        </div>
                        <div class="sk_title">
                           <h4>Finished</h4>
                        </div>
                        <div class="sk_link">
                           <a href="#">83</a>
                        </div>
                        <div class="sk_title">
                           <h4>Live Classes</h4>
                        </div>
                        <div class="sk_link">
                           <a href="#">M & W 5-5:45</a>
                        </div>

                        <div class="ribbon prek"><span><?php echo $firstCharacter; ?></span></div>
                        
                     </div>
                  </div>
               </div><!-- box -->

                

 




               
          