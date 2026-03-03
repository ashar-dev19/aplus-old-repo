<?php
use yii\helpers\Html;
/**
 * @var $this \yii\web\View
 * @var $url \common\models\User
 * @var $model frontend\modules\studentms\models\ApplicationDetail
 */
?>

<p>Dear <?php echo $model->forename ?><br /> <br /> 
    <strong>You have successfully submitted your online application form. </strong>
    The Admin team at MIHE will process your application and contact you in due course.&nbsp;</p>

<p>This message has been generated automatically. Do not reply to this mail.&nbsp;<br />
<p>Please contact MIHE for any further information or clarification.<br />
<p>Kind regards<br /> <br /> 
    <strong>The Markfield Institute of Higher Education&nbsp;</strong>
    <br /> Telephone: +44 (0) 1530 244922<br /> Fax: +44 (0) 1530 243102<br />
    Email: <a href="mailto:admissions@mihe.ac.uk">admissions@mihe.ac.uk</a></p>
<?php echo Yii::t('frontend', 'You may check application status {applicationurl}.',
        ['applicationurl' => Html::a( "here", $url )])  ?>
