<?php


namespace App\Observer;


use App\Entity\Insight;
use App\Entity\InsightType;
use App\Entity\Journal;
use App\Entity\Source;
use App\Repository\InsightTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Goutte\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObserver;
use Symfony\Component\DomCrawler\Crawler;

class WileyCrawlObserver extends CrawlObserver
{
    /**
     * @var InsightType
     */
    private $impactFactorType;
    /**
     * @var InsightTypeRepository
     */
    private $insightTypeRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * WileyCrawlObserver constructor.
     * @param InsightTypeRepository $insightTypeRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(InsightTypeRepository $insightTypeRepository, EntityManagerInterface $entityManager)
    {
        $this->insightTypeRepository = $insightTypeRepository;
        $this->entityManager = $entityManager;
    }

    public function willCrawl(UriInterface $url)
    {
        $this->impactFactorType = $this->insightTypeRepository->findOneBy(['name' => 'Impact factor']);
    }

    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null)
    {
        $urlStr = (string) $url;

        dump($urlStr);

        if( ! preg_match('#https://onlinelibrary\\.wiley\\.com/journal/(.*)#',$urlStr)) {
            dump('match not');
            return;
        }

        $selector = '.journal-info-container :nth-child(3) .info-block:first-child .info_value';
        $content = (string) $response->getBody();
        $client = new Client();
        $crawler = $client->request('GET', (string)$url);
        $journalName = trim($crawler->filter('#journal-banner-text')->text());

        $impactFactor = trim($crawler->filter($selector)->text());
        $journal = new Journal();
        $journal->setName($journalName);

        $insight = new Insight();
        $insight->setType($this->impactFactorType);
        $insight->setValue($impactFactor);
        $insight->setJournal($journal);
        $insight->setDate(new \DateTime());

        $source = new Source();

        $source->setUrl((string)$url);
        $source->setSelector($selector);
        $source->setInsightType($this->impactFactorType);

        $journal->addInsight($insight);
        $journal->addSource($source);

        $this->entityManager->persist($journal);
        $this->entityManager->persist($source);
        $this->entityManager->persist($insight);
        $this->entityManager->flush();

    }

    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null)
    {
        // TODO: Implement crawlFailed() method.
    }
}