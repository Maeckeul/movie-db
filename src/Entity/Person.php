<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM; 

/**
 * @ORM\Entity
 */
class Person {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="date")
     */
    private $birthDate;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Movie", mappedBy="actors")
     */
    private $actedMovies;

    public function __construct()
    {
        $this->actedMovies = new ArrayCollection();
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

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getActedMovies(): Collection
    {
        return $this->actedMovies;
    }

    public function addActedMovie(Movie $actedMovie): self
    {
        if (!$this->actedMovies->contains($actedMovie)) {
            $this->actedMovies[] = $actedMovie;
            $actedMovie->addActor($this);
        }

        return $this;
    }

    public function removeActedMovie(Movie $actedMovie): self
    {
        if ($this->actedMovies->contains($actedMovie)) {
            $this->actedMovies->removeElement($actedMovie);
            $actedMovie->removeActor($this);
        }

        return $this;
    }
}