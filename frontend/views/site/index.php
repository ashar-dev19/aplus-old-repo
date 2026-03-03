<?php
   /**
    * @var yii\web\View $this
    */
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\bootstrap4\ActiveForm;

   
   $this->title = Yii::$app->name;
   use frontend\models\Newsletter;
   $model = new Newsletter(); 
    // use app\assets\FrontendAsset;
   
   
   
    // FrontendAsset::register($this);
   ?>
<!-- banner section-->
<div class="banner">
   <div class="container">
      <div class="row">
         <div class="col-lg-5 col-md-12 col-sm-12">
            <div class="content">
               <h4>Reaching for Higher Education</h4>
               <h1>Structured And Proven Tutoring Program For Grade KG - 12 </h1>
               <p>With over 15 years and thousands of students, we have perfected the concept of online learning. 
                  Utilizing an advanced system that is linked to your child’s curriculum, 
                  we use an accelerated learning platform to help the children learn any concept in a fraction of the time. 
               </p>
               <div class="btns">
                  <!-- <a href="/site/contact" class="btn1"> Get Started Now! <i class="fa fa-chevron-right"></i></a>
                  <a href="/assessment/create" class="btn-white">Get assessment <i class="fa fa-chevron-right"></i></a> -->
                  <a href="/assessment/create" class="animated_btn">Get assessment <i class="fa fa-chevron-right"></i></a>
               </div>
            </div>
         </div>
         <div class="col-lg-7 col-md-12 col-sm-12"></div>
      </div>
   </div>
</div>
<!-- banner section-->

<!-- online courses -->
<div class="tutorials_sec">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <h4 class="subheading"> Comprehensive Learning Experience</h4>
            <h2 class="heading"> Unlock Your Learning Potential</h2>
            <div class="main">
               <div class="box animate__animated animate__fadeInDown animate__slow animate__delay-1s">
                  <div class="icon">
                    <img src="/images/vid-tuts.png">
                  </div>
                  <h5>1000+ Video Tutorials</h5>
                  <p> Gain instant access to our full library of online classes and step by step tutorials</p>
               </div>
               <div class="box animate__animated animate__fadeInDown animate__slow animate__delay-1s">
                  <div class="icon">
                     <img src="/images/one-on-one.png">
                  </div>
                  <h5>1-on-1 Personal Tutoring</h5>
                  <p> Get the one-on-one help you need to master those hard to grasp concepts</p>
               </div>
               <div class="box animate__animated animate__fadeInDown animate__slow animate__delay-1s">
                  <div class="icon">
                     <img src="/images/daily.png">
                  </div>
                  <h5>100,000+ Daily Lessons </h5>
                  <p> Stay ahead of your class with daily lessons that come straight from your curriculum</p>
               </div>

               <!-- <div class="box animate__animated animate__fadeInDown animate__slow animate__delay-2s">
                  <div class="icon">
                     <img src="/images/laptop.png">
                  </div>
                  <h5>Free Laptop</h5>
                  <span class="small">with enrolment</span>
                  <a href="/assessment/create">Claim Offer</a>
               </div> -->
              <div class="box animate__animated animate__fadeInDown animate__slow animate__delay-2s">
                  <div class="icon">
                     <img src="/images/live-clases.png">
                  </div>
                  <h5>Daily Live Classes</h5>
                  <p> Follow along with the daily live classes for step-by-step tutorials on the lessons of the day.</p>
               </div>
               <div class="box animate__animated animate__fadeInDown animate__slow animate__delay-2s">
               <div class="icon">
                      <img src="/images/best-price.png">
               </div>
           
                   
                  <h3><sup>$</sup> 200 <span>off</span></h3>
                  <h5>Any Tutoring Package</h5>
                  <a href="/assessment/create">Claim Offer</a>
               </div>
                
            </div>
         </div>
      </div>
   </div>
</div>
<!-- online courses -->

