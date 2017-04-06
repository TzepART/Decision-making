<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class TaskControllerTest extends WebTestCase
{
    const EXAMPLE_TASK_NAME = 'test_example_task';
    const CREATE_URL = '/task/new/';
    const LIST_URL = '/task/list/';

    const EXAMPLE_LOGIN = 'expert';
    const EXAMPLE_PASSWORD = '123';
    const LOGIN_URL = '/login';


    public function testCreateTask()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadUserData',
        ));

        $client = static::createClient();

        $client = $this->login($client);

        $crawler = $client->request('GET', self::CREATE_URL);

        // Get the form.
        $form = $crawler->filter('form')->form();


        $values = array(
            'app_bundle_task_form_type[name]'  => self::EXAMPLE_TASK_NAME,
            'app_bundle_task_form_type[_token]' => $form->getValues()["app_bundle_task_form_type[_token]"]
        );

        // Submit the data.
        $client->request($form->getMethod(), $form->getUri(),
            $values);

        $this->assertTrue(
            $this->countTask() == 1
        );

    }

    public function testTaskList()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadUserData',
            'AppBundle\DataFixtures\ORM\LoadTaskData',
        ));

        $client = static::createClient();

        $crawler = $client->request('GET', self::LIST_URL);

        // Get the form.
        $li = $crawler->filter('li')->text();

        $this->assertTrue(
            $li == "example_task"
        );

    }


    /**
     * @param Client $client
     * @return Client
     */
    private function login(Client $client)
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadUserData',
        ));

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

    private function countTask(){
        $tasks = $this->getContainer()->get('doctrine')->getRepository(Task::class)->findAll();
        return $this->count($tasks);
    }
}
