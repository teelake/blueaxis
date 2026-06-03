<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    public static function render(string $view, array $data = [], ?string $layout = 'layouts/public'): void
    {
        extract($data, EXTR_SKIP);
        $viewFile = APP_PATH . '/Views/' . str_replace('.', '/', $view) . '.php';
        if (!is_readable($viewFile)) {
            throw new \RuntimeException("View not found: {$view}");
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        if ($layout) {
            $layoutFile = APP_PATH . '/Views/' . str_replace('.', '/', $layout) . '.php';
            require $layoutFile;
            return;
        }

        echo $content;
    }

    public static function partial(string $partial, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        require APP_PATH . '/Views/partials/' . $partial . '.php';
    }
}
