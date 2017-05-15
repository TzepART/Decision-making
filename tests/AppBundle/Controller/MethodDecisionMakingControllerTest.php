<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;



class MethodDecisionMakingControllerTest extends WebTestCase
{
    /**
     * @dataProvider getUrlsProvider
     */
    public function testPagesResponseStatus($url)
    {

        $client = static::createClient();

        $client->request('GET',  $url);
        $this->assertTrue(
            $client->getResponse()->isSuccessful()
        );
    }

    public function getUrlsProvider()
    {
        return [
            ['/method/main-criteria/'],
            ['/method/pareto/'],
            ['/method/common-criteria/'],
            ['/method/biased-ideal/'],
        ];
    }
}
