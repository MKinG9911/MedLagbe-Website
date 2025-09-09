<?php
class Router {
    private $routes = [];

    public function get($route, $handler) {
        $this->routes['GET'][$route] = $handler;
    }

    public function post($route, $handler) {
        $this->routes['POST'][$route] = $handler;
    }

    public function resolve() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = str_replace('/MedLagbe', '', $uri);
        $uri = $uri === '' ? '/' : $uri;

        if (isset($this->routes[$method][$uri])) {
            $handler = $this->routes[$method][$uri];
            
            if (is_array($handler)) {
                [$controller, $method] = $handler;
                $controller = new $controller();
                call_user_func([$controller, $method]);
            }
        } else {
            http_response_code(404);
            echo "Page not found";
        }
    }
}
?>
