<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use \App\Http\Resources\Task as theTask;
use Illuminate\Support\Facades\Validator;

class TasksController extends Controller
{
  /**
   * get all task for the date by request
   *
   * @param Request
   * @param Task Modal
   *
   * @return Resources Collection has task and category in array
   */
  public function tasks(Request $request, Task $task)
  {
    $date_of_task = $request->task_at;
    $theTasks = $task->where('task_at', $date_of_task)->get();
    if (count($theTasks) <= 0)
      return response()->json(["message" => "the date of your choose do not has any task", "status" => 220], 200);
    foreach ($theTasks as $theTask)
      $theTask->category;
    foreach ($theTasks as $theTask)
      $theTask->step;
    return theTask::collection($theTasks)->keyBy("id");
  }
  /**
   * create new task
   *
   * @param Request
   * @param Task Modal
   *
   * @return Json
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'task_text' => 'required|min:10',
      'categories_id' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }
    $task = new Task;
    $task->task_text = $request->task_text;
    $task->categories_id = $request->categories_id;
    $task->task_at = $request->task_at;
    if ($task->save()) {
      $result = $this->getTask($task);
      return response()->json(['message' => "The Task was created successfully", "task" => $result]);
    } else
      return response()->json(['message' => "The Task was not created"]);
  }
  /**
   * create new task
   *
   * @param Request
   * @param Task Modal
   *
   * @return Json
   */
  public function update(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'task_text' => 'required|min:10',
      'categories_id' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    $task = Task::where("id", $request->id)->first();
    $task->task_text = $request->task_text;
    $task->categories_id = $request->categories_id;
    if ($task->save()) {
      $result = $this->getTask($task);
      return response()->json(['message' => "The Task was update successfully", "task" => $result]);
    } else
      return response()->json(['message' => "The Task was not update"]);
  }
  /**
   * get one task
   *
   * @param Task Record
   *
   * @return Resources  task and category in array
   */
  public function getTask(Task $task)
  {
    $task->category;
    $task->step;
    return new theTask($task);
  }
  /**
   * delete task
   * make Soft Deleting
   *
   * @param Request
   *
   * @return Json
   */
  public function destroy(Request $request)
  {
    if ($request->destroy) {
      if (Task::where('id', $request->id)->forceDelete())
        return response()->json(['message' => "The Task was Deleted successfully"]);
    } else {
      if (Task::where('id', $request->id)->delete())
        return response()->json(['message' => "The Task was set in trash bin successfully"]);
    }
    return response()->json(['message' => "The Task was not delete"], 422);
  }

  /**
   * get deleted tasks
   *
   * @param Request
   *
   * @return Json
   */
  public function getTaskDeleted(Request $request)
  {
    $tasks =  Task::onlyTrashed()->get();
    if (count($tasks) <= 0)
      return response()->json(["message" => "the task of your choose not found"], 422);
    return theTask::collection($tasks)->keyBy("id");
  }
  /**
   * restore task
   * @param Request
   *
   * @return Json
   */
  public function restore(Request $request)
  {
    $task = Task::onlyTrashed()->where('id', $request->id)->first();
    if (empty($task))
      return response()->json(["message" => "the task of your choose not found"], 422);
    $task->restore();
    return response()->json(['message' => "The Task was restored successfully"]);
  }
  /**
   * change between task status
   *
   * @param Request
   *
   * @return Json
   */
  public function completed(Request $request)
  {
    $task = Task::where("id", $request->id)->first();
    $task->task_completed =  $request->completed;
    if ($task->save())
      return response()->json(['message' => "The status was changed "]);
    return response()->json(['message' => "The status was not changed "], 422);
  }

  /**
   *
   */
  public function taskInDate(Request $request)
  {
    $allTasks =  Task::whereBetween("task_at", [$request->startDate, $request->endDate])->get(['task_at']);
    if (count($allTasks) == 0)
      return "0";
    return response()->json($this->countTaskInMonth($allTasks));


    $cot =  Task::whereBetween("task_at", ["2020-7-1", "2020-7-30"])->get("task_at");
  }
  /**
   *  It counts the number of tasks by date and creates an array. The key is date and the values represent the number of tasks on that date
   *
   * @param tasks : record form database
   * @return Array
   */
  private function countTaskInMonth($tasks)
  {
    $countTasks = [];
    for ($i = 0; $i < count($tasks); $i++) {
      $date = date("j", strtotime($tasks[$i]->task_at));
      if (!array_key_exists($date, $countTasks)) {
        $countTasks[$date] = 1;
      } else {
        $countTasks[$date] += 1;
      }
    }

    return $countTasks;
  }
}