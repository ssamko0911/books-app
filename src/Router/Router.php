<?php declare(strict_types=1);

namespace App\Router;
use App\Factory\ControllerFactory;
use App\Utils\UrlTool;
use JetBrains\PhpStorm\NoReturn;
use RuntimeException;

final class Router
{
    private array $routes = [];

    public function __construct(
        private readonly ControllerFactory $factory
    )
    {
    }

    public function post(string $pattern, array $handler): self
    {
        return $this->addRoute('POST', $pattern, $handler);
    }
    public function get(string $pattern, array $handler): self
    {
        return $this->addRoute('GET', $pattern, $handler);
    }

    private function addRoute(string $method, string $pattern, array $handler): self
    {
        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'handler' => $handler,
            'regex' => $this->buildRegex($pattern)
        ];

        return $this;
    }

    private function buildRegex(string $pattern): string
    {
        $regex = preg_replace_callback('#\{([a-zA-Z_]+)}#', static function ($matches) {
            return '(?P<' . $matches[1] . '>[0-9]+)';
        }, $pattern);

        return "#^$regex$#";
    }

    public function launchRouter(?string $uri = null, ?string $method = null): void
    {
        $uri = $uri ?? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $method ?? $_SERVER['REQUEST_METHOD'];

        $route = $this->findRoute($uri, $method);

        if (!$route) {
            $this->handleNotFound();
        }

        $this->executeRoute($route, $uri);
    }

    private function findRoute(string $uri, string $method): ?array
    {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['regex'], $uri)) {
                return $route;
            }
        }

        return null;
    }

    #[NoReturn] private function handleNotFound(): void
    {
        UrlTool::abort();
    }

    private function executeRoute(array $route, string $uri): void
    {
        [$controllerClass, $method] = $route['handler'];

        preg_match($route['regex'], $uri, $matches);
        $params = $this->extractParams($matches);

        try {
            $controller = $this->factory->make($controllerClass);
            $controller->$method(...array_values($params));
        } catch (RuntimeException $e) {
            // TODO: Add Logging + Ex handling
            throw $e;
        }
    }

    private function extractParams(array $matches): array
    {
        $params = [];

        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $params[$key] = is_numeric($value) ? (int) $value : $value;
            }
        }

        return $params;
    }
}
