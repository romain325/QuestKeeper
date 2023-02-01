<?php

class Router {

    private array $router;
    private array $authedRouter;

    /**
     * @param mixed $router
     */
    public function setRouter(array $router)
    {
        $this->router = $router;
    }

    /**
     * @param array $authedRouter
     */
    public function setAuthedRouter(array $authedRouter): void
    {
        $this->authedRouter = $authedRouter;
    }



    public function render() : string {
        $requestUri = $_SERVER["REQUEST_URI"];
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $request = $requestMethod . $requestUri;

        if($this->router[$request]) {
            return $this->router[$request]();
        }

        if($this->authedRouter[$request]){
            if(isConnected()) {
                return $this->authedRouter[$request]();
            } else {
                http_response_code(301);
                die();
            }
        } else {
            http_response_code(404);
            include("src/404.html");
            die();
        }
    }
}