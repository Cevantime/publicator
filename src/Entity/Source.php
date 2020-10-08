<?php

namespace App\Entity;

use App\Repository\SourceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=SourceRepository::class)
 * @UniqueEntity(fields={"insightType","journal"})
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(columns={"insight_type_id", "journal_id"})})
 */
class Source
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=InsightType::class, inversedBy="sources")
     * @ORM\JoinColumn(nullable=false)
     */
    private $insightType;

    /**
     * @ORM\ManyToOne(targetEntity=Journal::class, inversedBy="sources")
     * @ORM\JoinColumn(nullable=false)
     */
    private $journal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $selector;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $script;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $regex;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $regexCaptureGroup;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInsightType(): ?InsightType
    {
        return $this->insightType;
    }

    public function setInsightType(?InsightType $insightType): self
    {
        $this->insightType = $insightType;

        return $this;
    }

    public function getJournal(): ?Journal
    {
        return $this->journal;
    }

    public function setJournal(?Journal $journal): self
    {
        $this->journal = $journal;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getSelector(): ?string
    {
        return $this->selector;
    }

    public function setSelector(?string $selector): self
    {
        $this->selector = $selector;

        return $this;
    }

    public function getScript(): ?string
    {
        return $this->script;
    }

    public function setScript(?string $script): self
    {
        $this->script = $script;

        return $this;
    }

    public function getRegex(): ?string
    {
        return $this->regex;
    }

    public function setRegex(?string $regex): self
    {
        $this->regex = $regex;

        return $this;
    }

    public function getRegexCaptureGroup(): ?int
    {
        return $this->regexCaptureGroup;
    }

    public function setRegexCaptureGroup(?int $regexCaptureGroup): self
    {
        $this->regexCaptureGroup = $regexCaptureGroup;

        return $this;
    }
}
