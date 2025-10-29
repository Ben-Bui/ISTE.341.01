<?php 

//normally interact with a DB, hard-coding for now
//have a function for each route
//post
function getProductsHandler($id="") {
    //create dummy data
    $products = [];
    for ($i=1; $i<=5; $i++){
        $products[]= ['id'=>$i,'name'=>"Product $i"];
    }

    if ($id===""){
        header("Content-Type: application/json");
        echo json_encode($products);
    }else{
        $products = findElementByKeyValue($products,"id",intval($id));
        if ($products != null){
            header("Content-Type: application/json");
            echo json_encode($products);
        }else{
            http_response_code(404);
            echo "Product not found";
        }
    }

}//getProductsHandler

//post
function createProductHandler() {
    $contentType = $_SERVER['CONTENT_TYPE'];

    if($contentType == "application/json"){
        $data =  json_decode(file_get_contents('php://input'),true); //gets as associative array
    }else{
        //assume x-ww-form-urlencoded
        $data = $_POST;
    }
    //validate and process the data
    //create dummy item
    $products = ['id'=>6, 'name'=>$data['name']];
    http_response_code(201);
    header("Content-Type: application/json");
    echo json_encode($products);    

}//create Product

//put
function updateProductsHandler($id="") {
    
    $data =  json_decode(file_get_contents('php://input'),true); //gets as associative array

    //validate and process the data
    //create dummy item
    http_response_code(201);
    header("Content-Type: application/json");
    echo json_encode($data);//just echoing back for now 

}//updateProductsHandler

//delete
function deleteProductsHandler($id="") {
    //delete not allowed on Solace
    //delete from database
    http_response_code(201);//or 200
    header("Content-Type: application/json");
    json_encode(["response" => "Product $id deleted"]);//just echoing back for now 

}//deleteProductsHandler

function findElementByKeyValue($array,$key,$value){
    foreach ($array as $item){
        if (isset($item[$key]) && $item[$key] ===$value){
            return $item;
        }
    }
    return null; //not found
}//findElementByKeyValue