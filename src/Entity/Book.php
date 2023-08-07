<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/*
#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ORM\Table(name: "book", uniqueConstraints: [
    new ORM\UniqueConstraint(columns: ["isbn"])
])]*/

#[ORM\Entity]
#[UniqueEntity('isbn')]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $status = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\Regex('/^[A-Za-z\s]*$/')]

    private ?string $Author = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\Regex('/^[A-Za-z\s]*$/')]
    private ?string $bookName = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?int $Quantity = null;

    ##[ORM\Column(length: 255)]
    #[Assert\Regex('/^[A-Za-z]{2}\d{3}$/')]
    #[Assert\NotBlank]
    #[ORM\Column(name: 'isbn', type: 'string', length: 255, unique: true)]
    //#[Assert\Unique]
    ##[Assert\Unique]
    private ?string $isbn = null;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getAuthor(): ?string
    {
        return $this->Author;
    }

    public function setAuthor(string $Author): static
    {
        $this->Author = $Author;

        return $this;
    }

    public function getBookName(): ?string
    {
        return $this->bookName;
    }

    public function setBookName(string $bookName): static
    {
        $this->bookName = $bookName;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->Quantity;
    }

    public function setQuantity(int $Quantity): static
    {
        $this->Quantity = $Quantity;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }
}
