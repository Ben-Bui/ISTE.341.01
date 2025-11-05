let events = require('events');

//create an event emitter object
let eventEmmitter = new events.EventEmitter();

//listener 1
let listener1 = () =>{
    console.log("listener 1 executed");
};

//listener 2
let listener2 = () =>{
    console.log("listener 2 executed");
};

//bind listeners to events
eventEmmitter.addListener("connection", listener1);
eventEmmitter.on("connection", listener2);

let evenListeners = eventEmmitter.listenerCount('connection');
console.log(evenListeners + " listening to connection event");

eventEmmitter.emit("connection");

eventEmmitter.removeAllListeners("connection", listener1);

eventEmmitter.emit("connection");
evenListeners = eventEmmitter.listenerCount("connection");
console.log(evenListeners + " listening to connection event");

console.log("Program Ended");

// ****************************************************************
// let events = require('events');

// //create an event emitter object
// let eventEmmitter = new events.EventEmitter();

// //create an event handler
// let connectHandler = function connected(){
//     console.log('connection successful');
//     //fire off another event
//     eventEmmitter.emit('data_received');
// }

// //bind the events with handlers
// //pre-defined handler
// eventEmmitter.on('connection', connectHandler);

// //using an anonymous handler
// eventEmmitter.on('data_received', function () {
//     console.log("data received successfully");
// });

// eventEmmitter.emit("connection");
// console.log("Pogram Ended");

// **********************************************************************
// let fs = require("fs");

// //blocking example
// // let data = fs.readFileSync("input.txt");
// // console.log(data.toString());

// //nonblocking example using callback
// fs.readFile('input.txt', (err,data)=>{
//     if (err) return console.error(err);
//     console.log(data.toString());
// });
// console.log("Program Ended");


// ********************************************************************
// // let http = require("http");

// // http.createServer(
// //     (request, response)=>{
// //         response.writeHead(200,{'Content-Type':'text/plain'});
// //         response.end("Hello World \n");
// //     })
// //     .listen(8081);

// //     console.log("Server running at http://127.0.0.1:8081/");