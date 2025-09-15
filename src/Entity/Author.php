<?php declare(strict_types=1);

namespace App\Entity;
final class Author
{
    private ?int $id;
    private string $firstName;
    private string $lastName;
    private string $biography;

    public function __construct(?int $id, string $firstName, string $lastName, string $biography)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->biography = $biography;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
