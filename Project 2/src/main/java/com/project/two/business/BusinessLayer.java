package com.project.two.business;

import companydata.*;
import java.util.*;
import java.sql.*;
import java.text.*;

public class BusinessLayer {
    private DataLayer dl;
    private String company;

    public BusinessLayer(String company) {
        this.company = company;
        this.dl = new DataLayer(company);
    }

    // Company 
    public int deleteCompany() throws Exception {
        return dl.deleteCompany(company);
    }

    // Department 
    public Department getDepartment(int dept_id) throws Exception {
        return dl.getDepartment(company, dept_id);
    }

    public List<Department> getAllDepartments() throws Exception {
        return dl.getAllDepartment(company);
    }

    public Department createDepartment(String dept_name, String dept_no, String location) throws Exception {
        //validation
        if (dept_name == null || dept_name.trim().isEmpty()) {
            throw new Exception("Department name is required");
        }
        if (dept_no == null || dept_no.trim().isEmpty()) {
            throw new Exception("Department number is required");
        }
        
        //check if dept_no is unique
        Department existingDept = dl.getDepartmentNo(company, dept_no);
        if (existingDept != null) {
            throw new Exception("Department number must be unique");
        }
        
        Department newDept = new Department(company, dept_name, dept_no, location);
        Department createdDept = dl.insertDepartment(newDept);
        
        if (createdDept == null) {
            throw new Exception("Database failed to create department");
        }
        
        return createdDept;
    }

    public Department updateDepartment(int dept_id, String dept_name, String dept_no, String location) throws Exception {
        Department existingDept = dl.getDepartment(company, dept_id);
        if (existingDept == null) {
            throw new Exception("Department not found");
        }
        
        //check if dept_no is unique
        Department existing = dl.getDepartmentNo(company, dept_no);
        if (existing != null && existing.getId() != dept_id) {
            throw new Exception("Department number must be unique");
        }
        
        existingDept.setDeptName(dept_name);
        existingDept.setDeptNo(dept_no);
        existingDept.setLocation(location);
        
        return dl.updateDepartment(existingDept);
    }

    public int deleteDepartment(int dept_id) throws Exception {
        return dl.deleteDepartment(company, dept_id);
    }

    // Employee 
    public Employee getEmployee(int emp_id) throws Exception {
        return dl.getEmployee(emp_id);
    }

    public List<Employee> getAllEmployees() throws Exception {
        return dl.getAllEmployee(company);
    }

    public Employee createEmployee(String emp_name, String emp_no, String hire_date, String job, double salary, int dept_id, int mng_id) throws Exception {
        //validation
        Department dept = dl.getDepartment(company, dept_id);
        if (dept == null) {
            throw new Exception("Department does not exist");
        }
        
        //check if manager exists
        if (mng_id != 0) {
            Employee manager = dl.getEmployee(mng_id);
            if (manager == null) {
                throw new Exception("Manager does not exist");
            }
        }
        
        //check if emp_no is unique
        List<Employee> companyEmployees = dl.getAllEmployee(company);
        for (Employee emp : companyEmployees) {
            if (emp.getEmpNo().equals(emp_no)) {
                throw new Exception("Employee number must be unique");
            }
        }
        
        //validate hire_date
        DateFormat df = new SimpleDateFormat("yyyy-MM-dd");
        java.util.Date hireDate = df.parse(hire_date);
        java.util.Date currentDate = new java.util.Date();
        
        if (hireDate.after(currentDate)) {
            throw new Exception("Hire date cannot be in the future");
        }
        
        Calendar cal = Calendar.getInstance();
        cal.setTime(hireDate);
        int dayOfWeek = cal.get(Calendar.DAY_OF_WEEK);
        if (dayOfWeek == Calendar.SATURDAY || dayOfWeek == Calendar.SUNDAY) {
            throw new Exception("Hire date cannot be on weekend");
        }
        
        java.text.SimpleDateFormat sdf = new java.text.SimpleDateFormat("yyyy-MM-dd");
        java.util.Date hireDateObj = sdf.parse(hire_date);
        
        Employee newEmp = new Employee(emp_name, emp_no, new java.sql.Date(hireDateObj.getTime()), job, salary, dept_id, mng_id);
        return dl.insertEmployee(newEmp);
    }

