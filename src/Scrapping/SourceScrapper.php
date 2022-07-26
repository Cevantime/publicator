<?php


namespace App\Scrapping;


use App\Entity\Insight;
use App\Entity\Source;
use Doctrine\ORM\EntityManagerInterface;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Remote\Service\DriverCommandExecutor;
use Facebook\WebDriver\Remote\Service\DriverService;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Symfony\Component\Panther\Client;

class SourceScrapper
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    /**
     * SourceScrapper constructor.
     * @param EntityManagerInterface $entityManager
     * @param Client $client
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function scrapSource(Source $source)
    {
        $client = Client::createChromeClient(null, [
            '--user-agent=Mozilla/5.0 (X11; Linux x86_64; rv:102.0) Gecko/20100101 Firefox/102.0',
            '--headless',
            '--window-size=1200,1100',
            '--disable-gpu',
            '--no-sandbox'
        ]);
        $crawler = $client->request('GET', $source->getUrl());

        if ($source->getScript()) {
            $client->executeScript($source->getScript());
        }

        if (($selector = $source->getSelector())) {
            $client->waitFor($selector);
            $client->refreshCrawler();
            $crawler = $client->getCrawler();
            $crawler = $crawler->filter($selector);
        }

        if ($crawler->count() == 0) {
            throw new \Exception("Could not fetch the selected source {$source->getId()}");
        }

        $text = $crawler->text();

        if ($source->getRegex()) {
            if (preg_match($source->getRegex(), $text, $matches)) {
                $text = $matches[$source->getRegexCaptureGroup()];
            }
        }

        $result = trim($text);
        if (!$text) {
            throw new \Exception("Source {$source->getId()} returned an empty result");
        }
        $insight = new Insight();
        $insight->setDate(new \DateTime());
        $insight->setJournal($source->getJournal());
        $insight->setType($source->getInsightType());
        $insight->setValue($result);

        $source->getJournal()->addInsight($insight);

        $this->entityManager->persist($insight);
        $this->entityManager->flush();

        return $result;
    }
}