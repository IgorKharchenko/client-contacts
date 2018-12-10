<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Client */
/** @var $clientTypes array */

$this->title = 'Создать клиента';
$this->params['breadcrumbs'][] = ['label' => 'Клиенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'       => $model,
        'clientTypes' => $clientTypes,
    ]) ?>

</div>
