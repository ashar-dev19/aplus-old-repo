<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Assessment */

$this->title = 'Create Assessment';
// $this->params['breadcrumbs'][] = ['label' => 'Assessments', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
 
 <div class="inner_banner">
         <div class="container">
            <div class="row">
               
               <div class="col-md-12">
                  <div class="content">
                     <h4>Home <i class="fa fa-chevron-right"></i> Assessment</h4>
                     <h1>Assessment </h1>
                    
                  </div>
               </div>
        
                
            </div>
         </div>
         <img src="/images/inner-banner2.webp" alt="" class="bg_image">
</div>

<div class="assessment_page">
    <div class="container">
        <div class="row">
            <div class="col-md-1"></div>
            
            <div class="col-md-10">
                <?php if (Yii::$app->session->hasFlash('success')): ?>
                    <div class="alert alert-success">
                        <?= Yii::$app->session->getFlash('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (Yii::$app->session->hasFlash('error')): ?>
                    <div class="alert alert-danger">
                        <?= Yii::$app->session->getFlash('error') ?>
                    </div>
                <?php endif; ?>


                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
            </div>
            <div class="col-md-1"></div>
        </div>
    </div>
</div>            