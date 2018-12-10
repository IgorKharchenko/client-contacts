<?php

namespace tests\models;

use app\components\test\UnitTestHelper;
use app\models\Contact;
use app\tests\fixtures\ContactFixture;

class ContactTest extends \Codeception\Test\Unit
{
    use UnitTestHelper;

    public function fixtures ()
    {
        return [
            'contacts' => ContactFixture::class,
        ];
    }

    public function testCreateWithoutRequiredFields ()
    {
        $contact = new Contact([
            'type' => 'Skype',
        ]);

        $this->validateModel($contact, false);
    }

    public function testCreateSuccess ()
    {
        $contact = new Contact([
            'type'    => 'Skype',
            'content' => 'vasya_df3',
        ]);

        $this->validateModel($contact, true);
    }
}
