<?php

namespace Teamwork\Http\Controllers;

use Illuminate\Http\Request;
use \Teamwork\Tasks as Task;
use Teamwork\Response;
use \Teamwork\Time;
use Illuminate\Support\Facades\Log;
use \Teamwork\User;

class IndividualTaskController extends Controller
{
  public function uniqidReal($lenght = 13) {
      // uniqid gives 13 chars, but you could adjust it to your needs.
      if (function_exists("random_bytes")) {
          $bytes = random_bytes(ceil($lenght / 2));
      } elseif (function_exists("openssl_random_pseudo_bytes")) {
          $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
      } else {
          throw new Exception("no cryptographically secure random function available");
      }
      return substr(bin2hex($bytes), 0, $lenght);
  }
    public function getTask(Request $request) {

      $groupTasksAll = \Teamwork\GroupTask::where('group_id', \Auth::user()->group_id)
                             ->with('individualTasks')
                             ->orderBy('order', 'ASC')
                             ->get();

     // Filter out any completed tasks
     $groupTasks = $groupTasksAll->filter(function ($value, $key) {
       return $value->completed == false;
     });


     if(count($groupTasksAll) > 0 && count($groupTasks) == 0) {
       // The experiment is over
       return redirect('/participant-experiment-end');
     }

     // If there are no tasks at all, let's create some
     else if(count($groupTasksAll) == 0) {
       // Alternately, we could display a message for the user to login as a group
       // because the following code is duplicated in the group task controller
       $groupTasks = \Teamwork\GroupTask::initializeDefaultTasks(\Auth::user()->group_id, $randomize = false);
     }

      $currentTask = $groupTasks->first();
      Log::debug('??????');
      Log::debug($currentTask);
      $individualTask = $currentTask->individualTasks->first();

      $request->session()->put('currentGroupTask', $currentTask->id);

        $request->session()->put('currentIndividualTask', $currentTask->individualTasks->first()->id);
        return $this->routeTask($currentTask);
    }

    public function routeTask($task) {
      // Calculate how many tasks are completed, and how many more to go...
      $this->getProgress();

      switch($task->name) {

        case "Consent":
          request()->session()->put('currentIndividualTaskName', 'Consent');
          return redirect('/study-consent');

        case "Intro":
          request()->session()->put('currentIndividualTaskName', 'Intro');
          return redirect('/study-intro');

        case "DeviceCheck":
          request()->session()->put('currentIndividualTaskName','DeviceCheck');
          return redirect('/device-check');

        case "ChooseReporter":
          return redirect('/choose-reporter');

        case "Teammates":
          return redirect('/teammates');

        case "TeamRole":
          request()->session()->put('currentIndividualTaskName', 'Team Role Task');
          return redirect('/team-role-intro');

        case "BigFive":
          request()->session()->put('currentIndividualTaskName', 'Big Five Task');
          return redirect('/big-five-intro');

        case "Cryptography":
          request()->session()->put('currentIndividualTaskName', 'Cryptography Task');
          return redirect('/cryptography-individual-intro');

        case "Optimization":
          request()->session()->put('currentIndividualTaskName', 'Optimization Task');
          return redirect('/optimization-individual-intro');

        case "Memory":
          request()->session()->put('currentIndividualTaskName', 'Memory Task');
          return redirect('/memory-individual');

        case "Eyes":
          request()->session()->put('currentIndividualTaskName', 'Eyes Task');
          return redirect('/rmet-individual-intro');

        case "Brainstorming":
          request()->session()->put('currentIndividualTaskName', 'Brainstorming Task');
          return redirect('/brainstorming-individual-intro');

        case "Shapes":
          request()->session()->put('currentIndividualTaskName', 'Shapes Task');
          return redirect('/shapes-individual-intro');

        case "Feedback":
          request()->session()->put('currentIndividualTaskName', 'Feedback');
          return redirect('/study-feedback');

        case "Survey":
          request()->session()->put('currentIndividualTaskName', 'Demographics');
          return redirect('/survey');

        case "Conclusion":
          request()->session()->put('currentIndividualTaskName', 'Conclusion');
          return redirect('/check-for-confirmation-code');

        case "PsiIri":
          request()->session()->put('currentIndividualTaskName', 'PsiIri');
          return redirect('/psi-iri-intro');

        case "Leadership":
          request()->session()->put('currentIndividualTaskName', 'Leadership');
          return redirect('/leadership-intro');
      }
    }

    public function showTaskResults(Request $request) {
      return view('layouts.participants.tasks.participant-task-results')
             ->with('taskName', $request->session()->get('currentIndividualTaskName'))
             ->with('results', $request->session()->get('currentIndividualTaskResult'));

    }

    public function submitConsent(Request $request) {
      $user = User::find(\Auth::user()->id);


      $r = new Response;
        $r->group_tasks_id = $request->session()->get('currentGroupTask');
        $r->user_id = $user->id;
        $r->prompt = 'signature';
        $r->response = $request->signature;
        $r->save();
      return '200';
    }

    public function endTask(Request $request) {

      Log::debug('ENDING');



      $task = \Teamwork\GroupTask::with('response')
                                 ->find($request->session()->get('currentGroupTask'));

      Log::debug($task);

      // If this is an individual-only task, mark it as done
      $parameters = unserialize($task->parameters);

        $task->completed = 1;
        $task->save();
        Log::debug('###');
        Log::debug($task);
        return redirect('/get-individual-task');
    }

    public function endExperiment() {
      return view('layouts.participants.participant-experiment-end');
    }

    public function studyConsent(Request $request) {
      if(config('app.debug') == true){
        $this->getProgress();
        $this_user = User::where('id',\Auth::user()->id)->first();
        Log::debug('???');
        Log::debug($this_user);

        $currentTask = \Teamwork\GroupTask::where('name','Consent')->where('group_id',$this_user->group_id)->first();
        Log::debug($currentTask);

        $prior_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','<',$currentTask->order)->get();
        $later_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','>=',$currentTask->order)->get();
        foreach($prior_tasks as $key => $prior_task){
          $prior_task->completed = 1;
          $prior_task->save();
        }
        foreach($later_tasks as $key => $later_task){
          $later_task->completed = 0;
          $later_task->save();
        }

        request()->session()->put('currentGroupTask', $currentTask->id);
        //request()->session()->put('currentIndividualTask', \Teamwork\IndividualTask::where('group_task_id',$currentTask->id)->first()->id);
        //request()->session()->put('currentIndividualTaskName', 'Consent');



      }
      else
        $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      

      $this->recordStartTime($request, 'intro');
      //$currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      //$currentTask = \Teamwork\GroupTask::where('name','Consent')->where('group_id',$this_user->group_id)->first();
      
      
      $parameters = unserialize($currentTask->parameters);
      return view('layouts.participants.participant-study-consent')
             ->with('subjectPool', $parameters->subjectPool)
             ->with('url_endpoint',$parameters->url_endpoint);
    }

    public function noStudyConsent(Request $request) {
      return view('layouts.participants.participant-no-study-consent');
    }

    public function chooseReporter(Request $request) {

      return view('layouts.participants.choose-reporter')
             ->with('taskId', $request->session()->get('currentGroupTask'));
    }

