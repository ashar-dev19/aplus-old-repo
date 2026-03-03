<?php
use yii\helpers\Html;

?>

<h1>Salesperson Details</h1>

<div style="margin-bottom: 20px;">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= Html::encode($salesPerson->username) ?></td>
                <td><?= Html::encode($salesPerson->email) ?></td>
                <td><?= Html::encode($salesPerson->phone) ?></td>
            </tr>
        </tbody>
    </table>
</div>

<h2>Assigned Users</h2>

<?php if (!empty($assignedUsers)): ?>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                
            </tr>
        </thead>
        <tbody>
            <?php foreach ($assignedUsers as $index => $user): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= Html::encode($user->username) ?></td>
                    <td><?= Html::encode($user->email) ?></td>
                   
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No users are assigned to this salesperson.</p>
<?php endif; ?>
