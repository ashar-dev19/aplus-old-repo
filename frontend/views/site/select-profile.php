<?php
   /**
    * @var yii\web\View $this
    */

   use backend\modules\student_management\models\Student;
   use yii\helpers\Html;
   $this->title = Yii::$app->name;
     
   ?>

    <?php
     

    $stdData =Student::find()->where(['status'=> 1])->limit(1)->orderBy(['id'=>SORT_DESC])->all();
    ?>


    <?php foreach ($stdData as $student): ?>
            <h4><?= $student->full_name?></h4>
            <?= Html::a('Manage', ['update','id'=>$student->id], ['class'=>'view_btn']) ?>

            <?php echo Html::a('Manage Profile', ['/student/update', 'id' => $student->id], ['class' => 'btn btn-primary']) ?>

       <?php  endforeach; ?>
    


              <div class="select_profile">
                    <div class="boxes">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                   
                                    <h1 class="heading"> Who's studying?</h1>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-6 col-6 col-md-2 col-lg-2">
                                    <div class="box">
                                      <a href="#">
                                          <img src="/images/profile1.jpg">
                                         <h4>William</h4>
                                      </a>   
                                        
                                        <a href="#" class="view_btn"><i class="fa fa-gear"></i>Manage Profile</a>
                                        <a href="#" class="view_btn">Reports</a>
                                    </div>
                                </div>
                                 <div class="col-6 col-6 col-md-2 col-lg-2">
                                    <div class="box">
                                        <a href="#">
                                            <img src="/images/profile2.jpg">
                                            <h4>John Doe</h4>
                                        </a>
                                        
                                        <a href="#" class="view_btn"><i class="fa fa-gear"></i>Manage Profile</a>
                                        <a href="#" class="view_btn">Reports</a>
                                    </div>
                                </div>
                                 <div class="col-6 col-6 col-md-2 col-lg-2">
                                    <div class="box">
                                        <a href="#">
                                             <img src="/images/profile3.jpg">
                                             <h4>Rober Rick</h4>
                                        </a>
                                       
                                         <a href="#" class="view_btn"><i class="fa fa-gear"></i>Manage Profile</a>
                                        <a href="#" class="view_btn">Reports</a>
                                    </div>
                                </div>
                                <div class="col-6 col-6 col-md-2 col-lg-2">
                                    <div class="box">
                                        <a href="#">
                                            <img src="/images/profile4.jpg">
                                            <h4>Shamous </h4>
                                        </a>
                                        <a href="#" class="view_btn"><i class="fa fa-gear"></i>Manage Profile</a>
                                        <a href="#" class="view_btn">Reports</a>
                                    </div>
                                </div>
                                 
                                 <div class="col-6 col-6 col-md-2 col-lg-2 last_col">
                                    <div class="box">
                                        <div class="plus">
                                             <a href="#" class="icon"><i class="fa fa-plus"></i></a>
                                         </div>
                                          <a href="#" class="add">Add Profile</a>
                                          
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                            <div class="row">
                                <div class="manage_btn">
                                <a href="#">MANAGE PROFILES</a>
                                </div>
                            </div>
                       </div>
                </div>
        </div>
    



    <style>
    	
    .select_profile {
   /* height: 100vh;*/
   padding: 150px 0px;
    display: flex;
    justify-content: center;
    align-items: center;
}
.select_profile h1{
    text-align: center;
    margin-bottom: 30px;
     
}
.select_profile .last_col{
    display: flex;
    align-items: center;
    justify-content: center;
}

.select_profile .boxes{
    width: 100%;
}
.select_profile .box{
    transition: 0.5s;
}
.select_profile .box:hover{
        transform: scale(1.1);
}
.select_profile .last_col .box:hover{
     transform: scale(1);
}

.select_profile .box h4{
        padding-top: 12px;
    color: #000;
    font-size: 24px;
    text-transform: capitalize;
    margin-bottom: 10px;
}
.select_profile .box a.view_btn{
       color: #000;
    transition: 0.5s;
    display: block;
     font-size: 14px;
     margin-bottom: 5px;
}
.select_profile .box a.view_btn:hover{
    color: #1eb2a6;
}
.select_profile .box a.view_btn i{
    margin-right: 5px;
        color: #1eb2a6;
}
.select_profile .box img{
    width: 100%;
    height: 170px;
    object-fit: cover;
}
 
.select_profile .box .plus a.icon{
    width: 80px;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 80px;
    background-color: #bfbcbc;
    border-radius: 50%;
    transition: 0.5s;
}
.select_profile .box .plus a.icon:hover{
    background-color: #1eb2a6;
}
.select_profile .box .plus i{
    font-size: 47px;
    color: #ffffff;
        margin-top: 8px;
}

.select_profile .box h6{}
.select_profile a.add{
        margin-top: 22px;
    display: block;
        color: #000;
        transition: 0.5s;
}
.select_profile a.add:hover{
    color: #1eb2a6;
}

.select_profile .manage_btn{
    text-align: center;
    padding-top: 60px;
}
.select_profile .manage_btn a{
    border: 2px solid black;
    padding: 10px 40px;
    text-decoration: none;
    font-size: 18px;
    color: #000;
      transition: 0.5s;
}
.select_profile .manage_btn a:hover{
    background-color: #1eb2a6;
    color:#fff;
    border-color: #1eb2a6;
}

@media (max-width: 767px){
    .select_profile{
            padding: 50px 0px;
    }
    .select_profile .box{
            margin-bottom: 25px;
    }
    .select_profile .main{
        height: unset;

    }
    .select_profile .main h1 {
     
    font-size: 30px;
    }



}


    </style>