    public Employee updateEmployee(int emp_id, String emp_name, String emp_no, String hire_date, String job, double salary, int dept_id, int mng_id) throws Exception {
        Employee existingEmp = dl.getEmployee(emp_id);
        if (existingEmp == null) {
            throw new Exception("Employee not found");
        }
        
        //check if emp_no is unique
        List<Employee> companyEmployees = dl.getAllEmployee(company);
        for (Employee e : companyEmployees) {
            if (e.getEmpNo().equals(emp_no) && e.getId() != emp_id) {
                throw new Exception("Employee number must be unique");
            }
        }
        
        //run same validations as create
        Department dept = dl.getDepartment(company, dept_id);
        if (dept == null) {
            throw new Exception("Department does not exist");
        }
        
        if (mng_id != 0) {
            Employee manager = dl.getEmployee(mng_id);
            if (manager == null) {
                throw new Exception("Manager does not exist");
            }
        }
        
        DateFormat df = new SimpleDateFormat("yyyy-MM-dd");
        java.util.Date hireDate = df.parse(hire_date);
        java.util.Date currentDate = new java.util.Date();
        
        if (hireDate.after(currentDate)) {
            throw new Exception("Hire date cannot be in the future");
        }
        
        Calendar cal = Calendar.getInstance();
        cal.setTime(hireDate);
        int dayOfWeek = cal.get(Calendar.DAY_OF_WEEK);
        if (dayOfWeek == Calendar.SATURDAY || dayOfWeek == Calendar.SUNDAY) {
            throw new Exception("Hire date cannot be on weekend");
        }
        
        java.text.SimpleDateFormat sdf = new java.text.SimpleDateFormat("yyyy-MM-dd");
        java.util.Date hireDateObj = sdf.parse(hire_date);
        
        existingEmp.setEmpName(emp_name);
        existingEmp.setEmpNo(emp_no);
        existingEmp.setHireDate(new java.sql.Date(hireDateObj.getTime()));
        existingEmp.setJob(job);
        existingEmp.setSalary(salary);
        existingEmp.setDeptId(dept_id);
        existingEmp.setMngId(mng_id);
        
        return dl.updateEmployee(existingEmp);
    }

    public int deleteEmployee(int emp_id) throws Exception {
        return dl.deleteEmployee(emp_id);
    }

    // Timecard 
    public Timecard getTimecard(int timecard_id) throws Exception {
        return dl.getTimecard(timecard_id);
    }

    public List<Timecard> getTimecards(int emp_id) throws Exception {
        return dl.getAllTimecard(emp_id);
    }

    public Timecard createTimecard(int emp_id, String start_time, String end_time) throws Exception {
        //validation
        Employee emp = dl.getEmployee(emp_id);
        if (emp == null) {
            throw new Exception("Employee does not exist");
        }
        
        DateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        Timestamp start = new Timestamp(df.parse(start_time).getTime());
        Timestamp end = new Timestamp(df.parse(end_time).getTime());
        
        //end timee at least 1 hour greater than start time
        long diff = end.getTime() - start.getTime();
        long hours = diff / (60 * 60 * 1000);
        if (hours < 1) {
            throw new Exception("End time must be at least 1 hour after start time");
        }
        
        //must be on same day
        Calendar startCal = Calendar.getInstance();
        startCal.setTime(start);
        Calendar endCal = Calendar.getInstance();
        endCal.setTime(end);
        
        if (startCal.get(Calendar.DAY_OF_YEAR) != endCal.get(Calendar.DAY_OF_YEAR) || startCal.get(Calendar.YEAR) != endCal.get(Calendar.YEAR)) {
            throw new Exception("Start and end time must be on the same day");
        }
        
        //must be weekday
        int startDayOfWeek = startCal.get(Calendar.DAY_OF_WEEK);
        if (startDayOfWeek == Calendar.SATURDAY || startDayOfWeek == Calendar.SUNDAY) {
            throw new Exception("Timecard cannot be on weekend");
        }
        
        //must be between 8am and 18pm
        int startTotalMinutes = startCal.get(Calendar.HOUR_OF_DAY) * 60 + startCal.get(Calendar.MINUTE);
        int endTotalMinutes = endCal.get(Calendar.HOUR_OF_DAY) * 60 + endCal.get(Calendar.MINUTE);
        
        if (startTotalMinutes < 8*60 || endTotalMinutes > 18*60) {
            throw new Exception("Time must be between 08:00:00 and 18:00:00");
        }
        
        //check if employee already has timecard for same day
        List<Timecard> existingTimecards = dl.getAllTimecard(emp_id);
        for (Timecard tc : existingTimecards) {
            Calendar existingCal = Calendar.getInstance();
            existingCal.setTime(tc.getStartTime());
            if (existingCal.get(Calendar.DAY_OF_YEAR) == startCal.get(Calendar.DAY_OF_YEAR) && existingCal.get(Calendar.YEAR) == startCal.get(Calendar.YEAR)) {
                throw new Exception("Employee already has timecard for this day");
            }
        }
        
        java.text.SimpleDateFormat sdf = new java.text.SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        Timestamp startTime = new Timestamp(sdf.parse(start_time).getTime());
        Timestamp endTime = new Timestamp(sdf.parse(end_time).getTime());
        
        Timecard newTc = new Timecard(startTime, endTime, emp_id);
        return dl.insertTimecard(newTc);
    }

