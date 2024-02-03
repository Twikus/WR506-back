<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\MovieRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
#[ApiResource(
    description: 'Des films',
    operations: [
        new GetCollection(),
        new Post(security: "is_granted('ROLE_ADMIN')"),
        new Get(),
        new Delete(security: "is_granted('ROLE_ADMIN')"),
        new Put(security: "is_granted('ROLE_ADMIN')"),
    ],
    normalizationContext: [
        'groups' => ['movie:read'],
    ],
    denormalizationContext: [
        'groups' => ['movie:write'],
    ],
)]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial', 'category.id' => 'exact'])]
#[Vich\Uploadable()]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['movie:read', 'actor:read', 'category:read'])]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'movie', targetEntity: MediaObject::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['movie:read', 'movie:write'])]
    private Collection|null $mediaObjects = null;

    #[ORM\Column(length: 255)]
    #[Groups(['movie:read', 'movie:write', 'actor:read'])]
    #[Assert\NotBlank(message: 'Le titre du film est obligatoire')]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['movie:read', 'movie:write', 'actor:read'])]
    #[Assert\NotBlank(message: 'La description du film est obligatoire')]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['movie:read', 'movie:write'])]
    private ?int $duration = null;
    
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Groups(['movie:read', 'movie:write'])]
    #[Assert\Positive(message: 'Le nombre d\'entrées doit être positif')]
    private ?int $entries = null;
    
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Groups(['movie:read', 'movie:write'])]
    #[Assert\Positive(message: 'Le budget doit être positif')]
    private ?int $budget = null;
    
    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Assert\Range(min: 0, max: 10)]
    #[Groups(['movie:read', 'movie:write'])]
    private ?int $note = null;

    #[ORM\Column(length: 255)]
    #[Groups(['movie:read', 'movie:write'])]
    #[Assert\NotBlank(message: 'Le réalisateur est obligatoire')]
    private ?string $director = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['movie:read', 'movie:write'])]
    #[Assert\Url(message: 'Le lien doit être valide')]
    private ?string $website = null;

    #[ORM\ManyToOne(inversedBy: 'movies')]
    #[Groups(['movie:read', 'movie:write'])]
    private ?Category $category = null;

    #[ORM\ManyToMany(targetEntity: Actor::class, inversedBy: 'movies')]
    #[Groups(['movie:read'])]
    private Collection $actors;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['movie:read', 'movie:write'])]
    private ?\DateTimeInterface $releaseDate = null;

    /**
     * @return Collection<int, MediaObject>
     */
    public function getMediaObjects(): Collection
    {
        return $this->mediaObjects;
    }

    public function addMediaObject(MediaObject $mediaObject): static
    {
        if (!$this->mediaObjects->contains($mediaObject)) {
            $this->mediaObjects->add($mediaObject);
            $mediaObject->setMovie($this);
        }

        return $this;
    }

    public function removeMediaObject(MediaObject $mediaObject): static
    {
        $this->mediaObjects->removeElement($mediaObject);

        return $this;
    }

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
        $this->mediaObjects = new ArrayCollection();
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
