<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testViewTask(): void
    {
        $client = static::createClient();
        $client->request('GET', '/task/view/1');

        $crawler = $client->followRedirect();
         
        $this->assertResponseIsSuccessful();
        $this->assertCount(1, $crawler->filter('.container'));
    }
}

