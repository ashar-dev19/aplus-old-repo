<?php
/**
 * @var yii\web\View $this
 * @var string $content
 */

use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;
 
 


$this->beginContent('@frontend/views/layouts/_clear.php');

use frontend\models\Newsletter;

$model = new Newsletter(); 

?>






<header>
  <!-- header top -->
  <div class="header_top">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-sm-12 col-md-12 col-lg-6">
          <ul class="info">
            <li class="item">
              <a href="mailto:admin@aplustudents.com"><i class="fa fa-envelope"></i> admin@aplustudents.com</a>
            </li>
            <li class="item">
              <a href="tel:905-460-4834"><i class="fa fa-phone"></i> 905-460-4834</a>
            </li>
          </ul>
        </div>

        <div class="col-sm-12 col-md-12 col-lg-6 d-flex justify-content-end align-items-center dnone_mob">
          <div class="social_icons">
            <a href="https://www.facebook.com/aplustudents" target="_blank"><i class="fa fa-facebook"></i></a>
            <a href="https://www.instagram.com/studentsaplus/" target="_blank"><i class="fa fa-instagram"></i></a>
            <a href="https://www.youtube.com/channel/UCi_BNskNcmwdwX4abUBAu1g" target="_blank"><i class="fa fa-youtube"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /header top -->

  <!-- unified navbar (desktop + mobile) -->
  <div class="header">
    <div class="container">
      <div class="row">
          
         <div class="col-md-12">
            <?php 

                  NavBar::begin([
                     'brandLabel' => Html::img('/images/logo_transparen11.png', [
                        'alt'   => 'A+ Students',
                        'class' => 'logo_main',
                     ]),
                     'brandUrl' => Url::to(['/site/index']),
                     'options'  => ['class' => 'navbar navbar-expand-lg navbar-light bg-white my_nav'],

                     // collapse wrapper id (must match toggler "data-target")
                     'collapseOptions' => ['id' => 'main-nav-collapse', 'class' => 'collapse navbar-collapse'],

                     // ✅ Bootstrap4 property is "togglerOptions" (not toggleButtonOptions)
                     'togglerOptions' => [
                        'class' => 'navbar-toggler',
                        'data'  => ['toggle' => 'collapse', 'target' => '#main-nav-collapse'],
                        'aria'  => ['controls' => 'main-nav-collapse', 'expanded' => 'false', 'label' => 'Toggle navigation'],
                     ],
                     // optional: icon inside button
                     'togglerContent' => '<span class="navbar-toggler-icon"></span>',
                  ]);

                  echo Nav::widget([
                     'options' => ['class' => 'navbar-nav ml-auto'],
                     'items' => [
                        ['label' => Yii::t('frontend', 'Home'),    'url' => ['/site/index']],
                        ['label' => Yii::t('frontend', 'About'),   'url' => ['/site/about']],
                        ['label' => Yii::t('frontend', 'Contact'), 'url' => ['/site/contact']],

                        // [
                        //       'label'   => Yii::t('frontend', 'Login'),
                        //       'url'     => ['/user-management/auth/login'],
                        //       'visible' => Yii::$app->user->isGuest && Yii::$app->controller->id !== 'application-detail',
                        // ],
                        [
                           'label'   => Yii::t('frontend', 'Login'),
                           'url'     => 'https://beta.aplustudents.com/user-management/auth/login',  
                           'active'  => false, 
                           'visible' => Yii::$app->user->isGuest && Yii::$app->controller->id !== 'application-detail',
                           ],
                        [
                              'label'   => Yii::$app->user->isGuest ? '' : Yii::$app->user->username,
                              'visible' => !Yii::$app->user->isGuest,
                              'items'   => [
                                 ['label' => Yii::t('frontend', 'Profile'),  'url' => ['/studentms/student/view']],
                                 ['label' => Yii::t('frontend', 'Settings'), 'url' => ['/user/default/index']],
                                 [
                                    'label'   => Yii::t('frontend', 'Backend'),
                                    'url'     => Yii::getAlias('@backendUrl'),
                                    'visible' => Yii::$app->user->can('manager')
                                 ],
                                 [
                                    'label'       => Yii::t('frontend', 'Logout'),
                                    // 'url'         => ['/user-management/auth/logout'],
                                    'url'     => 'https://beta.aplustudents.com/user-management/auth/logout',  
                                    'linkOptions' => ['data-method' => 'post']
                                 ],
                              ],
                        ],
                     ],
                  ]);

                  NavBar::end();
            ?>
         </div>
      </div>   
    </div>
  </div>
  <!-- /unified navbar -->