<!-- Newsletter Sec -->
<div class="newsletter_sec">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="inner_wrap">
                    <div class="content">
                        <h2>Subscribe Now to Stay Informed and Inspired!</h2>
                     
                        <?= $this->render('_newsletterForm', ['model' => new Newsletter()]) ?>


                           

                           
                           
                              
                                 
                  </div>


                </div>
            </div>
        </div>
    </div>
</div>
<!-- Newsletter Sec -->

<!-- about section-->
<div class="about_sec">
   <div class="container">
      <div class="row">
         <div class="col-lg-6 col-md-12 col-sm-12  align-items-center">
            <div class="image">
               <img src="/images/weare.jpg" alt="">
            </div>
         </div>
         <div class="col-lg-6 col-md-12 col-sm-12 right_parent">
            <div class="right_wrapper">
               <h3 class="subheading">Learn anything</h3>
               <h1>We are with you throughout the entire journey!</h1>
               <div class="about_box">
                  <div class="icon">
                     <img src="/images/online-learning.png">
                  </div>
                  <div class="content">
                     <h4>Step 1</h4>
                     <p>Catch up and get ahead of the class</p>
                  </div>
               </div>
               <div class="about_box">
                  <div class="icon">
                     <img src="/images/certificate.png">
                  </div>
                  <div class="content">
                     <h4>Step 2 </h4>
                     <p>connect to your teacher, review a lesson, get homework help and daily assignments</p>
                  </div>
               </div>
               <div class="about_box">
                  <div class="icon">
                     <img src="/images/scientist.png">
                  </div>
                  <div class="content">
                     <h4>Step 3</h4>
                     <p>Track your progress and earn achievement points</p>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- about section-->
<!-- CTA1 section-->
<div class="cta cta1">
   <div class="container">
      <div class="main">
         <div class="left_col">
            <h4>Looking for education solutions? Join thousands of students in your city using  A+ Students</h4>
         </div>
         <div class="right_col">
            <a href="/assessment/create">Explore A+ Students <i class="fa fa-chevron-right"></i></a>
         </div>
      </div>
   </div>
</div>
<!-- CTA1 section-->
<!-- own pace section -->
<div class="own_pace">
   <div class="container">
      <h2>Accelerate Your Learning Experience</h2>
      <h5>Experience flexible learning, tailored lessons, expert tutoring, and convenient resources. 
         A+ Student accelerates your education, ensuring success at your pace.
      </h5>
      <div class="main">
         <div class="box">
            <img src="/images/own-pace.jpg">
            <div class="content">
               <h4>Study at your own pace</h4>
               <p>Whether your goal is to catch up or get ahead, you can study at your own pace, 
                  in your free time and instantly connect with your tutor.
               </p>
               <a href="/assessment/create">Sign Up</a>   
            </div>
         </div>
         <div class="box">
            <img src="/images/own-pace1.jpg">
            <div class="content">
               <h4>Customized learning</h4>
               <p>Daily lessons, tutorials, homework and tests based on material directly linked to the child's classroom.</p>
               <a href="/assessment/create">Speak with the advisor</a>  
            </div>
         </div>
         <div class="box">
            <img src="/images/own-pace2.jpg">
            <div class="content">
               <h4>Online classes</h4>
               <p>1-on-1 tutoring from highly qualified teachers. Over 40 years of experience in personalized learning for Grades KG - 12.</p>
               <a href="/assessment/create">Book a lesson</a> 
            </div>
         </div>
         <div class="box">
            <img src="/images/own-pace3.jpg">
            <div class="content">
               <h4>Convenience</h4>
               <p>Get homework help, test prep and access to hundreds of textbooks, workbooks and printable material to practice Math and English.</p>
               <a href="/assessment/create">Learn more</a>   
            </div>
         </div>
      </div>
   </div>
