<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Client */
/* @var $form yii\widgets\ActiveForm */
/** @var $clientTypes array */
/** @var $redirectToClientPage boolean */
?>

<div class="client-form">
    <?= \yii\bootstrap\Alert::widget([
        'options' => [
            'class' => 'alert-info',
        ],
        'body'    => 'Все поля, помеченные знаком *, обязательны для заполнения.',
    ]) ?>

    <?php $form = ActiveForm::begin([
        'id' => 'create-client-form',
    ]); ?>

    <?= Html::hiddenInput('client-id', $model->id ?? 0); ?>
    <?= Html::hiddenInput('redirectToClientPage', $redirectToClientPage ? 1 : 0) ?>
    <?= Html::hiddenInput('is-modal', $redirectToClientPage ? 0 : 1) ?>
    <?= Html::hiddenInput('client-modal-mode', 'create') ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'patronymic')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList($clientTypes) ?>

    <?= $form->field($model, 'active')->dropDownList([1 => 'Да', 0 => 'Нет',]) ?>

    <?= $form->field($model, 'photo')->fileInput([]) ?>

    <?= \yii\bootstrap\Alert::widget([
        'options' => [
            'class' => 'alert-info',
        ],
        'body'    => 'Чтобы изменить уже загруженную фотографию, нажмите на кнопку "Выберите файл" и выберите новую фотографию.',
    ]) ?>

    <?= Html::img($model->photo, [
        'class' => 'client-image',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <div class="create-client-alert"></div>

    <?php ActiveForm::end(); ?>
</div>