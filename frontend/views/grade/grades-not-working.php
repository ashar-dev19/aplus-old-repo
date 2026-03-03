<div class="course_page math">

   <div class="container">

      <div class="row align-items-center">

         <div class="col-md-12">

            <h2 class="heading">Select Grade</h2>
            <!-- boxes -->
            <div class="boxes">
         
            <?= \yii\widgets\ListView::widget([
                       'dataProvider' => $dataProvider,
                        'pager' => [
                            'hideOnSinglePage' => true,
                        ],
                        'itemView' => '_item',                   
                        'layout' => '{items}',
            ]) ?>





</div> <!-- boxes -->

         </div><!-- col-md-12 --> 

        </div><!-- row --> 

   </div>

</div>