</div>
<!-- own pace section -->
<!-- CTA2 section -->
<div class="cta cta2">
   <div class="container">
      <div class="main">
         <div class="left_col">
            <h2>Try us for free</h2>
            <h4>Get one hour of live 1 on 1 tutoring on us. Seriously.</h4>
         </div>
         <div class="right_col">
            <a href="/assessment/create">Get Started!</a>
         </div>
      </div>
   </div>
</div>
<!-- CTA2 section -->


<!-- courses sec -->
<!-- <div class="courses_sec">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <h4 class="subheading"> Our Courses</h4>
            <h2 class="heading"> Explore Our Popular Online Courses</h2>
            <div class="courses_slider">
               <div class="box">
                  <div class="main">
                     <div class="icon">
                        <img src="/images/engineer.png">
                     </div>
                     <div class="content">
                        <h4>Introducing to Programming with WordPress </h4>
                        <p class="rating">
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           <span>(5.0)</span>
                        </p>
                        <div class="author">
                           <img src="/images/author.webp">
                           <span>by John Smith</span>
                        </div>
                        <p class="lectures"><a href="#">50 lectures (190 hrs)</a></p>
                     </div>
                  </div>
                  <p class="course_price"><span>$100 All Course</span> / <span>$15 per month</span></p>
                  <a href="#" class="enroll_btn">Enroll Now!</a>
               </div>
               <div class="box">
                  <div class="main">
                     <div class="icon">
                        <img src="/images/engineer.png">
                     </div>
                     <div class="content">
                        <h4>Introducing to Programming with WordPress </h4>
                        <p class="rating">
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           <span>(5.0)</span>
                        </p>
                        <div class="author">
                           <img src="/images/author.webp">
                           <span>by John Smith</span>
                        </div>
                        <p class="lectures"><a href="#">50 lectures (190 hrs)</a></p>
                     </div>
                  </div>
                  <p class="course_price"><span>$100 All Course</span> / <span>$15 per month</span></p>
                  <a href="#" class="enroll_btn">Enroll Now!</a>
               </div>
               <div class="box">
                  <div class="main">
                     <div class="icon">
                        <img src="/images/engineer.png">
                     </div>
                     <div class="content">
                        <h4>Introducing to Programming with WordPress </h4>
                        <p class="rating">
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           <span>(5.0)</span>
                        </p>
                        <div class="author">
                           <img src="/images/author.webp">
                           <span>by John Smith</span>
                        </div>
                        <p class="lectures"><a href="#">50 lectures (190 hrs)</a></p>
                     </div>
                  </div>
                  <p class="course_price"><span>$100 All Course</span> / <span>$15 per month</span></p>
                  <a href="#" class="enroll_btn">Enroll Now!</a>
               </div>
               <div class="box">
                  <div class="main">
                     <div class="icon">
                        <img src="/images/engineer.png">
                     </div>
                     <div class="content">
                        <h4>Introducing to Programming with WordPress </h4>
                        <p class="rating">
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           <span>(5.0)</span>
                        </p>
                        <div class="author">
                           <img src="/images/author.webp">
                           <span>by John Smith</span>
                        </div>
                        <p class="lectures"><a href="#">50 lectures (190 hrs)</a></p>
                     </div>
                  </div>
                  <p class="course_price"><span>$100 All Course</span> / <span>$15 per month</span></p>
                  <a href="#" class="enroll_btn">Enroll Now!</a>
               </div>
            </div>
         </div>
      </div>
   </div>
</div> -->
<!-- courses sec -->

