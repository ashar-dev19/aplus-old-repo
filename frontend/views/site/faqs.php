<?php
use yii\helpers\Html;

$this->title = Yii::t("frontend", "FAQ's");
?>
<!-- Inner Banner -->
<div class="inner_banner">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="content">
                     <h4>Home <i class="fa fa-chevron-right"></i> <?php echo Html::encode($this->title) ?></h4>
                     <h1>FAQ's </h1>
                    
                  </div>
               </div>
                
            </div>
         </div>
         <img src="/images/inner-banner2.webp" alt="" class="bg_image">
      </div>
<!-- Inner Banner -->

<div class="faqs_sec">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
              
                <h2 class="heading"> How can we help you?</h2>
                 
                <div class="accordion" id="accordionFlushExample">
                        <div class="accordion-item">
                            <h4 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                Who is A+ Students?
                            </button>
                            </h4>
                            <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body"><p>A+ Students is one of the only learning companies that incorporates online learning, workbooks and worksheeets, and live one on one tutor all in one.
                                    We create a customized learning experience for your child to ensure they reavh their maximum potential in class.</p>
                                    <p>Established in 2005, we bring over a decade in experience and have taught thousnads of students across the continent. Your child's future is
                                        secure in our hands through our advanced learning program created towards your child's needs.</p>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h4 class="accordion-header" id="flush-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                    How is A+ Students different from other online programs?
                            </button>
                            </h4>
                            <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body"> <p>It is a custimzed learning program that targets your child's learning 
                                needs and then creates a catered program to help them excel in school.</p>
                                <p>Families will get access to a comprehensive curriculum based program that covers everything being taught in school.</p>
                                <p>You will also be given all the workbooks, school textbooks, and worksheets to practice the same material from class.</p>
                                <p>Lastly, you will have access to a one-on-one teacher who will cover any concepts that the student is struggling in. It's a live tecaher 
                                    which is not pre-reorded or conducted in group sessions. It is catered only for your child's one-on-one.</p>
                            </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h4 class="accordion-header" id="flush-headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                    Do you teach the same material as my child's school?
                            </button>
                            </h4>
                            <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body"><p>Yes, our program is connected to the local curriculum and we teach the exact same lessons that are being taught in class. Through this, the students can learn before going to class to ensure that they are always ahead of everyone else. 
                                They can even strengthen any weak areas from previous grades to get a full understanding of all concepts.</p></div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h4 class="accordion-header" id="flush-headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseThree">
                                How can I track my child's progress
                            </button>
                            </h4>
                            <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body"><p>Through our advanced analytical tools, we provide daily and weekly reports dircectly to the parents. Futhermore, 
                                the families can connect with our teachers to get a direct report of the child's progress and performance.</p></div>
                            </div>
                        </div>
                        
                    </div>
          
                <div class="bottom_content">
                   <h4>Get the full A+ Students experience</h4>
                   <a href="#">Book a Free Trial</a>
                </div>
            </div>
             
        </div>

    </div>
</div>

<div class="faqs_contact">
    <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-6 col-lg-3">
                    <div class="box">
                        <div class="cont_box">
                            <div class="icon">
                                <img src="/images/clock.png">
                            </div>
                            <div class="text">
                                <span>Monday - Friday</span>
                                <strong>8:00AM-8:00PM</strong>
                            </div>
                            
                        </div>  
                        <div class="cont_box ">
                            <div class="icon">
                            <img src="/images/telephone.png">
                            </div>
                            <div class="text">
                            <span>Call Us</span>
                            <strong>905-460-4834</strong>
                            </div>
                            <a href="tel:9054604834">test</a>
                        </div>

                        <div class="social_icons">
                            <!-- <a href="#"><span class="fa fa-facebook"><i class="sr-only">Facebook</i></span></a> -->
                            <a href="https://www.facebook.com/aplustudents"><i class="fa fa-facebook"></i> </a>
                            <!-- <a href="#"><i class="fa fa fa-youtube"></i></a> -->
                            <a href="https://www.linkedin.com/in/Aplus-students"><i class="fa fa-linkedin"></i></a>
                            <a href="https://twitter.com/aplus_students"><i class="fa fa-twitter"></i></a>
                            <a href="https://www.instagram.com/studentsaplus/"><i class="fa fa-instagram"></i> </a>
                            <a href=" https://plus.google.com/100139132828254137034"><i class="fa fa-google-plus-g"></i> </a>
                        </div>
                    </div>    

                </div>
                <!-- <div class="col-12 col-sm-12 col-md-12 col-lg-1"></div> -->
                <div class="col-12 col-sm-12 col-md-6 col-lg-9 content_parent">
                    <div class="content">
                    <h3>We are here to answer any questions you may have</h3>
                    <p>Below you'll find answers to the most commonly asked questions. If you don't find what you're looking for, 
                        please contact us at: <a href="tel:9054604834">905-460-4834</a></p>
                    </div>
                </div> 
            </div>
    </div>    

</div>








