<?php
/**
 * @var $this yii\web\View
 * @var $model frontend\models\Student
 */

use yii\helpers\Html;
?>

<div class="card mb-3">
    <div class="box">
        <div class="pic">
            <?php
             
            $raw = trim((string)$model->details);
            $placeholders = ['user.png', 'user.jpg', 'default.png', 'default.jpg', 'awaiting.png'];
            $useGenderDefault = ($raw === '' || in_array(strtolower(basename($raw)), $placeholders, true));

            if (!$useGenderDefault) {
                $path = parse_url($raw, PHP_URL_PATH) ?: $raw;
                $path = '/' . ltrim($path, '/');

                if (stripos($path, '/uploads/') === false) {
                    $path = '/uploads/' . ltrim($path, '/');
                }

                $fs = Yii::getAlias('@webroot') . $path;
                if (!is_file($fs)) {
                    $useGenderDefault = true;
                }
            }

            if ($useGenderDefault) {
                
                $imagePath = ((int)$model->gender === 2) ? '/uploads/female.png' : '/uploads/male.png';
            } else {
                $imagePath = $path; 
            }
            
            ?>

            <?= Html::a(
                Html::img($imagePath, [
                    'alt'   => $model->full_name,
                    'class' => 'student-img'
                ]),
                ['view', 'id' => $model->id],
                ['class' => 'image-link']
            ) ?>
        </div>

        <div class="content">
            <?= Html::a(
                Html::encode($model->full_name),
                ['view', 'id' => $model->id],
                ['class' => ['title']]
            ) ?>

            <?= Html::a('<i class="fa fa-gear"></i> Manage Profile', ['/student/update', 'id' => $model->id], ['class' => 'view_btn']) ?>
          
            <?= Html::a('Reports', ['/student/select-and-reports', 'id' => $model->id], ['class' => 'view_btn']) ?>



        </div>
    </div>
</div>
