<?php

use \app\models\Client;

return [
    'user1' => [
        'id'         => 1,
        'surname'    => 'Косяков',
        'name'       => 'Дмитрий',
        'patronymic' => 'Анатольевич',
        'type'       => Client::TYPE_CUSTOMER,
        'active'     => 1,
        'created_at' => '2018-12-10 11:15:27',
        'updated_at' => '2018-12-10 11:15:27',
        'photo'      => '',
    ],
    'user2' => [
        'id'         => 2,
        'patronymic' => 'Васильевна',
        'name'       => 'Анастасия',
        'surname'    => 'Новикова',
        'type'       => Client::TYPE_PARTNER,
        'active'     => 1,
        'created_at' => '2018-12-10 11:15:27',
        'updated_at' => '2018-12-10 11:15:27',
        'photo'      => '',
    ],
    'user3' => [
        'id'         => 3,
        'surname'    => 'Смирнов',
        'name'       => 'Николай',
        'patronymic' => 'Николаевич',
        'type'       => Client::TYPE_PROVIDER,
        'active'     => 0,
        'created_at' => '2018-12-10 11:15:27',
        'updated_at' => '2018-12-10 11:15:27',
        'photo'      => '',
    ],
];