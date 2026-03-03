<?php
   /**
    * @var yii\web\View $this
    * @var common\models\Student $model
    * @var array $categories
    * @var array $archive
    */
   
   use yii\helpers\Html;
   use yii\helpers\HtmlPurifier;
    
   ?>
<!-- start desktop_header -->
<div class="profile_detail header desktop_header">
   <div class="container">
      <div class="row align-items-center">
         <div class="col-md-8">
            <div class="navi desktop_menu">
               <ul>
                  <li> 
                     <a class="logo" href="/student/current">
                     <img src="/images/logo-white.webp" alt="">
                     </a>
                  </li>
                  <li><a href="#">Home</a></li>
                  <li><a href="#">Reports</a></li>
                  <li><a href="#">Classes</a></li>
                  <li><a href="#">Math</a></li>
                  <li><a href="#">English</a></li>
               </ul>
            </div>
         </div>
         <div class="col-md-4">
            <div class="navi right">
               <ul>
                  <li> <a href="#"><i class="fa fa-search"></i> </a></li>
                  <li> <a href="#"><i class="fa fa-bell"></i> </a></li>
                  <li><a href="#" class="avator">
                     </a>
                  </li>
               </ul>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- end desktop_header -->
<!-- mob_header -->
<div class="profile_detail header mob_header">
   <div class="container">
      <div class="row align-items-center">
         <div class="col-md-12">
            <div class="inner">
               <div class="logo_parent">
                  <a class="logo" href="/student/current">
                  <img src="/images/logo-white.webp" alt="">
                  </a>
               </div>
               <!-- mobile menu -->
               <div class="menu-toggle"> <i class="fa fa-bars" aria-hidden="true"></i> </div>
               <div class="mobile_menu">
                  <div class="top-close-menu"><i class="fa fa-times" aria-hidden="true"></i></div>
                  <ul>
                     <li><a href="#">Home</a></li>
                     <li><a href="#">Reports</a></li>
                     <li><a href="#">Classes</a></li>
                     <li><a href="#">Math</a></li>
                     <li><a href="#">English</a></li>
                     <!-- <li><a href="#">Catalog</a>
                        <ul>
                            <li><a href="#">Catalog 1</a></li>
                            <li><a href="#">Catalog 2</a></li>
                            <li><a href="#">Catalog 3</a></li>
                            </ul>
                        </li> -->
                  </ul>
               </div>
               <!-- mobile menu -->
            </div>
         </div>
      </div>
   </div>
</div>

<div class="course_page math">
   <div class="container">
      <div class="row align-items-center">
         <div class="col-md-12">
            <h2 class="heading">Grages for high school</h2>
            <!-- boxes -->
            <div class="boxes">
               <div class="box">
                  <div class="inner">
                     <!-- <i class="fa fa-eercast"></i> -->
                     <a href="#" class="title">
                        <h2>Pre-K</h2>
                        <p> Counting objects, inside and outside, longer and shorter, 
                            letter names, rhyming words, and more.
                        </p>
                     </a>
                     <div class="skils">
                        <div class="sk_title">
                           <h4>Math</h4>
                        </div>
                        <div class="sk_link">
                           <a href="#">165 skils</a>
                        </div>
                        <div class="sk_title">
                           <h4>Language arts</h4>
                        </div>
                        <div class="sk_link">
                           <a href="#">83   skils</a>
                        </div>
                        <div class="ribbon prek"><span>P</span></div>
                     </div>
                  </div>
               </div><!-- box -->

               <div class="box">
             <div class="inner">
        <a href="#" class="title">
            <h2>Kindergarten</h2>
            <p>Comparing numbers, names of shapes, letter names and sounds, plants and animals, communities, and more.</p>
        </a>
        <div class="skils">
            <div class="sk_title">
                <h4>Math</h4>
            </div>
            <div class="sk_link">
                <a href="#">325 skills</a>
            </div>
            <div class="sk_title">
                <h4>Language arts</h4>
            </div>
            <div class="sk_link">
                <a href="#">143 skills</a>
            </div>
            <div class="sk_title">
                <h4>Science</h4>
            </div>
            <div class="sk_link">
                <a href="#">56 skills</a>
            </div>
            <div class="sk_title">
                <h4>Social studies</h4>
            </div>
            <div class="sk_link">
                <a href="#">45 skills</a>
            </div>
            <div class="ribbon prek"><span>K</span></div>
        </div>
    </div>
