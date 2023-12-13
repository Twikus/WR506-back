<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\NationalityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: NationalityRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['nationality:read']],
)]
class Nationality
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['nationality:read', 'actor:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    #[Groups(['nationality:read', 'actor:read'])]
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'nationality', targetEntity: Actor::class)]
    #[Groups(['nationality:read'])]
    private Collection $Actor;

    public function __construct()
    {
        $this->Actor = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Actor>
     */
    public function getActor(): Collection
    {
        return $this->Actor;
    }

    public function addActor(Actor $actor): static
    {
        if (!$this->Actor->contains($actor)) {
            $this->Actor->add($actor);
            $actor->setNationality($this);
        }

        return $this;
    }

    public function removeActor(Actor $actor): static
    {
        if ($this->Actor->removeElement($actor)) {
            // set the owning side to null (unless already changed)
            if ($actor->getNationality() === $this) {
                $actor->setNationality(null);
            }
        }

        return $this;
    }
}
