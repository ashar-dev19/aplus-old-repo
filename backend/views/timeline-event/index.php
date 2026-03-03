<?php
/**
 * @author Eugine Terentev <eugine@terentev.net>
 * @author Victor Gonzalez <victor@vgr.cl>
 * @var yii\web\View $this
 * @var common\models\TimelineEvent $model
 * @var yii\data\ActiveDataProvider $dataProvider
 */

use rmrevin\yii\fontawesome\FAS;

$this->title = Yii::t('backend', 'Admin Panel');
$icons = [
    'user' => FAS::icon('user', ['bg-blue'])
];


?>

<?php \yii\widgets\Pjax::begin() ?>
<div class="row ">
    <div class="col-md-12">
        <?php 
        
echo \yii\helpers\Html::a('Clear All',
    ['clear'], ['class'=>'btn btn-danger',
    'data'=>['method'=>'post','confirm'=>'Delete ALL notifications?']]);
?>
&nbsp;
<?php 
echo \yii\helpers\Html::a('Clear Newsletter',
    ['clear','category'=>'newsletter'], ['class'=>'btn btn-warning',
    'data'=>['method'=>'post','confirm'=>'Delete newsletter notifications?']]);


?>
&nbsp;

<?php 
// echo \yii\helpers\Html::a('Clear Contact', ['clear','category'=>'contact'], [
//   'class'=>'btn btn-info',
//   'data'=>['method'=>'post','confirm'=>'Clear contact for **you**?']
// ]);

?>

<br>
        <?php if ($dataProvider->count > 0) : ?>
            <div class="timeline">
                
                <?php foreach ($dataProvider->getModels() as $model) : ?>
                    <?php if (!isset($date) || $date != Yii::$app->formatter->asDate($model->created_at)) : ?>
                        <!-- timeline time label -->
                        <div class="time-label">
                            <span class="bg-blue">
                                <?php echo Yii::$app->formatter->asDate($model->created_at) ?>
                            </span>
                        </div>
                        <?php $date = Yii::$app->formatter->asDate($model->created_at) ?>
                    <?php endif; ?>
                    <div class="tpanel">
                        <?php
                        try {
                            $viewFile = sprintf('%s/%s', $model->category, $model->event);
                            echo $this->render($viewFile, ['model' => $model]);
                        } catch (\yii\base\InvalidArgumentException $e) {
                            echo $this->render('_item', ['model' => $model]);
                        }
                        ?>
                    </div>
                <?php endforeach; ?>

                <div><?php echo FAS::icon('clock', ['class' => ['bg-gray']]) ?></div>
            </div>
        <?php else : ?>
            <?php echo Yii::t('backend', 'No events found') ?>
        <?php endif; ?>
    </div>
    <div class="col-md-12 text-center">
        <?php echo \yii\widgets\LinkPager::widget([
            'pagination'=>$dataProvider->pagination,
            'options' => ['class' => 'pagination']
        ]) ?>
    </div>
</div>
<?php \yii\widgets\Pjax::end() ?>

