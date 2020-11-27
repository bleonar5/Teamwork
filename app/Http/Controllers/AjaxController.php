<?php

namespace Teamwork\Http\Controllers;

use Illuminate\Http\Request;
use Teamwork\Response;

class AjaxController extends Controller
{
  /**
   * Allows a task to be marked as completed via AJAX
   * so that individuals can confirm completion of their
   * portion of the task while remaining on-page.
   * @param  Request $response AJAX response data
   * @return void
   */
  public function markIndividualResponseComplete(Request $request) {

    $task = \Teamwork\GroupTask::with('response')
                               ->find($request->session()->get('currentGroupTask'));

    $individualTask = \Teamwork\IndividualTask::find($request->session()->get('currentIndividualTask'));



    $numUsersResponded = count($task->response->groupBy('user_id'));
    $usersInGroup = \Teamwork\User::where('group_id', \Auth::user()->group_id)
                                  ->where('role_id', 3)
                                  ->count();


    if($request->maxResponses) {

      $total_responses = count($task->response);

      if($total_responses == ($request->maxResponses * $usersInGroup)) {
        $individualTask->completed = true;
        $individualTask->save();
        $task->completed = $request->completeGroupTaskAlso;
        $task->save();
      }
    }
    else if($numUsersResponded == $usersInGroup) {
      $individualTask->completed = true;
      $individualTask->save();
      $task->completed = $request->completeGroupTaskAlso;
      $task->save();
    }

  }

  /**
   * Stores results of the current task in Session
  */
  public function storeTaskData(Request $request) {

    $data = json_decode($request->data);
    $request->session()->put('currentTaskData', $data);
  }

  /**
   * Checks if individual tasks are completed, meaning it is
   * safe for one member to login under the group ID to provide
   * a group response.
   */
   public function groupLoginAllowed(Request $request) {
     $individualTask = \Teamwork\IndividualTask::find($request->session()->get('currentIndividualTask'));
     echo $individualTask->completed;
   }

   /**
    * Returns if task is complete or not. This way,
    * we can keep users on a page until it is time for
    * them to continue.
    * @param  Request $request [description]
    * @return [type]           [description]
    */
   public function checkTaskCompletion(Request $request) {
      $task = \Teamwork\GroupTask::with('response')
                                ->with('progress')
                                ->find($request->session()->get('currentGroupTask'));
      $usersInGroup = \Teamwork\User::where('group_id', \Auth::user()->group_id)
                                    ->where('role_id', 3)
                                    ->count();
      $numUsersCompleted = count($task->progress->groupBy('user_id'));
      return ($numUsersCompleted == $usersInGroup) ? 1 : 0;
   }

   public function markIndividualReadyForGroup(Request $request) {
     $exists = \DB::table('waiting')
                ->where('user_id', $request->user_id)
                ->where('group_tasks_id', $request->group_tasks_id)
                ->where('step', $request->step)
                ->count();
    if(!$exists){
      \DB::table('waiting')->insert(['user_id' => $request->user_id,
                                     'group_id' => $request->group_id,
                                     'group_tasks_id' => $request->group_tasks_id,
                                     'step' => $request->step,
                                     'created_at' => date("Y-m-d H:i:s"),
                                     'updated_at' => date("Y-m-d H:i:s")]);
    }
   }

   public function checkGroupReady(Request $request) {
     $group = \Teamwork\User::where('group_id', $request->group_id)->count();
     $ready = \DB::table('waiting')
                 ->where('group_id', $request->group_id)
                 ->where('group_tasks_id', $request->group_tasks_id)
                 ->where('step', $request->step)
                 ->count();

     return ($group == $ready) ? 1 : 0;
   }

   public function getProbVal(Request $request) {
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_HEADER, 0);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_URL, 'http://hellogabe.me:3100?mean='.$request->mean);
     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
     $data = curl_exec($ch);
     curl_close($ch);
     echo $data;
   }

   public function saveMemoryReviewSelection(Request $request) {
      $task = \Teamwork\GroupTask::find($request->group_tasks_id);
      $params = unserialize($task->parameters);
      $test = $params->test;
      $previousResponse = Response::where('user_id', $request->user_id)
                                 ->where('group_tasks_id', $request->group_tasks_id)
                                 ->where('prompt', 'Memory stimulus type ('.$test.')')
                                 ->orderBy('id', 'desc')
                                 ->first();
      if($previousResponse){
        $previousResponse->updated_at = date("Y-m-d H:i:s");
        $previousResponse->save();
      }

     $response = new Response;
     $response->group_tasks_id = $request->group_tasks_id;
     $response->user_id = $request->user_id;
     $response->prompt = 'Memory stimulus type ('.$test.')';
     $response->response = $request->type;
     $response->save();

   }
}
