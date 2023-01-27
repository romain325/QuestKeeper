<?php

class Router {

    private array $router;

    /**
     * @param mixed $router
     */
    public function setRouter($router)
    {
        $this->router = $router;
    }

    public function render() : string {
        $requestUri = $_SERVER["REQUEST_URI"];
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $request = $requestMethod . $requestUri;

        if($this->router[$request]) {
            return $this->router[$request]();
        } else {
            http_response_code(404);
            include("src/404.html");
            die();
        }
    }
}