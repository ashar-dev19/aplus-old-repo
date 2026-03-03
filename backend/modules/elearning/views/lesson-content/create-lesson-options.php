<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// Set the initial option count to 4 if not explicitly passed
$optionCount = isset($optionCount) ? $optionCount : 4;
?>

<h1>Add Test Options for: <?= $lessonContent->title ?></h1>

<?php $form = ActiveForm::begin(); ?>

<div id="options-container">
    <?php for ($i = 0; $i < $optionCount; $i++): ?>
        <div class="option-item">
            <label>Option <?= $i + 1 ?></label>
            <input type="text" name="LessonTestOption[<?= $i ?>][option_value]" class="form-control" />
            <label>
                <input type="checkbox" name="LessonTestOption[<?= $i ?>][is_correct]" />
                Correct Answer
            </label>
        </div>
    <?php endfor; ?>
</div>

<button type="button" id="add-more-options" class="btn btn-secondary">Add More Options</button>

<div class="form-group mt-3">
    <?= Html::submitButton('Save Options', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>

<script>
    let optionIndex = <?= $optionCount ?>; // Start with the current option count
    document.getElementById('add-more-options').addEventListener('click', function () {
        const container = document.getElementById('options-container');
        const optionHtml = `
            <div class="option-item">
                <label>Option ${optionIndex + 1}</label>
                <input type="text" name="LessonTestOption[${optionIndex}][option_value]" class="form-control" />
                <label>
                    <input type="checkbox" name="LessonTestOption[${optionIndex}][is_correct]" />
                    Correct Answer
                </label>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', optionHtml);
        optionIndex++;
    });
</script>
