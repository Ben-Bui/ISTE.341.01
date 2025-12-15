<?php

//use the following whereever you want to use the data layer
require_once "Products.php";
require_once "Product.php";

// Get the request 
$method = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

$basePath = "/FinalService";

$path = str_replace($basePath, "", $requestUri);

$pathParts = explode("/", $path);

$pathParts = array_values(array_filter($pathParts));

$queryParams = $_GET;

header("Content-Type: application/json");

// Handle the request based on the path
if (count($pathParts) == 1) {
    if ($pathParts[0] == "CountProducts") {
        handleCountProducts();
    } 
    else if ($pathParts[0] == "Product") {
        if (isset($queryParams['descrip'])) {
            $partialDescription = $queryParams['descrip'];
            handleProductsByDescription($partialDescription);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Missing descrip parameter"]);
        }
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Endpoint not found"]);
    }
} 
// Handle UPC 
else if (count($pathParts) == 2 && $pathParts[0] == "Product") {
    $upc = $pathParts[1];
    handleProductByUpc($upc);
} 
else {
    http_response_code(404);
    echo json_encode(["error" => "Endpoint not found"]);
}

function handleCountProducts() {
    try {
        $products = new Products();
        $count = $products->getCount();

        echo json_encode(["count" => $count]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Internal server error", "message" => $e->getMessage()]);
    }
}

//GET count
function handleProductByUpc($upc) {
    // Validate UPC parameter
    if (empty($upc)) {
        http_response_code(400);
        echo json_encode(["error" => "UPC parameter is required"]);
        return;
    }
    
    try {
        $product = new Product($upc);
        $productUpc = $product->getUpc();
        if (empty($productUpc)) {
            http_response_code(404);
            echo json_encode(["error" => "Product not found for UPC: " . $upc]);
            return;
        }
       
        $description = $product->getDescription();        
        echo json_encode([
            "upc" => $productUpc,
            "description" => $description
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Internal server error", "message" => $e->getMessage()]);
    }
}

//handle get descrip
function handleProductsByDescription($partialDescription) {
    if (empty($partialDescription)) {
        http_response_code(400);
        echo json_encode(["error" => "Description parameter is required"]);
        return;
    }
    
    try {
        $products = new Products();
        $searchTerm = "%" . $partialDescription . "%";
        $upcs = $products->getUpcs($searchTerm);

        if (empty($upcs)) {
            echo json_encode([]);
            return;
        }
        
        $result = [];
        foreach ($upcs as $upc) {
            $product = new Product($upc);
            $result[] = [
                "upc" => $product->getUpc(),
                "description" => $product->getDescription()
            ];
        }
        
        echo json_encode($result);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Internal server error", "message" => $e->getMessage()]);
    }
}
?> "