<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Client;

/** @var $this yii\web\View */
/** @var $model app\models\Client */
/** @var $clientTypes array */
/** @var $contacts \app\models\ClientContact[] */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Клиенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="client-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], [
            'class'  => 'btn btn-primary',
            'target' => '_blank',
        ]) ?>

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
                    if (!$model->photo) {
                        return 'Фотография не загружена.';
                    }

                    return Html::a(
                        Html::img($model->photo, [
                            'class' => 'client-image',
                        ]),
                        $model->photo,
                        ['target' => '_blank']
                    );
                },
                'format' => 'raw',
            ],
            [
                'label' => 'Контакты',
                'value' => function (Client $model) use ($contacts) {
                    $out = '';
                    foreach ($contacts as $contact) {
                        $out .= "<p><b>{$contact->contact_type}</b>: {$contact->content}</p>";
                    }
                    return $out;
                },
                'format' => 'raw',
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
