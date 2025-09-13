<?php

namespace App\Enum;

enum AppStrings: string
{
    case WRONG_INPUT = 'Wrong email or password';
    case EMAIL_EXISTS = 'Email already in use';

    case REG_FAILED = 'Registration failed. Please try again.';
}
