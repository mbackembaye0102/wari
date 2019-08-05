<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testProfil()
    {
        $client = static::createClient([],[
            'PHP_AUTH_USER'=>'kabirou',
            'PHP_AUTH_PW'=>'123456'
         ]);      
         $client->request('POST', '/api/profils',[],[],
        ['CONTENT_TYPE'=>"application/json"],
        '{"libelle":"admin secondiare sup"}');
        $rep=$client->getResponse();
        var_dump($rep);
        $this->assertSame(201,$client->getResponse()->getStatusCode());
    }

    public function testUserBloquer()
    {
        $client = static::createClient([],[
            'PHP_AUTH_USER'=>'kabirou',
            'PHP_AUTH_PW'=>'123456'
         ]);      
         $client->request('POST', '/api/users/bloquer',[],[],
        ['CONTENT_TYPE'=>"application/json"],
        '{"username":"caissiere"}');
        $rep=$client->getResponse();
        var_dump($rep);
        $this->assertSame(201,$client->getResponse()->getStatusCode());
    }

    // public function testUserDebloquer()
    // {
    //     $client = static::createClient([],[
    //         'PHP_AUTH_USER'=>'kabirou',
    //         'PHP_AUTH_PW'=>'123456'
    //      ]);      
    //      $client->request('POST', '/api/users/debloquer',[],[],
    //     ['CONTENT_TYPE'=>"application/json"],
    //     '{"username":"caissiere"}');
    //     $rep=$client->getResponse();
    //     var_dump($rep);
    //     $this->assertSame(201,$client->getResponse()->getStatusCode());
    // }

}
