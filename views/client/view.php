<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Client;

/* @var $this yii\web\View */
/* @var $model app\models\Client */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Клиенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="client-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', [
            'update',
            'id' => $model->id,
        ], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data'  => [
                'confirm' => 'Вы уверены, что хотите удалить данного клиента?',
                'method'  => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model'      => $model,
        'attributes' => [
            'surname',
            'name',
            'patronymic',
            'type',
            [
                'label' => (new Client())->getAttributeLabel('active'),
                'value' => function(Client $model) {
                    return $model->active ? 'Да' : 'Нет';
                },
            ],
            [
                'label'  => (new Client())->getAttributeLabel('photo'),
                'value'  => function(Client $model) {
                    return Html::img($model->photo, [
                        'class' => 'client-image client-image-full',
                    ]);
                },
                'format' => 'raw',
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
