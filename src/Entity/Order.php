<?php

namespace App\Entity;


use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use App\Entity\Book;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userId = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $orderedBookId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): static
    {
        $this->userId = $userId;
        return $this;
    }

    public function getOrderedBookId(): ?Book
    {
        return $this->orderedBookId;
    }

    public function setOrderedBookId(?Book $orderedBookId): static
    {
        $this->orderedBookId = $orderedBookId;

        return $this;
    }
}
