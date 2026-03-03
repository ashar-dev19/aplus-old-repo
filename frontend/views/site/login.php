<?php
   /**
    * @var yii\web\View $this
    */
   
   
   $this->title = Yii::$app->name;
    // use app\assets\FrontendAsset;
   
   
   
    // FrontendAsset::register($this);
   ?>

<!-- Login Page -->
   <div class="login_sec">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8 form_col">
                            <h1 class="heading">Login to Your Account</h1>
                            
                            <div class="form_wrapper">
                                <form>
                                     <div class="field">
                                            <input type="email" placeholder="Email">
                                        </div>
                                        <div class="field pass">
                                             <input type="password" placeholder="Password" id="password">
                                              <i class="fa fa-eye" id="eyeicon"></i>
                                        </div>
                                     <input type="submit" name="Sign in">
                                </form>
                            </div>        
                         
                    </div>
                    <div class="col-md-4">
                        <div class="new-box">
                            <div class="n-box">
                                <h2 class="heading">New Here?</h2>
                                 <p>Sign up and Discover a great amount<br> of new opportunities!</p>
                                 <a href="#" class="signup_btn">Sign Up</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>





