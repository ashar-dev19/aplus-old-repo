<?php
/**
 * @var $this yii\web\View
 * @var $model common\models\Article
 */
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\StringHelper;
?>

<div class="card mb-4">
    <!-- card image -->
    <?php if ($model->picture) : ?>
        <?php echo Html::img(
            Yii::$app->glide->createSignedUrl([
                'glide/index',
                'path' => $model->picture,
                'w' => 100
            ], true),
            ['class' => 'card-img-top']
        ) ?>
    <?php endif; ?>
    <!-- /card image -->

    <!-- card body -->
    <div class="card-body d-flex flex-column align-items-start">
        <?php echo Html::a(
            Html::encode($model->title),
            ['view', 'id'=>$model->id],
            ['class' => ['h3', 'text-decoration-none', 'card-title']]
        ) ?>
        <p class="card-text">
            Price: <?php echo Yii::$app->formatter->asCurrency($model->sale_price) ?><br />
            Points Required: <?php echo Yii::$app->formatter->asDecimal($model->points_price) ?>
        </p>
        <p class="card-text">
            <?php echo StringHelper::truncate(HtmlPurifier::process($model->description), 400, '...', null, true) ?>
        </p>

        <?php echo Html::a(
            Html::encode(Yii::t('frontend', 'Read More ')). ' <i class="fas fa-arrow-right"></i>',
            ['view', 'id'=>$model->id],
            ['class' => ['btn', 'btn-primary']]
        ) ?>
    </div>
    <!-- /card body -->

   
</div>
