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

    public function taskRoom(Request $request){

    	

      return view('layouts.participants.task-room')
      	->with('user', \Auth::user());
    }
}