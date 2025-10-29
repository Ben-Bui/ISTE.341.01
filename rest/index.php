<?php

require 'handlers.php';

$route = $_GET['route']; //from modrewrite

//split the route in parts e.g. /products/123 -> ['products', 123]
$routeParts = explode('/', $route);

$method = $_SERVER['REQUEST_METHOD'];

//handle the request based on the method and route
switch ($method." ".$routeParts[0]) {
    case 'GET products':
        $id = isset($routeParts[1]) ? $routeParts[1] : "";
        getProductsHandler($id);
        break;
    case 'PSOT products':
        createProductsHandler();
        break;
    case 'PUT products':
        $id = isset($routeParts[1]) ? $routeParts[1] : "";
        //if id = "" should  return 404 or 400 bad request
        if ($id =="") {
            http_response_code(404);
            echo "Product not found";
        }else{
            updateProductsHandler($id); 
        }
        break;
    case 'PUT products':
        $id = isset($routeParts[1]) ? $routeParts[1] : "";
        //if id = "" should  return 404 or 400 bad request
        deleteProductsHandler($id);
        break;
    default:
        http_response_code(404);
        echo "Endpoint not found";
        break;
}


?>