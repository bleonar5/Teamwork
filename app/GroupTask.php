<?php

# UPDATED / LEGACY CODE -- GABE MANSURE AND BRIAN LEONARD
# IMPORTANT ELEMENT OF APP. DEFINES MODEL FOR A "GROUP TASK", 
# INCLUDING FUNCTIONS TO USE ON IT AND DEFINITIONS OF THE VARIOUS TASK-LISTS THAT WE USE IN DIFFERENT VERSIONS OF THE STUDY

namespace Teamwork;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class GroupTask extends Model
{
  protected $fillable = ['group_id', 'name', 'parameters', 'order'];

  private static $TASKS = [
    ['name' => 'Consent',
    'params' => [],
    'hasIndividuals' => true],
    ['name' => 'Intro',
    'params' => [],
    'hasIndividuals' => true],
    ['name' => 'Eyes',
    'params' => [],
    'hasIndividuals' => true],
    ['name' => 'Memory',
    'params' => [],
    'hasIndividuals' => true],
    ['name' => 'BigFive',
    'params' => [],
    'hasIndividuals' => true],
    ['name' => 'TeamRole',
    'params' => [],
    'hasIndividuals' => true],
    ['name' => 'Cryptography',
    'params' => [],
    'hasIndividuals' => false],
    ['name' => 'Optimization',
    'hasIndividuals' => true],
    ['name' => 'UnscrambleWords',
    'hasIndividuals' => false],
    ['name' => 'Brainstorming',
    'hasIndividuals' => true],
    ['name' => 'Shapes',
    'hasIndividuals' => true],
    ['name' => 'Feedback',
    'hasIndividuals' => true],
    ['name' => 'Conclusion',
    'params' => [],
    'hasIndividuals' => true],
  ];

  public function group() {
    return $this->belongsTo('\Teamwork\Group');
  }

  public function individualTasks() {
    return $this->hasMany('\Teamwork\IndividualTask');
  }

  public function response() {
    return $this->hasMany('\Teamwork\Response', 'group_tasks_id', 'id');
  }

  public function progress() {
    return $this->hasMany('\Teamwork\Progress', 'group_tasks_id', 'id');
  }

  public static function getTasks() {
    $tasks = [];
    foreach (Self::$TASKS as $key => $task) {
      $class = "\Teamwork\Tasks\\".$task['name'];
      $tasks[$key]['name'] = $task['name'];
      $tasks[$key]['params'] = $class::getAvailableParams();
    }
    return $tasks;
  }

