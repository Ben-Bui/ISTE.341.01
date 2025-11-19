package com.project.two;

import jakarta.ws.rs.*;
import jakarta.ws.rs.core.*;
import com.project.two.business.BusinessLayer;
import companydata.*;
import java.util.*;
import java.io.*;
import jakarta.json.*;

@Path("CompanyServices")
public class MyResource {

    @Context
    UriInfo uriInfo;

    @GET
    @Produces(MediaType.TEXT_PLAIN)
    public String getIt() {
        return "Got it!";
    }

    //DELETE all company data
    @Path("company")
    @DELETE
    @Produces(MediaType.APPLICATION_JSON)
    public Response deleteCompany(@QueryParam("company") String company) {
        BusinessLayer bl = new BusinessLayer(company);
        try {
            int rowsDeleted = bl.deleteCompany();
            bl.close();
            return Response.ok("{\"success\":\"" + company + "'s information deleted.\"}").build();
        } catch (Exception e) {
            bl.close();
            return Response.status(Response.Status.BAD_REQUEST).entity("{\"error\":\"" + e.getMessage() + "\"}").build();
        }
    }

    //GET single department
    @Path("department")
    @GET
    @Produces(MediaType.APPLICATION_JSON)
    public Response getDepartment(@QueryParam("company") String company, @QueryParam("dept_id") int dept_id) {
        BusinessLayer bl = new BusinessLayer(company);
        try {
            Department dept = bl.getDepartment(dept_id);
            bl.close();
            
            if (dept != null) {
                String json = "{\"dept_id\":" + dept.getId() + ",\"company\":\"" + dept.getCompany() + "\",\"dept_name\":\"" + dept.getDeptName() + "\",\"dept_no\":\"" + dept.getDeptNo() + "\",\"location\":\"" + dept.getLocation() + "\"}";
                return Response.ok(json).build();
            } else {
                return Response.status(Response.Status.NOT_FOUND).entity("{\"error\":\"Department not found\"}").build();
            }
        } catch (Exception e) {
            bl.close();
            return Response.status(Response.Status.BAD_REQUEST).entity("{\"error\":\"" + e.getMessage() + "\"}").build();
        }
    }

    //Get all departments
    @Path("departments")
    @GET
    @Produces(MediaType.APPLICATION_JSON)
    public Response getDepartments(@QueryParam("company") String company) {
        BusinessLayer bl = new BusinessLayer(company);
        try {
            List<Department> departments = bl.getAllDepartments();
            bl.close();
            
            StringBuilder json = new StringBuilder("[");
            for (int i = 0; i < departments.size(); i++) {
                Department dept = departments.get(i);
                json.append("{\"dept_id\":" + dept.getId() + ",\"company\":\"" + dept.getCompany() + "\",\"dept_name\":\"" + dept.getDeptName() + "\",\"dept_no\":\"" + dept.getDeptNo() + "\",\"location\":\"" + dept.getLocation() + "\"}");
                if (i < departments.size() - 1) {
                    json.append(",");
                }
            }
            json.append("]");
            
            return Response.ok(json.toString()).build();
        } catch (Exception e) {
            bl.close();
            return Response.status(Response.Status.BAD_REQUEST).entity("{\"error\":\"" + e.getMessage() + "\"}").build();
        }
    }

    //PUT department
    @Path("department")
    @PUT
    @Consumes(MediaType.APPLICATION_JSON)
    @Produces(MediaType.APPLICATION_JSON)
    public Response updateDepartment(String jsonInput) {
        BusinessLayer bl = null;
        try (JsonReader reader = Json.createReader(new StringReader(jsonInput))) {
            JsonObject obj = reader.readObject();
            String company = obj.getString("company");
            int dept_id = obj.getInt("dept_id");
            String dept_name = obj.getString("dept_name");
            String dept_no = obj.getString("dept_no");
            String location = obj.getString("location");
            
            bl = new BusinessLayer(company);
            Department updatedDept = bl.updateDepartment(dept_id, dept_name, dept_no, location);
            bl.close();
            
            String responseJson = "{\"success\":{\"dept_id\":" + updatedDept.getId() + ",\"company\":\"" + updatedDept.getCompany() + "\",\"dept_name\":\"" + updatedDept.getDeptName() + "\",\"dept_no\":\"" + updatedDept.getDeptNo() + "\",\"location\":\"" + updatedDept.getLocation() + "\"}}";
            return Response.ok(responseJson).build();
        } catch (Exception e) {
            if (bl != null) bl.close();
            return Response.status(Response.Status.BAD_REQUEST).entity("{\"error\":\"" + e.getMessage() + "\"}").build();
        }
    }

