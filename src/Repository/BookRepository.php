<?php declare(strict_types=1);

namespace App\Repository;

final class BookRepository extends BaseRepository
{
    protected string $table = 'book_recommendations_books';
}
