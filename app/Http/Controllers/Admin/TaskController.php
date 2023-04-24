<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Task;
use App\Services\TaskService;
use App\Services\EmployeeService;

class TaskController extends Controller
{
        
    public function __construct()
    {
        $this->taskService = new TaskService();
        $this->employeeService = new EmployeeService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = $this->taskService->getTaskList(true);
        return view('admin.task.list', ['tasks' => $tasks]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.task.task');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $newtask = $this->taskService->addtask($request);
        if($newtask){
            \Session::flash('success', 'The new task has been added successfully.');
            return "OK";
        }
        else{
            return false;
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

        $task = \App\Models\Admin\Task::findOrFail($id);
        if (!$task) {
            dump('Task not found');
            return false;
        }
        return view(('admin.task.task'), ['task' => $task, 'updating' => true]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = $this->taskService->get($id);

        if ($task) {
            $updated = $this->taskService->update($request, $id);
            if ($updated) {
                \Session::flash('success', 'The task has been updated successfully.');
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

        // $task = \App\Models\Admin\Task::findOrFail($id);
        // if ($task) {
        //     $task->delete();
        //     \Session::flash('success', 'The selected task details have been deleted successfully.');
        // }

        // return "OK";
    }

    public function assignTask()
    {
        $tasks = $this->taskService->getTaskList(true);
        $employees = $this->employeeService->getEmployeeList(true);
        return view(('admin.task.assign-task'), ['tasks' => $tasks, 'employees' => $employees]);
    }

    public function postAssignTask(Request $request)
    {
        $tasks = $this->taskService->getTaskList(true);
        $employees = $this->employeeService->getEmployeeList(true);

        $added = $this->taskService->addAssignee($request);
        if ($added) {
            \Session::flash('success', 'The task assigning has been updated successfully.');
            return "OK";
        } else {
            \Session::flash('danger', 'The task assigning cannot be updated as the task status is In Progress.');
            return false;
        }
        
    }
}
