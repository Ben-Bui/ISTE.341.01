package com.bui.ben;

import com.google.gson.*;
import java.util.*;
import java.net.*;
import org.apache.commons.io.IOUtils;
import java.nio.charset.StandardCharsets;
public class App 

{
    public static void main( String[] args )
    {
        //using gson object binding
        List<Park> dataset = new ArrayList<Park>();
        Park p = new Park("Letworth", "NY","Grand Canyon of the East" );
        dataset.add(p);
        p = new Park("Watkins Glen", "NY", "Gorgeous!" );
        dataset.add(p);

        GsonBuilder builder = new GsonBuilder();
        Gson gson = builder.create();
        System.out.println(gson.toJson(dataset));

        try{
            URL url = URI.create("https://ischool.gccis.rit.edu/mobile/parks.php?type=json")
                .toURL();

            Parks parks = gson.fromJson(IOUtils.toString(url.openStream(), "UTF-8"),Parks.class );

            for(Park park: parks.parks){
                System.out.println(park.parkName);
            }

        }catch (Exception e){
            System.out.println(e.getMessage());
        }

        try{

            String inStr ="""
                    {"parkName":"Test Park","parkLocation":"CA","parkDescription";"Some park in CA"}
                    """;

        }catch (Exception e){
            System.out.println(e.getMessage());
        }
    }//Main 
}
