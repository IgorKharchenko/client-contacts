<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Client */
/* @var $form yii\widgets\ActiveForm */
/** @var $clientTypes array */
/** @var $contactTypes array */
/** @var $redirectToClientPage boolean */
/** @var $contacts \app\models\ContactType[] */
?>

<div class="client-form">
    <?= \yii\bootstrap\Alert::widget([
        'options' => [
            'class' => 'alert-info',
        ],
        'body'    => 'Все поля, помеченные знаком *, обязательны для заполнения.<br><br> Для сохранения изменений (в т.ч. контактов) нажмите кнопку &laquo;Сохранить&raquo; внизу формы.',
    ]) ?>

    <?php $form = ActiveForm::begin([
        'id' => 'create-client-form',
    ]); ?>

    <?= Html::hiddenInput('client-id', $model->id ?? 0); ?>
    <?= Html::hiddenInput('redirectToClientPage', $redirectToClientPage ? 1 : 0) ?>
    <?= Html::hiddenInput('is-modal', $redirectToClientPage ? 0 : 1) ?>
    <?= Html::hiddenInput('client-modal-mode', 'create') ?>

    <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'patronymic')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList($clientTypes, [
        'prompt' => 'Выберите тип клиента',
    ]) ?>

    <?= $form->field($model, 'active')->dropDownList([1 => 'Да', 0 => 'Нет',], [
        'prompt' => 'Выберите, активен ли клиент',
    ]) ?>

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

    <div class="client-contacts-wrap">
        <label class="control-label">Контакты</label>

        <div class="client-contacts">
            <?php if ($redirectToClientPage): ?>
                <?= $this->render('//contact/_contacts', [
                    'contactTypes' => $contactTypes,
                    'contacts'     => $contacts ?? [],
                ]) ?>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <div class="create-client-alert"></div>
    </div>

    <?php ActiveForm::end(); ?>
</div>