<!-- online courses -->
<!-- <div class="online_courses">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <h4 class="subheading"> Courses</h4>
            <h2 class="heading"> Browse Our Online Courses</h2>
            <div class="main">
               <a href="#" class="box">
                  <div class="icon">
                     <img src="/images/scientist.png">
                  </div>
                  <h5>UI/UX Design Courses</h5>
                  <span> 25 Courses</span>
               </a>
               <a href="#" class="box">
                  <div class="icon">
                     <img src="/images/scientist.png">
                  </div>
                  <h5>Art & Design</h5>
                  <span> 25 Courses</span>
               </a>
               <a href="#" class="box">
                  <div class="icon">
                     <img src="/images/scientist.png">
                  </div>
                  <h5>Computer Science</h5>
                  <span> 10 Courses</span>
               </a>
               <a href="#" class="box">
                  <div class="icon">
                     <img src="/images/scientist.png">
                  </div>
                  <h5>History & Archeologic</h5>
                  <span> 25 Courses</span>
               </a>
               <a href="#" class="box">
                  <div class="icon">
                     <img src="/images/scientist.png">
                  </div>
                  <h5>Software Engineering</h5>
                  <span> 25 Courses</span>
               </a>
               <a href="#" class="box">
                  <div class="icon">
                     <img src="/images/scientist.png">
                  </div>
                  <h5>Information Software</h5>
                  <span> 25 Courses</span>
               </a>
               <a href="#" class="box">
                  <div class="icon">
                     <img src="/images/scientist.png">
                  </div>
                  <h5>Health & Fitness</h5>
                  <span> 25 Courses</span>
               </a>
               <a href="#" class="box">
                  <div class="icon">
                     <img src="/images/scientist.png">
                  </div>
                  <h5>Marketing</h5>
                  <span> 25 Courses</span>
               </a>
               <a href="#" class="box">
                  <div class="icon">
                     <img src="/images/scientist.png">
                  </div>
                  <h5>Graphic Design</h5>
                  <span> 25 Courses</span>
               </a>
               <a href="#" class="box">
                  <div class="icon">
                     <img src="/images/scientist.png">
                  </div>
                  <h5>Music</h5>
                  <span> 25 Courses</span>
               </a>
               <a href="#" class="box">
                  <div class="icon">
                     <img src="/images/scientist.png">
                  </div>
                  <h5>Business Administration</h5>
                  <span> 25 Courses</span>
               </a>
               <a href="#" class="box">
                  <div class="icon">
                     <img src="/images/scientist.png">
                  </div>
                  <h5>Web Management</h5>
                  <span> 25 Courses</span>
               </a>
            </div>
         </div>
      </div>
   </div>
</div> -->
<!-- online courses -->

