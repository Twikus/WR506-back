<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\ActorRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ActorRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(),
        new Get(),
        new Delete(),
        new Put(),
    ],
    normalizationContext: [
        'groups' => ['actor:read'],
    ],
    denormalizationContext: [
        'groups' => ['actor:write'],
    ],
)]
#[ApiFilter(SearchFilter::class, properties: ['firstName' => 'partial', 'lastName' => 'partial'])]
class Actor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['actor:read', 'movie:read', 'nationality:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['actor:read', 'actor:write', 'movie:read'])]
    #[Assert\NotBlank(message: 'Le prénom de l\'acteur est obligatoire')]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups(['actor:read', 'actor:write', 'movie:read'])]
    #[Assert\NotBlank(message: 'Le nom de l\'acteur est obligatoire')]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Groups(['actor:read', 'movie:read', 'nationality:read'])]
    private ?string $fullName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['actor:read', 'actor:write', 'movie:read'])]
    private ?string $reward = null;

    #[ORM\ManyToMany(targetEntity: Movie::class, mappedBy: 'actors')]
    #[Groups(['actor:read'])]
    private Collection $movies;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'Actor')]
    #[Groups(['actor:read', 'actor:write', 'movie:read'])]
    #[Assert\NotNull(message: 'La nationalité de l\'acteur est obligatoire')]
    private ?Nationality $nationality = null;

    /**
     * @return Collection<int, Movie>
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(Movie $movie): static
    {
        if (!$this->movies->contains($movie)) {
            $this->movies->add($movie);
            $movie->addActor($this);
        }

        return $this;
    }

    public function removeMovie(Movie $movie): static
    {
        if ($this->movies->removeElement($movie)) {
            $movie->removeActor($this);
        }

        return $this;
    }

    public function __construct()
    {
        $this->movies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;
        $this->updateFullName();

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;
        $this->updateFullName();

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateFullName(): void
    {
        $this->fullName = $this->firstName . ' ' . $this->lastName;
    }

    public function getNationality(): ?Nationality
    {
        return $this->nationality;
    }

    public function setNationality(?Nationality $nationality): static
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getReward(): ?string
    {
        return $this->reward;
    }

    public function setReward(string $reward): static
    {
        $this->reward = $reward;

        return $this;
    }
}
