let http = require("http");
let fs = require("fs");
let url = require("url")

http.createServer((request,response)=>{
    
    //parse the request to get the filename
    let pathname = url.parse(request.url).pathname;

    console.log("Requeste for "+pathname+" received.");

    fs.readFile(pathname.substring(1),(err,data)=>{
        if(err){
            console.error(err);
            response.writeHead(404,{"Content-Type":"text/html"});
            response.write("<html><body>File Not Found</body></html>");
        }else{
            response.writeHead(200, {"Content-Type": "text/html"});
            response.write(data.toString());
        }
        response.end();
    });
})
.listen(8081);
console.log("Server listening on 8081");