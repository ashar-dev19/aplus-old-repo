<?php
use yii\helpers\Html;
/**
 * @var $this \yii\web\View
 * @var $url \common\models\User
 * @var $model frontend\modules\studentms\models\ApplicationDetail
 */
?>

<p>Dear <?php echo $model->forename ?><br /> <br /> 
    <strong>Your application at MIHE is approved for further process. </strong>
    Next step is to please find and submit the MIHE Student agreement/consent below.&nbsp;</p>

    <p>
        <strong>Url: </strong> <?php echo $url; ?>
    </p>
    
<p>This message has been generated automatically. Do not reply to this mail.&nbsp;<br />
<p>Please contact MIHE for any further information or clarification.<br />
<p>Kind regards<br /> <br /> 
    <strong>The Markfield Institute of Higher Education&nbsp;</strong>
    <br /> Telephone: +44 (0) 1530 244922<br /> Fax: +44 (0) 1530 243102<br />
    Email: <a href="mailto:admissions@mihe.ac.uk">admissions@mihe.ac.uk</a></p>

