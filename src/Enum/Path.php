<?php declare(strict_types=1);

namespace App\Enum;
enum Path: string
{
    case MAIN_TEMPLATE = '/../../views/template.view.php';
    case HTTP_RESPONSE_TEMPLATE = '/../../views/$code.php';
    case LOGIN = '/../../views/auth/components/login.php';
    case REGISTER = '/../../views/auth/components/registration.php';
    case ADD_BOOK = '/../../views/books/add.view.php';
    case EDIT_BOOK = '/../../views/books/edit.view.php';
    case BOOKS_LIST = '/../../views/books/index.view.php';
    case RECOMMENDATIONS_LIST = '/../../views/recommendations/index.view.php';
    case RECOMMENDATIONS_SHOW = '/../../views/recommendations/show.view.php';
}
