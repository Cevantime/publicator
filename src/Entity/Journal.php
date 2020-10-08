<?php

namespace App\Entity;

use App\Repository\JournalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JournalRepository::class)
 */
class Journal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Insight::class, mappedBy="journal", orphanRemoval=true)
     */
    private $insights;

    /**
     * @ORM\OneToMany(targetEntity=Source::class, mappedBy="journal")
     */
    private $sources;

    public function __construct()
    {
        $this->insights = new ArrayCollection();
        $this->sources = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Insight[]
     */
    public function getInsights(): Collection
    {
        return $this->insights;
    }

    public function addInsight(Insight $insight): self
    {
        if (!$this->insights->contains($insight)) {
            $this->insights[] = $insight;
            $insight->setJournal($this);
        }

        return $this;
    }

    public function removeInsight(Insight $insight): self
    {
        if ($this->insights->contains($insight)) {
            $this->insights->removeElement($insight);
            // set the owning side to null (unless already changed)
            if ($insight->getJournal() === $this) {
                $insight->setJournal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Source[]
     */
    public function getSources(): Collection
    {
        return $this->sources;
    }

    public function addSource(Source $source): self
    {
        if (!$this->sources->contains($source)) {
            $this->sources[] = $source;
            $source->setJournal($this);
        }

        return $this;
    }

    public function removeSource(Source $source): self
    {
        if ($this->sources->contains($source)) {
            $this->sources->removeElement($source);
            // set the owning side to null (unless already changed)
            if ($source->getJournal() === $this) {
                $source->setJournal(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
