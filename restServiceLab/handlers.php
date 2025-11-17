<?php 

// Product data stored in memory 
$products = [
    ['name' => 'Apples', 'price' => 3.99],
    ['name' => 'Peaches', 'price' => 4.05],
    ['name' => 'Pumpkin', 'price' => 13.99],
    ['name' => 'Pie', 'price' => 8.00]
];

//returns all product names
function getNamesHandler() {
    global $products;
    
    $names = []; 
    
    //loop extract names
    foreach($products as $product){
        $names[] = $product['name']; 
    }
    
    header("Content-Type: application/json");
    echo json_encode($names); 
    
}//getNamesHandler

// returns price for product
function getPriceHandler($productName="") {
    global $products;
    
    if ($productName === ""){
        http_response_code(400);
        echo "Product name required";
        return;
    }
    
    $foundProduct = null;
    
    //search products to find matching name
    foreach($products as $product){
        if (strtolower($product['name']) === strtolower($productName)){
            $foundProduct = $product; //store found product
            break; //exit loop when found
        }
    }
    
    if ($foundProduct != null){
        header("Content-Type: application/json");
        echo json_encode($foundProduct); //return product with price
    }else{
        //return not found 
        header("Content-Type: application/json");
        echo json_encode(['name' => 'Not Found', 'price' => 0]);
    }
    
}//getPriceHandler

//returns cheapest product
function getCheapestHandler() {
    global $products;
    
    if (empty($products)) {
        header("Content-Type: application/json");
        echo json_encode(['name' => 'Not Found', 'price' => 0]);
        return;
    }
    
    $cheapest = $products[0]; //start with first product
    
    //loop to find cheapest
    foreach($products as $product){
        if ($product['price'] < $cheapest['price']){
            $cheapest = $product; //update if cheaper found
        }
    }
    
    header("Content-Type: application/json");
    echo json_encode($cheapest); //return cheapest product
    
}//getCheapestHandler

// returns most expensive product
function getCostliestHandler() {
    global $products;
    
    if (empty($products)) {
        header("Content-Type: application/json");
        echo json_encode(['name' => 'Not Found', 'price' => 0]);
        return;
    }
    
    $costliest = $products[0]; 
    
    foreach($products as $product){
        if ($product['price'] > $costliest['price']){
            $costliest = $product; 
        }
    }
    
    header("Content-Type: application/json");
    echo json_encode($costliest); 
    
}//getCostliestHandler

//find product by name
function findProductByName($productName){
    global $products;
    
    foreach($products as $product){
        if (strtolower($product['name']) === strtolower($productName)){
            return $product; 
        }
    }
    return null;
    
}//findProductByName

?>