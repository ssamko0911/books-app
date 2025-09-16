<?php declare(strict_types=1);

namespace App\Utils\ValidatorImpls;

use App\Enum\AppStrings;
use App\Utils\BaseValidator;

final class BookValidator extends BaseValidator
{
    private const int TITLE_LEN_LIMIT = 200;

    public function validate(array $data): array
    {
        $errors = [];

        if ('' === $data['title']) {
            $errors['title'] = AppStrings::EMPTY_TITLE->value;
        }

        if (self::TITLE_LEN_LIMIT < strlen($data['title'])) {
            $errors['title'] = AppStrings::LONG_TITLE->value . self::TITLE_LEN_LIMIT;
        }

        if ('' === ($data['publishedYear'])) {
            $errors['publishedYear'] = AppStrings::EMPTY_PUBLISHED_YEAR->value;
        }

        if ($data['publishedYear'] < 0 || $data['publishedYear'] > 2050) {
            $errors['publishedYear'] = AppStrings::OUT_OF_RANGE_YEAR->value;
        }

        return $errors;
    }
}
