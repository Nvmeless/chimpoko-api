<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ChimpokodexRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ChimpokodexRepository::class)]
class Chimpokodex
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[
        Groups(["getChimpokomon", "getChimpokodex"])
    ]

    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[
        Groups(["getChimpokomon", "getChimpokodex"])
    ]

    private ?string $name = null;

    #[ORM\Column]
    #[
        Groups(["getChimpokomon", "getChimpokodex"])
    ]
    private ?int $momId = null;

    #[ORM\Column]
    #[
        Groups(["getChimpokomon", "getChimpokodex"])
    ]
    private ?int $dadId = null;

    #[ORM\Column]
    #[
        Groups(["getChimpokomon", "getChimpokodex"])
    ]
    private ?int $pvMax = null;

    #[ORM\Column]
    #[
        Groups(["getChimpokomon", "getChimpokodex"])
    ]
    private ?int $pvMin = null;

    #[ORM\Column(length: 25)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @var Collection<int, Chimpokomon>
     */
    #[ORM\OneToMany(targetEntity: Chimpokomon::class, mappedBy: 'chimpokodex')]
    private Collection $chimpokomons;

    public function __construct()
    {
        $this->chimpokomons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getMomId(): ?int
    {
        return $this->momId;
    }

    public function setMomId(int $momId): static
    {
        $this->momId = $momId;

        return $this;
    }

    public function getDadId(): ?int
    {
        return $this->dadId;
    }

    public function setDadId(int $dadId): static
    {
        $this->dadId = $dadId;

        return $this;
    }

    public function getPvMax(): ?int
    {
        return $this->pvMax;
    }

    public function setPvMax(int $pvMax): static
    {
        $this->pvMax = $pvMax;

        return $this;
    }

    public function getPvMin(): ?int
    {
        return $this->pvMin;
    }

    public function setPvMin(int $pvMin): static
    {
        $this->pvMin = $pvMin;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Chimpokomon>
     */
    public function getChimpokomons(): Collection
    {
        return $this->chimpokomons;
    }

    public function addChimpokomon(Chimpokomon $chimpokomon): static
    {
        if (!$this->chimpokomons->contains($chimpokomon)) {
            $this->chimpokomons->add($chimpokomon);
            $chimpokomon->setChimpokodex($this);
        }

        return $this;
    }

    public function removeChimpokomon(Chimpokomon $chimpokomon): static
    {
        if ($this->chimpokomons->removeElement($chimpokomon)) {
            // set the owning side to null (unless already changed)
            if ($chimpokomon->getChimpokodex() === $this) {
                $chimpokomon->setChimpokodex(null);
            }
        }

        return $this;
    }
}
