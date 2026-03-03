<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var common\models\Article $model */
/** @var array $archive */
/** @var array $categories */

$this->title = $model->title;
?>

<div class="blog_sec blog_single">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
           <div class="inner">
                <h1><?= Html::encode($this->title) ?></h1>
                <!-- <p><i class="fa fa-calendar"></i> <?php //echo Yii::$app->formatter->asDate($model->published_at) ?></p> -->
                 
                <img src="<?= $model->thumbnail_base_url ?>" alt="<?= Html::encode($model->title) ?>">
                <?= $model->body ?>

                <a href="<?= Url::to(['article/index']) ?>" class="theme_btn">Back to Blog</a>
           </div>
            

             
            <!-- <h3>Categories</h3> -->
            <ul class="d-none">
                <?php foreach ($categories as $category): ?>
                    <li>
                        <a href="<?= Url::to(['article/index', 'category' => $category['slug']]) ?>">
                            <?= Html::encode($category['title']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

        
            <!-- <h3>Archive</h3> -->
            <ul class="d-none">
                <?php foreach ($archive as $month): ?>
                    <li>
                        <a href="<?= Url::to(['article/index', 'month' => $month['month']]) ?>">
                            <?= Html::encode($month['month']) ?> (<?= $month['count'] ?>)
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            


         </div>
       </div>  
    </div>
</div>  

 
    

    
   
 


