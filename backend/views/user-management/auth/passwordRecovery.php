<?php

use webvimark\modules\UserManagement\UserManagementModule;
// use yii\bootstrap\ActiveForm;
use yii\bootstrap4\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\forms\PasswordRecoveryForm $model
 */

$this->title = UserManagementModule::t('front', 'Password recovery');
$this->params['breadcrumbs'][] = $this->title;
?>






<div class="login_sec forgot_pass">
   <div class="login_top">
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
      <div class="box">
         <img src="/images/logo-white.webp" alt="">
      </div>
   </div>
   <div class="main">
      <div class="left_col form_col">
         <h1 class="heading"><?= $this->title ?></h1>
         <div class="FormDiv">

		 
	 

			<?php if ( Yii::$app->session->hasFlash('error') ): ?>
				<div class="alert-alert-warning text-center">
					<?= Yii::$app->session->getFlash('error') ?>
				</div>
			<?php endif; ?>
			
            <?php $form = ActiveForm::begin([
					'id'=>'user',
					'layout'=>'horizontal',
					'validateOnBlur'=>false,
				]); ?>

				<?= $form->field($model, 'email')->textInput(['maxlength' => 255, 'autofocus'=>true]) ?>

				<?= $form->field($model, 'captcha')->widget(Captcha::className(), [
					'template' => '<div class="row3">
									<div >{image}</div>
									<div >{input}</div>
									</div>',
					'captchaAction'=>['/user-management/auth/captcha']
				]) ?>

				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9">
						<?= Html::submitButton(
							'<span class="glyphicon glyphicon-ok"></span> ' . UserManagementModule::t('front', 'Recover'),
							['class' => 'btn btn-primary']
						) ?>
					</div>
				</div>

				<?php ActiveForm::end(); ?>
         </div>
      </div>
      <div class="right_col">
         <div class="new-box">
            <div class="n-box">
               <h2 class="heading">New Here?</h2>
               <p>Sign up and Discover a great amount<br> of new opportunities!</p>
               <a href="https://beta.aplustudents.com/assessment/create" class="signup_btn">Sign Up</a>
            </div>
         </div>
      </div>
   </div>
</div>
<style>
   /*starts login page*/
   .login_sec{}
   .login_sec .login_top{
   position: absolute;
   padding: 15px 0px;
   z-index: 2;
   }
   .login_sec .login_top .box{
   margin-left: 20%;
   }
   .login_sec .login_top img{}
   .login_sec h1{
   text-align: center;
   margin-bottom: 35px;
   font-size: 55px;
   }
   .login_sec .main{
   display: flex;
   flex-wrap: wrap;
   align-items: center;
   }
   .FormDiv {
   display: flex;
   justify-content: center;
   }
   .login_sec .form_col{
   /* display: flex; */
   flex-direction: column;
   align-items: center;
   justify-content: center;
   }
   .login_sec .left_col{
   width: 70%;
   padding: 30px 0px;
   }
   .login_sec .right_col{
   width: 30%;
   }
   .login_sec .form_wrapper{
   }
   .login_sec form{
   width: 100%;
   }
   .login_sec form input{
   background-color: #EEF5F3;
   border: unset;
   outline: none;
   padding: 28px 20px;
   width: 100%;
   border-radius: 107px;
   font-size: 16px;
   font-weight: 500;
   } 
   .login_sec form input::placeholder{
   }
   .login_sec form button{
   background-color: #1EB2A6;
   border-radius: 100px;
   width: 40%;
   padding: 15px 20px;
   display: block;
   margin: 0 auto;
   font-size: 16px;
   color: #fff;
   font-weight: 600;
   outline: none;
   border: unset;
   }
   .login_sec form button:hover{
   background-color: #000;
   }
   .login_sec form .field{
   margin-bottom: 20px;
   }
   .login_sec form .field.pass{
   position: relative;
   }
   .login_sec form .field.pass #eyeicon{
   position: absolute;
   right: 33px;
   top: 50%;
   transform: translateY(-50%);
   color: #8b8b8b;
   }
   .login_sec form .field.pass #eyeicon:hover{
   color:#1EB2A6 ;
   cursor: pointer;
   }
   .new-box{
   display: flex;
   justify-content: center;
   align-items: center;
   height: 100vh;
   background-color: #1EB2A6;
   }
   .n-box{}
   .n-box h2{
   color: #fff;
   text-align: center;
   }
   .n-box p{
   font-size: 20px;
   text-align: center;
   color: #fff;
   line-height: 38px;
   margin-bottom: 30px;
   }
   .n-box .signup_btn{
   color: #000;
   background-color: #fff;
   padding: 15px 20px;
   font-size: 16px;
   text-decoration: none;
   border-radius: 100px;
   display: block;
   margin: 0 auto;
   width: 71%;
   text-align: center;
   font-weight: 600;
   }
   .was-validated 
   .custom-control-input:valid ~ 
   .custom-control-label, 
   .custom-control-input.is-valid ~ 
   .custom-control-label{
   color: #1EB2A6;
   }
   .custom-control-input:not(:disabled):active ~ 
   .custom-control-label::before{
   color: #fff;
   background-color: #1eb2a6;
   border-color: #1eb2a6;
   }
   .was-validated .custom-control-input:valid:checked ~ 
   .custom-control-label::before, 
   .custom-control-input.is-valid:checked ~ 
   .custom-control-label::before{
   background-color: #1eb2a6;
   border-color: #1eb2a6;
   }
   /*End Loing page*/
</style>
 
 