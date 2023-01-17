<?php

namespace App\Command;

use App\Service\CallApiService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Repository\CurrencyRepository;
use App\Repository\ValuationRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\SaveValuation;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:save-valuation',
    description: 'Save valuation every day.',
    hidden: false,
    aliases: ['app:save-valuation']
)]
class ValuationCommand extends Command
{   
    public function __construct(
        private CallApiService $callApiService,
        private CurrencyRepository $currencyRepo,
        private ValuationRepository $valuationRepo,
        private EntityManagerInterface $em,
        private SaveValuation $saveValuation)
    {
        parent::__construct();
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $callApiService = $this->callApiService;
        $currencyRepo = $this->currencyRepo;
        $valuationRepo = $this->valuationRepo;
        $em = $this->em;
        $this->saveValuation->save($callApiService, $currencyRepo, $valuationRepo, $em);
        return Command::SUCCESS;
    }
}