<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TransactionControllerTest extends WebTestCase
{
    public function testCompte()
    {
        $client = static::createClient([],[
            'PHP_AUTH_USER'=>'kabirou',
            'PHP_AUTH_PW'=>'123456'
         ]);      
         $client->request('POST', '/api/comptes',[],[],
        ['CONTENT_TYPE'=>"application/json"],
        '{"partenaire":32}');
        $rep=$client->getResponse();
        var_dump($rep);
        $this->assertSame(201,$client->getResponse()->getStatusCode());
    }


    // public function testDepot()
    // {
    //     $client = static::createClient([],[
    //         'PHP_AUTH_USER'=>'caissier',
    //         'PHP_AUTH_PW'=>'123456'
    //      ]);      
    //      $client->request('POST', '/api/depots',[],[],
    //     ['CONTENT_TYPE'=>"application/json"],
    //     '{"montant":320000,
    //         "compte":7}');
    //     $rep=$client->getResponse();
    //     var_dump($rep);
    //     $this->assertSame(201,$client->getResponse()->getStatusCode());
    // }
}
