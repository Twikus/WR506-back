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
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2, 
        max: 1000,
        minMessage: 'La description doit faire entre 2 et 1000 caractères',
        maxMessage: 'La description doit faire entre 2 et 1000 caractères'
    )]
    #[Assert\Type('string')]
    #[Groups(['movie:read'])]
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\Range(
        min: 1,
        max: 1000,
        minMessage: 'La durée doit faire entre 1 et 1000 minutes',
        maxMessage: 'La durée doit faire entre 1 et 1000 minutes'
    )]
    #[Assert\Type('integer')]
    #[Groups(['movie:read'])]
    private ?int $duration = null;

    #[ORM\ManyToMany(targetEntity: Actor::class, inversedBy: 'movies')]
    #[Groups(['movie:read'])]
    private Collection $actors;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
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
}