</header>

<style>
  /* optional: make the hamburger icon visible on light background */
  .navbar .navbar-toggler { border: 0; }
  .navbar-light .navbar-toggler-icon{
    background-image:url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(0,0,0,.65)' stroke-width='2' stroke-linecap='round' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
  }
  .my_nav .nav-link{ padding:.5rem .9rem; }
</style>





<main class="flex-shrink-0" role="main">
    <?php echo $content ?>
</main>

<!-- footer section -->
 

<div class="newsletter">
    <div class="container">
        <div class="row">
            <!-- <div class="col-md-1"></div> -->
            <div class="col-md-12">
                <div class="inner_wrap">
                    <div class="content">
                        <h2>Newsletter - Stay tuned and get the latest update</h2>
                    </div>
                        <div class="subs">

                                 <?php 
                                 $form = ActiveForm::begin([
                                    'action' => ['site/subscribe'],
                                    'method' => 'post',
                                    'options' => ['class' => 'newsletter-form'],
                                    'enableClientValidation' => true,
                                 ]);
                                 ?>

                                 <?= $form->field($model, 'email')->input('email', [
                                    'placeholder' => 'Enter your email',
                                    'required'    => true,
                                    'maxlength'   => 222,
                                    'pattern'     => '^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[A-Za-z]{2,}$',
                                 ])->label(false) ?>

                                 <!-- honeypot -->
                                 <input type="text" name="hp" value="" style="display:none" autocomplete="off">
                                 <!-- time-to-submit -->
                                 <input type="hidden" name="t0" value="<?= time() ?>">

                                 <?= Html::submitButton('Subscribe <i class="fa fa-paper-plane"></i>', ['class' => 'btn btn-primary']) ?>

                                 <?php ActiveForm::end(); ?>



                                       
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
                        </div>


                </div>
            </div>
            <!-- <div class="col-md-1"></div> -->
        </div>
    </div>
</div>
  
<!-- main footer  -->
<footer>
   <div class="container">
       <div class="parent">
            <div class="col">
                  <a class="logo" href="home.html">
                     <!-- Academia
                     <span>Online Education &amp; Learning</span> -->
                     <img src="/images/logo_transparen11.png" class="logo_footer" alt="Site logo">
                     
                  </a>
                  <p> With over 15 years and thousands of students, we have perfected the concept of online learning.  </p>

                  
                  <div class="social_icons">
                            <a href="https://www.facebook.com/aplustudents" target="_blank" ><i class="fa fa-facebook"></i> </a>
                                 <a href="https://www.instagram.com/studentsaplus/" target="_blank" ><i class="fa fa-instagram"></i> </a>
                                 <a href="https://www.youtube.com/channel/UCi_BNskNcmwdwX4abUBAu1g" target="_blank"><i class="fa fa fa-youtube"></i></a>
               </div>

             </div>
             <div class="col col_small">
                <h4>Explore</h4>
                <ul>
                   <li> <a href="/site/about"> <i class="fa fa-chevron-right"></i>About Us</a></li>
                      <li><a href="/article/index"> <i class="fa fa-chevron-right"></i>Blog</a></li>
                    </ul>
            </div>

             <div class="col col_small">
                <h4>Quick Links</h4>
                <ul>
                   <li>
                        <a href="/site/contact"> <i class="fa fa-chevron-right"></i>Contact Us</a>
                  </li>
                 
                  
                       
                        
                       
                </ul>

             </div>
             

             <div class="col">
             <h4>Have a Questions?</h4>
                <div class="cont_fields">
                     <div>
                        <i class="icon fa fa-map marker"></i>
                        <p>161 Bay St, Toronto, Ontario M5J 1C4</p>
                     </div>
                      <div>
                        <i class="icon fa fa-phone"></i>
                        <a href="tel:9054604834"> 905-460-4834</a>
                     </div>
                      <div>
                        <i class="icon fa fa-paper-plane"></i>
                       <a href="mailto:admin@aplustudents.com ">  admin@aplustudents.com </a>
                     </div>
                </div>
             </div>
            
             

        </div> 

        
   </div>  
   <div class="copyright_sec">
            <p>Copyright © 2025 All rights reserved </p>
   </div>   
</footer>

<!-- for mobile menu  -->
<div class="overlay-main"></div>
<?php $this->endContent() ?>