</div><!-- box -->

<div class="box">
    <div class="inner">
        <a href="#" class="title">
            <h2>First Grade</h2>
            <p>Adding and subtracting, tens and ones, short and long vowel words, light and sound, rules and laws, and more.</p>
        </a>
        <div class="skils">
            <div class="sk_title">
                <h4>Math</h4>
            </div>
            <div class="sk_link">
                <a href="#">286 skills</a>
            </div>
            <div class="sk_title">
                <h4>Language arts</h4>
            </div>
            <div class="sk_link">
                <a href="#">190 skills</a>
            </div>
            <div class="sk_title">
                <h4>Science</h4>
            </div>
            <div class="sk_link">
                <a href="#">59 skills</a>
            </div>
            <div class="sk_title">
                <h4>Social studies</h4>
            </div>
            <div class="sk_link">
                <a href="#">44 skills</a>
            </div>
            <div class="ribbon prek"><span>1</span></div>
        </div>
    </div>
</div><!-- box -->

<div class="box">
    <div class="inner">
        <a href="#" class="title">
            <h2>Second Grade</h2>
            <p>Place-value models, contractions, irregular plurals, plants and animals, historical figures, and more.</p>
        </a>
        <div class="skils">
            <div class="sk_title">
                <h4>Math</h4>
            </div>
            <div class="sk_link">
                <a href="#">299 skills</a>
            </div>
            <div class="sk_title">
                <h4>Language arts</h4>
            </div>
            <div class="sk_link">
                <a href="#">236 skills</a>
            </div>
            <div class="sk_title">
                <h4>Science</h4>
            </div>
            <div class="sk_link">
                <a href="#">78 skills</a>
            </div>
            <div class="sk_title">
                <h4>Social studies</h4>
            </div>
            <div class="sk_link">
                <a href="#">62 skills</a>
            </div>
            <div class="ribbon prek"><span>2</span></div>
        </div>
    </div>
</div><!-- box -->

<div class="box">
    <div class="inner">
        <a href="#" class="title">
            <h2>Third Grade </h2>
            <p>Multiplying and dividing, bar graphs, pronouns, possessives, weather and climate, geography, and more.</p>
        </a>
        <div class="skils">
            <div class="sk_title">
                <h4>Math</h4>
            </div>
            <div class="sk_link">
                <a href="#">337 skills</a>
            </div>
            <div class="sk_title">
                <h4>Language arts</h4>
            </div>
            <div class="sk_link">
                <a href="#">231 skills</a>
            </div>
            <div class="sk_title">
                <h4>Science</h4>
            </div>
            <div class="sk_link">
                <a href="#">91 skills</a>
            </div>
            <div class="sk_title">
                <h4>Social studies</h4>
            </div>
            <div class="sk_link">
                <a href="#">98 skills</a>
            </div>
            <div class="ribbon prek"><span>3</span></div>
        </div>
    </div>
</div><!-- box -->

<div class="box">
    <div class="inner">
        <a href="/math/grade-4" class="title">
            <h2>Fourth Grade</h2>
            <p>Fractions and decimals, synonyms and antonyms, fossils and rock layers, government, and more.</p>
        </a>
        <div class="skils">
            <div class="sk_title">
                <h4>Math</h4>
            </div>
            <div class="sk_link">
                <a href="#">358 skills</a>
            </div>
            <div class="sk_title">
                <h4>Language arts</h4>
            </div>
            <div class="sk_link">
                <a href="#">233 skills</a>
            </div>
            <div class="sk_title">
                <h4>Science</h4>
            </div>
            <div class="sk_link">
                <a href="#">114 skills</a>
            </div>
            <div class="sk_title">
                <h4>Social studies</h4>
            </div>
            <div class="sk_link">
                <a href="#">111 skills</a>
            </div>
            <div class="ribbon prek"><span>4</span></div>
        </div>
    </div>
</div><!-- box -->




               
            </div> <!-- boxes -->
         </div><!-- col-md-12 --> 
        </div><!-- row --> 
   </div>
</div>


 