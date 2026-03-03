<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $totalPoints int */
/* @var $totalAttempts int */

?>

<style>
    body {
        font-family: 'Comic Sans MS', cursive, sans-serif;
        background-color: #f0f8ff;
    }
    .quiz_result {
        text-align: center;
        padding: 50px;
    }
    .quiz_result h2 {
        font-size: 36px;
        color: #333;
    }
    .quiz_result p {
        font-size: 24px;
        color: #666;
    }
    .quiz_result .theme_btn {
        background-color: #ffcccb;
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        margin: 20px 0;
    }
    .quiz_result .theme_btn:hover {
        background-color: #ff6347;
    }
</style>

<div class="quiz_result">
    <h2>Quiz Completed!</h2>
    <p>Total Points: <?= Html::encode($totalPoints) ?></p>
    <p>Total Attempts: <?= Html::encode($totalAttempts) ?></p>
    <a href="/quiz" class="theme_btn">Take the Quiz Again</a>
</div>
