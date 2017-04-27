<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Tests\AppBundle\CommonAppTest;
use Symfony\Bundle\FrameworkBundle\Client;

class TaskControllerTest extends WebTestCase
{
    const EXAMPLE_TASK_NAME = 'test_example_task';
    const CREATE_URL = '/task/new/';
    const LIST_URL = '/task/list/';


    public function testCreateTask()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadUserData',
        ));

        $client = static::createClient();

        $client = CommonAppTest::loginUser($client);

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
        $li = $crawler->filter('ul[class="tasks_list"] > li')->first()->text();

        $this->assertTrue(
            $li == "example_task"
        );

    }


    private function countTask(){
        $tasks = $this->getContainer()->get('doctrine')->getRepository(Task::class)->findAll();
        return $this->count($tasks);
    }
}
