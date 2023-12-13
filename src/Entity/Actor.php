<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ActorRepository;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ActorRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['actor:read']],
    denormalizationContext: ['groups' => ['actor:write']],
    paginationItemsPerPage: 6,
),
ApiFilter(SearchFilter::class, properties: ['firstName' => 'partial', 'lastName' => 'partial'])]
class Actor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['actor:read', 'movie:read', 'nationality:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    #[Groups(['actor:read', 'movie:read', 'nationality:read'])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    #[Groups(['actor:read', 'movie:read', 'nationality:read'])]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Groups(['actor:read', 'movie:read', 'nationality:read'])]
    private ?string $fullName = null;

    #[ORM\Column(length: 255)]
    #[Assert\Type('text')]
    #[Groups(['actor:read', 'movie:read', 'nationality:read'])]
    private ?string $reward = null;

    #[ORM\ManyToMany(targetEntity: Movie::class, mappedBy: 'actors')]
    #[Groups(['actor:read'])]
    private Collection $movies;

    #[ORM\ManyToOne(inversedBy: 'Actor')]
    #[Assert\NotNull]
    #[Assert\Type('string')]
    #[Groups(['actor:read'])]
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

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

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