    //POST department
    @Path("department")
    @POST
    @Consumes(MediaType.APPLICATION_FORM_URLENCODED)
    @Produces(MediaType.APPLICATION_JSON)
    public Response createDepartment(@FormParam("company") String company, @FormParam("dept_name") String dept_name, @FormParam("dept_no") String dept_no, @FormParam("location") String location) {
        BusinessLayer bl = new BusinessLayer(company);
        try {
            Department createdDept = bl.createDepartment(dept_name, dept_no, location);
            bl.close();
            
            String responseJson = "{\"success\":{\"dept_id\":" + createdDept.getId() + ",\"company\":\"" + createdDept.getCompany() + "\",\"dept_name\":\"" + createdDept.getDeptName() + "\",\"dept_no\":\"" + createdDept.getDeptNo() + "\",\"location\":\"" + createdDept.getLocation() + "\"}}";
            return Response.ok(responseJson).build();
        } catch (Exception e) {
            bl.close();
            return Response.status(Response.Status.BAD_REQUEST).entity("{\"error\":\"" + e.getMessage() + "\"}").build();
        }
    }

    //DELETE department
    @Path("department")
    @DELETE
    @Produces(MediaType.APPLICATION_JSON)
    public Response deleteDepartment(@QueryParam("company") String company, @QueryParam("dept_id") int dept_id) {
        BusinessLayer bl = new BusinessLayer(company);
        try {
            int rowsDeleted = bl.deleteDepartment(dept_id);
            bl.close();
            
            if (rowsDeleted > 0) {
                return Response.ok("{\"success\":\"Department " + dept_id + " from " + company + " deleted.\"}").build();
            } else {
                return Response.status(Response.Status.NOT_FOUND).entity("{\"error\":\"Department not found\"}").build();
            }
        } catch (Exception e) {
            bl.close();
            return Response.status(Response.Status.BAD_REQUEST).entity("{\"error\":\"" + e.getMessage() + "\"}").build();
        }
    }

    //GET single employee
    @Path("employee")
    @GET
    @Produces(MediaType.APPLICATION_JSON)
    public Response getEmployee(@QueryParam("company") String company, @QueryParam("emp_id") int emp_id) {
        BusinessLayer bl = new BusinessLayer(company);
        try {
            Employee emp = bl.getEmployee(emp_id);
            bl.close();
            
            if (emp != null) {
                java.text.SimpleDateFormat sdf = new java.text.SimpleDateFormat("yyyy-MM-dd");
                String hireDate = sdf.format(emp.getHireDate());
                
                String json = "{\"emp_id\":" + emp.getId() + ",\"emp_name\":\"" + emp.getEmpName() + "\",\"emp_no\":\"" + emp.getEmpNo() + "\",\"hire_date\":\"" + hireDate + "\",\"job\":\"" + emp.getJob() + "\",\"salary\":" + emp.getSalary() + ",\"dept_id\":" + emp.getDeptId() + ",\"mng_id\":" + emp.getMngId() + "}";
                return Response.ok(json).build();
            } else {
                return Response.status(Response.Status.NOT_FOUND).entity("{\"error\":\"Employee not found\"}").build();
            }
        } catch (Exception e) {
            bl.close();
            return Response.status(Response.Status.BAD_REQUEST).entity("{\"error\":\"" + e.getMessage() + "\"}").build();
        }
    }

    //GET all employees
    @Path("employees")
    @GET
    @Produces(MediaType.APPLICATION_JSON)
    public Response getEmployees(@QueryParam("company") String company) {
        BusinessLayer bl = new BusinessLayer(company);
        try {
            List<Employee> employees = bl.getAllEmployees();
            bl.close();
            
            StringBuilder json = new StringBuilder("[");
            java.text.SimpleDateFormat sdf = new java.text.SimpleDateFormat("yyyy-MM-dd");
            
            for (int i = 0; i < employees.size(); i++) {
                Employee emp = employees.get(i);
                String hireDate = sdf.format(emp.getHireDate());
                
                json.append("{\"emp_id\":" + emp.getId() + ",\"emp_name\":\"" + emp.getEmpName() + "\",\"emp_no\":\"" + emp.getEmpNo() + "\",\"hire_date\":\"" + hireDate + "\",\"job\":\"" + emp.getJob() + "\",\"salary\":" + emp.getSalary() + ",\"dept_id\":" + emp.getDeptId() + ",\"mng_id\":" + emp.getMngId() + "}");
                if (i < employees.size() - 1) {
                    json.append(",");
                }
            }
            json.append("]");
            
            return Response.ok(json.toString()).build();
        } catch (Exception e) {
            bl.close();
            return Response.status(Response.Status.BAD_REQUEST).entity("{\"error\":\"" + e.getMessage() + "\"}").build();
        }
    }

