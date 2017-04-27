<?php

namespace Tests\AppBundle;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class CommonAppTest extends WebTestCase
{
    const EXAMPLE_LOGIN = 'expert';
    const EXAMPLE_PASSWORD = '123';
    const LOGIN_URL = '/login';

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @param Client $client
     * @return Client
     */
    static function loginUser(Client $client)
    {
        $crawler = $client->request('GET', self::LOGIN_URL);

        // Get the form.
        $form = $crawler->filter('form')->form();

        $values = array(
            '_username' => self::EXAMPLE_LOGIN,
            '_password' => self::EXAMPLE_PASSWORD,
            '_csrf_token' => $form->getValues()['_csrf_token']
        );

        // Submit the data.
        $client->request($form->getMethod(), $form->getUri(),
            $values);

        return $client;
    }

}
