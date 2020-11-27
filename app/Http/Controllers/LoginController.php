<?php

namespace Teamwork\Http\Controllers;

use Illuminate\Http\Request;
use Teamwork\User;
use Teamwork\Group;

class LoginController extends Controller
{
    public function participantLogin() {
      return view('layouts.participants.participant-login');
    }

    public function participantPackageLogin($package) {

      return view('layouts.participants.participant-login')
             ->with('package', $package);
    }

    public function postParticipantLogin(Request $request) {
      $group = Group::where('group_number', $request->group_id)->first();
      $newGroup = false;
      // If the group doesn't exist yet, create it
      if(!$group){
        $newGroup = true;
        $group = new Group;
        $group->group_number = $request->group_id;
        $group->save();

      }

      // Create or find the user
      $user = User::firstOrCreate(['participant_id' => $request->participant_id],
                                  ['name' => 'participant',
                                   'participant_id' => $request->participant_id,
                                   'password' => bcrypt('participant'),
                                   'role_id' => 3,
                                   'group_id' => $group->id]);
      $user->save();
      \Auth::login($user);

      // If the user exists, update the user's group ID, if needed
      if($group->id != $user->group_id) {
       $user->group_id = $group->id;
       $user->save();
      }

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
      if($newGroup && isset($request->task_package)) {
       if($request->task_package == 'group-memory'){
         \Teamwork\GroupTask::initializeGroupMemoryTasks(\Auth::user()->group_id, $randomize = false);
       }
       if($request->task_package == 'group-1'){
         \Teamwork\GroupTask::initializeGroupOneTasks(\Auth::user()->group_id, $randomize = false);
       }
       if($request->task_package == 'group-2'){
         \Teamwork\GroupTask::initializeGroupTwoTasks(\Auth::user()->group_id, $randomize = false);
       }
       if($request->task_package == 'group-3'){
         \Teamwork\GroupTask::initializeGroupThreeTasks(\Auth::user()->group_id, $randomize = false);
       }
       if($request->task_package == 'group-test'){
         \Teamwork\GroupTask::initializeGroupTestTasks(\Auth::user()->group_id, $randomize = false);
       }
       if($request->task_package == 'lab-round-1'){
         \Teamwork\GroupTask::initializeLabRoundOneTasks(\Auth::user()->group_id, $randomize = false);
       }
       if($request->task_package == 'lab-round-2'){
         \Teamwork\GroupTask::initializeLabRoundTwoTasks(\Auth::user()->group_id, $randomize = false);
       }
       if($request->task_package == 'lab-round-3'){
         \Teamwork\GroupTask::initializeLabRoundThreeTasks(\Auth::user()->group_id, $randomize = false);
       }
       if($request->task_package == 'lab-round-4'){
         \Teamwork\GroupTask::initializeLabRoundFourTasks(\Auth::user()->group_id, $randomize = false);
       }
       if($request->task_package == 'lab-round-5'){
         \Teamwork\GroupTask::initializeLabRoundFiveTasks(\Auth::user()->group_id, $randomize = false);
       }
      }

      return redirect('/get-individual-task');
    }

    public function individualLogin() {
      return view('layouts.participants.individual-only-login');
    }

    public function individualPackageLogin(Request $request, $package) {
      return view('layouts.participants.individual-only-login')
             ->with('package', $package)
             ->with('surveyCode', $request->c);
    }

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
      }

      return redirect('/get-individual-task');
    }

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


    public function groupLogin() {
      return view('layouts.participants.group-login');
    }

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

    public function groupCreateLogin() {
      $tasks = \Teamwork\GroupTask::getTasks();
      return view('layouts.participants.group-create-login')
             ->with('tasks', $tasks);
    }

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

    public function groupAddParticipants() {
      return view('layouts.participants.group-add-participants');
    }

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
