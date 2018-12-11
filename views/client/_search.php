<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ClientSearch */
/* @var $form yii\widgets\ActiveForm */
/** @var $clientTypes array */
/** @var $contactTypes array */
?>

<div class="client-search">

    <?php $form = ActiveForm::begin([
        'id' => 'search-client-form',
    ]); ?>

    <?= Html::a('Сбросить фильтр', ['index'], [
        'class' => 'btn btn-success reset-search-filter',
    ]) ?>

    <h2>Поиск клиента</h2>

    <?= $form->field($model, 'fullName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact')->textInput() ?>

    <?= $form->field($model, 'typesOfContact')->checkboxList($contactTypes); ?>

    <?= $form->field($model, 'typeOfActiveness')->dropDownList([
        'all'      => 'Все',
        'active'   => 'Активные',
        'inactive' => 'Неактивные',
    ], [
        'prompt' => 'Выберите, должен ли клиент быть активен',
    ]) ?>

    <?= $form->field($model, 'typeOfClient')->dropDownList($clientTypes, [
        'prompt' => 'Выберите тип клиента',
    ]); ?>

    <?= Html::submitButton('Найти', [
        'class' => 'btn btn-success',
    ]) ?>

    <?php ActiveForm::end(); ?>
</div>