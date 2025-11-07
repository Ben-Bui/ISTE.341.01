const express = require("express");
const cookieParser = require("cookie-parser");

const app = express();
app.use(express.static("public"));
app.use(cookieParser());
let urlencodedParser = express.urlencoded({extended: false});

app.get("/",(req,res) => {
    console.log("Got a Get request for the homepage");
    console.log("Cookies: ", req.cookies);
    res.json(req.cookies);
    //res.send('Hello GET');
});

app.get("/index.html",(req,res)=>{
    res.sendFile(__dirname + "/index.html");
});

app.get("/process_get",(req,res)=>{
    let response = {
        first_name:req.query.first_name,
        last_name:req.query.last_name
    };
    console.log(response);
    //res.end(JSON.stringify(response));
    //res.send(JSON.stringify(response));
    res.json(response)
});

app.post("/process_post", urlencodedParser,(req,res)=>{
    let response = {
        first_name: req.body.first_name,
        last_name: req.body.last_name
    };
    console.log(response);
    //res.end(JSON.stringify(response));
    //res.send(JSON.stringify(response));
    res.json(response)
});

app.post("/",(req,res) => {
    console.log("Got a Post request for the homepage");
    res.send('Hello POST');
});

app.delete("/del_user",(req,res) => {
    console.log("Got a Delete request for the homepage");
    res.send('Hello Delete');
});

app.get("/list_user ",(req,res) => {
    console.log("Got a Get request for the /list_user");
    res.send('Hello Get');
});

app.get("/ab*cd",(req,res) => {
    console.log("Got a Get request for the /ab*cd");
    res.send('Hello Get');
});

let server = app.listen(8081,()=>{
    let host = server.address().address;
    let port = server.address().port;
    console.log("Server listening at http://%s:%s", host, port);
});