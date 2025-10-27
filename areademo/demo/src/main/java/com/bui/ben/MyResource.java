package com.bui.ben;

import jakarta.ws.rs.*;
import jakarta.ws.rs.core.*;

import com.bui.ben.business.*;
import java.io.*;
import jakarta.json.*;
    
/**
 * Root resource (exposed at "myresource" path)
 */
@Path("AreaCalculator")
public class MyResource {

    @Context
    UriInfo uriInfo;

    /**
     * Method handling HTTP GET requests. The returned object will be sent
     * to the client as "text/plain" media type.
     *
     * @return String that will be returned as a text/plain response.
     */
    @GET
    @Produces(MediaType.TEXT_PLAIN)
    public String getIt() {
        return "Got it!";
    }

    @Path("Hello")
    @GET
    @Produces(MediaType.APPLICATION_JSON)
    public Response helloWorld() {
        //use business layer
        BusinessLayer bl = new BusinessLayer();
        return Response.ok("{\"response\": \"" + 
        bl.sayHello()+ "\"}").build();
    }

    @Path("Hello/{name}")
    @GET
    @Produces(MediaType.APPLICATION_JSON)
    public Response helloName(@PathParam("name") String name) {
  
        return Response.ok("{\"hi\": \"" + 
            name+ "\"}").build();
    }

    @Path("Rectangle")
    @GET
    @Produces(MediaType.APPLICATION_XML)
    public Response calcRectangleXML(
        @DefaultValue("1") @QueryParam("width") double width,
        @DefaultValue("1") @QueryParam("length") double length
    ) {
        return Response.ok("<area>"+(width*length)+"</area>").build();
    }

    @Path("Rectangle")
    @GET
    @Produces(MediaType.APPLICATION_JSON)
    public Response calcRectangleJSON(
        @DefaultValue("1") @QueryParam("width") double width,
        @DefaultValue("1") @QueryParam("length") double length
    ) {
        return Response.ok("{\"area\":"+(width*length)+"}").build();
    }

    @Path("Circle")
    @GET
    @Produces(MediaType.APPLICATION_JSON)
    public Response calcCircle(
        @QueryParam("radius") double radius
    ) {
        return Response.ok("{\"area\":"+(radius*radius*Math.PI)+"}").build();
    }

    @Path("Circle")
    @POST
    @Consumes(MediaType.APPLICATION_FORM_URLENCODED)
    @Produces(MediaType.APPLICATION_JSON)
    public Response createCircle(
        @FormParam("radius") double radius
    ) {
        //validate input, create object and insert in database
        //we're assuming returning the id of 1 and a valid link
        
        Circle c = new Circle(radius);

        //comment out the following for non-deploy testing
        Link lnk = Link.fromUri(uriInfo.getPath()+"/"+c.id)
            .rel("self").build();
        // Link lnk = Link.fromUri("http://localhost:8080/Circle/"+c.id)
        //     .rel("self").build();

            return Response.status(Response.Status.CREATED)
                .location(lnk.getUri()).build();
    }

    @Path("Circle/{id}")
    @PUT
    @Consumes(MediaType.APPLICATION_JSON)
    @Produces(MediaType.APPLICATION_JSON)
    public Response updateCircle(
        @PathParam("id") int id,
        String circleIn
    ) {
        //body/data comes in as a JSON string
        //do pasing and validation and update the datastore
        JsonReader rdr = Json.createReader(new StringReader(circleIn));

        try(rdr){
            JsonObject obj = rdr.readObject();
            var wrapper = new Object() {String retvalue = "\n";};

            obj.keySet().forEach(key ->{
                Object value = obj.get(key);
                wrapper.retvalue += " Key: "+key+"\tValye: "
                    + value + "\n";
            });

            //assume it exist in the db 
            boolean exist = true;

            if (!exist) {
                return Response.status(Response.Status.NOT_FOUND).build();
            }

            if (obj.getJsonNumber("radius").doubleValue()<=0){
                return Response.status(Response.Status.NOT_FOUND).build();           
            }
            //update the db
            //makre sure we're return a valid JSON string
            return Response.ok("Circle Update: "+wrapper.retvalue).build();
        
        }//try
    
    }//Update Circle



    @Path("Circle/{id}")
    @DELETE
    @Produces(MediaType.APPLICATION_JSON)
    public Response deleteCircle(
        @PathParam("id") int id
    ) {
        //do pasing and validation and update the datastore

        //assume it exist in the db 
        boolean exist = true;

        if (!exist) {
            return Response.status(Response.Status.NOT_FOUND).build();
        }

        //Delete from the db
        //should return a valid JSON string
        return Response.ok("Circle deleted: "+id).build();
        
    }//delete circle
}