  #TASK LIST FOR CRYPTO PILOT
  public static function initializeCryptoPilotTasks($group_id, $randomize) {
    $taskArray = '[
      {"taskName":"Consent","taskParams":{"url_endpoint":"end-group-task","hasIndividuals":"false","hasGroup":"true","subjectPool":"hdsl_individual"}},
      {"taskName":"Intro","taskParams":{"hasIndividuals":"false","hasGroup":"true","type":"adblock"}},
      {"taskName":"DeviceCheck","taskParams":{"hasIndividuals":"false","hasGroup":"true","type":"eligibility"}},
      {"taskName":"Intro","taskParams":{"hasIndividuals":"false","hasGroup":"true","type":"crypto_pilot_guide"}},
      {"taskName":"Intro","taskParams":{"hasIndividuals":"false","hasGroup":"true","type":"crypto_pilot_guide2"}},
      {"taskName":"WaitingRoom","taskParams":{"hasIndividuals":"false","hasGroup":"true","task":"1"}}
      ]';
    
    
    return Self::initializeTasks($group_id, $taskArray, $randomize);
  }

  #TASK LIST FOR PHASE 1+2 PILOT
  public static function initializeCombinedPilotTasks($group_id, $randomize) {
    $taskArray = '[
      {"taskName":"Consent","taskParams":{"url_endpoint":"end-individual-task","hasIndividuals":"true","hasGroup":"false","subjectPool":"july_pilot"}},
      {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"chat_notification"}},
      {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"adblock"}},
      {"taskName":"DeviceCheck","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"eligibility"}},
      {"taskName":"Cryptography","taskParams":{"intro":"intro","hasIndividuals":"true","hasGroup":"false","mapping":"random","maxResponses":"10"}},
      {"taskName":"Cryptography","taskParams":{"intro":"individual_alt","hasIndividuals":"true","hasGroup":"false","mapping":"random","maxResponses":"10"}},
      {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"selection_page"}},
      {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"individual_crypto_end"}},
      {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"crypto_pilot_guide"}},
      {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"crypto_pilot_guide2"}},
      {"taskName":"WaitingRoom","taskParams":{"hasIndividuals":"true","hasGroup":"false","task":"1"}}
      ]';
    
    
    return Self::initializeTasks($group_id, $taskArray, $randomize);
  }

  #FOR DEV PURPOSES
  public static function initializeTestTasks($group_id, $randomize) {
    $taskArray = '[
      {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"selection_page","statementOrder":"ordered"}}

      ]';

    /*{"taskName":"GroupSurvey","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_survey_members_1","statementOrder":"ordered"}},
      {"taskName":"GroupSurvey","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_survey_members_2","statementOrder":"ordered"}},
      {"taskName":"GroupSurvey","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_survey_leader_hypothesis","statementOrder":"ordered"}},
      {"taskName":"GroupSurvey","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_survey_leader_equations","statementOrder":"ordered"}}*/
    
    
    return Self::initializeTasks($group_id, $taskArray, $randomize);
  }

  public static function initializeConclusionTasks($group_id, $randomize) {
    $taskArray = '[
      {"taskName":"Conclusion","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"mturk","hasCode":"false","displayScoreGroup":"false", "digitalReceipt":"false", "sonaId": "547", "payment": "30", "feedback":"true", "feedbackLinkType":"qualtrics"}}
      ]';
    
    return Self::initializeTasks($group_id, $taskArray, $randomize);
  }

  //CALLED DURING THE ASSIGNGROUPS JOB TO CREATE SUBSESSION
  public static function initializeCryptoTasks($group_id, $randomize,$final) {
    if($final){
      $taskArray = '[
      {"taskName":"Cryptography","taskParams":{"hasIndividuals":"false","intro":"group_1","hasGroup":"true","mapping":"random","maxResponses":"10","type":"intro"}},
      {"taskName":"Cryptography","taskParams":{"hasIndividuals":"false","intro":"group_1","hasGroup":"true","mapping":"random","maxResponses":"10","type":"task"}},
      {"taskName":"GroupSurvey","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"1","statementOrder":"ordered"}},
      {"taskName":"Conclusion","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"mturk","hasCode":"false","displayScoreGroup":"false", "digitalReceipt":"false", "sonaId": "547", "payment": "30", "feedback":"true", "feedbackLinkType":"qualtrics"}}
      ]';
    }
    else{
      $taskArray = '[
      {"taskName":"Cryptography","taskParams":{"hasIndividuals":"false","intro":"group_1","hasGroup":"true","mapping":"random","maxResponses":"10","type":"intro"}},
      {"taskName":"Cryptography","taskParams":{"hasIndividuals":"false","intro":"group_1","hasGroup":"true","mapping":"random","maxResponses":"10","type":"task"}},
      {"taskName":"GroupSurvey","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"1","statementOrder":"ordered"}},
      {"taskName":"WaitingRoom","taskParams":{"hasIndividuals":"false","hasGroup":"true","task":"1"}}
      ]';
    }
    
    return Self::initializeTasks($group_id, $taskArray, $randomize);
  }


  #TASK LIST FOR PARTICIPANT-LOGIN/WAITING-ROOM SHORTCUT
  public static function initializeCryptoWaitingRoomTasks($group_id, $randomize) {
    $taskArray = '[
      {"taskName":"WaitingRoom","taskParams":{"hasIndividuals":"false","hasGroup":"true","task":"1"}}
      ]';
    return Self::initializeTasks($group_id, $taskArray, $randomize);
  }

  #TASK LIST IF USER HAS ALREADY SIGNED CONSENT FORM
  public static function initializeCryptoPilotNoConsentTasks($group_id, $randomize) {
    $taskArray = '[
      {"taskName":"WaitingRoom","taskParams":{"hasIndividuals":"false","hasGroup":"true","task":"1"}}
      ]';
    return Self::initializeTasks($group_id, $taskArray, $randomize);
  }

  #TASK LIST FOR INDIVIDUAL PILOT (INDIVIDUAL TASK LISTS ARE ALSO DEFINED HERE, NOT JUST GROUP)
  public static function initializeLabIndividualPilotTasks($group_id, $randomize) {
    $taskArray = '[
        {"taskName":"Consent","taskParams":{"url_endpoint":"end-individual-task","hasIndividuals":"true","hasGroup":"false","subjectPool":"mturk"}},
        {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"mturk"}},
        {"taskName":"Eyes","taskParams":{"hasIndividuals":"true","hasGroup":"false"}},
        {"taskName":"BigFive","taskParams":{"hasIndividuals":"true","hasGroup":"false","statementOrder":"random"}},
        {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"intro"}},
        {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"words_instructions"}},
        {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"words_1"}},
        {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"images_instructions"}},
        {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"faces_1"}},
        {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"story_instructions"}},
        {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"story_1"}},
        {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"results"}},
        {"taskName":"PsiIri","taskParams":{"hasIndividuals":"true","hasGroup":"false","statementOrder":"random"}},
        {"taskName":"Shapes","taskParams":{"hasIndividuals":"true","hasGroup":"false","subtest":"subtest5"}},
        {"taskName":"Leadership","taskParams":{"hasIndividuals":"true","hasGroup":"false","statementOrder":"random"}},
        {"taskName":"Cryptography","taskParams":{"intro":"intro","hasIndividuals":"true","hasGroup":"false","mapping":"random","maxResponses":"10"}},
        {"taskName":"Cryptography","taskParams":{"intro":"individual_alt","hasIndividuals":"true","hasGroup":"false","mapping":"random","maxResponses":"10"}},
        {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"selection_page"}},
        {"taskName":"Feedback","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"mturk","hasCode":"true"}},
        {"taskName":"Conclusion","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"hdsl_individual","hasCode":"false","displayScoreGroup":"false","digitalReceipt":"false","feedback":"false", "feedbackLinkType":"pilot"}}
      ]';
    return Self::initializeTasks($group_id, $taskArray, $randomize);
  }

  public static function initializeDefaultTasks($group_id, $randomize) {

    $taskArray = '[
    {"taskName":"TeamRole","taskParams":{"hasIndividuals":"true","hasGroup":"false","scenarios":"all"}},
    {"taskName":"BigFive","taskParams":{"hasIndividuals":"true","hasGroup":"false","statementOrder":"random"}},
    {"taskName":"Eyes","taskParams":{"hasIndividuals":"true","hasGroup":"false"}},
    {"taskName":"Cryptography","taskParams":{"hasIndividuals":"true","hasGroup":"false","mapping":"random","maxResponses":"10"}},
    {"taskName":"Optimization","taskParams":{"hasIndividuals":"true","hasGroup":"false","function":"a","maxResponses":"9","intro":"individual"}},
    {"taskName":"Optimization","taskParams":{"hasIndividuals":"true","hasGroup":"false","function":"b","maxResponses":"9","intro":"individual_alt"}},
    {"taskName":"Shapes","taskParams":{"hasIndividuals":"true","hasGroup":"false","subtest":"subtest1"}},{"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":["faces_1","words_1","story_1"]}}]';
    return Self::initializeTasks($group_id, $taskArray, $randomize);

  }


  public static function initializeIQTasks($group_id, $randomize) {
    $taskArray = '[{"taskName":"Shapes","taskParams":{"hasIndividuals":"true","hasGroup":"false","subtest":"subtest1"}},{"taskName":"Cryptography","taskParams":{"hasIndividuals":"true","hasGroup":"false","mapping":"random","maxResponses":"10"}},{"taskName":"Optimization","taskParams":{"hasIndividuals":"true","hasGroup":"false","function":"c","intro":"individual","maxResponses":"9"}},{"taskName":"Optimization","taskParams":{"hasIndividuals":"true","hasGroup":"false","function":"f","intro":"individual_alt","maxResponses":"9"}},{"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":["faces_1","words_1","story_1"]}}]';
    return Self::initializeTasks($group_id, $taskArray, $randomize);
  }

  public static function initializeEQTasks($group_id, $randomize) {
    $taskArray = '[{"taskName":"BigFive","taskParams":{"hasIndividuals":"true","hasGroup":"false","statementOrder":"random"}},{"taskName":"Eyes","taskParams":{"hasIndividuals":"true","hasGroup":"false"}},{"taskName":"TeamRole","taskParams":{"hasIndividuals":"true","hasGroup":"false","scenarios":"all"}}]';
    return Self::initializeTasks($group_id, $taskArray, $randomize);
  }

  public static function initializeTestingTasks($group_id, $randomize) {
    $taskArray = '[
      {"taskName":"Consent","taskParams":{"hasIndividuals":"true","hasGroup":"false","subjectPool":"hdsl_individual"}},
      {"taskName":"Shapes","taskParams":{"hasIndividuals":"true","hasGroup":"false","subtest":"subtest5"}},
      {"taskName":"Survey","taskParams":{"hasIndividuals":"true","hasGroup":"false","survey":"hdsl"}},
      {"taskName":"Conclusion","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"hdsl_individual","hasCode":"false","displayScoreGroup":"false","digitalReceipt":"false","feedback":"false", "feedbackLinkType":"pilot"}}
      ]';
    return Self::initializeTasks($group_id, $taskArray, $randomize);
  }


  public static function initializeGroupTestTasks($group_id, $randomize) {
    $taskArray = '[
      {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_2"}},
      {"taskName":"ChooseReporter","taskParams":{"hasIndividuals":"true","hasGroup":"true"}},
      {"taskName":"Shapes","taskParams":{"hasIndividuals":"false","hasGroup":"true","subtest":"subtest5"}},
      {"taskName":"Optimization","taskParams":{"hasIndividuals":"true","hasGroup":"false","function":"1","intro":"individual","maxResponses":"10"}},
      {"taskName":"Cryptography","taskParams":{"hasIndividuals":"false","intro":"group_5","hasGroup":"true","mapping":"random","maxResponses":"10"}},
      {"taskName":"Conclusion","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_4","hasCode":"false","displayScoreGroup":"false", "digitalReceipt":"true", "sonaId": "547", "payment": "30", "feedback":"false", "feedbackLinkType":"group5Pilot"}}
      ]';
    return Self::initializeTasks($group_id, $taskArray, $randomize);
  }

  public static function initializeWaitingRoomTasks($group_id, $randomize) {
    $taskArray = '[
      {"taskName":"WaitingRoom","taskParams":{"hasIndividuals":"false","hasGroup":"true","task":"1"}}
      ]';
    
    
    return Self::initializeTasks($group_id, $taskArray, $randomize);
  }



  public static function initializeMemoryWaitingRoomTasks($group_id, $randomize) {
    $taskArray = '[
      {"taskName":"WaitingRoom","taskParams":{"hasIndividuals":"false","hasGroup":"true","task":"2"}}
      ]';
    return Self::initializeTasks($group_id, $taskArray, $randomize);
  }

  

  public static function initializeMemoryTasks($group_id, $randomize) {
    $taskArray = '[
      {"taskName":"Memory","taskParams":{"hasIndividuals":"false","hasGroup":"true","test":"group_1_instructions"}},
      {"taskName":"Memory","taskParams":{"hasIndividuals":"false","hasGroup":"true","test":"group_1"}},
      {"taskName":"Conclusion","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_1","hasCode":"false","displayScoreGroup":"false", "digitalReceipt":"false", "sonaId": "547", "payment": "30", "feedback":"false", "feedbackLinkType":"group1Pilot"}}
      ]';
    return Self::initializeTasks($group_id, $taskArray, $randomize);
  }

  

  public static function initializeLabIndividualTasks($group_id, $randomize) {
    $taskArray = '[
        {"taskName":"Consent","taskParams":{"url_endpoint":"end-individual-task","hasIndividuals":"true","hasGroup":"false","subjectPool":"hdsl_individual"}},
        {"taskName":"DeviceCheck","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"eligibility"}},
        {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"eligibility"}},
        {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"mturk"}},
        {"taskName":"Eyes","taskParams":{"hasIndividuals":"true","hasGroup":"false"}},
        {"taskName":"BigFive","taskParams":{"hasIndividuals":"true","hasGroup":"false","statementOrder":"random"}},
        {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"intro"}},
        {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"words_instructions"}},
        {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"words_1"}},
        {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"images_instructions"}},
        {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"faces_1"}},
        {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"story_instructions"}},
        {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"story_1"}},
        {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"results"}},
        {"taskName":"PsiIri","taskParams":{"hasIndividuals":"true","hasGroup":"false","statementOrder":"random"}},
        {"taskName":"Shapes","taskParams":{"hasIndividuals":"true","hasGroup":"false","subtest":"subtest5"}},
        {"taskName":"Leadership","taskParams":{"hasIndividuals":"true","hasGroup":"false","statementOrder":"random"}},
        {"taskName":"Cryptography","taskParams":{"intro":"intro","hasIndividuals":"true","hasGroup":"false","mapping":"random","maxResponses":"10"}},
        {"taskName":"Cryptography","taskParams":{"intro":"individual_alt","hasIndividuals":"true","hasGroup":"false","mapping":"random","maxResponses":"10"}},
        {"taskName":"Conclusion","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"hdsl_individual","hasCode":"false","displayScoreGroup":"true","digitalReceipt":"false","feedback":"false", "feedbackLinkType":"pilot"}}
      ]';
    return Self::initializeTasks($group_id, $taskArray, $randomize);
  }

  public static function initializeLabRoundOneTasks($group_id, $randomize) {
    $taskArray = '[
      {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_1"}},
      {"taskName":"ChooseReporter","taskParams":{"hasIndividuals":"true","hasGroup":"true"}},
      {"taskName":"Teammates","taskParams":{"hasIndividuals":"true","hasGroup":"false"}},
      {"taskName":"Optimization","taskParams":{"hasIndividuals":"false","hasGroup":"true","function":"4","intro":"group_1","maxResponses":"10"}},
      {"taskName":"Optimization","taskParams":{"hasIndividuals":"false","hasGroup":"true","function":"5","intro":"group_alt_intro","maxResponses":"10"}},
      {"taskName":"Memory","taskParams":{"hasIndividuals":"false","hasGroup":"true","test":"group_1_instructions"}},
      {"taskName":"Memory","taskParams":{"hasIndividuals":"false","hasGroup":"true","test":"group_1"}},
      {"taskName":"Shapes","taskParams":{"hasIndividuals":"false","hasGroup":"true","subtest":"subtest2"}},
      {"taskName":"Conclusion","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_1","hasCode":"false","displayScoreGroup":"false","digitalReceipt":"false", "feedback":"false", "feedbackLinkType":"none"}}
      ]';
    return Self::initializeTasks($group_id, $taskArray, $randomize);
  }

  public static function initializeLabRoundTwoTasks($group_id, $randomize) {
    $taskArray = '[
      {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_2"}},
      {"taskName":"ChooseReporter","taskParams":{"hasIndividuals":"true","hasGroup":"true"}},
      {"taskName":"Teammates","taskParams":{"hasIndividuals":"true","hasGroup":"false"}},
      {"taskName":"Optimization","taskParams":{"hasIndividuals":"false","hasGroup":"true","function":"6","intro":"group_2","maxResponses":"10"}},
      {"taskName":"Optimization","taskParams":{"hasIndividuals":"false","hasGroup":"true","function":"7","intro":"group_alt_intro","maxResponses":"10"}},
      {"taskName":"Memory","taskParams":{"hasIndividuals":"false","hasGroup":"true","test":"group_2"}},
      {"taskName":"Shapes","taskParams":{"hasIndividuals":"false","hasGroup":"true","subtest":"subtest3"}},
      {"taskName":"Conclusion","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_2","hasCode":"false","displayScoreGroup":"false", "digitalReceipt":"true", "sonaId": "547", "payment": "30", "feedback":"false", "feedbackLinkType":""}}
      ]';
    return Self::initializeTasks($group_id, $taskArray, $randomize);
  }

  public static function initializeLabRoundThreeTasks($group_id, $randomize) {
    $taskArray = '[
      {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_2"}},
      {"taskName":"ChooseReporter","taskParams":{"hasIndividuals":"true","hasGroup":"true"}},
      {"taskName":"Teammates","taskParams":{"hasIndividuals":"true","hasGroup":"false"}},
      {"taskName":"Optimization","taskParams":{"hasIndividuals":"false","hasGroup":"true","function":"8","intro":"group_3","maxResponses":"10"}},
      {"taskName":"Optimization","taskParams":{"hasIndividuals":"false","hasGroup":"true","function":"9","intro":"group_alt_intro","maxResponses":"10"}},
      {"taskName":"Memory","taskParams":{"hasIndividuals":"false","hasGroup":"true","test":"group_3_instructions"}},
      {"taskName":"Memory","taskParams":{"hasIndividuals":"false","hasGroup":"true","test":"group_3"}},
      {"taskName":"Shapes","taskParams":{"hasIndividuals":"false","hasGroup":"true","subtest":"subtest4"}},
      {"taskName":"Conclusion","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_3","hasCode":"false","displayScoreGroup":"false","digitalReceipt":"false", "feedback":"false", "feedbackLinkType":"none"}}
      ]';
    return Self::initializeTasks($group_id, $taskArray, $randomize);
  }

  public static function initializeLabRoundFourTasks($group_id, $randomize) {
    $taskArray = '[
      {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_2"}},
      {"taskName":"ChooseReporter","taskParams":{"hasIndividuals":"true","hasGroup":"true"}},
      {"taskName":"Teammates","taskParams":{"hasIndividuals":"true","hasGroup":"false"}},
      {"taskName":"Optimization","taskParams":{"hasIndividuals":"false","hasGroup":"true","function":"10","intro":"group_4","maxResponses":"10"}},
      {"taskName":"Optimization","taskParams":{"hasIndividuals":"false","hasGroup":"true","function":"12","intro":"group_alt_intro","maxResponses":"10"}},
      {"taskName":"Memory","taskParams":{"hasIndividuals":"false","hasGroup":"true","test":"group_4"}},
      {"taskName":"Shapes","taskParams":{"hasIndividuals":"false","hasGroup":"true","subtest":"subtest1"}},
      {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_5_break"}},
      {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_5"}},
      {"taskName":"Cryptography","taskParams":{"hasIndividuals":"false","intro":"group_5","hasGroup":"true","mapping":"random","maxResponses":"10"}},
      {"taskName":"Cryptography","taskParams":{"hasIndividuals":"false","intro":"group_5_alt","hasGroup":"true","mapping":"random","maxResponses":"10"}},
      {"taskName":"Conclusion","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_4","hasCode":"false","displayScoreGroup":"false","digitalReceipt":"true", "sonaId": "547", "payment": "60", "feedback":"false", "feedbackLinkType":"group5Pilot"}}
      ]';
    return Self::initializeTasks($group_id, $taskArray, $randomize);
  }

  public static function initializeLabRoundFiveTasks($group_id, $randomize) {
    $taskArray = '[
      {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_5"}}
      ]';
    return Self::initializeTasks($group_id, $taskArray, $randomize);
  }

  public static function initializeAssignedBlockTasks($group_id) {
    $nextBlock = null;
    $lastBlock = \DB::table('random_block_assignments')->orderBy('created_at', 'desc')->first();

    switch ($lastBlock->block) {
      case 'A':
        $nextBlock = 'B';
        Self::initializeBlockBTasks($group_id, false);
        break;

      case 'B':
        $nextBlock = 'C';
        Self::initializeBlockCTasks($group_id, false);
        break;

      case 'C':
        $nextBlock = 'D';
        Self::initializeBlockDTasks($group_id, false);
        break;

      case 'D':
      default:
        $nextBlock = 'A';
        Self::initializeBlockATasks($group_id, false);
        break;
    }

    \DB::table('random_block_assignments')
        ->insert(['group_id' => $group_id, 'block' => $nextBlock,
                  'created_at' => \Carbon\Carbon::now(),
                  'updated_at' => \Carbon\Carbon::now()]);
    return;
  }

  #GENERAL FUNCTION FOR INITIALIZING TASKS FROM A TASKLIST 
  public static function initializeTasks($group_id, $requiredTasks, $randomize = false) {

    $tasks = json_decode($requiredTasks);

    //CREATE NEW GROUPTASK ENTRIES BY LOOPING THROUGH THE TASKLIST
    foreach ($tasks as $key => $task) {

      $g = new GroupTask;
      $g->group_id = $group_id;
      $g->name = $task->taskName;
      $g->order = $key + 1;
      $g->parameters = serialize($task->taskParams);
      $g->mapping = serialize((new \Teamwork\Tasks\Cryptography)->getMapping('random'));
      $g->save();

      if($task->taskParams->hasIndividuals == 'true') {
        \Teamwork\IndividualTask::create(['group_task_id' => $g->id]);
      }
    }

    return GroupTask::where('group_id', $group_id)
      ->with('individualTasks')
      ->orderBy('order', 'ASC')
      ->get();
  }

  public static function setDefaultTaskParameters($taskName) {
    $parameters = [];
    if($taskName == 'Brainstorming') {
      $parameters = ['prompt' => (new \Teamwork\Tasks\Brainstorming)->getRandomPrompt()];
    }
    if($taskName == 'Optimization') {
      $parameters = ['function' => (new \Teamwork\Tasks\Optimization)->getRandomFunction(),
                     'maxResponses' => 6];
    }
    if($taskName == 'Cryptography') {
      $parameters = ['mapping' => (new \Teamwork\Tasks\Cryptography)->randomMapping(),
                     'maxResponses' => 10];
    }

    return $parameters;
  }
}
