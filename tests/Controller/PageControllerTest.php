<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageControllerTest extends WebTestCase
{
    public function testAllRoutes() {
        $client = static::createClient();
        $urls = ['/', '/add', '/remove', 'chart'];
        foreach ($urls as $url) {
            $client->request('GET', $url);
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
        }
    }

    public function testIndexPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertSame(1, $crawler->filter('html:contains("Crypto Tracker")')->count());
    }

    public function testRemoveForm()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler->filter('#remove')->link();
        $crawler = $client->click($link);
        $form = $crawler->selectButton('form_submit')->form([
            'form[currency]' => '1',
            'form[quantity]' => '1'
        ]);
        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertSame(1, $crawler->filter(
            'html:contains("La quantité de 1 Bitcoin a bien été pris en compte.")')->count());
    }
}