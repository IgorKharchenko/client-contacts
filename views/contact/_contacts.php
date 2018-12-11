<?php

use yii\helpers\Html;
use app\models\ClientContact;

/** @var $this \yii\web\View */
/** @var $contacts \app\models\ClientContact[] */
/** @var $contactTypes array */

/**
 * вот это нужно для того, чтобы идентифицировать форму при её удалении
 * по нажатию на кнопку "X"
 * inb4: название от бога
 */
$key = 1;
?>

<?= Html::button('Добавить новый контакт', [
    'class' => 'btn btn-primary add-client-contact',
]); ?>

<?= Html::hiddenInput('removedContactsIds', '') ?>

<?php if (empty($contacts)): ?>

    <?= $this->render('_form', [
        'contact'      => new ClientContact(),
        'contactTypes' => $contactTypes,
    ]) ?>

<?php else: ?>

    <?php foreach ($contacts as $contact): ?>
        <?= $this->render('_form', [
            'contact'        => $contact,
            'contactTypes'   => $contactTypes,
            'key'            => $key++,
        ]) ?>
    <?php endforeach; ?>

<?php endif; ?>

