<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    use ValidatesForms;
    protected function view(string $view, array $data = [], ?string $layout = 'layouts/public'): void
    {
        View::render($view, $data, $layout);
    }

    protected function validateCsrf(): void
    {
        $token = $_POST['_csrf'] ?? '';
        if (!Csrf::validate($token)) {
            http_response_code(419);
            exit('Invalid security token. Please refresh and try again.');
        }
    }
}
