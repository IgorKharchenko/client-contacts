<?php

use yii\helpers\Html;
use \app\models\ClientContact;

/** @var $this \yii\web\View */
/** @var $clientContacts ClientContact[] */
?>

<b>Данный тип контакта не может быть удалён, поскольку используется у следующих клиентов.</b>

<ul class="clients-using-contact-type">
    <?php foreach ($clientContacts as $clientContact): ?>
        <li>
            <?= Html::a($clientContact->client->getFullName(), [
                '/client/view',
                'id' => $clientContact->client->id,
            ]) ?>
        </li>
    <?php endforeach; ?>
</ul>