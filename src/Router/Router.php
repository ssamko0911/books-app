<?php declare(strict_types=1);

namespace App\Router;
use App\Utils\UrlTool;
use JetBrains\PhpStorm\NoReturn;
use PDO;

class Router
{
    private array $routes = [];
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
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
        $regex = preg_replace('#\{[a-zA-Z_]+\}#', '([0-9]+)', $pattern);

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

        $controller = new $controllerClass($this->connection);
        $controller->$method(...array_values($params));
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
