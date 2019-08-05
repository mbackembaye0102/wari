<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PartenaireControllerTest extends WebTestCase
{
    public function testPartenaireBloquer()
    {
        $client = static::createClient([],[
            'PHP_AUTH_USER'=>'kabirou',
            'PHP_AUTH_PW'=>'123456'
         ]);      
         $client->request('POST', '/api/users/bloquer',[],[],
        ['CONTENT_TYPE'=>"application/json"],
        '{"entreprise":"ALDIANA"}');
        $rep=$client->getResponse();
        var_dump($rep);
        $this->assertSame(201,$client->getResponse()->getStatusCode());
    }

    // public function testPartenaireDebloquer()
    // {
    //     $client = static::createClient([],[
    //         'PHP_AUTH_USER'=>'kabirou',
    //         'PHP_AUTH_PW'=>'123456'
    //      ]);      
    //      $client->request('POST', '/api/users/bloquer',[],[],
    //     ['CONTENT_TYPE'=>"application/json"],
    //     '{"entreprise":"ALDIANA"}');
    //     $rep=$client->getResponse();
    //     var_dump($rep);
    //     $this->assertSame(201,$client->getResponse()->getStatusCode());
    // }
}
