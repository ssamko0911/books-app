<?php declare(strict_types=1);

namespace App\Dto;

final class BookDTO
{
    public ?int $id = null;
    public string $title;
    public AuthorSelectDTO $author;
    public string $description;
    public int $publishedYear;
    public int $addedByUserId;
}