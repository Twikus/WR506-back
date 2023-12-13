<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\MovieRepository;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['movie:read']],
    denormalizationContext: ['groups' => ['movie:write']],
    paginationItemsPerPage: 6,
),
ApiFilter(SearchFilter::class, properties: ['title' => 'partial'])]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['movie:read', 'actor:read', 'category:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'movies')]
    #[Groups(['movie:read'])]
    private ?Category $category = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2, 
        max: 50,
        minMessage: 'Le titre doit faire entre 2 et 50 caractères',
        maxMessage: 'Le titre doit faire entre 2 et 50 caractères'
    )]
    #[Assert\Type('string')]
    #[Groups(['movie:read', 'actor:read', 'category:read'])]
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\Type('text')]
    #[Groups(['movie:read'])]
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\Type('integer')]
    #[Groups(['movie:read'])]
    private ?int $duration = null;
    
    #[ORM\Column]
    #[Assert\Type('integer')]
    #[Groups(['movie:read'])]
    private ?int $entries = null;
    
    #[ORM\Column]
    #[Assert\Type('integer')]
    #[Groups(['movie:read'])]
    private ?int $budget = null;
    
    #[ORM\Column]
    #[Assert\Type('float')]
    #[Assert\Range(min: 0, max: 10)]
    #[Groups(['movie:read'])]
    private ?int $note = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Type('string')]
    #[Groups(['movie:read'])]
    private ?string $director = null;

    #[ORM\Column]
    #[Assert\Type('string')]
    #[Groups(['movie:read'])]
    private ?string $website = null;

    #[ORM\ManyToMany(targetEntity: Actor::class, inversedBy: 'movies')]
    #[Groups(['movie:read'])]
    private Collection $actors;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\Url(message: 'The url {{ value }} is not a valid url')]
    #[Groups(['movie:read'])]
    private ?\DateTimeInterface $releaseDate = null;

    /**
     * @return Collection<int, Actor>
     */
    public function getActors(): Collection
    {
        return $this->actors;
    }

    public function addActor(Actor $actor): static
    {
        if (!$this->actors->contains($actor)) {
            $this->actors->add($actor);
        }

        return $this;
    }

    public function removeActor(Actor $actor): static
    {
        $this->actors->removeElement($actor);

        return $this;
    }

    public function __construct()
    {
        $this->actors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }


    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): static
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getEntries(): ?int
    {
        return $this->entries;
    }

    public function setEntries(int $entries): static
    {
        $this->entries = $entries;

        return $this;
    }

    public function getBudget(): ?int
    {
        return $this->budget;
    }

    public function setBudget(int $budget): static
    {
        $this->budget = $budget;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function setDirector(string $director): static
    {
        $this->director = $director;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(string $website): static
    {
        $this->website = $website;

        return $this;
    }
}