    public function setReporter($choice, Request $request) {

      // Save this user's task progress
      $progress = new \Teamwork\Progress;
      $progress->user_id = \Auth::user()->id;
      $progress->group_id = \Auth::user()->group_id;
      $progress->group_tasks_id = $request->session()->get('currentGroupTask');
      $progress->save();

      $task = \Teamwork\GroupTask::with('response')
                                        ->with('progress')
                                        ->find($request->session()->get('currentGroupTask'));

      if($choice == 'true') {
        try{
          \DB::table('reporters')
              ->insert(['user_id' => \Auth::user()->id,
                        'group_id' => \Auth::user()->group_id,
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s")]);
        }
        catch(\Exception $e) {
          if($e->getCode() == '23000') {
            $request->session()->put('msg', 'Someone in your group has already volunteered to be the Reporter. You will NOT be the Reporter.');
            return redirect('/reporter-chosen');
          }
          else return redirect('/choose-reporter');
        }
      }

      // Check if a reporter has been chosen. If not, the last member of the group
      // will be the reporter.
      else {
        $reporter = \DB::table('reporters')
                        ->where('group_id', \Auth::user()->group_id)
                        ->first();
        if(!$reporter){

          $usersInGroup = \Teamwork\User::where('group_id', \Auth::user()->group_id)
                                        ->where('role_id', 3)
                                        ->count();

          $numUsersCompleted = count($task->progress->groupBy('user_id'));

          if($numUsersCompleted == $usersInGroup){
            \DB::table('reporters')
                ->insert(['user_id' => \Auth::user()->id,
                          'group_id' => \Auth::user()->group_id,
                          'created_at' => date("Y-m-d H:i:s"),
                          'updated_at' => date("Y-m-d H:i:s")]);

            $request->session()->put('msg', 'The other members of your group have chosen not to be The Reporter. So, you have been assigned this role! You are now The Reporter');
            return redirect('/reporter-chosen');
          }
        }

      }

      $request->session()->put('waitingMsg', 'Please wait for the other members in your group to make their selection.');
      return redirect('/end-group-task');
    }

    public function reporterChosen() {
      return view('layouts.participants.reporter-chosen');
    }

    public function getTeammates(Request $request) {

      return view('layouts.participants.participant-study-teammates');
    }

    public function saveTeammates(Request $request) {
      \DB::table('teammates')
         ->insert(['user_id' => \Auth::user()->id,
                   'group_id' => \Auth::user()->group_id,
                   'know_teammates' => $request->teammates,
                   'created_at' => date("Y-m-d H:i:s"),
                   'updated_at' => date("Y-m-d H:i:s")]);
      return redirect('/end-individual-task');
    }

    public function studyIntro(Request $request) {
      if(config('app.debug') == true){
        $this->getProgress();
        $this_user = User::where('id',\Auth::user()->id)->first();
        $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
        if($currentTask->name != 'Intro')
          $currentTask = \Teamwork\GroupTask::where('name','Intro')->where('group_id',$this_user->group_id)->first();


        $prior_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','<',$currentTask->order)->get();
        $later_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','>=',$currentTask->order)->get();
        foreach($prior_tasks as $key => $prior_task){
          $prior_task->completed = 1;
          $prior_task->save();
        }
        foreach($later_tasks as $key => $later_task){
          $later_task->completed = 0;
          $later_task->save();
        }

        request()->session()->put('currentGroupTask', $currentTask->id);
        #request()->session()->put('currentIndividualTask', \Teamwork\IndividualTask::where('group_task_id',$currentTask->id)->first()->id);
        #request()->session()->put('currentIndividualTaskName', 'Intro');



      }
      else
        $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));


      $this->recordStartTime($request, 'intro');
      $parameters = unserialize($currentTask->parameters);
      $introContent = (new \Teamwork\Tasks\Intro)->getIntro($parameters->type);

      return view('layouts.participants.participant-study-intro')
             ->with('introContent', $introContent);
    }

    public function studyFeedback(Request $request) {
      $this->recordStartTime($request, 'intro');
      $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $parameters = unserialize($currentTask->parameters);
      $feedbackMessage = (new \Teamwork\Tasks\Feedback)->getMessage($parameters->type);
      $hasCode = ($parameters->hasCode == 'true') ? true : false;
      return view('layouts.participants.participant-study-feedback')
             ->with('feedbackMessage', $feedbackMessage)
             ->with('hasCode', $hasCode);
    }

    public function postStudyFeedback(Request $request) {
      $this->recordEndTime($request, 'intro');
      $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $individualTaskId = $request->session()->get('currentIndividualTask');

      $r = new Response;
      $r->group_tasks_id = $currentTask->id;
      $r->individual_tasks_id = $individualTaskId;
      $r->user_id = \Auth::user()->id;
      $r->prompt = 'Study feedback';
      $r->response = $request->feedback;
      $r->save();
      return redirect('/end-individual-task');
    }

    public function survey(Request $request) {
      $this->recordStartTime($request, 'intro');
      $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $parameters = unserialize($currentTask->parameters);
      if(!isset($parameters->survey)) $survey == 'hdsl';
      else $survey = $parameters->survey;
      return view('layouts.participants.participant-survey')
             ->with('survey', $survey);
    }

    public function saveSurvey(Request $request) {
      $this->recordEndTime($request, 'intro');
      $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $individualTaskId = $request->session()->get('currentIndividualTask');
      foreach ($request->all() as $key => $answer) {
        if($key == '_token') continue;
          $r = new Response;
          $r->group_tasks_id = $currentTask->id;
          $r->individual_tasks_id = $individualTaskId;
          $r->user_id = \Auth::user()->id;
          $r->prompt = 'Survey: ' .$key;
          $r->response = $answer;
          $r->save();
      }
      return redirect('/end-individual-task');
    }

    public function pickLeader(Request $request) {
      $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $r = Response::where('group_tasks_id',$currentTask->id)->
                     where('user_id',\Auth::user()->id)->
                     where('prompt','role_select')
                     ->first();
      if(!$r){
        $r = new Response;
        $r->group_tasks_id = $currentTask->id;
        $r->user_id = \Auth::user()->id;
        $r->prompt = 'role_select';
        $r->response = 'leader';
        $r->save();
      }

      return redirect('/end-individual-task');
    }

    public function pickMember(Request $request) {
      $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $r = Response::where('group_tasks_id',$currentTask->id)->
                     where('user_id',\Auth::user()->id)->
                     where('prompt','role_select')
                     ->first();
      if(!$r){
        $r = new Response;
        $r->group_tasks_id = $currentTask->id;
        $r->user_id = \Auth::user()->id;
        $r->prompt = 'role_select';
        $r->response = 'member';
        $r->save();
      }

      return redirect('/end-individual-task');
    }

    public function checkForConfirmationCode(Request $request) {
      $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $parameters = unserialize($currentTask->parameters);
      $conclusion = new \Teamwork\Tasks\Conclusion;
      if($parameters->hasCode == 'true') {
        $code = $conclusion->newConfirmationCode($parameters->type);
        $code->user_id = \Auth::user()->id;
        $code->save();
      }
      return redirect('/study-conclusion');
    }

