<?php

namespace Teamwork\Http\Controllers;

use Illuminate\Http\Request;
use Teamwork\GroupTask;
use Teamwork\Response;
use \Teamwork\Tasks as Task;
use \Teamwork\Time;
use \Teamwork\User;
use \Teamwork\Progress;

class TaskRoomController extends Controller
{

    #GET TASK WRAPPER PAGE. ADDS VIDEO CONF ELEMENTS TO EXISTING TASKS LIKE "CRYPTO" AND "MEMORY"
    public function taskRoom(Request $request){

      $admin = User::where('id',1)->first();

      $time_remaining = null;

      #GET TIME TIL NEXT SUBSESSION FOR LEADER
      if($admin->current_session){

        $session_start = \Teamwork\Time::where('type','session')->orderBy('created_at','desc')->first();

        $time_elapsed = $session_start->created_at->diffInSeconds(\Carbon\Carbon::now());

        $task_length = env('TASK_LENGTH',300);

        $survey_length = env('SURVEY_LENGTH',120);

        $buffer_length = env('BUFFER_LENGTH',30);
       
        $session_length = $task_length + $survey_length + $buffer_length;

        $time_remaining = $session_length * $admin->current_session - $time_elapsed - ($survey_length + $buffer_length);

      }
      else
        $time_remaining = null;

      #THIS WAS TO FIX LOCALSTORAGE ISSUES. NOT SURE IF STILL NEEDED
      if ($request->clear)
        $clear=true;
      else
        $clear = false;

    	$user = \Teamwork\User::find(\Auth::user()->id);

      #MAKE SURE CURRENTGROUPTASK IS UPDATED
    	$currentTask = GroupTask::where('group_id',$user->group_id)
                              ->where('completed',0)
                              ->orderBy('order','ASC')
                              ->first();

   		$request->session()->put('currentGroupTask',$currentTask->id);

      //IF THE TASK ISN'T CRYPTO OR MEMORY, DON'T USE TASK-ROOM
   		if($currentTask->name != "Cryptography" && $currentTask->name != "Memory")
   			return redirect('get-group-task');

      return view('layouts.participants.task-room')
      	->with('user', $user)
      	->with('task',$currentTask)
        ->with('clear',$clear)
        ->with('time_remaining',$time_remaining);
    }
}