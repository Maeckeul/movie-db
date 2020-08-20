<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM; 

/**
 * @ORM\Entity
 */
class Movie {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="date")
     */
    private $releaseDate;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="movies")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person")
     */
    private $director;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Person", inversedBy="actedMovies")
     */
    private $actors;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Award", mappedBy="movie")
     */
    private $award;

    public function __construct()
    {
        $this->award = new ArrayCollection();
        $this->actors = new ArrayCollection();
        $this->category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getDirector(): ?Person
    {
        return $this->director;
    }

    public function setDirector(?Person $director): self
    {
        $this->director = $director;

        return $this;
    }

    /**
     * @return Collection|Award[]
     */
    public function getAward(): Collection
    {
        return $this->award;
    }

    public function addAward(Award $award): self
    {
        if (!$this->award->contains($award)) {
            $this->award[] = $award;
            $award->setMovie($this);
        }

        return $this;
    }

    public function removeAward(Award $award): self
    {
        if ($this->award->contains($award)) {
            $this->award->removeElement($award);
            // set the owning side to null (unless already changed)
            if ($award->getMovie() === $this) {
                $award->setMovie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Person[]
     */
    public function getActors(): Collection
    {
        return $this->actors;
    }

    public function addActor(Person $actor): self
    {
        if (!$this->actors->contains($actor)) {
            $this->actors[] = $actor;
        }

        return $this;
    }

    public function removeActor(Person $actor): self
    {
        if ($this->actors->contains($actor)) {
            $this->actors->removeElement($actor);
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->category->contains($category)) {
            $this->category->removeElement($category);
        }

        return $this;
    }
}