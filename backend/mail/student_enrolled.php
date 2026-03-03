<?php
use yii\helpers\Html;
/**
 * @var $this \yii\web\View
 * @var $url \common\models\User
 * @var $model frontend\modules\studentms\models\ApplicationDetail
 */
?>

<p>Dear <?php echo $model->forename ?><br /> <br /> 
    <strong>Your have successfully enrolled as MIHE student. </strong>
    The Admin team at MIHE  processed your application.&nbsp;</p>
<p>
    Please use following Authentication details to check your information online.
    <br />
    <b>Url:</b> <?php echo Yii::getAlias('@frontendUrl') ?><br />
    <b>Username:</b> <?php echo $user->username ?><br />
    <b>Password:</b> <?php echo $user->password ?>
</p>

<p>This message has been generated automatically. Do not reply to this mail.&nbsp;<br />
<p>Please contact MIHE for any further information or clarification.<br />
<p>Kind regards<br /> <br /> 
    <strong>The Markfield Institute of Higher Education&nbsp;</strong>
    <br /> Telephone: +44 (0) 1530 244922<br /> Fax: +44 (0) 1530 243102<br />
    Email: <a href="mailto:admissions@mihe.ac.uk">admissions@mihe.ac.uk</a></p>

