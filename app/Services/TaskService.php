<?php

namespace App\Services;

use App\Services\AppService;

use Illuminate\Support\Str;
use App\Models\Admin\Task;
use App\Models\Admin\Employee;
use App\Models\Admin\EmployeeTask;

class TaskService extends AppService {

	function __construct() {

	}

	public function get($id)
	{
		try {
			$task = \App\Models\Admin\Task::findOrFail($id);
		}
		catch(\Exception $e) {
			// $this->setLastError("getting task failed :  could not find a type matching the id  ".$e->getMessage());
            dump("Getting task failed :  ".$e->getMessage());
            return false;
		}
		return $task;
	}
    
	public function getTaskList($all)
	{
		if($all)
			$tasks = \App\Models\Admin\Task::with('employee')->orderBy('id', 'DESC')->get();
		else{
		// \DB::enableQueryLog();

			$tasks = \App\Models\Admin\Task::with('employee')->where([['status',1]])->orderBy('id', 'DESC')->get();

		// dump(DB::getQueryLog());
		}
		return $tasks;
	}

	public function addtask($requestDetails)
	{
		$newtask = new Task();

        $taskTitle = $requestDetails['taskTitle'];
		$taskDescription = $requestDetails['taskDescription'];
        $taskStatus = "Unassigned";

		$newtask->title = $taskTitle;
		$newtask->description = $taskDescription;
		$newtask->status = $taskStatus;

		try {
			$newtask->save();
		}
		catch (\Exception $e) {
			dump("Adding task: save failed ".$e->getMessage());
			return false;
		}

        return $newtask;

	}

	public function update($requestData,$id)
	{
		$task = $this->get($id);

		if(!$task) {
			return false;
		}
        $taskTitle = $requestData['taskTitle'];
		$taskDescription = $requestData['taskDescription'];

		$task->title = $taskTitle;
		$task->description = $taskDescription;

		try {
			$task->save();
		}
		catch (\Exception $e) {
			dump("Updating task failed : ".$e->getMessage());
			return false;
		}

        return $task;
	}
    
	public function addAssignee($requestData)
	{

        $taskAssign = $requestData->taskAssign;
        $taskAssignee = $requestData->taskAssignee;

		$newEmployeeTask = new EmployeeTask();
        $task = $this->get($taskAssign);

		$newEmployeeTask->employee_id = $taskAssignee;
		$newEmployeeTask->task_id = $taskAssign;
        if(($task->status != 'In Progress') || ($task->status != 'Done')){
            try {
                $newEmployeeTask->save();

                $taskStatus = "Assigned";

                if(!$task) {
                    return false;
                }
                $task->status = $taskStatus;

                try {
                    $task->save();
                }
                catch (\Exception $e) {
                    dump("Updating task failed : ".$e->getMessage());
                    return false;
                }
            }
            catch (\Exception $e) {
                dump("Adding task: save failed ".$e->getMessage());
                return false;
            }

            return $newEmployeeTask;
        }
        else{
            return false;
        }


	}
}