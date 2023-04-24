<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Employee;
use App\Services\EmployeeService;

class EmployeeController extends Controller
{
    
    public function __construct()
    {
        $this->employeeService = new EmployeeService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = $this->employeeService->getEmployeeList(true);
        return view('admin.employee.list', ['employees' => $employees]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.employee.employee');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $employee = \App\Models\Admin\Employee::where('email', '=', $request->input('employeeEmail'))->first();
        if(!$employee) {

            $newEmployee = $this->employeeService->addEmployee($request);
            if($newEmployee){
                \Session::flash('success', 'The new employee has been added successfully.');
                return "OK";
            }
            else{
                return false;
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $employee = \App\Models\Admin\Employee::findOrFail($id);
        if (!$employee) {
            dump('Employee not found');
            return false;
        }
        return view(('admin.employee.employee'), ['employee' => $employee, 'updating' => true]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $employee = $this->employeeService->get($id);

        if ($employee) {
            $updated = $this->employeeService->update($request, $id);
            if ($updated) {
                \Session::flash('success', 'The employee has been updated successfully.');
                return "OK";
            } else {
                return false;
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $employee = \App\Models\Admin\Employee::findOrFail($id);
        if ($employee) {
            $employee->delete();
            \Session::flash('success', 'The selected employee details have been deleted successfully.');
        }

        return "OK";
    }

    public function postCheckMail(Request $request)
    {
        $employeeCount = '';
        if($request->has('email'))
          $employeeCount = \App\Models\Admin\Employee::where('email', $request->email);

        if ($employeeCount->count()) {
            return \Response::json(array('msg' => 'true')); // , 'field' => $field
        } else {
            return \Response::json(array('msg' => 'false')); // , 'field' => $field
        }
    }
}
