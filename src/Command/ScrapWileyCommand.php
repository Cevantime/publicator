<?php

namespace App\Command;

use App\Observer\WileyCrawlObserver;
use Spatie\Crawler\Crawler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ScrapWileyCommand extends Command
{
    protected static $defaultName = 'app:scrap-wiley';

    /**
     * @var WileyCrawlObserver
     */
    protected $crawlObserver;

    /**
     * ScrapWileyCommand constructor.
     * @param WileyCrawlObserver $crawlObserver
     * @param $entityManager
     */
    public function __construct(WileyCrawlObserver $crawlObserver, string $defaultName = null)
    {
        parent::__construct($defaultName);
        $this->crawlObserver = $crawlObserver;
    }

    protected function configure()
    {
        $this
            ->setDescription('Scraps onlinelibrary.wiley.com website')
            ->addArgument('journalWileyId', InputArgument::REQUIRED, 'the wiley id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        Crawler::create()
            ->setCrawlObserver($this->crawlObserver)
            ->setMaximumCrawlCount(1)
            ->startCrawling('https://onlinelibrary.wiley.com/journal/'.$input->getArgument('journalWileyId'));

        $io->success('urls scrapped');

        return 0;
    }
}
