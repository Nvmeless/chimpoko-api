<?php

namespace App\Entity;

use App\Repository\ChimpokomonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ChimpokomonRepository::class)]
class Chimpokomon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    #[Groups(["getChimpokomon"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getChimpokomon"])]

    private ?string $name = null;

    #[Groups([
        "getChimpokomon"
    ])]
    #[ORM\Column]
    private ?int $pv = null;

    #[Groups([
        "getChimpokomon"
    ])]
    #[ORM\Column]
    private ?int $pvMax = null;

    #[ORM\Column(length: 25)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'chimpokomons')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["getChimpokomon"])]
    private ?Chimpokodex $chimpokodex = null;


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

    public function getChimpokodex(): ?Chimpokodex
    {
        return $this->chimpokodex;
    }

    public function setChimpokodex(?Chimpokodex $chimpokodex): static
    {
        $this->chimpokodex = $chimpokodex;

        return $this;
    }

    public function getPv(): ?int
    {
        return $this->pv;
    }

    public function setPv(int $pv): static
    {
        $this->pv = $pv;

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
}
