<?php


namespace App\Scrapping;


use App\Entity\Insight;
use App\Entity\Source;
use Doctrine\ORM\EntityManagerInterface;
use Goutte\Client;

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
        $client = new Client();
        $crawler = $client->request('GET', (string)$source->getUrl());

        if($source->getSelector()) {
            $crawler = $crawler->filter($source->getSelector());
        }

        $text = $crawler->text();

        if($source->getRegex()) {
            if(preg_match($source->getRegex(), $text, $matches)) {
                $text = $matches[$source->getRegexCaptureGroup()];
            }
        }
        $result = trim($text);
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