const http = require('http');
const server = http.createServer((request, response)=>{
});
server.listen(1234,function(){
    console.log((new Date())+ "Server is listening on port 1234");
});

const WebSocketServer = require("websocket").server;
wsServer = new WebSocketServer({httpServer : server});

var count = 0;
var client = {};

wsServer.on('request',(request)=>{
    //run connection
    let connection = request.accept(null, request.origin);
    let id = count++;
    client[id] = connection;
    console.log((new Date())+ "Connection accepted [" + id + "]");

    connection.on("message", (message)=>{
        //should make sure only text and is UTF* for encoding before next line
        let msgString = message.utf8Data;
        for (var i in client){
            client[i].sendUTF(msgString);
        }

    });

    //user disconnected
    connection.on('close',(reasonCode,description)=>{
        delete clients[id];
        console.log((new Date())+ "User: "+ connection.remoteAddress + "disconnected");
    });
});