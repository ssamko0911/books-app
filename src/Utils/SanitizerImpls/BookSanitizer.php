<?php declare(strict_types=1);

namespace App\Utils\SanitizerImpls;

use App\Utils\BaseSanitizer;

final class BookSanitizer extends BaseSanitizer
{
    public static function sanitize(array $data): array
    {
        return [
            'id' => (int)($data['book_id'] ?? null),
            'title' => trim(strip_tags($data['title'] ?? '')),
            'author' => (int)$data['author'],
            'author_name' => $data['author_name'] ?? '',
            'description' => trim(strip_tags($data['description'] ?? '')),
            'published_year' => (int)($data['published_year'] ?? null),
        ];
    }
}