var express = require("express");
var Product = require("upcbiz").Product;
var Products = require("upcbiz").Products;
var logger = require('morgan');

var app = express();

//Since the data layer is asynchronous, make sure
//your callback functions for each route is async as 
//well and then await the call to the data layer methods
//also, console.log the results of the data layer
//calls to see format of the data.

app.get("/",async function(req,res){
    //let data = await callToDataLayerFunction
});

app.listen(8282);
console.log('Express started on port 8282');

