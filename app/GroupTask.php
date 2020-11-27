<?php

namespace Teamwork;

use Illuminate\Database\Eloquent\Model;

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

    public static function initializeDefaultTasks($group_id, $randomize) {

      $taskArray = '[{"taskName":"TeamRole","taskParams":{"hasIndividuals":"true","hasGroup":"false","scenarios":"all"}},{"taskName":"BigFive","taskParams":{"hasIndividuals":"true","hasGroup":"false","statementOrder":"random"}},{"taskName":"Eyes","taskParams":{"hasIndividuals":"true","hasGroup":"false"}},{"taskName":"Cryptography","taskParams":{"hasIndividuals":"true","hasGroup":"false","mapping":"random","maxResponses":"10"}},{"taskName":"Optimization","taskParams":{"hasIndividuals":"true","hasGroup":"false","function":"a","maxResponses":"9","intro":"individual"}},{"taskName":"Optimization","taskParams":{"hasIndividuals":"true","hasGroup":"false","function":"b","maxResponses":"9","intro":"individual_alt"}},{"taskName":"Shapes","taskParams":{"hasIndividuals":"true","hasGroup":"false","subtest":"subtest1"}},{"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":["faces_1","words_1","story_1"]}}]';
      return Self::initializeTasks($group_id, $taskArray, $randomize);

    }

    public static function initializeIQTasks($group_id, $randomize) {
      $taskArray = '[{"taskName":"Shapes","taskParams":{"hasIndividuals":"true","hasGroup":"false","subtest":"subtest1"}},{"taskName":"Cryptography","taskParams":{"hasIndividuals":"true","hasGroup":"false","mapping":"random","maxResponses":"15"}},{"taskName":"Optimization","taskParams":{"hasIndividuals":"true","hasGroup":"false","function":"c","intro":"individual","maxResponses":"9"}},{"taskName":"Optimization","taskParams":{"hasIndividuals":"true","hasGroup":"false","function":"f","intro":"individual_alt","maxResponses":"9"}},{"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":["faces_1","words_1","story_1"]}}]';
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
        {"taskName":"Optimization","taskParams":{"hasIndividuals":"true","hasGroup":"false","function":"1","intro":"individual","maxResponses":"15"}},
        {"taskName":"Cryptography","taskParams":{"hasIndividuals":"false","intro":"group_5","hasGroup":"true","mapping":"random","maxResponses":"15"}},
        {"taskName":"Conclusion","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_4","hasCode":"false","displayScoreGroup":"false", "digitalReceipt":"true", "sonaId": "547", "payment": "30", "feedback":"false", "feedbackLinkType":"group5Pilot"}}
        ]';
      return Self::initializeTasks($group_id, $taskArray, $randomize);
    }


    public static function initializeLabIndividualTasks($group_id, $randomize) {
      $taskArray = '[
          {"taskName":"Consent","taskParams":{"hasIndividuals":"true","hasGroup":"false","subjectPool":"hdsl_individual"}},
          {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"eligibility"}},
          {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"hdsl_individual"}},
          {"taskName":"BigFive","taskParams":{"hasIndividuals":"true","hasGroup":"false","statementOrder":"random"}},
          {"taskName":"Shapes","taskParams":{"hasIndividuals":"true","hasGroup":"false","subtest":"subtest5"}},
          {"taskName":"Eyes","taskParams":{"hasIndividuals":"true","hasGroup":"false"}},
          {"taskName":"Optimization","taskParams":{"hasIndividuals":"true","hasGroup":"false","function":"1","intro":"individual","maxResponses":"15"}},
          {"taskName":"Optimization","taskParams":{"hasIndividuals":"true","hasGroup":"false","function":"2","intro":"individual_alt","maxResponses":"15"}},
          {"taskName":"Optimization","taskParams":{"hasIndividuals":"true","hasGroup":"false","function":"3","intro":"individual_alt","maxResponses":"15"}},
          {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"intro"}},
          {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"words_instructions"}},
          {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"words_1"}},
          {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"images_instructions"}},
          {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"faces_1"}},
          {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"story_instructions"}},
          {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"story_1"}},
          {"taskName":"Memory","taskParams":{"hasIndividuals":"true","hasGroup":"false","test":"results"}},
          {"taskName":"Survey","taskParams":{"hasIndividuals":"true","hasGroup":"false","survey":"hdsl"}},
          {"taskName":"Conclusion","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"hdsl_individual","hasCode":"false","displayScoreGroup":"true","digitalReceipt":"false","feedback":"false", "feedbackLinkType":"pilot"}}
        ]';
      return Self::initializeTasks($group_id, $taskArray, $randomize);
    }

    public static function initializeLabRoundOneTasks($group_id, $randomize) {
      $taskArray = '[
        {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_1"}},
        {"taskName":"ChooseReporter","taskParams":{"hasIndividuals":"true","hasGroup":"true"}},
        {"taskName":"Teammates","taskParams":{"hasIndividuals":"true","hasGroup":"false"}},
        {"taskName":"Optimization","taskParams":{"hasIndividuals":"false","hasGroup":"true","function":"4","intro":"group_1","maxResponses":"15"}},
        {"taskName":"Optimization","taskParams":{"hasIndividuals":"false","hasGroup":"true","function":"5","intro":"group_alt_intro","maxResponses":"15"}},
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
        {"taskName":"Optimization","taskParams":{"hasIndividuals":"false","hasGroup":"true","function":"6","intro":"group_2","maxResponses":"15"}},
        {"taskName":"Optimization","taskParams":{"hasIndividuals":"false","hasGroup":"true","function":"7","intro":"group_alt_intro","maxResponses":"15"}},
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
        {"taskName":"Optimization","taskParams":{"hasIndividuals":"false","hasGroup":"true","function":"8","intro":"group_3","maxResponses":"15"}},
        {"taskName":"Optimization","taskParams":{"hasIndividuals":"false","hasGroup":"true","function":"9","intro":"group_alt_intro","maxResponses":"15"}},
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
        {"taskName":"Optimization","taskParams":{"hasIndividuals":"false","hasGroup":"true","function":"10","intro":"group_4","maxResponses":"15"}},
        {"taskName":"Optimization","taskParams":{"hasIndividuals":"false","hasGroup":"true","function":"12","intro":"group_alt_intro","maxResponses":"15"}},
        {"taskName":"Memory","taskParams":{"hasIndividuals":"false","hasGroup":"true","test":"group_4"}},
        {"taskName":"Shapes","taskParams":{"hasIndividuals":"false","hasGroup":"true","subtest":"subtest1"}},
        {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_5_break"}},
        {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_5"}},
        {"taskName":"Cryptography","taskParams":{"hasIndividuals":"false","intro":"group_5","hasGroup":"true","mapping":"random","maxResponses":"15"}},
        {"taskName":"Cryptography","taskParams":{"hasIndividuals":"false","intro":"group_5_alt","hasGroup":"true","mapping":"random","maxResponses":"15"}},
        {"taskName":"Conclusion","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_4","hasCode":"false","displayScoreGroup":"false","digitalReceipt":"true", "sonaId": "547", "payment": "60", "feedback":"false", "feedbackLinkType":"group5Pilot"}}
        ]';
      return Self::initializeTasks($group_id, $taskArray, $randomize);
    }

    public static function initializeLabRoundFiveTasks($group_id, $randomize) {
      $taskArray = '[
        {"taskName":"Intro","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_5"}},
        {"taskName":"ChooseReporter","taskParams":{"hasIndividuals":"true","hasGroup":"true"}},
        {"taskName":"Cryptography","taskParams":{"hasIndividuals":"false","intro":"group_5","hasGroup":"true","mapping":"random","maxResponses":"15"}},
        {"taskName":"Cryptography","taskParams":{"hasIndividuals":"false","intro":"group_5_alt","hasGroup":"true","mapping":"random","maxResponses":"15"}},
        {"taskName":"Conclusion","taskParams":{"hasIndividuals":"true","hasGroup":"false","type":"group_5","hasCode":"false","displayScoreGroup":"false","digitalReceipt":"true", "feedback":"true"}}
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


    public static function initializeTasks($group_id, $requiredTasks, $randomize = false) {
      $tasks = json_decode($requiredTasks);
      foreach ($tasks as $key => $task) {
        $g = new GroupTask;
        $g->group_id = $group_id;
        $g->name = $task->taskName;
        $g->order = $key + 1;
        $g->parameters = serialize($task->taskParams);
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
