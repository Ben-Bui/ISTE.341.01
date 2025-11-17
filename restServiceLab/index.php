<?php

require 'handlers.php';

$route = $_GET['route']; 

//split the route in parts 
$routeParts = explode('/', $route);

$method = $_SERVER['REQUEST_METHOD'];

//handle the request based on the method and route
switch ($method." ".$routeParts[0]) {
    case 'GET Services':
        if (isset($routeParts[1]) && $routeParts[1] === 'Products') {
            getNamesHandler();
        } else {
            http_response_code(404);
            echo "Endpoint not found";
        }
        break;
    case 'GET Products':
        if (isset($routeParts[1])) {
            if ($routeParts[1] === 'Cheapest') {
                getCheapestHandler(); //Cheapest
            } else if ($routeParts[1] === 'Costliest') {
                getCostliestHandler(); //Costliest
            } else {
                getPriceHandler($routeParts[1]); 
            }
        } else {
            http_response_code(404);
            echo "Endpoint not found";
        }
        break;
    default:
        http_response_code(404);
        echo "Endpoint not found";
        break;
}

?>