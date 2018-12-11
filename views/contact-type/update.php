<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ContactType */

$this->title = 'Переименовать тип контакта: ' . $model->type;
$this->params['breadcrumbs'][] = ['label' => 'Типы контактов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->type, 'url' => ['view', 'id' => $model->type]];
$this->params['breadcrumbs'][] = 'Переименовать тип контакта';
?>
<div class="contact-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
