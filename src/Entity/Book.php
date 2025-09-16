<?php declare(strict_types=1);

namespace App\Entity;

final class Book
{
    private ?int $id;
    private string $title;
    private Author $author;
    private string $description;
    private int $publishedYear;
    private int $addedByUserId;

    /**
     * @param int|null $id
     * @param string $title
     * @param Author $author
     * @param string $description
     * @param int $publishedYear
     * @param int $addedByUserId
     */
    public function __construct(?int $id, string $title, Author $author, string $description, int $publishedYear, int $addedByUserId)
    {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->description = $description;
        $this->publishedYear = $publishedYear;
        $this->addedByUserId = $addedByUserId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }


    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPublishedYear(): int
    {
        return $this->publishedYear;
    }

    public function setPublishedYear(int $publishedYear): void
    {
        $this->publishedYear = $publishedYear;
    }

    public function getAddedByUserId(): int
    {
        return $this->addedByUserId;
    }

    public function setAddedByUserId(int $addedByUserId): void
    {
        $this->addedByUserId = $addedByUserId;
    }
}