    public Timecard updateTimecard(int timecard_id, String start_time, String end_time, int emp_id) throws Exception {
        Timecard existingTc = dl.getTimecard(timecard_id);
        if (existingTc == null) {
            throw new Exception("Timecard not found");
        }
        
        //run same validations as create
        Employee emp = dl.getEmployee(emp_id);
        if (emp == null) {
            throw new Exception("Employee does not exist");
        }
        
        DateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        Timestamp start = new Timestamp(df.parse(start_time).getTime());
        Timestamp end = new Timestamp(df.parse(end_time).getTime());
        
        long diff = end.getTime() - start.getTime();
        long hours = diff / (60 * 60 * 1000);
        if (hours < 1) {
            throw new Exception("End time must be at least 1 hour after start time");
        }
        
        Calendar startCal = Calendar.getInstance();
        startCal.setTime(start);
        Calendar endCal = Calendar.getInstance();
        endCal.setTime(end);
        
        if (startCal.get(Calendar.DAY_OF_YEAR) != endCal.get(Calendar.DAY_OF_YEAR) || startCal.get(Calendar.YEAR) != endCal.get(Calendar.YEAR)) {
            throw new Exception("Start and end time must be on the same day");
        }
        
        int startDayOfWeek = startCal.get(Calendar.DAY_OF_WEEK);
        if (startDayOfWeek == Calendar.SATURDAY || startDayOfWeek == Calendar.SUNDAY) {
            throw new Exception("Timecard cannot be on weekend");
        }
        
        int startTotalMinutes = startCal.get(Calendar.HOUR_OF_DAY) * 60 + startCal.get(Calendar.MINUTE);
        int endTotalMinutes = endCal.get(Calendar.HOUR_OF_DAY) * 60 + endCal.get(Calendar.MINUTE);
        
        if (startTotalMinutes < 8*60 || endTotalMinutes > 18*60) {
            throw new Exception("Time must be between 08:00:00 and 18:00:00");
        }
        
        List<Timecard> existingTimecards = dl.getAllTimecard(emp_id);
        for (Timecard tc : existingTimecards) {
            if (tc.getId() != timecard_id) {
                Calendar existingCal = Calendar.getInstance();
                existingCal.setTime(tc.getStartTime());
                if (existingCal.get(Calendar.DAY_OF_YEAR) == startCal.get(Calendar.DAY_OF_YEAR) && existingCal.get(Calendar.YEAR) == startCal.get(Calendar.YEAR)) {
                    throw new Exception("Employee already has timecard for this day");
                }
            }
        }
        
        java.text.SimpleDateFormat sdf = new java.text.SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        Timestamp startTime = new Timestamp(sdf.parse(start_time).getTime());
        Timestamp endTime = new Timestamp(sdf.parse(end_time).getTime());
        
        existingTc.setStartTime(startTime);
        existingTc.setEndTime(endTime);
        existingTc.setEmpId(emp_id);
        
        return dl.updateTimecard(existingTc);
    }

    public int deleteTimecard(int timecard_id) throws Exception {
        return dl.deleteTimecard(timecard_id);
    }

    public void close() {
        if (dl != null) {
            dl.close();
        }
    }
}