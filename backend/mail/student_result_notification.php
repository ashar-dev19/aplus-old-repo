<?php
use yii\helpers\Html;
/**
 * @var $model backenend\modules\studentms\models\ModuleResult
 */
?>
Student Number: <?php echo $model->student->university_number; ?>
<p>&nbsp;</p>
<p>Dear <?php echo $model->student->first_name. " ".$model->student->surname; ?>,<br /> <br /> 
This is an automated response stating that your result for <?php echo $model->module->title;?> is,<?php echo $model->result==0? "Fail": "Pass";?>.
</p>
<p>
You may have failed one or more assessments and will need to refer to your module page on Moodle. Please check your marks and re-submission date under submissions header. 
</p>
<p>
This message is automated. Do not reply to this email,<br />
please contact MIHE administration for any further information. .&nbsp;<br />
<br /> Telephone: 01530 244 922<br />
Email: <a href="mailto:students@mihe.ac.uk">students@mihe.ac.uk</a></p>

