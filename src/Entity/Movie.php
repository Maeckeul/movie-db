<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"api_v1_movies"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     * @Groups({"api_v1_movies"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"api_v1_movies"})
     */
    private $imageFilename;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotNull
     * @Assert\Type(\DateTime::class)
     * @Groups({"api_v1_movies"})
     */
    private $releaseDate;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="movies")
     * @Assert\Count(min=1, max=4)
     * @Groups({"api_v1_movies"})
     */
    private $categories;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="directedMovies")
     * @Assert\NotNull
     */
    private $director;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Person", inversedBy="writedMovies")
     * @ORM\JoinTable(name="movie_writer")
     * @Assert\Count(min=1)
     */
    private $writers;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Award", mappedBy="movie")
     */
    private $awards;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Post", mappedBy="movies")
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity=MovieActor::class, mappedBy="movie", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid
     */
    private $movieActors;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    public function __construct()
    {   
        $this->category = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->writers = new ArrayCollection();
        $this->awards = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->movieActors = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
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

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

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
     * @return Collection|Person[]
     */
    public function getWriters(): Collection
    {
        return $this->writers;
    }

    public function addWriter(Person $writer): self
    {
        if (!$this->writers->contains($writer)) {
            $this->writers[] = $writer;
        }

        return $this;
    }

    public function removeWriter(Person $writer): self
    {
        if ($this->writers->contains($writer)) {
            $this->writers->removeElement($writer);
        }

        return $this;
    }

    /**
     * @return Collection|Award[]
     */
    public function getAwards(): Collection
    {
        return $this->awards;
    }

    public function addAward(Award $award): self
    {
        if (!$this->awards->contains($award)) {
            $this->awards[] = $award;
            $award->setMovie($this);
        }

        return $this;
    }

    public function removeAward(Award $award): self
    {
        if ($this->awards->contains($award)) {
            $this->awards->removeElement($award);
            // set the owning side to null (unless already changed)
            if ($award->getMovie() === $this) {
                $award->setMovie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->addMovie($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            $post->removeMovie($this);
        }

        return $this;
    }

    /**
     * @return Collection|MovieActor[]
     */
    public function getMovieActors(): Collection
    {
        return $this->movieActors;
    }

    public function addMovieActor(MovieActor $movieActor): self
    {
        if (!$this->movieActors->contains($movieActor)) {
            $this->movieActors[] = $movieActor;
            $movieActor->setMovie($this);
        }

        return $this;
    }

    public function removeMovieActor(MovieActor $movieActor): self
    {
        if ($this->movieActors->contains($movieActor)) {
            $this->movieActors->removeElement($movieActor);
            // set the owning side to null (unless already changed)
            if ($movieActor->getMovie() === $this) {
                $movieActor->setMovie(null);
            }
        }

        return $this;
    }

    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }

    public function setImageFilename(?string $imageFilename): self
    {
        $this->imageFilename = $imageFilename;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
    
    /**
     * @Groups({"api_v1_movies"})
     */
    public function getCountCategory()
    {
        return count($this->categories);
    }

    /**
     * @Groups({"api_v1_movies"})
     */
    public function getDirectorName()
    {
        return $this->director->getName();
    }
}