    //POST employee
    @Path("employee")
    @POST
    @Consumes(MediaType.APPLICATION_FORM_URLENCODED)
    @Produces(MediaType.APPLICATION_JSON)
    public Response createEmployee(@FormParam("company") String company, @FormParam("emp_name") String emp_name, @FormParam("emp_no") String emp_no, @FormParam("hire_date") String hire_date, @FormParam("job") String job, @FormParam("salary") double salary, @FormParam("dept_id") int dept_id, @FormParam("mng_id") int mng_id) {
        BusinessLayer bl = new BusinessLayer(company);
        try {
            Employee createdEmp = bl.createEmployee(emp_name, emp_no, hire_date, job, salary, dept_id, mng_id);
            bl.close();
            
            String responseJson = "{\"success\":{\"emp_id\":" + createdEmp.getId() + ",\"emp_name\":\"" + createdEmp.getEmpName() + "\",\"emp_no\":\"" + createdEmp.getEmpNo() + "\",\"hire_date\":\"" + hire_date + "\",\"job\":\"" + createdEmp.getJob() + "\",\"salary\":" + createdEmp.getSalary() + ",\"dept_id\":" + createdEmp.getDeptId() + ",\"mng_id\":" + createdEmp.getMngId() + "}}";
            return Response.ok(responseJson).build();
        } catch (Exception e) {
            bl.close();
            return Response.status(Response.Status.BAD_REQUEST).entity("{\"error\":\"" + e.getMessage() + "\"}").build();
        }
    }

    //PUT employee
    @Path("employee")
    @PUT
    @Consumes(MediaType.APPLICATION_JSON)
    @Produces(MediaType.APPLICATION_JSON)
    public Response updateEmployee(String jsonInput) {
        BusinessLayer bl = null;
        try (JsonReader reader = Json.createReader(new StringReader(jsonInput))) {
            JsonObject obj = reader.readObject();
            String company = obj.getString("company");
            int emp_id = obj.getInt("emp_id");
            String emp_name = obj.getString("emp_name");
            String emp_no = obj.getString("emp_no");
            String hire_date = obj.getString("hire_date");
            String job = obj.getString("job");
            double salary = obj.getJsonNumber("salary").doubleValue();
            int dept_id = obj.getInt("dept_id");
            int mng_id = obj.getInt("mng_id");
            
            bl = new BusinessLayer(company);
            Employee updatedEmp = bl.updateEmployee(emp_id, emp_name, emp_no, hire_date, job, salary, dept_id, mng_id);
            bl.close();
            
            String responseJson = "{\"success\":{\"emp_id\":" + updatedEmp.getId() + ",\"emp_name\":\"" + updatedEmp.getEmpName() + "\",\"emp_no\":\"" + updatedEmp.getEmpNo() + "\",\"hire_date\":\"" + hire_date + "\",\"job\":\"" + updatedEmp.getJob() + "\",\"salary\":" + updatedEmp.getSalary() + ",\"dept_id\":" + updatedEmp.getDeptId() + ",\"mng_id\":" + updatedEmp.getMngId() + "}}";
            return Response.ok(responseJson).build();
        } catch (Exception e) {
            if (bl != null) bl.close();
            return Response.status(Response.Status.BAD_REQUEST).entity("{\"error\":\"" + e.getMessage() + "\"}").build();
        }
    }

    //DELETE employee
    @Path("employee")
    @DELETE
    @Produces(MediaType.APPLICATION_JSON)
    public Response deleteEmployee(@QueryParam("company") String company, @QueryParam("emp_id") int emp_id) {
        BusinessLayer bl = new BusinessLayer(company);
        try {
            int rowsDeleted = bl.deleteEmployee(emp_id);
            bl.close();
            
            if (rowsDeleted > 0) {
                return Response.ok("{\"success\":\"Employee " + emp_id + " deleted.\"}").build();
            } else {
                return Response.status(Response.Status.NOT_FOUND).entity("{\"error\":\"Employee not found\"}").build();
            }
        } catch (Exception e) {
            bl.close();
            return Response.status(Response.Status.BAD_REQUEST).entity("{\"error\":\"" + e.getMessage() + "\"}").build();
        }
    }

    // GET single timecard
    @Path("timecard")
    @GET
    @Produces(MediaType.APPLICATION_JSON)
    public Response getTimecard(@QueryParam("company") String company, @QueryParam("timecard_id") int timecard_id) {
        BusinessLayer bl = new BusinessLayer(company);
        try {
            Timecard tc = bl.getTimecard(timecard_id);
            bl.close();
            
            if (tc != null) {
                java.text.SimpleDateFormat sdf = new java.text.SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
                String startTime = sdf.format(tc.getStartTime());
                String endTime = sdf.format(tc.getEndTime());
                
                String json = "{\"timecard\":{\"timecard_id\":" + tc.getId() + ",\"start_time\":\"" + startTime + "\",\"end_time\":\"" + endTime + "\",\"emp_id\":" + tc.getEmpId() + "}}";
                return Response.ok(json).build();
            } else {
                return Response.status(Response.Status.NOT_FOUND).entity("{\"error\":\"Timecard not found\"}").build();
            }
        } catch (Exception e) {
            bl.close();
            return Response.status(Response.Status.BAD_REQUEST).entity("{\"error\":\"" + e.getMessage() + "\"}").build();
        }
    }

