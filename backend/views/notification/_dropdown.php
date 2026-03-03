<?php
use yii\helpers\Html;

/** @var int $count */
/** @var common\models\TimelineEvent[] $events */
?>
<span class="dropdown-item dropdown-header"><?= (int)$count ?> new</span>
<div class="dropdown-divider"></div>

<?php foreach ($events as $e): ?>
    <?php
    $d = is_array($e->data) ? $e->data : (json_decode($e->data, true) ?: []);

    $title = '';
    $url   = '#';

    switch ($e->category) {
        case 'assessment':
            $title = 'New assessment' . (!empty($d['name']) ? ': ' . $d['name'] : '');
            $url   = ['/assessment/view', 'id' => $d['assessment_id'] ?? 0];
            break;

        case 'newsletter':
            $title = 'New newsletter signup' . (!empty($d['email']) ? ': ' . $d['email'] : '');
            $url   = ['/newsletter/index']; // list pe le jao, ya koi view ho to use karo
            break;

        default:
            $title = ucfirst($e->category) . ' – ' . ($e->event ?: 'event');
            $url   = ['/timeline-event/index'];
    }
    ?>
    <?= Html::a(Html::encode($title), $url, ['class' => 'dropdown-item']) ?>
    <div class="dropdown-divider"></div>
<?php endforeach; ?>

<?= Html::a('See all timeline', ['/timeline-event/index'], ['class' => 'dropdown-item dropdown-footer']) ?>




<style>
    /* Bell dropdown: unread highlight */
.dropdown-menu .dropdown-item.unread {
  background: #fff7e0;      /* subtle highlight */
  font-weight: 600;
}
.dropdown-menu .dropdown-item.unread:hover {
  background: #ffe9b8;
}

/* unread highlight in notif dropdown */
.dropdown-menu .notif-unread { background: #fff9db; }  /* subtle yellow */


</style>


<script>
document.addEventListener('click', function (e) {
  const a = e.target.closest('.dropdown-item[data-tlid]');
  if (!a) return;
  a.classList.remove('unread'); // instant visual feedback
});
</script>
