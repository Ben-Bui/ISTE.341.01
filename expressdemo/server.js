const express = require("express");
const app = express();

app.get("/",(req,res) => {
    console.log("Got a Get request for the homepage");
    res.send('Hello GET');
});

app.post("/",(req,res) => {
    console.log("Got a Post request for the homepage");
    res.send('Hello Post');
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