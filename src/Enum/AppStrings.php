<?php

namespace App\Enum;

enum AppStrings: string
{
    case WRONG_INPUT = 'Wrong email or password';
    case EMAIL_EXISTS = 'Email already in use';
    case REG_FAILED = 'Registration failed. Please try again.';
    case INTERNAL_SERVER_ERROR = 'Internal server error. Try again later';
    case NOT_FOUND = 'Sorry, page not found.';
}
