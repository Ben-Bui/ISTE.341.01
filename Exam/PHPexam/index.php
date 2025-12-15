<?php
// Main entry point for PHP RESTful service

// Include the data layer classes
require_once "Products.php";
require_once "Product.php";

// Get the request method and URI
$method = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

// Base path for our service
$basePath = "/FinalService";

// Remove the base path from the request URI
$path = str_replace($basePath, "", $requestUri);

// Parse the path to get the endpoint
$pathParts = explode("/", $path);

// Remove empty elements
$pathParts = array_values(array_filter($pathParts));

// Get query parameters if any
$queryParams = $_GET;

// Set content type to JSON
header("Content-Type: application/json");

// Handle the request based on the path
if (count($pathParts) == 1) {
    // Check for CountProducts endpoint
    if ($pathParts[0] == "CountProducts") {
        handleCountProducts();
    } 
    // Check for Product endpoint with query parameter
    else if ($pathParts[0] == "Product") {
        if (isset($queryParams['descrip'])) {
            $partialDescription = $queryParams['descrip'];
            handleProductsByDescription($partialDescription);
        } else {
            // No description parameter provided
            http_response_code(400);
            echo json_encode(["error" => "Missing descrip parameter"]);
        }
    } else {
        // Invalid endpoint
        http_response_code(404);
        echo json_encode(["error" => "Endpoint not found"]);
    }
} 
// Handle Product with UPC parameter
else if (count($pathParts) == 2 && $pathParts[0] == "Product") {
    $upc = $pathParts[1];
    handleProductByUpc($upc);
} 
else {
    // Invalid path
    http_response_code(404);
    echo json_encode(["error" => "Endpoint not found"]);
}

/**
 * Handle GET /FinalService/CountProducts
 * Returns the total number of products in the data store
 */
function handleCountProducts() {
    try {
        // Create Products object
        $products = new Products();
        
        // Get the count
        $count = $products->getCount();
        
        // Return as JSON
        echo json_encode(["count" => $count]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Internal server error", "message" => $e->getMessage()]);
    }
}

/**
 * Handle GET /FinalService/Product/{upc}
 * Returns the description for a specific UPC
 */
function handleProductByUpc($upc) {
    // Validate UPC parameter
    if (empty($upc)) {
        http_response_code(400);
        echo json_encode(["error" => "UPC parameter is required"]);
        return;
    }
    
    try {
        // Create Product object with the UPC
        $product = new Product($upc);
        
        // Check if product exists (empty UPC means not found)
        $productUpc = $product->getUpc();
        if (empty($productUpc)) {
            http_response_code(404);
            echo json_encode(["error" => "Product not found for UPC: " . $upc]);
            return;
        }
        
        // Get the description
        $description = $product->getDescription();
        
        // Return as JSON
        echo json_encode([
            "upc" => $productUpc,
            "description" => $description
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Internal server error", "message" => $e->getMessage()]);
    }
}

/**
 * Handle GET /FinalService/Product?descrip=XXXX
 * Returns all products matching the partial description
 */
function handleProductsByDescription($partialDescription) {
    // Validate description parameter
    if (empty($partialDescription)) {
        http_response_code(400);
        echo json_encode(["error" => "Description parameter is required"]);
        return;
    }
    
    try {
        // Create Products object
        $products = new Products();
        
        // Add wildcards for LIKE search
        $searchTerm = "%" . $partialDescription . "%";
        
        // Get UPCs that match the description
        $upcs = $products->getUpcs($searchTerm);
        
        // If no matches found, return empty array
        if (empty($upcs)) {
            echo json_encode([]);
            return;
        }
        
        // For each UPC, get the product details
        $result = [];
        foreach ($upcs as $upc) {
            $product = new Product($upc);
            $result[] = [
                "upc" => $product->getUpc(),
                "description" => $product->getDescription()
            ];
        }
        
        // Return as JSON
        echo json_encode($result);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Internal server error", "message" => $e->getMessage()]);
    }
}
?> "