<!-- testimonials sec -->
<div class="testimonials_sec">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <h4 class="subheading"> Testimonial</h4>
            <h2 class="heading"> Our Successful Students</h2>
            <div class="testimonial_slider">
               <div class="box">
                  <div class="author">
                     <div class="pic">
                        <!-- <img src="/images/RogerScott.webp"> -->
                        <i class="fa fa-quote-left"></i>
                     </div>
                     <div class="name">
                        <h5>Aujtel Aujtel</h5>
                        <!-- <h6>Marketing Manager</h6> -->
                     </div>
                  </div>
                  <div class="text">
                     <p>Thank you A+ students for helping with your exceptional tutoring services. We were really struggling with our children’s after school activities, they never came home with homework or text books. I looked into kumon and Oxford but the prices were much higher than I could afford. I was really struggling to find the right tool to help my kids but then I stumbled upon A+ students. They sent a teacher to our house to assess my child and to show how the program worked, I was very impressed with the information and knowledge the teacher shared. Highly recommend for an affordable option for daily tutoring.</p>
                  </div>
               </div>
               <div class="box">
                  <div class="author">
                     <div class="pic">
                        <!-- <img src="/images/RogerScott2.webp"> -->
                        <i class="fa fa-quote-left"></i>
                     </div>
                     <div class="name">
                        <h5>Sunday Aondona</h5>
                        <!-- <h6>Marketing Manager</h6> -->
                     </div>
                  </div>
                  <div class="text">
                     <p>A+ is the most amazing educational resource we have used. It was very challenging to mark and correct the children’s work due to time constraints but since we started using A+, the issue has been addressed as A+ tests are automatically marked. The children can also see their scores and corrections instantly. With A+, we can monitor the progress of the children from our phones anywhere we are. We are happy that the children can even solve questions in grades higher than their grades in school. We sincerely appreciate the prompt responses and guidance received from the fantastic A+ team whenever we make enquiries. Thank you A+ for the great work you are doing.</p>
                  </div>
               </div>
               <div class="box">
                  <div class="author">
                     <div class="pic">
                       <!--  <img src="/images/RogerScott.webp"> -->
                        <i class="fa fa-quote-left"></i>
                     </div>
                     <div class="name">
                        <h5>Bizad A</h5>
                        <!-- <h6>Marketing Manager</h6> -->
                     </div>
                  </div>
                  <div class="text">
                     <p>My niece and nephew have been using this program and I am beyond impressed. They were going to Oxford and paying $350 for one child and they only got 2 hours of tutoring in a group setting with 4 other kids. Here, they have a class daily, lessons to do and their own teacher. My nephew has used 2 hours of one on one tutoring everyday for the last 2 months to prepare for his exams. The tutors are amazing and so helpful. My nephew has used more tutoring in 2 months here than he did in Oxford for the entire year. And we are paying a fraction do what we were paying in Oxford. I don’t know how they do it…This program is amazing and I am so happy that they are enrolled. I usually don’t write reviews but I will highly recommend A+ to any family looking for additional support for their kids.</p>
                  </div>
               </div>
               <div class="box">
                  <div class="author">
                     <div class="pic">
                        <!-- <img src="/images/RogerScott2.webp"> -->
                        <i class="fa fa-quote-left"></i>
                     </div>
                     <div class="name">
                        <h5>Daniel Ramos</h5>
                       <!--  <h6>Marketing Manager</h6> -->
                     </div>
                  </div>
                  <div class="text">
                     <p>A+ Students School has been a game-changer for my child's education. The personalized approach, highly qualified tutors, and flexible options have made a significant impact.

                     The tutors here are experts in their fields, and their engaging teaching methods make learning enjoyable. Personalized learning plans ensure targeted support, and regular progress updates keep us informed.

                     Beyond academics, they instill important life skills. Since enrolling, my child's grades have improved, and their confidence has soared. Exceptional Tutoring School offers all this at competitive prices, making it a top choice for parents seeking quality education support. Highly recommended!</p>
                  </div>
               </div>
               <div class="box">
                  <div class="author">
                     <div class="pic">
                        <!-- <img src="/images/RogerScott2.webp"> -->
                        <i class="fa fa-quote-left"></i>
                     </div>
                     <div class="name">
                        <h5>Ahmed Shah</h5>
                       <!--  <h6>Marketing Manager</h6> -->
                     </div>
                  </div>
                  <div class="text">
                     <p>I started at the beginning of high school,unmotivated, and never wanted to to try just because I did not 'like' Math. over the years of services provided and with special help from tutors such as Amna, I was able to truly learn with them. I was no top student to begin with but after years of hard work and help I was able to get into one of my top university choices at UofT. Every tutor takes their time making sure each step is understood, they are very diligent workers and such kind people.</p>
                  </div>
               </div>
               <div class="box">
                  <div class="author">
                     <div class="pic">
                        <!-- <img src="/images/RogerScott2.webp"> -->
                        <i class="fa fa-quote-left"></i>
                     </div>
                     <div class="name">
                        <h5>Adeola Ifanse</h5>
                       <!--  <h6>Marketing Manager</h6> -->
                     </div>
                  </div>
                  <div class="text">
                     <p>This course has been amazing for me. Ever since
                     have been doing this my maths skills has gone up
                     quite a lot and i was performed poorly in Maths.
                     Now i applied for college and was accepted by
                     centennial for aerospace my maths was 93 all tnx
                     to at plus. got help virtually and one on one
                     virtual meeting with the teachers who helped me
                     a lot</p>
                  </div>
               </div>
               <div class="box">
                  <div class="author">
                     <div class="pic">
                        <!-- <img src="/images/RogerScott2.webp"> -->
                        <i class="fa fa-quote-left"></i>
                     </div>
                     <div class="name">
                        <h5>Arjun</h5>
                       <!--  <h6>Marketing Manager</h6> -->
                     </div>
                  </div>
                  <div class="text">
                     <p>I'm currently a student there with Tutor #3 - Ms.
                     Sonia and | 100% recommend joining here. My
                     parents have put me with 5-6 different teachers
                     where l've always got average grades in
                     elementary and once I got to high-school it
                     dropped very low but ever since Mr. Frank
                     contacted me and gave me a time slot with Ms.
                     Sonia l've had the greatest math grades l've ever
                     earned. Ms.Sonia does a wonderful job at
                     teaching and has easily helped me get a high
                     grade. Really recommend joining they give you an
                     A plus and thats also the service score that they
                     deserve.</p>
                  </div>
               </div>
               <div class="box">
                  <div class="author">
                     <div class="pic">
                        <!-- <img src="/images/RogerScott2.webp"> -->
                        <i class="fa fa-quote-left"></i>
                     </div>
                     <div class="name">
                        <h5>Omolara Akigbogun</h5>
                       <!--  <h6>Marketing Manager</h6> -->
                     </div>
                  </div>
                  <div class="text">
                     <p>My children have been registered with At for
                     about 3 years. This has really helped them as
                     they are always ahead of their class and always
                     getting top grades. I would recommend At to
                     anyone looking to keep their kids at the top of
                     their class.</p>
                  </div>
               </div>
               <div class="box">
                  <div class="author">
                     <div class="pic">
                        <!-- <img src="/images/RogerScott2.webp"> -->
                        <i class="fa fa-quote-left"></i>
                     </div>
                     <div class="name">
                        <h5>Ade Makinde</h5>
                       <!--  <h6>Marketing Manager</h6> -->
                     </div>
                  </div>
                  <div class="text">
                     <p>This is an AWESOME program and all team
                     members are doing great jobs as this program
                     had and still greatly impacting my children
                     positively in their academics endeavors. They get
                     busy most times with it taking their minds off
                     mundane activities... they all doing excellently
                     well in school. I'm very happy for signing them up
                     for it as a parent. Kudos guys.</p>
                  </div>
               </div>
                <div class="box">
                  <div class="author">
                     <div class="pic">
                        <!-- <img src="/images/RogerScott2.webp"> -->
                        <i class="fa fa-quote-left"></i>
                     </div>
                     <div class="name">
                        <h5>Deejay</h5>
                       <!--  <h6>Marketing Manager</h6> -->
                     </div>
                  </div>
                  <div class="text">
                     <p>I have been on At students for 2 years now and I feel that the program has been helping me in class I'm ahead of my grades got At on my report card and got better in my studies and I hope it could change other peoples lives.</p>
                  </div>
               </div>

            </div>
         </div>
      </div>
   </div>
