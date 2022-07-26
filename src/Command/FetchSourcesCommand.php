<?php

namespace App\Command;

use App\Entity\Insight;
use App\Observer\SourceCrawlObserver;
use App\Repository\SourceRepository;
use App\Scrapping\SourceScrapper;
use Doctrine\ORM\EntityManagerInterface;
use Goutte\Client;
use Spatie\Crawler\Crawler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FetchSourcesCommand extends Command
{
    protected static $defaultName = 'app:fetch-sources';

    /**
     * @var SourceRepository
     */
    protected $sourceRepository;

    /**
     * @var SourceScrapper
     */
    protected $sourceScrapper;

    public function __construct(SourceRepository $sourceRepository, SourceScrapper $sourceScrapper, string $name = null)
    {
        parent::__construct($name);
        $this->sourceRepository = $sourceRepository;
        $this->sourceScrapper = $sourceScrapper;
    }

    protected function configure()
    {
        $this
            ->setDescription('Fetches all sources')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $sources = $this->sourceRepository->findAll();

        $io->comment("Fetching all sources, this may take a while...");
        $progressBar = new ProgressBar($output, count($sources));
        $progressBar->display();

        foreach ($sources as $source) {
            try {
                $this->sourceScrapper->scrapSource($source);
            } catch (\Exception $exception) {
                $io->warning($exception->getMessage() .' source id '.$source->getId());

            }
            $progressBar->advance();
        }

        $io->success('Sources fetched');

        return 0;
    }
}
