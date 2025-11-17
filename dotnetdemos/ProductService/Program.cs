using System.Collections.Generic;
using ProductService.Models;

var products = new List<Product>();

for (int i=1; i<=5; i++)
{
    products.Add(new Product() {Name = "Product " + i, Id = i});
}


var builder = WebApplication.CreateBuilder(args);

// Add services to the container.
// Learn more about configuring OpenAPI at https://aka.ms/aspnet/openapi
builder.Services.AddOpenApi();

var app = builder.Build();

// Configure the HTTP request pipeline.
if (app.Environment.IsDevelopment())
{
    app.MapOpenApi();
}

app.UseSwaggerUI(options =>
{
    options.SwaggerEndpoint("/openapi/v1.json", "v1");
});

app.UseHttpsRedirection();


app.MapGet("/",()=> "Hello World");

app.MapGet("/Product", () =>  products);

app.MapGet("/Product/{id}", (int id) =>  
{
    Product product = products.Find(x => x.Id == id)!; 
    
    if (product == null)
    {
        return Results.NotFound();
    }

    return Results.Ok(product);
});

app.MapPut("/Product", (Product product) =>  
{
    //validation
    bool isValid = !String.IsNullOrWhiteSpace(product.Name) &&
            product.Name.Length < 40 ? true : false;
   
    if(product == null || !isValid)
    {
        return Results.BadRequest();
    }

    //normally insert into db and return a valid link
    product.Id = 6;
    return Results.Created($"Product/{product.Id}", product);
});

app.MapPost("/Product", (int id, Product product) =>  
{
    //validation
    bool isValid = !String.IsNullOrWhiteSpace(product.Name) &&
            product.Name.Length < 40 ? true : false;
   
    if(product == null || !isValid)
    {
        return Results.BadRequest();
    }

    if(!products.Exists( x => x.Id == id))
    {
        return Results.NotFound();
    }
    
    //normally update
    product.Id = 6;
    return Results.NoContent();
});

app.MapDelete("/Product/{id}", (int id) =>  
{
    
    if(!products.Exists( x => x.Id == id))
    {
        return Results.NotFound();
    }
    
    //normally insert into db and return a valid link

    return Results.NoContent();
});

app.MapGet("/Product2/{id}", (int id) =>  
{
    Product product = products.Find(x => x.Id == id)!;
    
    if (product == null)
    {
        return Results.NotFound();
    }

    //if properties of object are private, can't return object like in /Product/{id}
    //or will get an empty json{}, so need to build a dictionary and send that back
    var returnPod = new Dictionary<string,object>
    {
            {"id",product.Id},
            {"name",product.Name}
    };

    return Results.Ok(returnPod);
});

app.Run();

