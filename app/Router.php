<?php
class Router {
    public function handleRequest() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        switch ($path) {
            case '/archive':
            case '/archive/home':
                Controller::render('home');
                break;
            case '/archive/check-email':
                Controller::render('check-email');
                break;
            case '/archive/check-ip':
                Controller::render('check-ip');  
                break;   
            case '/archive/calculate-money':
                Controller::render('calculate-money');  
                break;   
            default:
                Controller::render('404');
                break;
        }
    }
}
?>