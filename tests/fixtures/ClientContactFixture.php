<?php

namespace app\tests\fixtures;

use yii\test\ActiveFixture;

class ClientContactFixture extends ActiveFixture
{
    public $modelClass = 'app\models\ClientContact';

    public $depends = [
        'app\tests\fixtures\ClientFixture',
        'app\tests\fixtures\ContactTypeFixture',
    ];
}
