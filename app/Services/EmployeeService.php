<?php

namespace App\Services;

use App\Services\AppService;

use Illuminate\Support\Str;
use App\Models\Admin\Employee;

class EmployeeService extends AppService {

	function __construct() {

	}

	public function get($id)
	{
		try {
			$employee = \App\Models\Admin\Employee::findOrFail($id);
		}
		catch(\Exception $e) {
			// $this->setLastError("getting employee failed :  could not find a type matching the id  ".$e->getMessage());
            dump("Getting employee failed :  ".$e->getMessage());
            return false;
		}
		return $employee;
	}
    
	public function getEmployeeList($all)
	{
		if($all)
			$employees = \App\Models\Admin\Employee::with('tasks')->orderBy('id', 'DESC')->get();
		else{
		// \DB::enableQueryLog();

			$employees = \App\Models\Admin\Employee::with('tasks')->where([['status',1]])->orderBy('id', 'DESC')->get();

		// dump(DB::getQueryLog());
		}
		return $employees;
	}

	public function addEmployee($requestDetails)
	{
		$newEmployee = new Employee();

        $employeeName = $requestDetails['employeeName'];
		$employeeEmail = $requestDetails['employeeEmail'];
		$employeeMobile = $requestDetails['employeeMobile'];
		$employeeDepartment = $requestDetails['employeeDepartment'];
		$employeeStatus = $requestDetails['employeeStatus'];

		$newEmployee->name = $employeeName;
		$newEmployee->email = $employeeEmail;
		$newEmployee->mobile = $employeeMobile;
		$newEmployee->department = $employeeDepartment;
		$newEmployee->status = $employeeStatus;

		try {
			$newEmployee->save();
		}
		catch (\Exception $e) {
			dump("Adding Employee: save failed ".$e->getMessage());
			return false;
		}

        return $newEmployee;

	}

	public function update($requestData,$id)
	{
		$employee = $this->get($id);

		if(!$employee) {
			return false;
		}
        $employeeName = $requestData['employeeName'];
		$employeeEmail = $requestData['employeeEmail'];
		$employeeMobile = $requestData['employeeMobile'];
		$employeeDepartment = $requestData['employeeDepartment'];
		$employeeStatus = $requestData['employeeStatus'];

		$employee->name = $employeeName;
		$employee->email = $employeeEmail;
		$employee->mobile = $employeeMobile;
		$employee->department = $employeeDepartment;
		$employee->status = $employeeStatus;

		try {
			$employee->save();
		}
		catch (\Exception $e) {
			dump("Updating employee failed : ".$e->getMessage());
			return false;
		}

        return $employee;
	}
}