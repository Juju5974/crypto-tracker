<?php

namespace App\tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\Valuation;

class ValuationRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
    
    public function testCount()
    {
        $valuations = $this->entityManager
        ->getRepository(Valuation::class)->findAll();
        $this->assertEquals(10, count($valuations));
    }
}