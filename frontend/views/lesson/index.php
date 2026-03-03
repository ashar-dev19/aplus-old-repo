<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\search\LessonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// Fetch the current student ID from the session
$currentStudent = Yii::$app->session->get('current_student');
$studentId = $currentStudent ? $currentStudent['id'] : null;

// Generate the dynamic Home URL
$homeUrl = $studentId ? Url::to(['/student/view', 'id' => $studentId]) : '#';

// $gradeName = $chapter->grade ? $chapter->grade->title : 'Unknown Grade';



$this->title = 'Lessons';

// Default Yii2 Breadcrumbs
$this->params['breadcrumbs'][] = ['label' => 'Home', 'url' => $homeUrl];
$this->params['breadcrumbs'][] = ['label' => $gradeName, 'url' => Url::to(['grade/grades', 'id' => $gradeId, 'subjectid' => $subjectId])];
$this->params['breadcrumbs'][] = ['label' => $chapterName, 'url' => Url::to(['/chapter', 'grade_id' => $gradeId, 'subject_id' => $subjectId])];

 

?>







<div class="topic_page lesson_page">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12">
            

            <?= Breadcrumbs::widget([
                'homeLink' => false,
                'links' => $this->params['breadcrumbs'],
                'itemTemplate' => "<li class='breadcrumb-item'>{link}</li>\n",
                'activeItemTemplate' => "<li class='breadcrumb-item active'>{link}</li>\n",
                'options' => ['class' => 'breadcrumb'],
            ]) ?>

            <!-- ✅ New back button (same style as chapters page) -->
            <a href="<?= Url::to(['/chapter', 'grade_id' => $gradeId, 'subject_id' => $subjectId]) ?>"
            class="theme_btn back">
            <i class="fa fa-chevron-left"></i><?= Html::encode($chapterName) ?>
            </a>


                <div class="boxes">
                    <?= ListView::widget([
                        'dataProvider' => $dataProvider,
                        'pager' => [
                            'hideOnSinglePage' => true,
                        ],
                        'itemView' => function ($model, $key, $index, $widget) use ($lessonModels) {
                            // Find the corresponding lesson model with percentage
                            $lessonModel = array_filter($lessonModels, function($lesson) use ($model) {
                                return $lesson['model']->id === $model->id;
                            });
                            $percentage = !empty($lessonModel) ? reset($lessonModel)['percentage'] : 0;

                            return $this->render('_item', [
                                'model' => $model,
                                'percentage' => $percentage,
                                'index' => $index + 1, // Pass the lesson number (index + 1)
                            ]);
                        },
                        'layout' => '{items}',
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Start of LiveAgent integration script: Chat button: Circle animated button 37 -->
<script type="text/javascript">
(function(d, src, c) { var t=d.scripts[d.scripts.length - 1],s=d.createElement('script');s.id='la_x2s6df8d';s.defer=true;s.src=src;s.onload=s.onreadystatechange=function(){var rs=this.readyState;if(rs&&(rs!='complete')&&(rs!='loaded')){return;}c(this);};t.parentElement.insertBefore(s,t.nextSibling);})(document,
'https://aplustudents.ladesk.com/scripts/track.js',
function(e){ LiveAgent.createButton('3s64radz', e); });
</script>
<!-- End of LiveAgent integration script -->