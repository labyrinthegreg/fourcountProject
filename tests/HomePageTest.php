<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    public function testHomePage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        //Test texts
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Bienvenue sur Fourcount');
        $this->assertSelectorTextContains('#createFourcount', 'Créer un fourcount');

        //Test if link is correct
        $this->assertEquals($crawler->selectLink('Créer un fourcount')->link()->getUri(), 'http://localhost/fourcount/new');
        
        //Test links
        $client->clickLink('Créer un fourcount');
        $this->assertResponseIsSuccessful();
    }

    public function testCreateFourcount(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/fourcount/new');

        //Test texts
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Create new Fourcount');
        $buttonCrawlerNode = $crawler->selectButton('Save');
        $form = $buttonCrawlerNode->form();
        $fields = $form->all();
        $keys = ['fourcount[title]', 'fourcount[currency]', 'fourcount[description]', 'fourcount[created_by]', 'fourcount[participants]', 'fourcount[_token]'];
        foreach ($keys as $key => $value) {
            $this->assertArrayHasKey($value, $fields);
            
            # code...
        }

        $crawler = $client->submitForm('Save', [
            'fourcount[title]' => 'Titre',
            'fourcount[currency]' => 'euro',
            'fourcount[description]' => 'descr',
        ]);
    }
}
