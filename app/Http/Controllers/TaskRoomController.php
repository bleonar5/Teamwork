<?php

namespace Teamwork\Http\Controllers;

use Illuminate\Http\Request;
use Teamwork\GroupTask;
use Teamwork\Response;
use \Teamwork\Tasks as Task;
use \Teamwork\Time;
use \Teamwork\Progress;

class TaskRoomController extends Controller
{

    public function taskRoom(Request $request,$task){
    	if ($task == "cryptography")
    		$task = "Cryptography";
    	if ($task == 'memory')
    		$task = "Memory";
    	$user = \Teamwork\User::find(\Auth::user()->id);
    	$currentTask = GroupTask::where('id',($request->session()->get('currentGroupTask')))->first();
   		//$currentTask = \Teamwork\GroupTask::where('group_id',$user->group_id)->where('name',$task)->orderBy('created_at','DESC')->first();

   		//$request->session()->put('currentGroupTask',$currentTask->id);

   		if($currentTask->completed){
   			return redirect('get-group-task');
   		}

      return view('layouts.participants.task-room')
      	->with('user', $user)
      	->with('task',$currentTask);
    }
}