</div>
<!-- testimonials sec -->


<!-- trust section -->
<div class="trust_sec">
   <div class="container">
      <div class="row">
         <div class="col-6 col-sm-6 col-md-6 col-lg-3">
            <div class="box ">
               <div class="icon">
                  <img src="/images/scientist-white.png">
               </div>
               <div class="content">
                  <h2><span class="count">3000</span></h2>
                  <h4>Successful Families</h4>
               </div>
            </div>
         </div>
         <div class="col-6 col-sm-6 col-md-6 col-lg-3">
            <div class="box">
               <div class="icon">
                  <img src="/images/scientist-white.png">
               </div>
               <div class="content">
                  <h2><span class="count">4500</span></h2>
                  <h4>A Grades Earned</h4>
               </div>
            </div>
         </div>
         <div class="col-6 col-sm-6 col-md-6 col-lg-3">
            <div class="box">
               <div class="icon">
                  <img src="/images/scientist-white.png">
               </div>
               <div class="content">
                  <h2><span class="count">40000</span></h2>
                  <h4>Hours Tutored</h4>
               </div>
            </div>
         </div>
         <div class="col-6 col-sm-6 col-md-6 col-lg-3">
            <div class="box">
               <div class="icon">
                  <img src="/images/scientist-white.png">
               </div>
               <div class="content">
                  <h2><span class="count">118000</span></h2>
                  <h4>Questions Solved</h4>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
 


