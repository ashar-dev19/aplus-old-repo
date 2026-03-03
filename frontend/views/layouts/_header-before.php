<?php
/**
 * @var yii\web.View $this
 * @var string       $content
 */

use yii\helpers\Html;
use yii\helpers\Url;

\frontend\assets\FrontendAsset::register($this);

/* --- user / avatar --- */
$userName = Html::encode(
    Yii::$app->user->identity->full_name
    ?? Yii::$app->user->identity->username
    ?? 'Account'
);
$studentImage = '/uploads/male.png';
if ($cs = Yii::$app->session->get('current_student')) {
    if (!empty($cs['image'])) {
        $studentImage = '/' . ltrim($cs['image'], '/');
    } elseif (isset($cs['gender']) && (int)$cs['gender'] === 2) {
        $studentImage = '/uploads/female.png';
    }
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
  <meta charset="<?= Yii::$app->charset ?>"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= Html::encode($this->title) ?></title>
  <?php $this->head() ?>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <?= Html::tag('link', '', ['rel'=>'icon','type'=>'image/x-icon','href'=>Yii::getAlias('@web/uploads/favicon.ico')]) ?>
  <?= Html::csrfMetaTags() ?>

</head>

<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

 
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm inner_nav">
  <div class="container">
 
    <a class="navbar-brand" href="<?= Url::to(['/student/current']) ?>">
      <img src="/images/logo_transparen11.png" class="logo_main" alt="A+ Students">
    </a>
 
    <button class="navbar-toggler ms-auto"
            type="button"
            data-bs-toggle="collapse" data-bs-target="#headerCollapse"
            data-toggle="collapse" data-target="#headerCollapse"
            aria-controls="headerCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
 
    <div class="collapse navbar-collapse" id="headerCollapse">

       
      <ul class="navbar-nav"> 
        <!-- <li class="nav-item"><a class="nav-link" href="https://www.aplusclasses.ca" target="_blank">Classes</a></li> -->
         
      </ul>
 
      <ul class="navbar-nav ms-auto">
        <?php if (!Yii::$app->user->isGuest): ?>
          <li class="nav-item dropdown">
             
            <a class="nav-link dropdown-toggle" href="#" id="userMenu"
               data-toggle="dropdown" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fa fa-user-circle"></i>
              <?= Html::encode(Yii::$app->user->identity->full_name ?? Yii::$app->user->identity->username) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
              <li><?= Html::a('<i class="fa fa-cog"></i> Change Password', ['/site/change-password'], ['class'=>'dropdown-item']) ?></li>
              <li>
                <?= Html::beginForm(['/site/logout'], 'post', ['class'=>'dropdown-item p-0']) .
                     Html::submitButton('<i class="fa fa-sign-out"></i> Logout', ['class'=>'btn btn-link dropdown-item']) .
                     Html::endForm(); ?>
              </li>
            </ul>
          </li>
        <?php endif; ?>
      </ul>

    </div>
  </div>
</nav>
 

<?= $content ?>

<?php $this->endBody() ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>
</html>
<?php $this->endPage() ?>
