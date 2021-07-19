<?php

#LEGACY CODE -- GABE MANSUR
#UPDATED BY BRIAN LEONARD
#HANDLES LOGIN OPERATIONS

namespace Teamwork\Http\Controllers;

use Illuminate\Http\Request;
use Teamwork\User;
use Teamwork\Group;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
  //DISPLAYS DEFAULT LOGIN SCREEN
  //USERS SIGN IN AND ARE ENROLLED IN THE DEFAULT TASK LIST (CURRENTLY CRYPTO PILOT)
  public function participantLogin() {
    //CHECK STUDY IS OPEN
    $in_session = User::where('id',1)->first()->in_room;
    //GET THE SCHEDULED TIME FOR SESSION
    $date = User::where('id',1)->first()->signature;

    return view('layouts.participants.participant-login')
      ->with('in_session',$in_session)
      ->with('date',$date);
  }

  //DISPLAYS SPECIAL LOGIN SCREEN FOR OUR MTURK PILOT (ASSIGNED TO PHASE 1 INDIVIDUAL TASK LIST)
  public function mturkLogin() {

    $in_session = User::where('id',1)->first()->in_room;

    return view('layouts.participants.mturk-login')
      ->with('in_session',1)
      ->with('package', 'individual-pilot');;
  }

  //DISPLAYS LOGIN SCREEN WITH PARAMETER INDICATING WHICH TASK LIST TO ASSIGN
  //DIFFERENT 'TASK-PACKAGE' LABELS CAN BE FOUND IN GROUPTASK.PHP
  //EXAMPLE LABEL: CRYPTO-PILOT
  public function participantPackageWaveLogin($package,$wave) {

    $in_session = User::where('id',1)->first()->in_room;

    $date = User::where('id',1)->first()->signature;

    return view('layouts.participants.participant-login')
      ->with('in_session',$in_session)
      ->with('package', $package)
      ->with('wave',1)
      ->with('date',$date);
  }

  public function participantPackageLogin($package) {

    $in_session = User::where('id',1)->first()->in_room;

    $date = User::where('id',1)->first()->signature;

    return view('layouts.participants.participant-login')
      ->with('in_session',$in_session)
      ->with('package', $package)
      ->with('wave',$wave)
      ->with('date',$date);
  }

  //LOGS USER IN ONCE THEY'VE ENTERED THEIR PARTICIPANT ID
  public function postParticipantLogin(Request $request) {

      // Create or find the user
      $user = User::firstOrCreate(['participant_id' => $request->participant_id],
                                  ['name' => 'participant',
                                   'participant_id' => $request->participant_id,
                                   'password' => bcrypt('participant'),
                                   'role_id' => 3,
                                   'group_id'=>1,
                                   'wave' => $request->wave]);

      Log::debug('YEEES'.$request->wave);
      $user->save();
      \Auth::login($user);

      $newGroup = false;

      // If the group doesn't exist yet, create it
      if($user->group_id == 1){

        $newGroup = true;
        $group = new Group;
        $group->save();

      }
      //ELSE FIND IT
      else
        $group = Group::find($user->group_id);

      //GET THE LATEST INCOMPLETE TASK ON THE TASK LIST FOR THIS GROUP
      $currentTask = \Teamwork\GroupTask::where('group_id',$user->group_id)
        ->where('completed',0)
        ->orderBy('order','ASC')
        ->first();

      //IF SUCH A TASK EXISTS
      if($currentTask){
        //MARK IT IN THE SESSION AS THE CURRENT TASK
        $request->session()->put('currentGroupTask', $currentTask->id);
        //SEND THEM TO IT
        return redirect('/get-group-task');
      }

      // If the user exists, update the user's group ID, if needed
      if($group->id != $user->group_id) {
       $user->group_id = $group->id;
       $user->save();
      }

      //CREATES A CROSS-TABLE RECORD FOR THIS USER-GROUP PAIR
      try{
        \DB::table('group_user')
           ->insert(['user_id' => $user->id,
                     'group_id' => $group->id,
                     'created_at' => date("Y-m-d H:i:s"),
                     'updated_at' => date("Y-m-d H:i:s")]);
      }

      catch(\Exception $e){
        // Will throw an exception if the group ID and user ID are duplicates. Just ignore
      }


      // If this is a newly created group, create some tasks if requested
      // IF THERE IS A TASK_PACKAGE SPECIFIED
      if(isset($request->task_package)) {
        //ASSIGN MEMORY TASKS:q
        
        if($request->task_package == 'group-memory'){
          \Teamwork\GroupTask::initializeMemoryTasks(\Auth::user()->group_id, $randomize = false);
          $user->group_role = 'leader';
          $user->save();
          return redirect('/task-room');
        }
        elseif($request->task_package == 'test'){
          \Teamwork\GroupTask::initializeTestTasks(\Auth::user()->group_id, $randomize = false);
        }
        elseif($request->task_package == 'group-1'){
          \Teamwork\GroupTask::initializeGroupOneTasks(\Auth::user()->group_id, $randomize = false);
        }
        elseif($request->task_package == 'group-2'){
          \Teamwork\GroupTask::initializeGroupTwoTasks(\Auth::user()->group_id, $randomize = false);
        }
        elseif($request->task_package == 'group-3'){
          \Teamwork\GroupTask::initializeGroupThreeTasks(\Auth::user()->group_id, $randomize = false);
        }
        elseif($request->task_package == 'group-test'){
          \Teamwork\GroupTask::initializeGroupTestTasks(\Auth::user()->group_id, $randomize = false);
        }
        //ASSIGN CRYPTO PILOT TASKS
        elseif($request->task_package == 'crypto-pilot'){
          //CHECKS IF USER HAS CONSENTED PREVIOUSLY
          $sig = \Teamwork\Response::where('user_id',\Auth::user()->id)->where('prompt','signature')->get();

          if(count($sig) > 0)
            \Teamwork\GroupTask::initializeCryptoPilotNoConsentTasks(\Auth::user()->group_id, $randomize = false);
          else
            \Teamwork\GroupTask::initializeCryptoPilotTasks(\Auth::user()->group_id, $randomize = false);
        }
        //ASSIGN COMBINED PILOT TASKS
        elseif($request->task_package == 'combined-pilot'){
          \Teamwork\GroupTask::initializeCombinedPilotTasks(\Auth::user()->group_id, $randomize = false);
          return redirect('/get-individual-task');
        }
        //ASSIGNS PHASE 1 PILOT TASKS
        elseif($request->task_package == 'individual-pilot'){
          \Teamwork\GroupTask::initializeLabIndividualPilotTasks(\Auth::user()->group_id, $randomize = false);
          return redirect('/get-individual-task');
        }
        elseif($request->task_package == 'lab-round-3'){
          \Teamwork\GroupTask::initializeLabRoundThreeTasks(\Auth::user()->group_id, $randomize = false);
        }
        elseif($request->task_package == 'lab-round-4'){
          \Teamwork\GroupTask::initializeLabRoundFourTasks(\Auth::user()->group_id, $randomize = false);
        }
        elseif($request->task_package == 'lab-round-5'){
          \Teamwork\GroupTask::initializeLabRoundFiveTasks(\Auth::user()->group_id, $randomize = false);
        }
        elseif($request->task_package == 'waiting-room'){
          \Teamwork\GroupTask::initializeWaitingRoomTasks(\Auth::user()->group_id, $randomize = false);
        }
        //IF TASK_PACKAGE UNIDENTIFIABLE, ASSIGN CRYPTO PILOT TASK LIST
        else{
          \Teamwork\GroupTask::initializeCryptoPilotTasks($group->id, $randomize = false);
        }
      }
      //IF NO TASK_PACKAGE SPECIFIED, ASSIGN CRYPTO PILOT TASK LIST
      else{
        //CHECK IF CONSENTED PRIOR
        $sig = \Teamwork\Response::where('user_id',\Auth::user()->id)->where('prompt','signature')->get();
        if(count($sig) > 0)
          \Teamwork\GroupTask::initializeCryptoPilotNoConsentTasks(\Auth::user()->group_id, $randomize = false);
        else
          \Teamwork\GroupTask::initializeCryptoPilotTasks(\Auth::user()->group_id, $randomize = false);
      }

      return redirect('/get-group-task');

    }

    //LEGACY CODE
    //NOT CURRENTLY USED
    public function individualLogin() {
      return view('layouts.participants.individual-only-login');
    }

    //LEGACY CODE
    public function individualPackageLogin(Request $request, $package) {
      return view('layouts.participants.individual-only-login')
             ->with('package', $package)
             ->with('surveyCode', $request->c);
    }

    //LEGACY CODE
    public function postIndividualLogin(Request $request) {

      // See if this user already exists
      $user = User::where('participant_id', $request->participant_id)->first();


      if($user) {
        \Auth::login($user);
      }

      else {
        // Create a group
        $group = Group::firstOrCreate(['group_number' => uniqid()]);
        $group->save();
        $user = User::firstOrCreate(['participant_id' => $request->participant_id],
                                    ['name' => 'participant',
                                     'survey_code' => $request->survey_code,
                                     'participant_id' => $request->participant_id,
                                     'password' => bcrypt('participant'),
                                     'role_id' => 3,
                                     'group_id' => $group->id]);
        $user->save();
        \Auth::login($user);
        \DB::table('group_user')
           ->insert(['user_id' => $user->id,
                     'group_id' => $group->id,
                     'created_at' => date("Y-m-d H:i:s"),
                     'updated_at' => date("Y-m-d H:i:s")]);

        if(isset($request->task_package)) {
          if($request->task_package == 'eq') \Teamwork\GroupTask::initializeEQTasks(\Auth::user()->group_id, $randomize = false);
          if($request->task_package == 'iq') \Teamwork\GroupTask::initializeIQTasks(\Auth::user()->group_id, $randomize = false);
          if($request->task_package == 'block-a') \Teamwork\GroupTask::initializeBlockATasks(\Auth::user()->group_id, $randomize = false);
          if($request->task_package == 'block-b') \Teamwork\GroupTask::initializeBlockBTasks(\Auth::user()->group_id, $randomize = false);
          if($request->task_package == 'block-c') \Teamwork\GroupTask::initializeBlockCTasks(\Auth::user()->group_id, $randomize = false);
          if($request->task_package == 'block-d') \Teamwork\GroupTask::initializeBlockDTasks(\Auth::user()->group_id, $randomize = false);
          if($request->task_package == 'assign-block') \Teamwork\GroupTask::initializeAssignedBlockTasks(\Auth::user()->group_id, $randomize = false);
          if($request->task_package == 'memory') \Teamwork\GroupTask::initializeMemoryTasks(\Auth::user()->group_id, $randomize = false);
          if($request->task_package == 'testing-block') \Teamwork\GroupTask::initializeTestingTasks(\Auth::user()->group_id, $randomize = false);
          if($request->task_package == 'hdsl') \Teamwork\GroupTask::initializeLabIndividualTasks(\Auth::user()->group_id, $randomize = false);


        }
        else
          \Teamwork\GroupTask::initializeLabIndividualPilotTasks(\Auth::user()->group_id, $randomize = false);
      }

      return redirect('/get-individual-task');
    }

    //LEGACY CODE
    public function retryIndividual() {

      $group = Group::firstOrCreate(['group_number' => uniqid()]);
      $group->save();
      $user = \Auth::user();
      $user->group_id = $group->id;
      $user->save();

      \DB::table('group_user')
         ->insert(['user_id' => $user->id,
                   'group_id' => $group->id,
                   'created_at' => date("Y-m-d H:i:s"),
                   'updated_at' => date("Y-m-d H:i:s")]);

      \Teamwork\GroupTask::initializeLabIndividualTasks(\Auth::user()->group_id, $randomize = false);
      return redirect('/get-individual-task');
    }

    //LEGACY CODE
    public function groupLogin() {
      return view('layouts.participants.group-login');
    }

    //LEGACY CODE
    public function postGroupLogin(Request $request) {

      $group = Group::firstOrCreate(['group_number' => $request->group_id]);
      $group->save();

      // Find or create a group user, for authentication purposes
      $user = User::firstOrCreate(['group_id' => $group->id,
                                   'role_id' => 4],
                                  ['name' => 'group',
                                   'participant_id' => null,
                                   'password' => bcrypt('group')]);
      \Auth::login($user);

      return redirect('/get-group-task');
    }

    //LEGACY CODE
    public function groupCreateLogin() {
      $tasks = \Teamwork\GroupTask::getTasks();
      return view('layouts.participants.group-create-login')
             ->with('tasks', $tasks);
    }

    //LEGACY CODE
    public function postGroupCreateLogin(Request $request) {

      $group = Group::firstOrCreate(['group_number' => $request->group_id]);
      $group->save();

      // Find or create a group user, for authentication purposes
      $user = User::firstOrCreate(['group_id' => $group->id,
                                   'role_id' => 4],
                                  ['name' => 'group',
                                   'participant_id' => null,
                                   'password' => bcrypt('group')]);

      \Teamwork\GroupTask::initializeTasks($group->id, $request->taskArray);

      $tasks = \Teamwork\GroupTask::getTasks();

      \Session::flash('message','Group ' .$request->group_id. ' was created.');
      return redirect('/group-create');
    }

    //LEGACY CODE
    public function groupAddParticipants() {
      return view('layouts.participants.group-add-participants');
    }

    //LEGACY CODE
    public function postGroupAddParticipants(Request $request) {

      $group = Group::firstOrCreate(['group_number' => $request->group_id]);
      $group->save();

      $participants = explode(';', $request->participant_ids);

      foreach ($participants as $key => $participant_id) {
        $user = User::firstOrCreate(['participant_id' => trim($participant_id)],
                                    ['name' => 'partipant',
                                     'participant_id' => trim($participant_id),
                                     'password' => bcrypt('participant'),
                                     'role_id' => 3,
                                     'group_id' => $group->id]);
        $user->save();
      }

      \Session::flash('message', 'Participant IDs '.$request->participant_ids. ' were added to group '.$group->id);
      return redirect('/group-add-participants');

    }
}
