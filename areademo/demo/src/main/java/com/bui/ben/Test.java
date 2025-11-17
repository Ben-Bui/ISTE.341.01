package com.bui.ben;

public class Test {
    
    public static void main(String[] args) {
        MyResource ac = new MyResource();

        System.out.println(ac.helloWorld());
        System.out.println(ac.helloWorld().getEntity());
        System.out.println("***********\n");

        System.out.println(ac.helloName("Ben"));
        System.out.println(ac.helloName("Ben").getEntity());
        System.out.println("***********\n");

        System.out.println(ac.calcRectangleXML(7.7,8.8));
        System.out.println(ac.calcRectangleXML(7.7,8.8).getEntity());
        System.out.println("***********\n");

        System.out.println(ac.calcRectangleJSON(7.7,8.8));
        System.out.println(ac.calcRectangleJSON(7.7,8.8).getEntity());
        System.out.println("***********\n");
    
        System.out.println(ac.calcCircle(3.3));
        System.out.println(ac.calcCircle(3.3).getEntity());
        System.out.println("***********\n");

        System.out.println(ac.createCircle(7));
        System.out.println(ac.createCircle(7).getLocation());
        System.out.println("***********\n");


        System.out.println(ac.updateCircle(1,
        "{\"raidus\":0,\"id\":1,\"area\":34.4}"));
        System.out.println(ac.updateCircle(1,
        "{\"raidus\":2.2,\"id\":1,\"area\":34.4}"));
        System.out.println(ac.updateCircle(1,
        "{\"raidus\":2.2,\"id\":1,\"area\":34.4}").getEntity());
        System.out.println("***********\n");

        System.out.println(ac.deleteCircle(1));
        System.out.println(ac.deleteCircle(1).getEntity());
        System.out.println("***********\n");
    }//Main

}//Test
