<?php
/**
 * Router Class - URL Routing and Dispatching
 */

class Router {
    private $routes = [];
    private $basePath = '';

    public function __construct($basePath = '') {
        $this->basePath = $basePath;
    }

    public function get($path, $handler) {
        $this->addRoute('GET', $path, $handler);
    }

    public function post($path, $handler) {
        $this->addRoute('POST', $path, $handler);
    }

    public function put($path, $handler) {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete($path, $handler) {
        $this->addRoute('DELETE', $path, $handler);
    }

    private function addRoute($method, $path, $handler) {
        $path = $this->basePath . $path;
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestUri = rtrim($requestUri, '/');

        if (isset($this->routes[$requestMethod])) {
            foreach ($this->routes[$requestMethod] as $path => $handler) {
                $pattern = $this->convertToRegex($path);
                if (preg_match($pattern, $requestUri, $matches)) {
                    array_shift($matches); // Remove full match
                    $this->callHandler($handler, $matches);
                    return;
                }
            }
        }

        // Default route for public homepage
        if ($requestUri === '' || $requestUri === '/') {
            $this->callHandler('PublicController@index', []);
            return;
        }

        // 404 Not Found
        http_response_code(404);
        echo "404 Not Found";
    }

    private function convertToRegex($path) {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $path);
        return '/^' . str_replace('/', '\/', $pattern) . '$/';
    }

    private function callHandler($handler, $params) {
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
        } elseif (is_string($handler)) {
            list($controller, $method) = explode('@', $handler);
            $controllerFile = CONTROLLERS_PATH . $controller . '.php';

            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                $controllerInstance = new $controller();
                if (method_exists($controllerInstance, $method)) {
                    call_user_func_array([$controllerInstance, $method], $params);
                } else {
                    throw new Exception("Method {$method} not found in {$controller}");
                }
            } else {
                throw new Exception("Controller {$controller} not found");
            }
        }
    }
}