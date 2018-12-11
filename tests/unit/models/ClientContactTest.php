<?php

namespace app\tests\unit\models;

use app\components\test\UnitTestHelper;
use app\controllers\ContactTypeController;
use app\models\ClientContact;
use app\models\ContactType;
use app\tests\fixtures\ClientContactFixture;
use app\tests\fixtures\ClientFixture;
use app\tests\fixtures\ContactTypeFixture;

class ClientContactTest extends \Codeception\Test\Unit
{
    use UnitTestHelper;

    public function _fixtures ()
    {
        return [
            'clients' => ClientFixture::class,
            //'client-contacts' => ClientContactFixture::class,
            // 'contact-types'   => ContactTypeFixture::class,
        ];
    }

    /**
     * >_<
     */
    public function _before ()
    {
        ClientContact::deleteAll('1 = 1');
        ContactType::deleteAll('1 = 1');
    }

    public function testCreateWithoutRequiredFields ()
    {
        $client = new ClientContact();

        $this->validateModel($client, false);
        $this->assertFalse($client->save());
    }

    public function testCreateWithWrongType ()
    {
        $client = new ClientContact([
            'client_id'    => 1,
            'contact_type' => 'бла-бла-бла',
            'content'      => 'бла-бла-бла',
        ]);

        $this->validateModel($client, false, ['contact_type']);
        $this->assertFalse($client->save());
    }

    public function testCreateSuccess ()
    {
        $client = $this->createValidClientContact();

        $this->validateModel($client, true);
        $this->assertTrue($client->save());
    }

    public function testFindByWrongId ()
    {
        $this->assertNull(ClientContact::findById(100500));
    }

    public function testFindById ()
    {
        $contact = $this->createValidClientContact();

        $this->assertNotNull(ClientContact::findById($contact->id));
    }

    private function createValidClientContact() : ClientContact
    {
        (new ContactType(['type' => 'E-mail']))->save();

        $contact = new ClientContact([
            'client_id'    => 1,
            'contact_type' => 'E-mail',
            'content'      => 'hatand@bk.ru',
        ]);
        $contact->save();

        return $contact;
    }
}
