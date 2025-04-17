<?php
//this brings all our web app functionalities together making it work
//handles our url requests


// show($_SERVER['REQUEST_URI']) . PHP_EOL;
// $url = $_GET['url'] ? 'Home' : (!$_GET['url'] ? 'Home' : 'Not working');

// show($_GET);
// $url = $_GET['url'] ?? 'Home';

// $url = !empty($_GET['url']) ? $_GET['url'] : 'Home';
// $url = isset($_GET['url']) ? $_GET['url'] : 'Home';

// $url = $_GET['url'];
// var_dump($url);

// $url =  explode("/", $url);
// show($url);


class App
{
    private $controller = 'Home';
    private $methods = 'index';

    private function splitUrl()
    {
        $url = $_GET['url'] ?? 'Home';
        $url = explode("/", trim($url, "/"));
        return $url;
    }


    public function loadControllerWithMethod($controller, $method, $params = [])
    {
        $filename = "../app/controllers/" . ucfirst($controller) . ".php";
        if (file_exists($filename)) {
            require $filename;
            $this->controller = ucfirst($controller);
        } else {
            $filename = "../app/controllers/_404.php";
            require $filename;
            $this->controller = '_404';
        }

        $control = new $this->controller;

        if (method_exists($control, $method)) {
            $this->methods = $method;
        } else {
            $this->methods = 'index'; // Default method
        }

        call_user_func_array([$control, $this->methods], $params);
    }

    public function loadController()
    {
        $url = $this->splitUrl();
        $this->loadControllerWithMethod($url[0], $url[1] ?? 'index', array_slice($url, 2));
    }
}

