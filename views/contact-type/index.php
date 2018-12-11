<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Типы контактов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-type-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= \yii\bootstrap\Alert::widget([
        'options' => [
            'class' => 'alert-warning',
        ],
        'body'   => '<b>Внимание!</b> <br />Тип контакта, который уже используется клиентами, не может быть удалён!',
    ]) ?>

    <p>
        <?= Html::a('Создать новый тип контакта', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'emptyText'    => 'Типов контактов не найдено.',
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],

            'type',

            [
                'class'    => \yii\grid\ActionColumn::class,
                'template' => '{update} {delete}',
                'buttons'  => [
                    'delete' => function($url, $model, $key) {
                        $options = [
                            'title'        => 'Удалить',
                            'aria-label'   => 'Удалить',
                            'data-pjax'    => 'contacts-pjax',
                            'data-confirm' => 'Вы уверены, что хотите удалить данный тип контакта?',
                            'data-method'  => 'post',
                            'class'        => '',
                        ];
                        return Html:: a("<span class='glyphicon glyphicon-trash' data-type='{$model->type}'></span>", [
                            'delete',
                            'type' => $model->type,
                        ], $options);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
