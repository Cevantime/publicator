<?php


namespace App\Observer;


use App\Entity\Insight;
use App\Entity\Source;
use Doctrine\ORM\EntityManagerInterface;
use Goutte\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObserver;
use Symfony\Component\DomCrawler\Crawler;

class SourceCrawlObserver extends CrawlObserver
{
    /**
     * @var Source
     */
    private $source;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * SourceCrawlObserver constructor.
     * @param Source $source
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null)
    {
        $content = (string)$response->getBody();
        dump((string)$url);

        $contentLen = strlen($content);

        $crawler = new Crawler($content);

        $client = new Client();
        $crawler = $client->request('GET', (string)$url);

        if( ! $crawler->count()) {
            return;
        }

        if($this->source->getSelector()) {
            $crawler = $crawler->filter($this->source->getSelector());
        }

        if( ! $crawler->count()) {
            return;
        }

        $text = $crawler->text();

        if($this->source->getRegex()) {
            if(preg_match($this->source->getRegex(), $text, $matches)) {
                $text = $matches[$this->source->getRegexCaptureGroup()];
            } else {
                return;
            }
        }

        $insight = new Insight();
        $insight->setDate(new \DateTime());
        $insight->setJournal($this->source->getJournal());
        $insight->setType($this->source->getInsightType());
        $insight->setValue(trim($text));

        $this->source->getJournal()->addInsight($insight);

        $this->manager->persist($insight);

        $this->manager->flush();

    }

    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null)
    {
        // TODO: Implement crawlFailed() method.
    }

    /**
     * @return Source
     */
    public function getSource(): Source
    {
        return $this->source;
    }

    /**
     * @param Source $source
     * @return SourceCrawlObserver
     */
    public function setSource(Source $source): SourceCrawlObserver
    {
        $this->source = $source;
        return $this;
    }
}
