<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var common\models\ArticleCategory[] $categories */
/** @var array $archive */

$this->title = 'Blog Archive';
?>

<div class="blog_sec">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <h4 class="subheading">Our Blog</h4>
            <h2 class="heading"><?= Html::encode($this->title) ?></h2>
            <div class="main">
               <?php if (!empty($dataProvider->models)): ?>
                  <?php foreach ($dataProvider->models as $article): ?>
                     <div class="box">
                        <a href="<?= Url::to(['article/view', 'slug' => $article->slug]) ?>" class="image">
                           <img src="<?= $article->thumbnail_base_url ?>" alt="<?= Html::encode($article->title) ?>">
                        </a>
                        <div class="content">
                           <div class="tags">
                              <!-- <span><i class="fa fa-user"></i> <?php //echo  $article->author->name ?? 'Admin' ?></span> -->
                              <span><i class="fa fa-calendar"></i> <?= Yii::$app->formatter->asDate($article->published_at, 'php:M. d, Y') ?></span>
                           </div>
                           <h3><a href="<?= Url::to(['article/view', 'slug' => $article->slug]) ?>"><?= Html::encode($article->title) ?></a></h3>
                           <p><?= \yii\helpers\StringHelper::truncateWords(strip_tags($article->body), 20) ?></p>
                        </div>
                     </div>
                  <?php endforeach; ?>
               <?php else: ?>
                  <p>No articles found.</p>
               <?php endif; ?>
            </div>

            <div class="pagination">
               <?= LinkPager::widget([
                  'pagination' => $dataProvider->pagination,
               ]) ?>
            </div>

         </div>
      </div>

      

   </div>
</div>


 