    //GET all timecards for employee
    @Path("timecards")
    @GET
    @Produces(MediaType.APPLICATION_JSON)
    public Response getTimecards(@QueryParam("company") String company, @QueryParam("emp_id") int emp_id) {
        BusinessLayer bl = new BusinessLayer(company);
        try {
            List<Timecard> timecards = bl.getTimecards(emp_id);
            bl.close();
            
            StringBuilder json = new StringBuilder("[");
            java.text.SimpleDateFormat sdf = new java.text.SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
            
            for (int i = 0; i < timecards.size(); i++) {
                Timecard tc = timecards.get(i);
                String startTime = sdf.format(tc.getStartTime());
                String endTime = sdf.format(tc.getEndTime());
                
                json.append("{\"timecard_id\":" + tc.getId() + ",\"start_time\":\"" + startTime + "\",\"end_time\":\"" + endTime + "\",\"emp_id\":" + tc.getEmpId() + "}");
                if (i < timecards.size() - 1) {
                    json.append(",");
                }
            }
            json.append("]");
            
            return Response.ok(json.toString()).build();
        } catch (Exception e) {
            bl.close();
            return Response.status(Response.Status.BAD_REQUEST).entity("{\"error\":\"" + e.getMessage() + "\"}").build();
        }
    }

    //POST timecard
    @Path("timecard")
    @POST
    @Consumes(MediaType.APPLICATION_FORM_URLENCODED)
    @Produces(MediaType.APPLICATION_JSON)
    public Response createTimecard(@FormParam("company") String company, @FormParam("emp_id") int emp_id, @FormParam("start_time") String start_time, @FormParam("end_time") String end_time) {
        BusinessLayer bl = new BusinessLayer(company);
        try {
            Timecard createdTc = bl.createTimecard(emp_id, start_time, end_time);
            bl.close();
            
            String responseJson = "{\"success\":{\"timecard_id\":" + createdTc.getId() + ",\"start_time\":\"" + start_time + "\",\"end_time\":\"" + end_time + "\",\"emp_id\":" + createdTc.getEmpId() + "}}";
            return Response.ok(responseJson).build();
        } catch (Exception e) {
            bl.close();
            return Response.status(Response.Status.BAD_REQUEST).entity("{\"error\":\"" + e.getMessage() + "\"}").build();
        }
    }

    //PUT timecard
    @Path("timecard")
    @PUT
    @Consumes(MediaType.APPLICATION_JSON)
    @Produces(MediaType.APPLICATION_JSON)
    public Response updateTimecard(String jsonInput) {
        BusinessLayer bl = null;
        try (JsonReader reader = Json.createReader(new StringReader(jsonInput))) {
            JsonObject obj = reader.readObject();
            String company = obj.getString("company");
            int timecard_id = obj.getInt("timecard_id");
            String start_time = obj.getString("start_time");
            String end_time = obj.getString("end_time");
            int emp_id = obj.getInt("emp_id");
            
            bl = new BusinessLayer(company);
            Timecard updatedTc = bl.updateTimecard(timecard_id, start_time, end_time, emp_id);
            bl.close();
            
            String responseJson = "{\"success\":{\"timecard_id\":" + updatedTc.getId() + ",\"start_time\":\"" + start_time + "\",\"end_time\":\"" + end_time + "\",\"emp_id\":" + updatedTc.getEmpId() + "}}";
            return Response.ok(responseJson).build();
        } catch (Exception e) {
            if (bl != null) bl.close();
            return Response.status(Response.Status.BAD_REQUEST).entity("{\"error\":\"" + e.getMessage() + "\"}").build();
        }
    }

    //DELETE timecard
    @Path("timecard")
    @DELETE
    @Produces(MediaType.APPLICATION_JSON)
    public Response deleteTimecard(@QueryParam("company") String company, @QueryParam("timecard_id") int timecard_id) {
        BusinessLayer bl = new BusinessLayer(company);
        try {
            int rowsDeleted = bl.deleteTimecard(timecard_id);
            bl.close();
            
            if (rowsDeleted > 0) {
                return Response.ok("{\"success\":\"Timecard " + timecard_id + " deleted.\"}").build();
            } else {
                return Response.status(Response.Status.NOT_FOUND).entity("{\"error\":\"Timecard not found\"}").build();
            }
        } catch (Exception e) {
            bl.close();
            return Response.status(Response.Status.BAD_REQUEST).entity("{\"error\":\"" + e.getMessage() + "\"}").build();
        }
    }
}