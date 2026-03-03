<?php
/**
 * @author Jawwad <jawwad.software@gmail.com>
 * @var common\models\TimelineEvent $model
 */

use rmrevin\yii\fontawesome\FAS;
use yii\helpers\Html;
$locations= [ '0' => 'Markfield' ,'1' => 'East London', '2' => 'Birmingham'];
?>

<?php echo FAS::icon('user-plus', ['class' => 'bg-green']) ?>
<div class="timeline-item">
    <span class="time">
        <?php echo FAS::icon('clock').' '.Yii::$app->formatter->asRelativeTime($model->created_at) ?>
    </span>

    <h3 class="timeline-header">
        <?php echo Yii::t('backend', 'New '. Html::a("ModuleAttendance added",
         '/studentms/student-attendance/?StudentAttendanceSearch[module_id]='.$model->data['module_id'].'&StudentAttendanceSearch[attendance_date]='.$model->data['date'] )
         .' for '.$model->data['module']. ' at '.$locations[$model->data['location_id']], 
        [
            'module' => Html::a($model->data['module_id'], ['user/view', 'id' => $model->data['module']]),
            'created_at' => Yii::$app->formatter->asDatetime($model->data['created_at'])
        ]) ?>
    </h3>
</div>