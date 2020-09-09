<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MovieControllerTest extends WebTestCase
{
    /**
     * @dataProvider getRoutes
     */
    public function testAsAdmin($routeName)
    {
        $client = static::createClient([],[
            'PHP_AUTH_USER' => 'cmaeckeul@gmail.com',
            'PHP_AUTH_PW' => 'root'
        ]);
        $crawler = $client->request('GET', $routeName);

        $this->assertResponseIsSuccessful();
        // $this->assertSelectorTextContains('h1', 'Hello World');
    }

    public function testAsAnonymous($routeName)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $routeName);

        $this->assertResponseRedirects();
    }
    
    public function getRoutes() 
    {
        return [
            ['/movie/list']
        ];
    }
}
