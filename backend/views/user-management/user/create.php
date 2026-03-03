    <?php
    use webvimark\modules\UserManagement\UserManagementModule;
    use yii\helpers\Html;

    /**
     * @var yii\web\View                                $this
     * @var webvimark\modules\UserManagement\models\User $model
     * @var backend\modules\UserManagement\models\UserProfile $profile
     */

    $this->title = UserManagementModule::t('back', 'User creation');
    $this->params['breadcrumbs'][] = ['label' => UserManagementModule::t('back', 'Users'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
    ?>
    

    <div class="user-create">
        <h2 class="lte-hide-title"><?= $this->title ?></h2>
        <div class="panel panel-default">
            <div class="panel-body">
                <!-- Pass BOTH model & profile into the form partial -->
                <?php //echo $this->render('_form', compact('model','profile')) ?>
                <?= $this->render('_form', compact('model','profile','students')) ?>

            </div>
        </div>
    </div>
