<?php

namespace App\Command;

use App\Repository\SourceRepository;
use App\Scrapping\SourceScrapper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestSourceCommand extends Command
{
    protected static $defaultName = 'app:test-source';

    /**
     * @var SourceRepository
     */
    protected $sourceRepository;

    /**
     * @var SourceScrapper
     */
    protected $sourceScrapper;

    /**
     * TestSourceCommand constructor.
     * @param SourceRepository $sourceRepository
     * @param SourceScrapper $sourceScrapper
     */
    public function __construct(SourceRepository $sourceRepository, SourceScrapper $sourceScrapper, $defaultName = null)
    {
        parent::__construct($defaultName);
        $this->sourceRepository = $sourceRepository;
        $this->sourceScrapper = $sourceScrapper;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('sourceId', InputArgument::REQUIRED, 'The Id of the source')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $sourceId = $input->getArgument('sourceId');

        $source = $this->sourceRepository->find($sourceId);

        if(!$source) {
            $io->error('No source for id ' . $sourceId);
        }

        $result = $this->sourceScrapper->scrapSource($source);

        $io->success('Source tested ! Result '.$result);

        return 0;
    }
}
