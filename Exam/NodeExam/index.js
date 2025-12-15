const express = require("express");
const Product = require("upcbiz").Product;
const Products = require("upcbiz").Products;
const logger = require('morgan');

const app = express();

app.use(express.json());
app.use(logger('dev'));

const basePath = "/FinalService";

//get count
app.get(`${basePath}/CountProducts`, async (req, res) => {
    try {
        console.log("GET /FinalService/CountProducts called");
        const products = new Products();
        const count = await products.getCount();
        console.log("Count result:", count);
        
        res.json({ count: count });
    } catch (error) {
        console.error("Error in CountProducts:", error);
        res.status(500).json({ 
            error: "Internal server error", 
            message: error.message 
        });
    }
});

//get product upc
app.get(`${basePath}/Product/:upc`, async (req, res) => {
    try {
        const upc = req.params.upc;
        console.log(`GET /FinalService/Product/${upc} called`);
        
        if (!upc || upc.trim() === "") {
            res.status(400).json({ error: "UPC parameter is required" });
            return;
        }
        
        const product = new Product(upc);
        const description = await product.getDescription();
        const productUpc = product.getUpc();
        
        console.log("Product result:", { upc: productUpc, description: description });
        
        if (!productUpc || productUpc.trim() === "") {
            res.status(404).json({ error: `Product not found for UPC: ${upc}` });
            return;
        }
        
        res.json({
            upc: productUpc,
            description: description
        });
    } catch (error) {
        console.error(`Error in Product/${req.params.upc}:`, error);
        res.status(500).json({ 
            error: "Internal server error", 
            message: error.message 
        });
    }
});

//get produc descrip
app.get(`${basePath}/Product`, async (req, res) => {
    try {
        const partialDescription = req.query.descrip;
        console.log(`GET /FinalService/Product?descrip=${partialDescription} called`);
        
        if (!partialDescription || partialDescription.trim() === "") {
            res.status(400).json({ error: "Description parameter is required" });
            return;
        }
        
        const products = new Products();
        
        const searchTerm = `%${partialDescription}%`;
        
        const upcs = await products.getUpcs(searchTerm);
        console.log("Found UPCs:", upcs);
        
        if (!upcs || upcs.length === 0) {
            res.json([]);
            return;
        }
        
        const result = [];
        for (const upc of upcs) {
            const product = new Product(upc);
            const description = await product.getDescription();
            const productUpc = product.getUpc();
            
            result.push({
                upc: productUpc,
                description: description
            });
        }
        
        console.log("Products result:", result);
        
        res.json(result);
    } catch (error) {
        console.error("Error in Product search:", error);
        res.status(500).json({ 
            error: "Internal server error", 
            message: error.message 
        });
    }
});


app.get("/", (req, res) => {
    res.send("UPC Product Service is running. Use /FinalService endpoints.");
});

//ROUTE
const PORT = 8282;
app.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}`);
    console.log(`Test endpoints:`);
    console.log(`  http://localhost:${PORT}/FinalService/CountProducts`);
    console.log(`  http://localhost:${PORT}/FinalService/Product/0071860432157`);
    console.log(`  http://localhost:${PORT}/FinalService/Product?descrip=Cat+Collar`);
});
