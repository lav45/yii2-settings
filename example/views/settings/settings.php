<?php
/**
 * @var $this yii\web\View
 * @var $model \example\models\SettingsForm
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Main settings';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="settings-form">

    <h2><?= Html::encode($this->title) ?></h2>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'meta_keywords')->textarea() ?>

    <?= $form->field($model, 'meta_description')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
