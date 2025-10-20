package com.bui.ben;

//dummy class to simulate getting something from a db
public class Circle {
    public double radius;
    public int id;
    public double area;

    public Circle(double r) {
        id = 1;//normally would come from the database
        radius = r;
        area = Math.PI*radius*radius;
    }

    public Circle(){
        //needed for deserialization
    }
}
 