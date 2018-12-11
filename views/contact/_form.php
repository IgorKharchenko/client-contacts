<?php

use yii\helpers\Html;

/** @var $this \yii\web\View */
/** @var $contact \app\models\ClientContact */
/** @var $contactTypes array */
/** @var $key integer */

/**
 * изначально сделал с помощью ActiveForm,
 * но штатная js-валидация $.yiiActiveForm дала сбой,
 * и поскольку времени уже совсем нет, сделал обычными формами
 * и ничего, знаете ли, всё работает
 */
?>

<div class="client-contact" data-key="<?= $key ?>">
    <form class="add-contact-form">
        <?= Html::hiddenInput('ClientContact[id]', $contact->id) ?>
        <?= Html::hiddenInput('ClientContact[client_id]', $contact->client_id) ?>

        <div class="row">
            <div class="col-12 col-md-5">
                <div class="form-group">
                    <label class="control-label">Тип</label>

                    <?= Html::dropDownList(
                        'ClientContact[contact_type]',
                        $contact->contact_type,
                        $contactTypes,
                        [
                            'prompt'   => 'Выберите тип контакта',
                            'class'    => 'form-control',
                            'required' => true,
                        ]
                    ) ?>

                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group field-clientcontact-content required">
                    <label class="control-label" for="clientcontact-content">Контакт</label>

                    <?= Html::textInput('ClientContact[content]', $contact->content, [
                        'class'     => 'form-control',
                        'maxlength' => 30,
                        'minlength' => 1,
                        'required'  => true,
                    ]) ?>

                    <div class="help-block"></div>
                </div>
            </div>

            <div class="col-12 col-md-1">
                <button class="btn btn-danger delete-client-contact" data-key="<?= $key ?>">X</button>
            </div>
        </div>

    </form>
</div>