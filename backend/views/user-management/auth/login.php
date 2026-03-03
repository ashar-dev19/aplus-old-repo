<?php
   /**
   
    * @var $this yii\web\View
   
    * @var $model webvimark\modules\UserManagement\models\forms\LoginForm
   
    */
   
   
   
   use webvimark\modules\UserManagement\components\GhostHtml;
   
   use webvimark\modules\UserManagement\UserManagementModule;
   
   //use yii\bootstrap\ActiveForm;
   
   use yii\bootstrap4\ActiveForm;
   
   use yii\helpers\Html;
   
   
   
   $cookies = Yii::$app->request->cookies;
   
   if (($cookie = $cookies->get('__company_id__')) !== null) {
   
       $companyid = $cookie->value;
   
   }
   
   $cookies = Yii::$app->request->cookies;
   
   $companyName =  $cookies->getValue('__company_name__');
   
   
   
   ?>
<div class="login_sec">
   <div class="login_top">
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
      <div class="box">
         <img src="/images/logo-white.webp" alt="">
      </div>
   </div>
   <div class="main">
      <div class="left_col form_col">
         <h1 class="heading">Login to Your Account</h1>
         <div class="FormDiv">
            <?php $form = ActiveForm::begin([
               'id'      => 'login-form',
               
               'options'=>['autocomplete'=>'off'],
               
               'validateOnBlur'=>false,
               
               'fieldConfig' => [
               
               	'template'=>"{input}\n{error}",
               
               ],
               
               ]) ?>
            <div class="form_wrapper">

            <!-- <div class="field">
                  <?php //echo $form->field($model, 'username')
                           //->textInput([
                          // 'placeholder' => 'Email address',
                           //'autocomplete'=> 'off',
                           //])
                           //->label('Email') ?>
               </div> -->

               <div class="field">
                  <?= $form->field($model, 'username')
                     ->textInput(['placeholder'=>$model->getAttributeLabel('username'), 'autocomplete'=>'off']) ?>
               </div>

               <div class="field pass">
                  <?= $form->field($model, 'password')
                     ->passwordInput	([
                     
                     	'id'=>'password',
                     
                     	'placeholder'=>$model->getAttributeLabel('password'),
                     
                     	 'autocomplete'=>'off'
                     
                     
                     
                     ]) ?>
                  <i class="fa fa-eye" id="eyeicon"></i>
               </div>
               <?= (isset(Yii::$app->user->enableAutoLogin) && Yii::$app->user->enableAutoLogin) ? $form->field($model, 'rememberMe')->checkbox(['value'=>true]) : '' ?>
               <?= Html::submitButton(
                  UserManagementModule::t('front', 'Sign in'),
                  
                  ['class' => 'btn btn-lg btn-primary btn-block']
                  
                  ) ?>
            </div>
            <div class="row registration-block">
              
               <div class="col-sm-12">
                  <?= Html::a(
                     UserManagementModule::t('front', "Forgot password ?"),
                     
                     ['/user-management/auth/password-recovery']
                     
                     ) ?>
               </div>
            </div>
            <?php ActiveForm::end() ?>
         </div>
      </div>
      <div class="right_col">
         <div class="new-box">
            <div class="n-box">
               <h2 class="heading">New Here?</h2>
               <p>Sign up and Discover a great amount<br> of new opportunities!</p>
               <a href="https://beta.aplustudents.com/assessment/create" class="signup_btn">Sign Up</a>
                <?php //echo GhostHtml::a(
                     //UserManagementModule::t('front', "Registration"),
                     //['/user-management/auth/registration']
                     
               //) 
               ?>
               
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
   width: 40%;
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
   .FormDiv .registration-block{
                padding-top: 20px;
        }
        .FormDiv .registration-block a{
           text-align: center;
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

   @media (max-width:767px){
      .login_sec h1 {
        font-size: 28px;
        }
        .login_sec .left_col{
            width: 100%;
            padding-top: 40%;
         }
         .login_sec form {
            width: 85%;
         }
        .login_sec .right_col {
            width: 100%;
        }
         .new-box {
            height: 35vh;
        }

   }
@media (min-width:768px) and (max-width: 1024px) {
   .login_sec .main {
    justify-content: center;
   }
   .login_sec form {
      width: 100%;
   }
   .login_sec .left_col {
      padding: 30px 0px;
      padding-top: 25%;
      width: 83%;
    }
    .login_sec .right_col {
    width: 100%;
    }
    .new-box {
            height: unset;
        padding: 40px 10px;
    
    }
    .login_sec form{

    }


}   
</style>
<script>
   var eyeicon = document.getElementById("eyeicon");
   
   var password = document.getElementById("password");
   
   
   
   eyeicon.onclick = function(){
   
       if(password.type == "password"){
   
           password.type = "text";
   
       }else{
   
           password.type = "password";
   
       }
   
   }
   
</script>
<?php
   $css = <<<CSS
   
   html, body {
   
   	 
   
   }
   
   #login-wrapper {
   
   	 
   
   }
   
   #login-wrapper .registration-block {
   
   	 
   
   }
   
   CSS;
   
   
   
   $this->registerCss($css);
   
   ?>