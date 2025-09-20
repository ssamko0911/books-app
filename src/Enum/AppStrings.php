<?php declare(strict_types=1);

namespace App\Enum;

enum AppStrings: string
{
    case WRONG_INPUT = 'Wrong email or password';
    case EMAIL_EXISTS = 'Email already in use';
    case REG_FAILED = 'Registration failed. Please try again.';
    case INTERNAL_SERVER_ERROR = 'Internal server error. Try again later';
    case NOT_FOUND = 'Sorry, page not found.';
    case NOT_AUTHORISED_EDIT = 'Unauthorized book edit attempt';
    case NOT_AUTHORISED_DELETE = 'Unauthorized book delete attempt';
    case BOOK_DELETE = 'Book deleted';
    case DB_CONNECTION_FAILED = 'DB Connection failed: ';
    case DB_CONNECTION_SUCCESS = 'DB connected successfully.';
    case EMPTY_TITLE = 'Title is required';
    case EMPTY_PUBLISHED_YEAR = 'Published year is required';
    case LONG_TITLE = 'Title should be less than ';
    case OUT_OF_RANGE_YEAR = 'Published year should be from 0 to 2050';
}
