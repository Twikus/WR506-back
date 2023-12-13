<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTest extends WebTestCase
{
    protected static function getKernelClass(): string
    {
        return \App\Kernel::class;
    }

    public function testLoginCheck()
    {
        $client = static::createClient();

        $client->request('GET', '/api', [], [], ['CONTENT_TYPE' => 'application/json']);

        $response = $client->getResponse();

        // Vérifiez le code de réponse HTTP
        $this->assertSame(401, $response->getStatusCode());
    }
}
