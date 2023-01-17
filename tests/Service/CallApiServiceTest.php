<?php

namespace App\tests\Service;

use App\Service\CallApiService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CallApiServiceTest extends KernelTestCase
{
    public function testCallApiService(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $getParams = $container->get(ParameterBagInterface::class);
        $cryptoApiKey = $getParams->get('CRYPTO_API_KEY');
        $callApiService = $container->get(CallApiService::class);
        $response = $callApiService->getCryptoData($cryptoApiKey);
        $results = json_decode($response, true);
        $this->assertCount(35, $results['data']);
    }
}