<!-- Blog Section -->
<div class="blog_sec">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <h4 class="subheading">Our Blog</h4>
            <h2 class="heading">Recent From Blog</h2>
            <div class="main">
               <?php if (!empty($articles)): ?>
                  <?php foreach ($articles as $article): ?>
                     <div class="box">
                        <a href="<?= \yii\helpers\Url::to(['article/view', 'slug' => $article->slug]) ?>" class="image">
                          
                           <img src="<?= $article->thumbnail_base_url ?>" alt="<?= $article->title ?>">
                        </a>
                        <div class="content">
                           <div class="tags">
                              <!-- <span><i class="fa fa-user"></i> <?php //echo $article->author->name ?? 'Admin' ?></span> -->
                              <span><i class="fa fa-calendar"></i> <?= Yii::$app->formatter->asDate($article->published_at, 'php:M. d, Y') ?></span>
                           </div>
                           <h3><a href="<?= \yii\helpers\Url::to(['article/view', 'slug' => $article->slug]) ?>"><?= $article->title ?></a></h3>
                           <p><?= \yii\helpers\StringHelper::truncateWords(strip_tags($article->body), 20) ?></p>
                        </div>
                     </div>
                  <?php endforeach; ?>
               <?php else: ?>
                  <p>No articles found.</p>
               <?php endif; ?>

               <a href="/article/index" class="theme_btn">View More</a>
            </div>
         </div>
      </div>
   </div>
</div>




<!-- <div class="pricing_sec">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <h4 class="subheading">Our Pricing</h4>
            <h2 class="heading">Pricing & Packages</h2>
            <div class="main">
               <div class="box">
                  <h5>Basic Plan</h5>
                  <h3><sup>$</sup> 49K</h3>
                  <p>Far far away, behind the word 
                     mountains, far from the countries 
                     Vokalia and Consonantia, there live 
                     the blind texts.
                  </p>
                  <a href="#" class="started_btn">Get Started</a>
               </div>
               <div class="box">
                  <h5>Beginner Plan</h5>
                  <h3><sup>$</sup> 79K</h3>
                  <p>Far far away, behind the word 
                     mountains, far from the countries 
                     Vokalia and Consonantia, there live 
                     the blind texts.
                  </p>
                  <a href="#" class="started_btn">Get Started</a>
               </div>
               <div class="box">
                  <h5>Premium Plan</h5>
                  <h3><sup>$</sup> 109K</h3>
                  <p>Far far away, behind the word 
                     mountains, far from the countries 
                     Vokalia and Consonantia, there live 
                     the blind texts.
                  </p>
                  <a href="#" class="started_btn">Get Started</a>
               </div>
               <div class="box">
                  <h5>Ultimate Plan</h5>
                  <h3><sup>$</sup> 149K</h3>
                  <p>Far far away, behind the word 
                     mountains, far from the countries 
                     Vokalia and Consonantia, there live 
                     the blind texts.
                  </p>
                  <a href="#" class="started_btn">Get Started</a>
               </div>
            </div>
         </div>
      </div>
   </div>
</div> -->



<?php 

$this->registerJs("
  $('.newsletter-form').on('submit', function(){
      var btn = $(this).find('button[type=submit]');
      btn.prop('disabled', true);
  });
");


?>