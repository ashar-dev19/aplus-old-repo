<?php
/**
 * @var yii\web\View $this
 * @var string $content
 */

use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;

\frontend\assets\FrontendAsset::register($this);


$currentStudent = Yii::$app->session->get('current_student');
$studentId      = $currentStudent['id'] ?? null;

if ($studentId) { 
    $row = \frontend\models\Student::find()
        ->select(['id','parent_id','status'])
        ->where(['id' => (int)$studentId])
        ->asArray()
        ->one();

    $owned = $row && ((int)$row['parent_id'] === (int)Yii::$app->user->id) && ((int)($row['status'] ?? 1) === 1);
    if (!$owned) { 
        Yii::$app->session->remove('current_student');
        $currentStudent = null;
        $studentId = null;
 
        $ctrl = Yii::$app->controller->id ?? '';
        $act  = Yii::$app->controller->action->id ?? '';
        $onAllowed = ($ctrl === 'student' && in_array($act, ['current','index','create']));
        if (!$onAllowed) {
            Yii::$app->response->redirect(['/student/current'])->send();
            Yii::$app->end();
        }
    }
}


$fallbackHomeUrl = Url::to(['/student/current']);


$homeUrl = $studentId ? Url::to(['/student/view', 'id' => $studentId]) : $fallbackHomeUrl;
$mathUrl = $studentId ? Url::to(['/grade/grades', 'id' => $studentId, 'subjectid' => 4]) : $fallbackHomeUrl;
$englishUrl = $studentId ? Url::to(['/grade/grades', 'id' => $studentId, 'subjectid' => 5]) : $fallbackHomeUrl;


$currentController = Yii::$app->controller->id ?? '';
$currentAction     = Yii::$app->controller->action->id ?? '';
$isStudentCurrent  = ($currentController === 'student' && $currentAction === 'current');
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

    <a class="navbar-brand" href="<?= Html::encode($homeUrl) ?>">
      <img src="/images/logo_transparen11.png" class="logo_main" alt="A+ Students">
    </a>


    <button class="navbar-toggler ms-auto"
            type="button"
            data-toggle="collapse" data-target="#innerNavCollapse"
            data-bs-toggle="collapse" data-bs-target="#innerNavCollapse"
            aria-controls="innerNavCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="innerNavCollapse">


      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if (!$isStudentCurrent): ?>
          <li class="nav-item"><a class="nav-link" href="<?= $homeUrl ?>">Home</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="/reports/progress-report">Reports</a></li>
        <li class="nav-item"><a class="nav-link" href="https://www.aplusclasses.ca" target="_blank">Classes</a></li>

        <?php if (!$isStudentCurrent): ?>
          <li class="nav-item"><a class="nav-link" href="<?= $mathUrl ?>">Math</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= $englishUrl ?>">English</a></li>
        <?php endif; ?>
      </ul>

      <ul class="avatar_box navbar-nav ms-auto mb-2 mb-lg-0">
  <?php if (!$isStudentCurrent && $currentStudent): ?>
    <?php 

      $studentName = $currentStudent['name'] ?? '';
      $gender      = isset($currentStudent['gender']) ? (int)$currentStudent['gender'] : 1;
 
      $studentImage = ($gender === 2) ? '/uploads/female.png' : '/uploads/male.png';
 
      $img = isset($currentStudent['image']) && is_string($currentStudent['image'])
           ? trim($currentStudent['image'])
           : '';

      if ($img !== '') {
          
          $img = '/'.ltrim($img, '/');

          
          if (!preg_match('~/(user\.png|default\.png|avatar\.png)$~i', $img)) {
              if (strpos($img, '/uploads/') !== 0) {
                  $img = '/uploads/'.ltrim($img, '/');
              }
              $studentImage = $img; // use custom image
          }
      }
  
    ?>
    <!-- Avatar dropdown -->
    <li class="nav-item dropdown me-lg-3">
      <a class="nav-link dropdown-toggle d-flex align-items-center"
         href="#"
         id="studentMenu"
         data-bs-toggle="dropdown"
         aria-expanded="false">
        <img src="<?= Html::encode($studentImage) ?>" class="avatar-mini me-2" alt="">
        <span class="d-lg-inline" style="text-transform:capitalize">
          <?= Html::encode($studentName) ?>
        </span>
      </a>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="studentMenu">
        <li>
          <h6 class="dropdown-header">Signed in as <?= Html::encode($studentName) ?></h6>
        </li>
        <li>
          <a class="dropdown-item" href="<?= Url::to(['/student/current']) ?>">
            <i class="fa fa-exchange"></i> Change profile
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="<?= Url::to(['/student/view', 'id' => $studentId]) ?>">
            <i class="fa fa-user"></i> Student home
          </a>
        </li>
      </ul>
    </li>
  <?php endif; ?>

  <?php if (!Yii::$app->user->isGuest): ?>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="userMenu"
         data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-user-circle"></i>
        <?= Html::encode(Yii::$app->user->identity->full_name ?? Yii::$app->user->identity->username) ?>
      </a>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
        <li><?= Html::a('<i class="fa fa-cog"></i> Change Password', ['/site/change-password'], ['class'=>'dropdown-item']) ?></li>
        <li>
          <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'dropdown-item p-0']) .
               Html::submitButton('<i class="fa fa-sign-out"></i> Logout', ['class' => 'btn btn-link dropdown-item']) .
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
