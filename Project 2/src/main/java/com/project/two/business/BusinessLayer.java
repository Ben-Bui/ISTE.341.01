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
        // validation
        if (dept_name == null || dept_name.trim().isEmpty()) {
            throw new Exception("Department name is required");
        }
        if (dept_no == null || dept_no.trim().isEmpty()) {
            throw new Exception("Department number is required");
        }
        
        String validationError = validateDepartmentInsert(dept_no, dept_name, location);
        if (validationError != null) {
            throw new Exception(validationError);
        }
        
        try {
            Department newDept = new Department(company, dept_name, dept_no, location);
            Department createdDept = dl.insertDepartment(newDept);
            
            if (createdDept == null) {
                throw new Exception("Database failed to create department");
            }
            
            return createdDept;
        } catch (Exception e) {
            throw new Exception("Failed to create department: " + e.getMessage());
        }
    }

    public Department updateDepartment(int dept_id, String dept_name, String dept_no, String location) throws Exception {
        String validationError = validateDepartmentUpdate(dept_id, dept_no);
        if (validationError != null) {
            throw new Exception(validationError);
        }
        
        Department existingDept = dl.getDepartment(company, dept_id);
        if (existingDept == null) {
            throw new Exception("Department not found");
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

    public Employee createEmployee(String emp_name, String emp_no, String hire_date, 
                                 String job, double salary, int dept_id, int mng_id) throws Exception {
        String validationError = validateEmployeeInsert(emp_no, hire_date, dept_id, mng_id, salary, emp_name, job);
        if (validationError != null) {
            throw new Exception(validationError);
        }
        
        java.text.SimpleDateFormat sdf = new java.text.SimpleDateFormat("yyyy-MM-dd");
        java.util.Date hireDate = sdf.parse(hire_date);
        
        Employee newEmp = new Employee(emp_name, emp_no, new java.sql.Date(hireDate.getTime()), 
                                     job, salary, dept_id, mng_id);
        return dl.insertEmployee(newEmp);
    }

    public Employee updateEmployee(int emp_id, String emp_name, String emp_no, String hire_date,
                                 String job, double salary, int dept_id, int mng_id) throws Exception {
        String validationError = validateEmployeeUpdate(emp_id, emp_no, hire_date, dept_id, mng_id, emp_name, job);
        if (validationError != null) {
            throw new Exception(validationError);
        }
        
        Employee existingEmp = dl.getEmployee(emp_id);
        if (existingEmp == null) {
            throw new Exception("Employee not found");
        }
        
        java.text.SimpleDateFormat sdf = new java.text.SimpleDateFormat("yyyy-MM-dd");
        java.util.Date hireDate = sdf.parse(hire_date);
        
        existingEmp.setEmpName(emp_name);
        existingEmp.setEmpNo(emp_no);
        existingEmp.setHireDate(new java.sql.Date(hireDate.getTime()));
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
        String validationError = validateTimecardInsert(emp_id, start_time, end_time);
        if (validationError != null) {
            throw new Exception(validationError);
        }
        
        java.text.SimpleDateFormat sdf = new java.text.SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        Timestamp startTime = new Timestamp(sdf.parse(start_time).getTime());
        Timestamp endTime = new Timestamp(sdf.parse(end_time).getTime());
        
        Timecard newTc = new Timecard(startTime, endTime, emp_id);
        return dl.insertTimecard(newTc);
    }

    public Timecard updateTimecard(int timecard_id, String start_time, String end_time, int emp_id) throws Exception {
        String validationError = validateTimecardUpdate(timecard_id, emp_id, start_time, end_time);
        if (validationError != null) {
            throw new Exception(validationError);
        }
        
        Timecard existingTc = dl.getTimecard(timecard_id);
        if (existingTc == null) {
            throw new Exception("Timecard not found");
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

    // Department validations
    public String validateDepartmentInsert(String dept_no, String dept_name, String location) {
        try {
            if (dept_no.length() > 10) return "Department number too long";
            if (dept_name.length() > 30) return "Department name too long";
            if (location != null && location.length() > 30) return "Location too long";
            
            // Check if dept_no is unique 
            try {
                Department existingDept = dl.getDepartmentNo(company, dept_no);
                if (existingDept != null) {
                    return "Department number must be unique";
                }
            } catch (Exception e) {// Department doesn't exist
            }
            
            return null; // No errors
        } catch (Exception e) {
            return "Validation error: " + e.getMessage();
        }
    }

    public String validateDepartmentUpdate(int dept_id, String dept_no) {
        try {
            // Check if department exists
            Department dept = dl.getDepartment(company, dept_id);
            if (dept == null) {
                return "Department not found";
            }
            
            // Check if dept_no is unique 
            try {
                Department existing = dl.getDepartmentNo(company, dept_no);
                if (existing != null && existing.getId() != dept_id) {
                    return "Department number must be unique";
                }
            } catch (Exception e) {// Department doesn't 
            }
            
            return null;
        } catch (Exception e) {
            return "Validation error: " + e.getMessage();
        }
    }

    // Employee validations
    public String validateEmployeeInsert(String emp_no, String hire_date, int dept_id, int mng_id, 
                                       double salary, String emp_name, String job) {
        try {
            Department dept = dl.getDepartment(company, dept_id);
            if (dept == null) {
                return "Department does not exist";
            }
            
            // Check if manager exists 
            if (mng_id != 0) {
                Employee manager = dl.getEmployee(mng_id);
                if (manager == null) {
                    return "Manager does not exist";
                }
            }
            
            List<Employee> companyEmployees = dl.getAllEmployee(company);
            for (Employee emp : companyEmployees) {
                if (emp.getEmpNo().equals(emp_no)) {
                    return "Employee number must be unique";
                }
            }
            
            // Validate hire_date
            DateFormat df = new SimpleDateFormat("yyyy-MM-dd");
            java.util.Date hireDate = df.parse(hire_date);
            java.util.Date currentDate = new java.util.Date();
            
            // Hire date current or past
            if (hireDate.after(currentDate)) {
                return "Hire date cannot be in the future";
            }
            
            // Hire date must be weekday
            Calendar cal = Calendar.getInstance();
            cal.setTime(hireDate);
            int dayOfWeek = cal.get(Calendar.DAY_OF_WEEK);
            if (dayOfWeek == Calendar.SATURDAY || dayOfWeek == Calendar.SUNDAY) {
                return "Hire date cannot be on weekend";
            }
            
            //field lengths
            if (emp_name.length() > 30) return "Employee name too long";
            if (emp_no.length() > 10) return "Employee number too long";
            if (job.length() > 30) return "Job title too long";
            
            return null;
        } catch (ParseException e) {
            return "Invalid date format. Use yyyy-MM-dd";
        } catch (Exception e) {
            return "Validation error: " + e.getMessage();
        }
    }

    public String validateEmployeeUpdate(int emp_id, String emp_no, String hire_date, int dept_id, 
                                       int mng_id, String emp_name, String job) {
        try {
            // Check if employee exists
            Employee emp = dl.getEmployee(emp_id);
            if (emp == null) {
                return "Employee not found";
            }
            
            // Check if emp_no is unique 
            List<Employee> companyEmployees = dl.getAllEmployee(company);
            for (Employee e : companyEmployees) {
                if (e.getEmpNo().equals(emp_no) && e.getId() != emp_id) {
                    return "Employee number must be unique";
                }
            }
            
            // Run all insert validations
            String insertError = validateEmployeeInsert(emp_no, hire_date, dept_id, mng_id, emp.getSalary(), emp_name, job);
            if (insertError != null) {
                return insertError;
            }
            
            return null;
        } catch (Exception e) {
            return "Validation error: " + e.getMessage();
        }
    }

    // Timecard validations
    public String validateTimecardInsert(int emp_id, String start_time, String end_time) {
        try {
            // Check if employee exists
            Employee emp = dl.getEmployee(emp_id);
            if (emp == null) {
                return "Employee does not exist";
            }
            
            // Parse timestamps
            DateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
            Timestamp start = new Timestamp(df.parse(start_time).getTime());
            Timestamp end = new Timestamp(df.parse(end_time).getTime());
            
            // Check if start_time is within range 
            Calendar cal = Calendar.getInstance();
            cal.setTime(new java.util.Date());
            
            // Find previous Monday
            Calendar mondayCal = Calendar.getInstance();
            while (mondayCal.get(Calendar.DAY_OF_WEEK) != Calendar.MONDAY) {
                mondayCal.add(Calendar.DATE, -1);
            }
            mondayCal.set(Calendar.HOUR_OF_DAY, 0);
            mondayCal.set(Calendar.MINUTE, 0);
            mondayCal.set(Calendar.SECOND, 0);
            mondayCal.set(Calendar.MILLISECOND, 0);
            
            Timestamp mondayTimestamp = new Timestamp(mondayCal.getTime().getTime());
            if (start.before(mondayTimestamp)) {
                return "Start time cannot be before Monday of current week";
            }
            
            // End time must be at least 1 hour greater than start time
            long diff = end.getTime() - start.getTime();
            long hours = diff / (60 * 60 * 1000);
            if (hours < 1) {
                return "End time must be at least 1 hour after start time";
            }
            
            // Must be on same day
            Calendar startCal = Calendar.getInstance();
            startCal.setTime(start);
            Calendar endCal = Calendar.getInstance();
            endCal.setTime(end);
            
            if (startCal.get(Calendar.DAY_OF_YEAR) != endCal.get(Calendar.DAY_OF_YEAR) ||
                startCal.get(Calendar.YEAR) != endCal.get(Calendar.YEAR)) {
                return "Start and end time must be on the same day";
            }
            
            // Must be weekday
            int startDayOfWeek = startCal.get(Calendar.DAY_OF_WEEK);
            if (startDayOfWeek == Calendar.SATURDAY || startDayOfWeek == Calendar.SUNDAY) {
                return "Timecard cannot be on weekend";
            }
            
            // Must be between 08:00:00 and 18:00:00
            int startTotalMinutes = startCal.get(Calendar.HOUR_OF_DAY) * 60 + startCal.get(Calendar.MINUTE);
            int endTotalMinutes = endCal.get(Calendar.HOUR_OF_DAY) * 60 + endCal.get(Calendar.MINUTE);
            
            if (startTotalMinutes < 8*60 || endTotalMinutes > 18*60) {
                return "Time must be between 08:00:00 and 18:00:00";
            }
            
            // Check if employee already has timecard for same day
            List<Timecard> existingTimecards = dl.getAllTimecard(emp_id);
            for (Timecard tc : existingTimecards) {
                Calendar existingCal = Calendar.getInstance();
                existingCal.setTime(tc.getStartTime());
                if (existingCal.get(Calendar.DAY_OF_YEAR) == startCal.get(Calendar.DAY_OF_YEAR) &&
                    existingCal.get(Calendar.YEAR) == startCal.get(Calendar.YEAR)) {
                    return "Employee already has timecard for this day";
                }
            }
            
            return null;
        } catch (ParseException e) {
            return "Invalid timestamp format. Use yyyy-MM-dd HH:mm:ss";
        } catch (Exception e) {
            return "Validation error: " + e.getMessage();
        }
    }

    public String validateTimecardUpdate(int timecard_id, int emp_id, String start_time, String end_time) {
        try {
            // Check if timecard exists
            Timecard tc = dl.getTimecard(timecard_id);
            if (tc == null) {
                return "Timecard not found";
            }
            
            // Run all insert validations
            return validateTimecardInsert(emp_id, start_time, end_time);
        } catch (Exception e) {
            return "Validation error: " + e.getMessage();
        }
    }

    public void close() {
        if (dl != null) {
            dl.close();
        }
    }
}