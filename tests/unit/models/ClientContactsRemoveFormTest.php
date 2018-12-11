<?php

namespace tests\models;

use app\components\test\UnitTestHelper;
use app\models\ClientContactsRemoveForm;
use app\tests\fixtures\ClientFixture;
use app\tests\fixtures\ContactTypeFixture;

class ClientContactsRemoveFormTest extends \Codeception\Test\Unit
{
    use UnitTestHelper;

    /**
     * @var \UnitTester
     */
    protected $tester;

    public function fixtures ()
    {
        return [
            'clients'       => ClientFixture::class,
            'contact-types' => ContactTypeFixture::class,
        ];
    }
}