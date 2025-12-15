const express = require("express");
const Product = require("upcbiz").Product;
const Products = require("upcbiz").Products;
const logger = require('morgan');

const app = express();

// Middleware
app.use(express.json());
app.use(logger('dev'));

// Base path for our service
const basePath = "/FinalService";

/**
 * GET /FinalService/CountProducts
 * Returns the total number of products in the data store
 */
app.get(`${basePath}/CountProducts`, async (req, res) => {
    try {
        console.log("GET /FinalService/CountProducts called");
        
        // Create Products object
        const products = new Products();
        
        // Get the count
        const count = await products.getCount();
        console.log("Count result:", count);
        
        // Return as JSON
        res.json({ count: count });
    } catch (error) {
        console.error("Error in CountProducts:", error);
        res.status(500).json({ 
            error: "Internal server error", 
            message: error.message 
        });
    }
});

/**
 * GET /FinalService/Product/:upc
 * Returns the description for a specific UPC
 */
app.get(`${basePath}/Product/:upc`, async (req, res) => {
    try {
        const upc = req.params.upc;
        console.log(`GET /FinalService/Product/${upc} called`);
        
        // Validate UPC parameter
        if (!upc || upc.trim() === "") {
            res.status(400).json({ error: "UPC parameter is required" });
            return;
        }
        
        // Create Product object with the UPC
        const product = new Product(upc);
        
        // Get the description and UPC
        const description = await product.getDescription();
        const productUpc = product.getUpc();
        
        console.log("Product result:", { upc: productUpc, description: description });
        
        // Check if product exists
        if (!productUpc || productUpc.trim() === "") {
            res.status(404).json({ error: `Product not found for UPC: ${upc}` });
            return;
        }
        
        // Return as JSON
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

/**
 * GET /FinalService/Product?descrip=XXXX
 * Returns all products matching the partial description
 */
app.get(`${basePath}/Product`, async (req, res) => {
    try {
        const partialDescription = req.query.descrip;
        console.log(`GET /FinalService/Product?descrip=${partialDescription} called`);
        
        // Validate description parameter
        if (!partialDescription || partialDescription.trim() === "") {
            res.status(400).json({ error: "Description parameter is required" });
            return;
        }
        
        // Create Products object
        const products = new Products();
        
        // Add wildcards for LIKE search
        const searchTerm = `%${partialDescription}%`;
        
        // Get UPCs that match the description
        const upcs = await products.getUpcs(searchTerm);
        console.log("Found UPCs:", upcs);
        
        // If no matches found, return empty array
        if (!upcs || upcs.length === 0) {
            res.json([]);
            return;
        }
        
        // For each UPC, get the product details
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
        
        // Return as JSON
        res.json(result);
    } catch (error) {
        console.error("Error in Product search:", error);
        res.status(500).json({ 
            error: "Internal server error", 
            message: error.message 
        });
    }
});

/**
 * Default route for testing
 */
app.get("/", (req, res) => {
    res.send("UPC Product Service is running. Use /FinalService endpoints.");
});

/**
 * Start the server on port 8282
 */
const PORT = 8282;
app.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}`);
    console.log(`Test endpoints:`);
    console.log(`  http://localhost:${PORT}/FinalService/CountProducts`);
    console.log(`  http://localhost:${PORT}/FinalService/Product/0071860432157`);
    console.log(`  http://localhost:${PORT}/FinalService/Product?descrip=Cat+Collar`);
});