    public function studyConclusion(Request $request) {
      if(config('app.debug') == true){
        $this->getProgress();
        $this_user = User::where('id',\Auth::user()->id)->first();

        $currentTask = \Teamwork\GroupTask::where('name','Conclusion')->where('group_id',$this_user->group_id)->first();

        $prior_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','<',$currentTask->order)->get();
        $later_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','>=',$currentTask->order)->get();
        foreach($prior_tasks as $key => $prior_task){
          $prior_task->completed = 1;
          $prior_task->save();
        }
        foreach($later_tasks as $key => $later_task){
          $later_task->completed = 0;
          $later_task->save();
        }

        request()->session()->put('currentGroupTask', $currentTask->id);
        request()->session()->put('currentIndividualTask', \Teamwork\IndividualTask::where('group_task_id',$currentTask->id)->first()->id);
        request()->session()->put('currentIndividualTaskName', 'Conclusion');



      }
      else
        $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));


      //$currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $parameters = unserialize($currentTask->parameters);
      $conclusion = new \Teamwork\Tasks\Conclusion;
      $conclusionContent = $conclusion->getConclusion($parameters->type);

      $user = \Teamwork\User::find(\Auth::user()->id);
      if(!$user->survey_code){
        $user->survey_code = $this->uniqidReal();
      }
      #$finalScore = $this->calculateScore(\Auth::user()->group_id);
      #$user->score = $finalScore;
      $user->save();

      if($parameters->displayScoreGroup == 'true') {

        $eligiblityStats = $this->calculateEligibility(\Auth::user()->group_id);

        $user->score_group = $eligiblityStats['passed'];
        $user->save();
      }

      else $eligiblityStats = null;

      if($eligiblityStats) {
        $eligibility = $eligiblityStats['passed'];

      }
      else $eligibility = false;

      if($parameters->digitalReceipt == 'true') {
        $receiptSonaId = $parameters->sonaId;
      }
      else $receiptSonaId = null;

      if(isset($parameters->payment)){
        $payment = $parameters->payment;
      }

      else $payment = null;

      if($parameters->feedback == 'true') {
        $feedbackLink = $conclusion->getFeedbackLink($parameters->feedbackLinkType);
      }
      else $feedbackLink = null;

      if($parameters->hasCode == 'true') {
        $code = $conclusion->getConfirmationCode(\Auth::user()->id)->code;
      }
      else $code = null;

      if(\Auth::user()->survey_code){
        $url = 'https://harvarddecisionlab.sona-systems.com/services/SonaAPI.svc/WebstudyCredit?experiment_id=549&credit_token=0d9329ec68f446afa677b3dff496e3db&survey_code='.\Auth::user()->survey_code;
        file_get_contents($url);
      }


      return view('layouts.participants.participant-study-conclusion')
             ->with('conclusionContent', $conclusionContent)
             ->with('code', $user->survey_code)
             ->with('score', false)
             ->with('checkEligibility', $parameters->displayScoreGroup == 'true')
             ->with('eligible', $eligibility)
             ->with('feedbackLink', $feedbackLink)
             ->with('receiptSonaId', $receiptSonaId)
             ->with('payment', $payment);
    }

   

    public function teamRoleIntro(Request $request) {
      $this->recordStartTime($request, 'intro');

      return view('layouts.participants.tasks.team-role-intro');
    }

    public function teamRole(Request $request) {
      $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $parameters = unserialize($currentTask->parameters);

      $scenarios = (new \Teamwork\Tasks\TeamRole)->getScenarios($parameters->scenarios);

      // Record the end time for this task's intro
      $this->recordEndTime($request, 'intro');


      // Record the start time for this task
      $this->recordStartTime($request, 'task');

      return view('layouts.participants.tasks.team-role')
             ->with('scenarios', $scenarios);
    }

    public function saveTeamRole(Request $request) {
      $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $individualTaskId = $request->session()->get('currentIndividualTask');
      $parameters = unserialize($currentTask->parameters);
      $scenarios = (new \Teamwork\Tasks\TeamRole)->getScenarios($parameters->scenarios);

      // Record the end time for this task
      $this->recordEndTime($request, 'task');

      // Save each response
      foreach ($request->all() as $key => $answer) {
        if($key == '_token') continue;
        $indices = explode('_', $key);
        $scenario = $scenarios[$indices[1]]['responses'][$indices[3]];

        $r = new Response;
        $r->group_tasks_id = $currentTask->id;
        $r->individual_tasks_id = $individualTaskId;
        $r->user_id = \Auth::user()->id;
        $r->prompt = $scenario['response'];
        $r->response = $answer;
        if($scenario['scoring'] == 'reverse') $r->points = 3 - $answer;
        else $r->points = $answer - 3;
        $r->save();
      }

      $results = 'You have now completed the Team Role Test.';
      $request->session()->put('currentIndividualTaskResult', $results);
      $request->session()->put('currentIndividualTaskName', 'Team Role Test');

      return redirect('\individual-task-results');
    }

    public function teamRoleEnd(Request $request) {
      return view('layouts.participants.tasks.team-role-end');
    }

    public function bigFiveIntro(Request $request) {
      if(config('app.debug') == true){
        $this->getProgress();
        $this_user = User::where('id',\Auth::user()->id)->first();

        $currentTask = \Teamwork\GroupTask::where('name','BigFive')->where('group_id',$this_user->group_id)->first();

        $prior_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','<',$currentTask->order)->get();
        $later_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','>=',$currentTask->order)->get();
        foreach($prior_tasks as $key => $prior_task){
          $prior_task->completed = 1;
          $prior_task->save();
        }
        foreach($later_tasks as $key => $later_task){
          $later_task->completed = 0;
          $later_task->save();
        }

        request()->session()->put('currentGroupTask', $currentTask->id);
        request()->session()->put('currentIndividualTask', \Teamwork\IndividualTask::where('group_task_id',$currentTask->id)->first()->id);
        request()->session()->put('currentIndividualTaskName', 'BigFive');



      }
      else
        $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));

      $this->recordStartTime($request, 'intro');
      return view('layouts.participants.tasks.big-five-intro');
    }

    public function leadershipIntro(Request $request) {
      if(config('app.debug') == true){
        $this->getProgress();
        $this_user = User::where('id',\Auth::user()->id)->first();

        $currentTask = \Teamwork\GroupTask::where('name','Leadership')->where('group_id',$this_user->group_id)->first();

        $prior_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','<',$currentTask->order)->get();
        $later_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','>=',$currentTask->order)->get();
        foreach($prior_tasks as $key => $prior_task){
          $prior_task->completed = 1;
          $prior_task->save();
        }
        foreach($later_tasks as $key => $later_task){
          $later_task->completed = 0;
          $later_task->save();
        }

        request()->session()->put('currentGroupTask', $currentTask->id);
        request()->session()->put('currentIndividualTask', \Teamwork\IndividualTask::where('group_task_id',$currentTask->id)->first()->id);
        request()->session()->put('currentIndividualTaskName', 'Leadership');



      }
      else
        $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));

      $this->recordStartTime($request, 'intro');
      return view('layouts.participants.tasks.leadership-intro');
    }

    public function leadership(Request $request) {

      $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      Log::debug($currentTask);

      // Record end time for task's intro
      $this->recordEndTime($request, 'intro');
      //Log::debug($currentTask);

      //$currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $parameters = unserialize($currentTask->parameters);
      //Log::debug($parameters);
      $statements = (new \Teamwork\Tasks\Leadership)->getStatements($parameters->statementOrder);

      // Record the start time for this task
      $this->recordStartTime($request, 'task');

      return view('layouts.participants.tasks.leadership')
             ->with('statements', $statements);
    }

    public function psiIriIntro(Request $request) {
      if(config('app.debug') == true){
        $this->getProgress();
        $this_user = User::where('id',\Auth::user()->id)->first();

        $currentTask = \Teamwork\GroupTask::where('name','PsiIri')->where('group_id',$this_user->group_id)->first();

        $prior_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','<',$currentTask->order)->get();
        $later_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','>=',$currentTask->order)->get();
        foreach($prior_tasks as $key => $prior_task){
          $prior_task->completed = 1;
          $prior_task->save();
        }
        foreach($later_tasks as $key => $later_task){
          $later_task->completed = 0;
          $later_task->save();
        }

        request()->session()->put('currentGroupTask', $currentTask->id);
        request()->session()->put('currentIndividualTask', \Teamwork\IndividualTask::where('group_task_id',$currentTask->id)->first()->id);
        request()->session()->put('currentIndividualTaskName', 'PsiIri');



      }
      else
        $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));

      $this->recordStartTime($request, 'intro');
      return view('layouts.participants.tasks.psi-iri-intro');
    }

    public function psiIri(Request $request) {
      if(config('app.debug') == true){
        $this->getProgress();
        $this_user = User::where('id',\Auth::user()->id)->first();

        $currentTask = \Teamwork\GroupTask::where('name','PsiIri')->where('group_id',$this_user->group_id)->first();

        $prior_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','<',$currentTask->order)->get();
        $later_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','>=',$currentTask->order)->get();
        foreach($prior_tasks as $key => $prior_task){
          $prior_task->completed = 1;
          $prior_task->save();
        }
        foreach($later_tasks as $key => $later_task){
          $later_task->completed = 0;
          $later_task->save();
        }

        request()->session()->put('currentGroupTask', $currentTask->id);
        request()->session()->put('currentIndividualTask', \Teamwork\IndividualTask::where('group_task_id',$currentTask->id)->first()->id);
        request()->session()->put('currentIndividualTaskName', 'PsiIri');



      }
      else
        $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));

      // Record end time for task's intro
      $this->recordEndTime($request, 'intro');

      //$currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $parameters = unserialize($currentTask->parameters);
      $statements = (new \Teamwork\Tasks\PsiIri)->getStatements($parameters->statementOrder);

      // Record the start time for this task
      $this->recordStartTime($request, 'task');

      return view('layouts.participants.tasks.psi-iri')
             ->with('statements', $statements);
    }

    public function savePsiIri(Request $request) {
      $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $individualTaskId = $request->session()->get('currentIndividualTask');
      $parameters = unserialize($currentTask->parameters);
      $statements = (new \Teamwork\Tasks\PsiIri)->getStatements('ordered');

      // Record the end time for this task
      $this->recordEndTime($request, 'task');

      foreach ($statements as $key => $statement) {
        $r = new Response;
        $r->group_tasks_id = $currentTask->id;
        $r->individual_tasks_id = $individualTaskId;
        $r->user_id = \Auth::user()->id;
        $r->prompt = $statement['statement'];
        $r->response = $request[$statement['number']];
        $r->save();
      }

      return redirect('/end-individual-task');
    }

    public function psiIriEnd(Request $request) {
      return view('layouts.participants.tasks.psi-iri-end');
    }

    public function saveLeadership(Request $request) {
      $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $individualTaskId = $request->session()->get('currentIndividualTask');
      $parameters = unserialize($currentTask->parameters);
      $statements = (new \Teamwork\Tasks\Leadership)->getStatements('ordered');

      // Record the end time for this task
      $this->recordEndTime($request, 'task');

      foreach ($statements as $key => $statement) {
        $r = new Response;
        $r->group_tasks_id = $currentTask->id;
        $r->individual_tasks_id = $individualTaskId;
        $r->user_id = \Auth::user()->id;
        $r->prompt = $statement['statement'];
        $r->response = $request[$statement['number']];
        $r->save();
      }

      return redirect('/end-individual-task');
    }

    public function leadershipEnd(Request $request) {
      return view('layouts.participants.tasks.leadership-end');
    }

    public function bigFive(Request $request) {
      if(config('app.debug') == true){
        $this->getProgress();
        $this_user = User::where('id',\Auth::user()->id)->first();

        $currentTask = \Teamwork\GroupTask::where('name','BigFive')->where('group_id',$this_user->group_id)->first();

        $prior_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','<',$currentTask->order)->get();
        $later_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','>=',$currentTask->order)->get();
        foreach($prior_tasks as $key => $prior_task){
          $prior_task->completed = 1;
          $prior_task->save();
        }
        foreach($later_tasks as $key => $later_task){
          $later_task->completed = 0;
          $later_task->save();
        }

        request()->session()->put('currentGroupTask', $currentTask->id);
        request()->session()->put('currentIndividualTask', \Teamwork\IndividualTask::where('group_task_id',$currentTask->id)->first()->id);
        request()->session()->put('currentIndividualTaskName', 'BigFive');



      }
      else
        $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));

      // Record end time for task's intro
      $this->recordEndTime($request, 'intro');

      //$currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $parameters = unserialize($currentTask->parameters);
      $statements = (new \Teamwork\Tasks\BigFive)->getStatements($parameters->statementOrder);

      // Record the start time for this task
      $this->recordStartTime($request, 'task');

      return view('layouts.participants.tasks.big-five')
             ->with('statements', $statements);
    }

    public function saveBigFive(Request $request) {
      $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $individualTaskId = $request->session()->get('currentIndividualTask');
      $parameters = unserialize($currentTask->parameters);
      $statements = (new \Teamwork\Tasks\BigFive)->getStatements('ordered');

      // Record the end time for this task
      $this->recordEndTime($request, 'task');

      foreach ($statements as $key => $statement) {
        $r = new Response;
        $r->group_tasks_id = $currentTask->id;
        $r->individual_tasks_id = $individualTaskId;
        $r->user_id = \Auth::user()->id;
        $r->prompt = $statement['statement'];
        $r->response = $request[$statement['number']];
        $r->save();
      }

      return redirect('/end-individual-task');
    }

    public function bigFiveEnd(Request $request) {
      return view('layouts.participants.tasks.big-five-end');
    }

    public function cryptographyIntro(Request $request) {
      if(config('app.debug') == true){
        $this->getProgress();
        $this_user = User::where('id',\Auth::user()->id)->first();

        $currentTask = \Teamwork\GroupTask::where('name','Cryptography')->where('completed',0)->where('group_id',$this_user->group_id)->first();

        $prior_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','<',$currentTask->order)->get();
        $later_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','>=',$currentTask->order)->get();
        foreach($prior_tasks as $key => $prior_task){
          $prior_task->completed = 1;
          $prior_task->save();
        }
        foreach($later_tasks as $key => $later_task){
          $later_task->completed = 0;
          $later_task->save();
        }

        request()->session()->put('currentGroupTask', $currentTask->id);
        request()->session()->put('currentIndividualTask', \Teamwork\IndividualTask::where('group_task_id',$currentTask->id)->first()->id);
        request()->session()->put('currentIndividualTaskName', 'Cryptography');



      }
      else
        $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));



      $this->recordStartTime($request, 'intro');

      //$currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $parameters = unserialize($currentTask->parameters);
      $maxResponses = $parameters->maxResponses;
      $mapping = (new \Teamwork\Tasks\Cryptography)->getMapping('random');
      $aSorted = $mapping;
      asort($aSorted); // Sort, but preserve key order
      $sorted = $mapping;
      sort($sorted); // Sort and re-index

      if($parameters->intro == 'individual_alt') {
        return view('layouts.participants.tasks.cryptography-individual-alt-intro')
               ->with('maxResponses', $maxResponses)
               ->with('mapping', json_encode($mapping))
                ->with('aSorted', $aSorted)
                ->with('sorted', $aSorted);
      }

      return view('layouts.participants.tasks.cryptography-individual-intro')
             ->with('maxResponses', $maxResponses)
             ->with('mapping', json_encode($mapping))
             ->with('aSorted', $aSorted)
             ->with('sorted', $aSorted);
    }

    public function deviceCheck(Request $request){
      return view('layouts.participants.device-check')
              ->with('user',$user = User::find(\Auth::user()->id));
    }

    public function cryptography(Request $request) {
      $user = User::find(\Auth::user()->id);
      //$isReporter = $this->isReporter(\Auth::user()->id, \Auth::user()->group_id);
      $this->recordEndTime($request, 'intro');
      $currentTask = \Teamwork\GroupTask::with('response')->find($request->session()->get('currentGroupTask'));
      //$whose_turn = $currentTask->whose_turn;
      //$currentTask->started = 1;
      //$currentTask->save();
      $parameters = unserialize($currentTask->parameters);
      $mapping = (new \Teamwork\Tasks\Cryptography)->getMapping($parameters->mapping);
      $maxResponses = $parameters->maxResponses;
      $sorted = $mapping;
      sort($sorted);


      $time = Time::where('user_id',\Auth::user()->id)
                    ->where('group_tasks_id',$request->session()->get('currentGroupTask'))
                    ->where('type','task')
                    ->first();
      if ($time){
        $time_remaining = 600 - \Carbon\Carbon::parse($time->start_time)->diffInSeconds(\Carbon\Carbon::now());
        
      }

      else{
        $this->recordStartTime($request, 'task');
        $time_remaining = 600;
      }

      return view('layouts.participants.tasks.cryptography-individual')
             ->with('user',$user)
             ->with('task_id',$currentTask->task_id)
             ->with('mapping',json_encode($mapping))
             ->with('sorted', $sorted)
             ->with('maxResponses', $maxResponses)
             ->with('isReporter', false)
             ->with('responses',$currentTask->response)
             ->with('time_remaining',$time_remaining)
             ->with('hasGroup', $parameters->hasGroup);
    }

    public function endCryptographyTask(Request $request) {

      $this->recordEndTime($request, 'task');
      $task = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $task->points = $request->task_result;
      $task->save();

      // Record the end time for this task
      $time = Time::where('user_id', '=', \Auth::user()->id)
                  ->where('group_tasks_id', '=', $task->id)
                  ->first();
      $time->recordEndTime();

      return redirect('/end-individual-task');
    }

    public function optimizationIntro(Request $request) {
      $this->recordStartTime($request, 'intro');

      $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $parameters = unserialize($currentTask->parameters);

      $totalTasks = \Teamwork\GroupTask::where('group_id', \Auth::user()->group_id)
                                       ->where('name', 'Optimization')
                                       ->get();
      $completedTasks = $totalTasks->filter(function($task){
        if($task->completed) { return $task; }
      });

      if($parameters->intro == 'individual_alt') {
        return redirect('/optimization-individual-alt-intro');
      }
      else return view('layouts.participants.tasks.optimization-individual-intro')
                  ->with('totalTasks', $totalTasks)
                  ->with('completedTasks', $completedTasks)
                  ->with('optFunction', 't1')
                  ->with('maxResponses', $parameters->maxResponses);
    }

    public function optimizationALtIntro(Request $request) {
      $this->recordStartTime($request, 'intro');

      $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $parameters = unserialize($currentTask->parameters);

      $totalTasks = \Teamwork\GroupTask::where('group_id', \Auth::user()->group_id)
                                       ->where('name', 'Optimization')
                                       ->get();
      $completedTasks = $totalTasks->filter(function($task){
        if($task->completed) { return $task; }
      });

      return view('layouts.participants.tasks.optimization-individual-alt-intro')
              ->with('totalTasks', $totalTasks)
              ->with('completedTasks', $completedTasks)
              ->with('maxResponses', $parameters->maxResponses);
    }

    public function optimization(Request $request) {
      $this->recordEndTime($request, 'intro');

      $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $parameters = unserialize($currentTask->parameters);
      $function = (new \Teamwork\Tasks\Optimization)->getFunction($parameters->function);
      $maxResponses = $parameters->maxResponses;

      // Record the start time for this task
      $this->recordStartTime($request, 'task');

      return view('layouts.participants.tasks.optimization-individual')
             ->with('function', $function)
             ->with('maxResponses', $maxResponses)
             ->with('hasGroup', $parameters->hasGroup);
    }

    public function saveOptimizationGuess(Request $request) {

      $groupTaskId = $request->session()->get('currentGroupTask');
      $individualTaskId = $request->session()->get('currentIndividualTask');

      $r = new Response;
      $r->group_tasks_id = $groupTaskId;
      $r->individual_tasks_id = $individualTaskId;
      $r->user_id = \Auth::user()->id;
      $r->prompt = $request->function;
      $r->response = $request->guess;
      $r->save();

    }

    public function saveOptimizationFinalGuess(Request $request) {

      $groupTaskId = $request->session()->get('currentGroupTask');
      $individualTaskId = $request->session()->get('currentIndividualTask');

      $currentTask = \Teamwork\GroupTask::find($groupTaskId);
      $parameters = unserialize($currentTask->parameters);
      $function = (new \Teamwork\Tasks\Optimization)->getFunction($parameters->function);

      $r = new Response;
      $r->group_tasks_id = $groupTaskId;
      $r->individual_tasks_id = $individualTaskId;
      $r->user_id = \Auth::user()->id;
      $r->prompt = 'final: '.$request->function;
      $r->response = $request->final_result;
      $r->save();

      // Record the end time for this task
      $this->recordEndTime($request, 'task');

      $request->session()->put('currentIndividualTaskResult', 'You have completed the Optimization Task.');
      $request->session()->put('currentIndividualTaskName', 'Optimization Task');

      $nextTask = \Teamwork\GroupTask::where('group_id', $currentTask->group_id)
                                     ->where('order', $currentTask->order + 1)
                                     ->first();

      // If there is another Optimization task coming, skip the task results page
      if($nextTask && $nextTask->name == 'Optimization') return redirect('/end-individual-task');

      return redirect('/individual-task-results');

    }

    public function memoryIntro(Request $request) {
      if(config('app.debug') == true){
        $this->getProgress();
        $this_user = User::where('id',\Auth::user()->id)->first();

        $currentTask = \Teamwork\GroupTask::where('name','Memory')->where('group_id',$this_user->group_id)->first();

        $prior_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','<',$currentTask->order)->get();
        $later_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','>=',$currentTask->order)->get();
        foreach($prior_tasks as $key => $prior_task){
          $prior_task->completed = 1;
          $prior_task->save();
        }
        foreach($later_tasks as $key => $later_task){
          $later_task->completed = 0;
          $later_task->save();
        }

        request()->session()->put('currentGroupTask', $currentTask->id);
        request()->session()->put('currentIndividualTask', \Teamwork\IndividualTask::where('group_task_id',$currentTask->id)->first()->id);
        request()->session()->put('currentIndividualTaskName', 'Memory');



      }
      else
        $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));

      $this->recordStartTime($request, 'intro');
      return view('layouts.participants.tasks.memory-individual-intro');
    }

    public function memory(Request $request) {

      //$this_user = User::where('id',\Auth::user()->id)->first();
      //request()->session()->put('currentIndividualTaskName', 'Memory');
      $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      //$currentTask = \Teamwork\GroupTask::where('name','Memory')->where('group_id',$this_user->group_id)->first();
      //request()->session()->put('currentIndividualTask', $currentTask->id);
      //request()->session()->put('currentGroupTask', $currentTask->id);

      //$currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));

      $parameters = unserialize($currentTask->parameters);
      $memory = new \Teamwork\Tasks\Memory;
      $test = $memory->getTest($parameters->test);
      $imgsToPreload = $memory->getImagesForPreloader($test['test_name']);
      if($test['task_type'] == 'intro') return redirect('/memory-individual-intro');
      if($test['task_type'] == 'results') return redirect('/memory-individual-results');
      if($test['type'] == 'intro') {
        $this->recordStartTime($request, 'intro');
      }

      else {
        $this->recordStartTime($request, 'task');
      }

      // Originally, there was an array of multiple tests. We've separated the
      // different memory tasks into individual tasks but to avoid rewriting a
      // lot of code, we'll construct a single-element array with the one test.
      return view('layouts.participants.tasks.memory-individual')
             ->with('tests', [$test])
             ->with('enc_tests', json_encode([$test]))
             ->with('imgsToPreload', $imgsToPreload);
    }

    public function saveMemory(Request $request) {
      $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $parameters = unserialize($currentTask->parameters);

      $test = (new \Teamwork\Tasks\Memory)->getTest($parameters->test);

      if($test['type'] == 'intro') {
        $this->recordEndTime($request, 'intro');
      }

      else {
        $this->recordEndTime($request, 'task');
      }

      // Retrieve all responses
      $responses = array_where($request->request->all(), function ($value, $key) {
        return strpos($key, 'response') !== false;
      });

      // Originally, there was an array of multiple tests. We've separated the
      // different memory tasks into individual tasks but to avoid rewriting a
      // lot of code, we'll construct a single-element array with the one test.
      $tests = [$test];


      foreach ($tests as $key => $t) {
        $testCount = count(array_where($t['blocks'], function($b, $k){
          return $b['type'] == 'test';
        }));

        $correct[$key] = ['name' => $t['test_name'],
                          'points'  => 0,
                          'count' =>$testCount,
                          'task_type' => $t['task_type']];
      }

      // Look up the test based on the response key
      foreach ($responses as $key => $response) {

        $indices = explode('_', $key);
        $test = $tests[$indices[1]]['blocks'][$indices[2]];

        $points = 0;

        // If the response is a single item and they got it correct,
        // give them 3 points
        if($test['selection_type'] == 'select_one') {

          if($test['correct'][0] == $response) {
            $correct[$indices[1]]['points'] += 3;
            $points = 3;
          }
        }

        // Otherwise, process arrays of choices against arrays of responses and correct answers
        else {
          // If they selected 'none' and there were no correct choices
          // give them 3 points
          if(count($response) == 1 && $response[0] == '0' && count($test['correct']) == 0) $points = 3;
          else {
            foreach($test['choices'] as $pos => $choice) {
              // If in responses arr and in correct arr, +1 point
              if(in_array($pos + 1, $response) && in_array($pos + 1, $test['correct'])) {
                $points += 1;
              }
              else if(!in_array($pos + 1, $response) && !in_array($pos + 1, $test['correct'])) {
                $points += 1;
              }
            }
          }
          $correct[$indices[1]]['points'] += $points;
        }

        $r = new Response;
        $r->user_id = \Auth::user()->id;
        $r->group_tasks_id = $currentTask->id;
        $r->individual_tasks_id = $request->session()->get('currentIndividualTask');

        $prompt = $tests[$indices[1]]['test_name'].' type: '.$tests[$indices[1]]['task_type'].' '.$tests[$indices[1]]['blocks'][$indices[2]]['type'];

        /*
        $r->prompt = serialize(['test' => $tests[$indices[1]]['test_name'],
                               'block' => $indices[2],
                               'test_type' => $tests[$indices[1]]['task_type']]);
        */
        $r->prompt = $prompt;
        if(is_array($response)) {
          $responseStr = '';
          foreach ($response as $val) {
            $responseStr .= $val.',';
          }
          $r->response = $responseStr;
        }
        else $r->response = $response;
        $r->points = $points;
        $r->save();

      }

      return redirect('/end-individual-task');
    }
    // http://teamwork.loc/test-mem-results/686
    public function testMemResults($id, Request $request) {
      $user = \Teamwork\User::find($id);
      \Auth::login($user);

      $groupTasks = \Teamwork\GroupTask::where('name', 'Memory')
                                   ->where('group_id', \Auth::user()->group_id)
                                   ->with('response')->get();


      $performance = ['words_1' => 0, 'faces_1' => 0, 'story_1' => 0];

      foreach($groupTasks as $id => $task) {
        if(count($task->response) == 0) continue;
        $parameters = unserialize($task->parameters);
        if($parameters->test == 'words_1' || $parameters->test == 'faces_1' || $parameters->test == 'story_1') {
          dump($parameters->test);
          dump('Total points: ' .$task->response->sum('points'). ' out of '.(3 * count($task->response)). ' avg: '.$task->response->avg('points'));

          $avg = $task->response->avg('points');

          $performance[substr($parameters->test, 0, -2)] = $this->calculateMemoryPercentileRank(substr($parameters->test, 0, -2), $avg);
        }
      }

      return $this->getMemoryTaskResults();
    }

    public function getIndividualMemoryTaskResults() {

      //$groupTasks = \Teamwork\GroupTask::where('name', 'Memory')
        //                           ->where('group_id', \Auth::user()->group_id)
          //                         ->with('response')->get();

      $groupTasks = \Teamwork\Response::where('user_id',\Auth::user()->id)->get()->groupBy('group_tasks_id');

      Log::debug($groupTasks);


      $performance = ['words_1' => 0, 'faces_1' => 0, 'story_1' => 0];

      foreach($groupTasks as $id => $array) {
        $task = \Teamwork\GroupTask::where('id',(int) $id)->first();
        if($task->name != "Memory")
          continue;
        Log::debug($task->parameters);
        $parameters = unserialize($task->parameters);
        if($parameters->test == 'words_1' || $parameters->test == 'faces_1' || $parameters->test == 'story_1') {
          $avg = $array->avg('points');

          $performance[$parameters->test] = $this->calculateMemoryPercentileRank($parameters->test, $avg);
        }
      }

      return $performance;

    }

    public function getMemoryTaskResults() {

      $groupTasks = \Teamwork\GroupTask::where('name', 'Memory')
                                   ->where('group_id', \Auth::user()->group_id)
                                   ->with('response')->get();


      $performance = ['words_1' => 0, 'faces_1' => 0, 'story_1' => 0];

      foreach($groupTasks as $id => $task) {
        if(count($task->response) == 0) continue;
        $parameters = unserialize($task->parameters);
        if($parameters->test == 'words_1' || $parameters->test == 'faces_1' || $parameters->test == 'story_1') {
          $avg = $task->response->avg('points');

          $performance[substr($parameters->test, 0, -2)] = $this->calculateMemoryPercentileRank(substr($parameters->test, 0, -2), $avg);
        }
      }

      return $performance;

    }

    public function displayMemoryTaskResults(Request $request) {


      $performance = $this->getMemoryTaskResults();

      $highestRank = 0;
      $bestTest;
      $bestTestName;

      foreach ($performance as $key => $rank) {
        if($rank > $highestRank){
          $highestRank = $rank;
          $bestTest = $key;
        }
      }

      switch ($bestTest) {
        case 'words':
          $bestTestName = 'Words';
          break;
        case 'faces':
          $bestTestName = 'Images';
          break;
        case 'story':
          $bestTestName = 'Story';
          break;
      }

      $results = 'Congratulations on completing the memory challenge.<br><br>';
      $results .= '<h2>Compared to other participants in this study, you did <strong>better</strong> than roughly:</h2>';
      $results .= '<h2>'.$performance['words'].'% on words</h2>';
      $results .= '<h2>'.$performance['faces'].'% on images</h2>';
      $results .= '<h2>'.$performance['story'].'% on stories</h2>';
      $results .= '<h2>Compared to others, your strongest memory skill is '.strtoupper($bestTestName).'</h2>';
      $request->session()->put('currentIndividualTaskResult', $results);
      $request->session()->put('currentIndividualTaskName', 'Memory Task');
      return redirect('/individual-task-results');

    }

    private function calculateMemoryPercentileRank($test, $avg){
      $percentiles = [
      'words' => ['20' => 2.076, '30' => 2.290,
                         '40' => 2.415, '50' => 2.465, '60' => 2.340, '70' => 2.390,
                         '80' => 2.665, '90' => 2.790],

      'faces' => ['20' => 1.490, '30' => 1.540,
                        '40' => 1.750, '50' => 1.865, '60' => 1.890, '70' => 2.140,
                        '80' => 2.240, '90' => 2.615],

      'story' => ['20' => 1.490, '30' => 1.540,
                         '40' => 1.590, '50' => 1.865, '60' => 2.140, '70' => 2.240,
                         '80' => 2.600, '90' => 2.615]
      ];
      foreach (array_reverse($percentiles[$test], true) as $key => $value) {
        if($avg >= $value) {
          return $key;
        }
      }
      return '20';
    }

    public function eyesIntro(Request $request) {
      if(config('app.debug') == true){
        $this->getProgress();
        $this_user = User::where('id',\Auth::user()->id)->first();

        $currentTask = \Teamwork\GroupTask::where('name','Eyes')->where('group_id',$this_user->group_id)->first();

        $prior_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','<',$currentTask->order)->get();
        $later_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','>=',$currentTask->order)->get();
        foreach($prior_tasks as $key => $prior_task){
          $prior_task->completed = 1;
          $prior_task->save();
        }
        foreach($later_tasks as $key => $later_task){
          $later_task->completed = 0;
          $later_task->save();
        }

        request()->session()->put('currentGroupTask', $currentTask->id);
        request()->session()->put('currentIndividualTask', \Teamwork\IndividualTask::where('group_task_id',$currentTask->id)->first()->id);
        request()->session()->put('currentIndividualTaskName', 'Eyes');



      }
      else
        $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));

      $this->recordStartTime($request, 'intro');
      return view('layouts.participants.tasks.eyes-individual-intro');
    }

    public function eyes(Request $request) {
      $this->recordEndTime($request, 'intro');
      $eyes = new \Teamwork\Tasks\Eyes;
      $tests = $eyes->getTest();
      $imgsToPreload = $eyes->getImagesForPreloader();

      $dir = (new \Teamwork\Tasks\Eyes)->getDirectory();

      // Record the start time for this task
      $this->recordStartTime($request, 'task');

      return view('layouts.participants.tasks.eyes-individual')
             ->with('dir', $dir)
             ->with('tests', $tests)
             ->with('imgsToPreload', $imgsToPreload);
    }

    public function saveEyes(Request $request) {
      $groupTaskId = $request->session()->get('currentGroupTask');
      $individualTaskId = $request->session()->get('currentIndividualTask');

      // Record the end time for this task
      $this->recordEndTime($request, 'task');

      $tests = (new \Teamwork\Tasks\Eyes)->getTest();
      $correct = 0;

      foreach ($request->all() as $key => $value) {
        if($key == '_token') continue;
        // If this item is a timing one, skip it
        if(strpos($key, 'timing_') !== false) continue;

        $isCorrect = false;
        if($value == $tests[$key]['correct']){
          $isCorrect = true;
          $correct++;
        }

        $response = new Response;
        $response->user_id = \Auth::user()->id;
        $response->group_tasks_id = $groupTaskId;
        $response->individual_tasks_id = $individualTaskId;
        $response->prompt = $tests[$key]['img'];
        $prop = 'timing_'.$key;
        if(isset($request->$prop)){
          $response->prompt .= ' timing: '. $request->$prop;
        }

        $response->response = $value;
        $response->correct = $isCorrect;
        $response->save();

      }

      $results = 'You have completed the Eyes Task.';

      $request->session()->put('currentIndividualTaskResult', $results);
      $request->session()->put('currentIndividualTaskName', 'Eyes Task');
      return redirect('/end-individual-task');
    }

    public function brainstormingIntro() {
      $this->recordStartTime($request, 'intro');
      return view('layouts.participants.tasks.brainstorming-individual-intro');
    }

    public function brainstorming(Request $request) {
      $this->recordEndTime($request, 'intro');
      $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));

      $task = new Task\Brainstorming;

      $prompt = unserialize($currentTask->parameters)->prompt;
      $this->recordStartTime($request, 'task');
      return view('layouts.participants.tasks.brainstorming-individual')
             ->with('prompt', $prompt);
    }

    public function scoreBrainstorming(Request $request) {
      $this->recordEndTime($request, 'task');
      $groupTaskId = $request->session()->get('currentGroupTask');
      $individualTaskId = $request->session()->get('currentIndividualTask');

      foreach ($request->responses as $response) {
        if(!$response) continue; // Skip any empty responses

        $r = new Response;
        $r->group_tasks_id = $groupTaskId;
        $r->individual_tasks_id = $individualTaskId;
        $r->user_id = \Auth::user()->id;
        $r->prompt = $request->prompt;
        $r->response = $response;
        $r->save();
      }

      $request->session()->put('currentIndividualTaskResult', false);
      $request->session()->put('currentIndividualTaskName', 'Brainstorming Task');

      return redirect('/individual-task-results');
    }

    public function shapesIntro(Request $request) {
      if(config('app.debug') == true){
        $this->getProgress();
        $this_user = User::where('id',\Auth::user()->id)->first();

        $currentTask = \Teamwork\GroupTask::where('name','Shapes')->where('group_id',$this_user->group_id)->first();

        $prior_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','<',$currentTask->order)->get();
        $later_tasks = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('order','>=',$currentTask->order)->get();
        foreach($prior_tasks as $key => $prior_task){
          $prior_task->completed = 1;
          $prior_task->save();
        }
        foreach($later_tasks as $key => $later_task){
          $later_task->completed = 0;
          $later_task->save();
        }

        request()->session()->put('currentGroupTask', $currentTask->id);
        request()->session()->put('currentIndividualTask', \Teamwork\IndividualTask::where('group_task_id',$currentTask->id)->first()->id);
        request()->session()->put('currentIndividualTaskName', 'Shapes');



      }
      else
        $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));



      $this->recordStartTime($request, 'intro');
      //$currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $parameters = unserialize($currentTask->parameters);
      return view('layouts.participants.tasks.shapes-individual-intro')
             ->with('subtest', $parameters->subtest);
    }

    public function shapesIndividual(Request $request) {
      $this->recordEndTime($request, 'intro');
      $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $parameters = unserialize($currentTask->parameters);

      $task = new Task\Shapes;
      $shapes = $task->getShapes($parameters->subtest);

      // Record the start time for this task
      $this->recordStartTime($request, 'task');

      return view('layouts.participants.tasks.shapes-individual')
             ->with('shapes', $shapes)
             ->with('subtest', $parameters->subtest);
    }

    public function saveShapesIndividual(Request $request) {

      $currentTask = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
      $individualTask = $request->session()->get('currentIndividualTask');
      $parameters = unserialize($currentTask->parameters);

      $task = new Task\Shapes;
      $shapes = $task->getShapes($parameters->subtest);

      $answers = $shapes['answers'];

      foreach ($request->all() as $key => $input) {
        if($key == '_token') continue;
        if(strpos($key, 'timing_') !== false) continue;

        $inputString = $input;

        if(is_array($answers[$key - 1])){
          $inputString = json_encode($input);

          $points = 2 - count(array_diff($input, $answers[$key - 1]));

          if($points == 2) $correct = 1;
          else $correct = 0;
        }

        else if($input == $answers[$key - 1]) {
          $correct = 1;
          $points = 1;
        }

        else $correct = 0;

        $r = new Response;
        $r->group_tasks_id = $currentTask->id;
        $r->individual_tasks_id = $individualTask;
        $r->user_id = \Auth::user()->id;
        $r->prompt = $parameters->subtest.' : '.$key;

        $prop = 'timing_'.$key;
        if(isset($request->$prop)){
          $r->prompt .= ' timing: '. $request->$prop;
        }

        $r->response = $inputString;
        $r->correct = $correct;
        $r->points = $correct;
        $r->save();

      }

      // Record the end time for this task
      $this->recordEndTime($request, 'task');

      $results = 'You have completed the Shapes Task.';
      $request->session()->put('currentIndividualTaskResult', $results);
      $request->session()->put('currentIndividualTaskName', 'Shapes Task');
      return redirect('/end-individual-task');
    }

    public function getProgress() {
      $tasks = \Teamwork\GroupTask::where('group_id', \Auth::user()->group_id)
                                      ->where('name', '!=', 'Consent')
                                      ->where('name', '!=', 'Intro')
                                      ->where('name', '!=', 'ChooseReporter')
                                      ->where('name', '!=', 'Feedback')
                                      ->where('name', '!=', 'DeviceCheck')
                                      ->where('name', '!=', 'Survey')
                                      ->where('name', '!=', 'Conclusion')
                                      ->get();

      $count = 0;
      $completed = 0;
      $lastTask = null;

      foreach ($tasks as $task) {
        if($task->name != $lastTask) {
          $count++;
          if($task->completed) $completed++;
        }
        $lastTask = $task->name;

      }
      \Session::put('totalTasks', $count);
      \Session::put('completedTasks', $completed);
    }

    public function testEligibility($groupId) {
      $finalScore = $this->calculateScore($groupId);
      $eligiblity = $this->calculateEligibility($groupId);
      dump($finalScore);
      dump($eligiblity);
    }

    public function calculateEligibility($groupId) {

      $passed = true;

      // Collect shapes scores and time they spent on shapes task
      $shapesTask = \Teamwork\GroupTask::where('group_id', $groupId)
                                       ->where('name', 'Shapes')
                                       ->with('response')
                                       ->first();

      $shapesCorrect = $shapesTask->response->sum('correct');

      $shapesTimestamps = Time::where('group_tasks_id', $shapesTask->id)
                         ->where('type', 'task')
                         ->first();


      $startTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $shapesTimestamps->start_time);
      $endTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $shapesTimestamps->end_time);
      $shapesTime = $startTime->diffInSeconds($endTime);

      // If less than 2 minutes AND they scored less than 8, they do not pass
      if($shapesTime < 120 && $shapesCorrect < 8) $passed = false;

      $memoryTasks = \Teamwork\GroupTask::where('group_id', $groupId)
                                       ->where('name', 'Memory')
                                       ->with('response')
                                       ->get();

      $memPracticeCount = 0;

      $performance = ['words' => 0, 'faces' => 0, 'story' => 0];

      foreach($memoryTasks as $key => $mem) {
        $parameters = unserialize($mem->parameters);

        if($parameters->test == 'images_instructions' || $parameters->test == 'story_instructions') {
          $memPracticeCount += $mem->response->sum('points');
        }

        if($parameters->test == 'words_1' || $parameters->test == 'faces_1' || $parameters->test == 'story_1') {
          $avg = $mem->response->avg('points');
          $performance[substr($parameters->test, 0, -2)] = $this->calculateMemoryPercentileRank(substr($parameters->test, 0, -2), $avg);
        }
      }

      if($memPracticeCount <= 6 && ($performance['words'] == '20' && $performance['faces'] == '20' && $performance['story'] == '20')) $passed = false;
      $eligibilityStats = [
        'shapesTime' => $shapesTime,
        'shapesCorrect' => $shapesCorrect,
        'memPracticeCount' => $memPracticeCount,
        'memPerformance' => $performance,
        'passed' => $passed
      ];
      return $eligibilityStats;

    }

    public function calculateScore($groupId) {
      // When we switch to filter to use only HDSL participants, we also need to un-square optStdDev
      //$filter = \DB::table('users')->whereRaw('CHAR_LENGTH(participant_id) > 11')->pluck('group_id')->toArray();
      $filter = \DB::table('users')
                   ->where('score_group', 1)
                   ->where('participant_id', 'like', '%@%')
                   ->pluck('group_id')
                   ->toArray();


      $standardizedShapesScore = $this->getIndividualShapesScore($groupId, $filter);

      $standardizedOptScore = $this->getIndividualOptimizationScore($groupId, $filter);

      $standardizedMemScore = $this->getIndividualMemoryScores($groupId, $filter);
      $finalScore = (1 / 3) * ($standardizedShapesScore + $standardizedOptScore + $standardizedMemScore);

      /* For reference, we're recording the score now, displaying the fruit in the data view.
      $fruit = 'pear';
      if($finalScore >= .45) $fruit = 'banana';
      elseif($finalScore >= -0.2) $fruit = 'grape';

      // Store their fruit in the users table
      $user = \Teamwork\User::find(\Auth::user()->id);

      $user->score_group = $fruit;
      $user->save();
      */
      return $finalScore;
    }

    private function getIndividualOptimizationScore($groupId, $filter) {

      $optimizationTasks = \Teamwork\GroupTask::where('group_id', $groupId)
                                       ->where('name', 'Optimization')
                                       ->with('response')
                                       ->get();
      if(!$optimizationTasks) return 0;
      $functionStats = \Teamwork\Tasks\Optimization::getFunctionStats();

      $optScores = [];

      foreach($optimizationTasks as $opt) {
        $parameters = unserialize($opt->parameters);
        $func = $parameters->function;
        $stats = $functionStats[(string) $func];
        $optScores[] = $this->calcOptimizationScore($opt->response, $functionStats[(string) $func]);
      }

      if(count($optScores) == 0) return 0;
      $avgOptScore = array_sum($optScores) * (1 / count($optScores));

      $populationOptimizationScores = $this->getOptimizationScores($filter);

      $optAvg = $this->getAvg(collect($populationOptimizationScores));
      $optStdDev = $this->getStdDev(collect($populationOptimizationScores));
      $standardizedOptScore = $this->standardizeScore($avgOptScore, $optAvg, pow($optStdDev, 2)); // This should just be optStdDev (not squared) when we switch to HDSL participants
      return $standardizedOptScore;
    }


    private function getOptimizationScores($filter) {
      $groups = \Teamwork\GroupTask::where('name', 'Optimization')
                                   ->with('response')
                                   ->whereIn('group_id', $filter)
                                   ->get();

      $functionStats = \Teamwork\Tasks\Optimization::getFunctionStats();

      $rawScores = [];
      $scores = [];
      foreach($groups as $id => $group) {
        if(count($group->response) == 0) continue;
        $parameters = unserialize($group->parameters);
        $userId = $group->response->pluck('user_id')->first();
        if(!array_key_exists($userId, $rawScores)) $rawScores[$userId] = [$this->calcOptimizationScore($group->response, $functionStats[(string) $parameters->function])];
        else $rawScores[$userId][] = $this->calcOptimizationScore($group->response, $functionStats[(string) $parameters->function]);
      }

      foreach($rawScores as $user => $scoreArr){
        $sum = 0;
        if(count($scoreArr) < 2) continue;
        foreach($scoreArr as $score) {
          $sum += $score;
        }
        $scores[] = $sum * (1 / count($scoreArr));
      }

      return $scores;
    }

    private function calcOptimizationScore($responses, $stats) {
      $finalGuess = $responses->filter(function($val, $k) {
        return strpos($val, 'final') !== false;
      })->pluck('response')->first();
      return 1 - ( abs($stats['ymax'] - $finalGuess) / ($stats['ymax'] - $stats['ymin']) );
    }

    private function getIndividualMemoryScores($groupId, $filter) {
      $memoryTasks = \Teamwork\GroupTask::where('group_id', $groupId)
                                       ->where('name', 'Memory')
                                       ->with('response')
                                       ->get();

      $sum = 0;
      foreach($memoryTasks as $id => $task) {
       if(count($task->response) == 0) continue;
       $parameters = unserialize($task->parameters);
       if($parameters->test == 'words_1' || $parameters->test == 'faces_1' || $parameters->test == 'story_1') {
         $sum += $task->response->avg('points');
       }
      }

      $memRaw = $sum * (1/3);
      $populationMemoryScores = collect($this->getMemoryScoresByUser($filter));

      $memAvg = $this->getAvg($populationMemoryScores);
      $memStdDev = $this->getStdDev($populationMemoryScores);
      $standardizedMemScore = $this->standardizeScore($memRaw, $memAvg, $memStdDev);
      return $standardizedMemScore;
    }

    private function getMemoryScores($filter) {
      $groups = \Teamwork\GroupTask::where('name', 'Memory')
                                    ->whereIn('group_id', $filter)
                                    ->with('response')
                                    ->get();

      $scores = ['words_1' => [], 'faces_1' => [], 'story_1' => []];

      foreach($groups as $id => $group) {
        if(count($group->response) == 0) continue;
        $parameters = unserialize($group->parameters);
        if($parameters->test == 'words_1' || $parameters->test == 'faces_1' || $parameters->test == 'story_1') {
          $avg = $group->response->avg('points');
          $scores[$parameters->test][] = $avg;
        }
      }

      usort($scores['words_1'], function( $a, $b ) {
        return $a == $b ? 0 : ( $a > $b ? 1 : -1 );
      });
      usort($scores['faces_1'], function( $a, $b ) {
        return $a == $b ? 0 : ( $a > $b ? 1 : -1 );
      });
      usort($scores['story_1'], function( $a, $b ) {
        return $a == $b ? 0 : ( $a > $b ? 1 : -1 );
      });
      return $scores;
    }

    private function getMemoryScoresByUser($filter) {
      $groups = \Teamwork\GroupTask::where('name', 'Memory')
                                    ->whereIn('group_id', $filter)
                                    ->with('response')
                                    ->get();

      $rawScores = [];
      $scores = [];
      foreach($groups as $id => $group) {
        if(count($group->response) == 0) continue;
        $parameters = unserialize($group->parameters);
        if($parameters->test == 'words_1' || $parameters->test == 'faces_1' || $parameters->test == 'story_1') {

          $userId = $group->response->pluck('user_id')->first();
          if(!array_key_exists($userId, $rawScores)) $rawScores[$userId] = [$group->response->avg('points')];
          else $rawScores[$userId][] = $group->response->avg('points');
        }
      }

      foreach($rawScores as $user => $score) {
        if(count($score) != 3) continue;
        $scores[] = (array_sum($score) * (1 / 3));
      }

      usort($scores, function( $a, $b ) {
        return $a == $b ? 0 : ( $a > $b ? 1 : -1 );
      });

      return $scores;
    }

    private function getIndividualShapesScore($groupId, $filter) {
      $shapesTask = \Teamwork\GroupTask::where('group_id', $groupId)
                                       ->where('name', 'Shapes')
                                       ->with('response')
                                       ->first();
      if(!$shapesTask){
        return 0;
      }
      $shapesCorrect = $shapesTask->response->sum('correct');
      $populationShapesScores = collect($this->getShapesScores($filter));

      // Remove any scores of 0
      $populationShapesScores = $populationShapesScores->filter(function($v, $k) {
        return $v > 0;
      });


      $shapesStdDev = $this->getStdDev(collect($populationShapesScores));
      $shapesAvg = $this->getAvg($populationShapesScores);

      $standardizedShapesScore = $this->standardizeScore($shapesCorrect, $shapesAvg, $shapesStdDev);
      return $standardizedShapesScore;
    }

    private function getShapesScores($filter) {
      $groups = \Teamwork\GroupTask::where('name', 'Shapes')
                                   ->whereIn('group_id', $filter)
                                   ->with('response')
                                   ->get();
      $scores = [];
      foreach($groups as $id => $group) {
        if(count($group->response) == 0) continue;
        $parameters = unserialize($group->parameters);
        if($parameters->subtest == 'subtest1') {
          $sum = $group->response->sum('correct');
          $scores[] = $sum;
        }
      }
      usort($scores, function( $a, $b ) {
        return $a == $b ? 0 : ( $a > $b ? 1 : -1 );
      });
      usort($scores, function( $a, $b ) {
        return $a == $b ? 0 : ( $a > $b ? 1 : -1 );
      });
      return $scores;
    }

    private function standardizeScore($score, $avg, $stdDev) {
      if($stdDev == 0) return 0;
      return ($score - $avg) / $stdDev;
    }

    private function getAvg($scores) {
      return $scores->avg();
    }

    private function getStdDev($scores) {
      if(count($scores) == 0) return 0;
      $mean = $scores->avg();
      $distFromMean = $scores->map(function($val, $k) use ($mean){
        return pow(abs($mean - $val), 2);
      });
      $sum = $distFromMean->sum();
      $stdDev = sqrt($sum / count($scores));
      return $stdDev;
    }

    private function calculatePercentileRank($score, $scores) {

      $lowerThan = $scores->filter(function($v, $i) use ($score) {
        return $v < $score;
      });
      $equalTo = $scores->filter(function($v, $i) use ($score) {
        return $v == $score;
      });
      return ((count($lowerThan) + ( 0.5 * count($equalTo) )) / count($scores)) * 100;
    }

    private function recordStartTime(Request $request, $type) {
      $time = Time::firstOrNew(['user_id' => \Auth::user()->id,
                                'group_tasks_id' => $request->session()->get('currentGroupTask'),
                                'individual_tasks_id' => $request->session()->get('currentIndividualTask'),
                                'type' => $type]);
      $time->recordStartTime();
    }

    private function recordEndTime(Request $request, $type) {
      $time = Time::where('user_id', '=', \Auth::user()->id)
                  ->where('group_tasks_id', '=', $request->session()->get('currentGroupTask'))
                  ->where('type', '=', $type)
                  ->first();
      $time->recordEndTime();
    }

    public function responsesTest() {
      $responses = \DB::table('responses')
                      ->where('user_id', \Auth::user()->id)
                      ->orderBy('created_at', 'desc')
                      ->get();
      foreach($responses as $key => $response) {
        dump($response);
      }
    }

    public function addScoresToUsers() {
      $groups = \DB::select( \DB::raw("SELECT group_id
      FROM group_user
      GROUP BY group_id
      HAVING COUNT(*) = 1"));
      $groupIds = [];
      foreach($groups as $key => $group) {
        $groupIds[] = $group->group_id;
      }

      $userData = [];

      foreach ($groupIds as $key => $group) {
        $userId = \DB::table('group_user')
                     ->where('group_id', $group)
                     ->pluck('user_id')
                     ->first();

        $users = \Teamwork\User::where('id', $userId)
                              ->with('group')
                              ->get();

        foreach($users as $user) {
          if($user->score != 0) continue;
          $user->score = $this->calculateScore($group);
          dump($user->participant_id .': '. $user->score);
          $user->save();
        }
      }
    }

}
