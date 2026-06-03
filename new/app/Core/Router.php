<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<int, array{method: string, pattern: string, handler: callable, name?: string}> */
    private array $routes = [];

    public function get(string $pattern, callable $handler, ?string $name = null): self
    {
        return $this->add('GET', $pattern, $handler, $name);
    }

    public function post(string $pattern, callable $handler, ?string $name = null): self
    {
        return $this->add('POST', $pattern, $handler, $name);
    }

    private function add(string $method, string $pattern, callable $handler, ?string $name): self
    {
        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'handler' => $handler,
            'name' => $name,
        ];
        return $this;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $path = rtrim($path, '/') ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            $regex = '#^' . preg_replace('#\{([a-z_]+)\}#', '(?P<$1>[^/]+)', $route['pattern']) . '$#i';
            if (!preg_match($regex, $path, $matches)) {
                continue;
            }
            $params = array_filter(
                $matches,
                static fn ($key) => !is_int($key),
                ARRAY_FILTER_USE_KEY
            );
            ($route['handler'])($params);
            return;
        }

        http_response_code(404);
        View::render('public/errors/404', ['title' => 'Page Not Found']);
    }
}
