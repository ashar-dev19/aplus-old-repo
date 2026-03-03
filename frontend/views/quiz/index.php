<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $question frontend\models\LessonContent */
/* @var $totalQuestions int */
/* @var $currentIndex int */

$this->title = 'Quiz';

// Decode HTML entities
$questionTitle = html_entity_decode($question->title);
$questionContent = html_entity_decode($question->content);

// Decode JSON options
$options = json_decode(html_entity_decode($question->options), true);

// Handle cases where JSON decode fails
if (json_last_error() !== JSON_ERROR_NONE) {
    $options = [];
    $decodeError = "Error decoding options. Please check the options format in the database.";
}
?>

<div class="top_bar_tuts">
    <div class="inner">
        <div>
            <a href="javascript:void(0);" onclick="goBack()" class="close_btn"><i class="fas fa-times"></i> Close</a>
        </div>
        <div class="navigation-buttons">
            <form method="post">
                <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                <?php if ($currentIndex > 0): ?>
                    <button type="submit" name="previous" class="prev">Previous</button>
                <?php endif; ?>
                <?php if ($currentIndex < $totalQuestions - 1): ?>
                    <button type="submit" name="next" class="next">Next</button>
                <?php else: ?>
                    <button type="submit" name="finish" class="finish">Finish</button>
                <?php endif; ?>
            </form>
        </div>
        <div>
            <a href="#" class="answer_btn theme_btn">Answer</a>
        </div>
    </div>
</div>

<div class="topic_page tutorials_page">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="quiz_slide">
                    <h2 class="heading"><?= Html::encode($questionTitle) ?></h2>
                    <?= html_entity_decode($questionContent) ?> <!-- Directly output decoded content -->
                    <form method="post">
                        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                        <div class="radio_choices">
                            <?php if (!empty($options)): ?>
                                <?php foreach ($options as $option): ?>
                                    <label class="custom-radio">
                                        <input type="radio" name="option" value="<?= Html::encode($option) ?>">
                                        <span><?= Html::encode($option) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p><?= $decodeError ?></p>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="btns">
                    <a href="/category" class="theme_btn"><i class="fa fa-chevron-left"></i> My Study Room</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function goBack() {
        window.history.back();
    }
</script>
