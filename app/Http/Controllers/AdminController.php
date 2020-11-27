<?php

namespace Teamwork\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use Maatwebsite\Excel\Concerns\FromView;

class AdminController extends Controller
{
    public function getAdminHome() {
      $sessionOneTimeslots = $this->getTimeslotData(547);
      $sessionTwoTimeslots = $this->getTimeslotData(548);
      return view('layouts.admin.admin')
             ->with('sessionOneSlots', $sessionOneTimeslots)
             ->with('sessionTwoSlots', $sessionTwoTimeslots);
    }

    public function getCSV() {


      $users = \Teamwork\User::where('role_id', 3)
                             ->with('group');

      $userData = [];

      // Pass reference to userData so we can push to it inside the closure
      $users->chunk(1, function ($users) use(&$userData) {
            array_push($userData, $this->collectResponses($users));
      });

      $responses = [];
      foreach($userData as $data) {
        foreach($data as $user) {
          foreach($user['tasks'] as $task) {
           foreach($task['responses'] as $responses) {
            foreach($responses as $response) {

                $responses[] = ['user' => $user['user'],
                                'isReporter' => $user['isReporter'],
                                'eligible' => $user['eligible'],
                                'score' => $user['score'],
                                'group' => $user['group'],
                                'task' => $task['name'],
                                'introTime' => $task['introTime'],
                                'taskTime' => $task['taskTime'],
                                'prompt' => $response['prompt'],
                                'response' => $response['response'],
                                'correct' => $response['correct'],
                                'points' => $response['points'],
                                'time' => $response['time']];
            }
          }
        }
      }
    }
      $collection = collect($responses);
      $filedate = date('Y-m-d');

      return Excel::create('TaskData_'.$filedate, function($excel) use($collection){

        $excel->sheet('TaskResponses', function($sheet) use($collection){
            $sheet->fromModel($collection, null, 'A1', true);
        });

        })->export('xls');
    }

    // Returns an array of groups that contain 1 person (i.e. Individual Task participants)
    private function getIndividualGroups() {
      $groups = \DB::select( \DB::raw("SELECT group_id
      FROM group_user
      GROUP BY group_id
      HAVING COUNT(*) = 1"));
      $groupIds = [];
      foreach($groups as $key => $group) {
        $groupIds[] = $group->group_id;
      }
      return $groupIds;
    }

    // Returns an array of groups that contain multiple people (i.e. NOT Individual Task participants)
    private function getGroups() {
      $groups = \DB::select( \DB::raw("SELECT group_id
      FROM group_user
      GROUP BY group_id
      HAVING COUNT(*) > 1"));
      $groupIds = [];
      foreach($groups as $key => $group) {
        $groupIds[] = $group->group_id;
      }
      return $groupIds;
    }

    public function getUsers() {

      $users = \Teamwork\User::where('id', '>', 667) // When we went live with the lab version
                            ->with('group')
                            ->get();


      $userData = [];

      foreach($users as $user) {
        $groups = \DB::table('group_user')
                     ->where('user_id', $user->id)
                     ->get();

        $groupTasks = [];

        foreach($groups as $group) {
          $tasks = \Teamwork\Group::where('id', $group->group_id)->with('groupTasks')->get();
          $groupTasks[] = $tasks;
        }
        $userData[] = ['user' => $user, 'groups' => $groupTasks];

      }

      return view('layouts.admin.data-users')
             ->with('userData', $userData);

    }

    private function applyDateFilter($filter, $date) {

      switch($filter){
        case 'all':
          $filterDate = \Carbon\Carbon::createFromFormat('Y-m-d H', '2019-07-01 0');
          break;

        case 'day':
          $filterDate = \Carbon\Carbon::now()->startOfDay();
          break;

        case 'date':
          $filterDate = \Carbon\Carbon::createFromFormat('Y-m-d H', $date .' 0'); // For midnight that day (i.e. ALL DAY)
          break;

        case 'week':
        default:
          $filterDate = \Carbon\Carbon::now()->subWeek();
          break;
      }
      return $filterDate;
    }

    public function getIndividualCSV(Request $request) {

      // Filter
      $filterDate = $this->applyDateFilter($request->filter, $request->date);

      $groups = $this->getIndividualGroups();
      $userData = [];

      foreach ($groups as $key => $group) {
        $userId = \DB::table('group_user')
                     ->where('group_id', $group)
                     ->pluck('user_id')
                     ->first();

        $users = \Teamwork\User::where('id', $userId)
                              ->with('group')
                              ->get();

        array_push($userData, $this->collectResponses($users, $group));

      }

      $responseData = [];
      foreach($userData as $data) {
        foreach($data as $user) {

          foreach($user['tasks'] as $task) {

            foreach($task['responses'] as $responses) {

              foreach($responses as $response) {
                  if($response['time']->lessThan($filterDate)){
                    continue;
                  }
                  $responseData[] = ['user' => $user['user'],
                                      'uniqueId' => $user['surveyCode'],
                                      'eligible' => $user['eligible'],
                                      'score' => $user['score'],
                                      'task' => $task['name'],
                                      'introTime' => $task['introTime'],
                                      'taskTime' => $task['taskTime'],
                                      'prompt' => $response['prompt'],
                                      'response' => $response['response'],
                                      'correct' => $response['correct'],
                                      'points' => $response['points'],
                                      'time' => $response['time']
                                    ];
              }
            }
          }
        }
      }

      $collection = collect($responseData);

      $filedate = date('d-m-Y');
      // Temporarily set exectution time and memory, cuz these files are large, yo
      ini_set('max_execution_time', 300); //300 seconds = 5 minutes
      ini_set('memory_limit', '1024M');

      Excel::create('individual_responses_'.$filedate, function($excel) use ($collection) {
          $excel->sheet('individual_responses', function($sheet) use($collection) {
              $sheet->appendRow(array(
                'user', 'uniqueId', 'eligible', 'score', 'task', 'introTime', 'taskTime',
                'prompt', 'response', 'correct', 'points', 'time'
              ));

              foreach($collection->chunk(100) as $chunks) {
                foreach ($chunks as $key => $row) {
                  $sheet->appendRow($row);
                }
              }
          });
      })->download('xlsx');
    }

    public function getGroupCSV(Request $request) {
      // Filter
      $filterDate = $this->applyDateFilter($request->filter, $request->date);

      $groups = $this->getGroups();
      $userData = [];

      foreach ($groups as $key => $group) {
        $userIds = \DB::table('group_user')
                     ->where('group_id', $group)
                     ->pluck('user_id');

        $users = \Teamwork\User::whereIn('id', $userIds)
                              ->with('group')
                              ->get();

        array_push($userData, $this->collectResponses($users, $group));

      }

      $responseData = [];
      foreach($userData as $data) {
        foreach($data as $user) {

          foreach($user['tasks'] as $task) {

            foreach($task['responses'] as $responses) {

              foreach($responses as $response) {
                if($response['time']->lessThan($filterDate)){
                  continue;
                }
                $responseData[] = ['user' => $user['user'],
                                'uniqueId' => $user['surveyCode'],
                                'isReporter' => $user['isReporter'],
                                'knowTeammates' => $user['knowTeammates'],
                                'eligible' => $user['eligible'],
                                'score' => $user['score'],
                                'group' => $user['group'],
                                'task' => $task['name'],
                                'introTime' => $task['introTime'],
                                'taskTime' => $task['taskTime'],
                                'prompt' => $response['prompt'],
                                'response' => $response['response'],
                                'correct' => $response['correct'],
                                'points' => $response['points'],
                                'time' => $response['time']];
              }
            }
          }
        }
      }

      $collection = collect($responseData);
      $filedate = date('d-m-Y');

      // Temporarily set exectution time and memory, cuz these files are large, yo
      ini_set('max_execution_time', 300); //300 seconds = 5 minutes
      ini_set('memory_limit', '1024M');

      Excel::create('group_responses_'.$filedate, function($excel) use ($collection) {
          $excel->sheet('group_responses', function($sheet) use($collection) {
              $sheet->appendRow(array(
                'user', 'uniqueId', 'isReporter', 'knowTeammates', 'eligible', 'score', 'group',
                'task', 'introTime', 'taskTime',
                'prompt', 'response', 'correct', 'points', 'time'
              ));

              foreach($collection->chunk(100) as $chunks) {
                foreach ($chunks as $key => $row) {
                  $sheet->appendRow($row);
                }
              }
          });
      })->download('xlsx');

    }

    public function getIndividualTaskResponses() {
      $groups = $this->getIndividualGroups();
      $userData = [];

      foreach ($groups as $key => $group) {
        $userId = \DB::table('group_user')
                     ->where('group_id', $group)
                     ->pluck('user_id')
                     ->first();

        $users = \Teamwork\User::where('id', $userId)
                              ->with('group')
                              ->get();

        array_push($userData, $this->collectResponses($users, $group));

      }

      return view('layouts.admin.data-individual')
             ->with('userData', $userData);
    }

    public function getGroupTaskResponses() {

      $individualGroups = $this->getIndividualGroups();

      $userGroups = \DB::table('group_user')
                   ->whereNotIn('group_id', $individualGroups)
                   ->get();

      $userData = [];

      foreach($userGroups as $k => $userGroup) {
        $users = \Teamwork\User::where('id', $userGroup->user_id)
                               ->with('group')
                               ->get();

        array_push($userData, $this->collectResponses($users, $userGroup->group_id));
        // Pass reference to userData so we can push to it inside the closure
        /*
        $users->chunk(1, function ($users) use(&$userData) {
              array_push($userData, $this->collectResponses($users, $userGroup->group_id));
        });
        */
      }

      return view('layouts.admin.data-group')
             ->with('userData', $userData);
    }

    public function getResponses() {
      $users = \Teamwork\User::where('role_id', 3)
                             ->with('group');

      $userData = [];

      // Pass reference to userData so we can push to it inside the closure
      $users->chunk(1, function ($users) use(&$userData) {
            array_push($userData, $this->collectResponses($users));
      });

      return view('layouts.admin.data')
             ->with('userData', $userData);
    }



    private function collectResponses($users, $groupId) {
      $userData = [];
      foreach ($users as $key => $user) {

          $reporterCheck = \DB::table('reporters')
             ->where('user_id', $user->id)
             ->where('group_id', $groupId)
             ->first();

          $isReporter = ($reporterCheck) ? true : false;

          $group = \Teamwork\Group::find($groupId);

          $knowTeammates = \DB::table('teammates')
                              ->where('user_id', $user->id)
                              ->where('group_id', $groupId)
                              ->pluck('know_teammates')
                              ->first();

          $uData = ['user' => $user->participant_id,
                    'isReporter' => $isReporter,
                    'knowTeammates' => $knowTeammates,
                    'eligible' => $user->score_group,
                    'score' => $user->score,
                    'surveyCode' => $user->survey_code,
                    'group'=> $group->group_number,
                    'tasks' => []];

          $groupTasks = \Teamwork\GroupTask::with('response')
                                           ->where('group_id', $groupId)
                                           ->get();

          // We'll create a mem object to access some memory test info later
          $mem = new \Teamwork\Tasks\Memory;

          foreach ($groupTasks as $k => $task) {

            $taskTime = \DB::table('times')
                       ->where('user_id', $user->id)
                       ->where('group_tasks_id', $task->id)
                       ->where('type', 'task')
                       ->first();

            if($taskTime && $taskTime->start_time && $taskTime->end_time) {
              $taskTime = strtotime($taskTime->end_time) - strtotime($taskTime->start_time);
            }

            else $taskTime = null;

            $introTime = \DB::table('times')
                       ->where('user_id', $user->id)
                       ->where('group_tasks_id', $task->id)
                       ->where('type', 'intro')
                       ->first();

            if($introTime && $introTime->start_time && $introTime->end_time) {
              $introTime = strtotime($introTime->end_time) - strtotime($introTime->start_time);
            }

            else $introTime = null;


            try {
              $params = json_encode(unserialize($task->parameters));
            }

            catch(\Exception $e) {
              $params = null;
            }


            $taskData = ['name' => $task->name,
                         'parameters' => $params,
                         'taskTime' => $taskTime,
                         'introTime' => $introTime,
                         'responses' => []];

            $responses = [];
            foreach ($task->response as $response) {
              if($response->user_id != $user->id) continue;
              if($task->name == 'Memory') {


                if(strpos($response->prompt, 'Memory stimulus type') !== false) {
                  $t = (strtotime($response->updated_at) - strtotime($response->created_at));
                  $response->response .= ' (' .$t. ' secs)';
                }

              }
              $responses[] = ['prompt' => $response->prompt,
                              'response' => $response->response,
                              'correct' => $response->correct,
                              'points' => $response->points,
                              'time' => $response->created_at];

            }
            array_push($taskData['responses'], $responses);
            array_push($uData['tasks'], $taskData);
          }
        array_push($userData, $uData);
      }
      return $userData;
    }

    private function getTimeslotData($expId) {
      $startDate = \Carbon\Carbon::now()->toDateString();
      $endDate = \Carbon\Carbon::now()->addMonth()->toDateString();
      $slots = $this->GetTimeslotsByExperimentIDDateRange($expId, $startDate, $endDate);

      $timeslots = [];

      foreach($slots as $slot) {

        $signups = $this->getSignUpsForTimeslot($slot->timeslot_id);
        $participants = [];
        foreach($signups as $signup){
          $name = $signup->first_name->__toString() .' '. $signup->last_name->__toString() .' (' .$signup->email->__toString() .')';
          $user = \Teamwork\User::where('participant_id', $signup->email->__toString())->first();
          if(!$user){
            $participants[] = ['participant' => $name,
                            'score' => -999, 'eligible' => 'Not Found'];
          }
          else {
            $participants[] = ['participant' => $name,
                            'score' => $user->score, 'eligible' => $user->score_group];
          }
        }
        
        usort($participants, function($a, $b) {
          return $a['score'] < $b['score'];
        });

        $timeslots[] = ['datetime' => $slot->timeslot_date->__toString(),
                        'numRequested' => $slot->num_students->__toString(),
                        'numSignedUp' => $slot->num_signed_up->__toString(),
                        'signups' => $participants];
      }
      return $timeslots;
    }


    private function getTimeslotsByExperimentIDDateRange($experiment_id, $start_date, $end_date)
    {
      $this->xml = $this->getSonaXml('GetTimeslotsByExperimentIDDateRange',
                                 ['experiment_id' => $experiment_id,
                                  'start_date' => $start_date,
                                  'end_date' => $end_date,
                                  'fill_status' => 'A']);


      $timeslots = [];
      foreach ($this->xml->Result->APIStudySchedule as $timeslot) {
        $timeslots[] = $timeslot;
      }
      return $timeslots;
    }

    private function getSignUpsForTimeslot($timeslot_id)
      {
        $this->xml = $this->getSonaXml('GetSignUpsForTimeslot', ['timeslot_id' => $timeslot_id]);
        $timeslots = [];
        foreach ($this->xml->Result->APISignUp as $signup) {
          $timeslots[] = $signup;
        }
        return $timeslots;
      }


    private function getSonaXml($func, $args)
    {
      $url = "https://harvarddecisionlab.sona-systems.com/services/SonaAPI.svc/" . $func . "?username=".env('SONA_USERNAME', '')."&password=".env('SONA_PASSWORD', '');
      foreach ($args as $key => $value) {
        $url .= "&".$key.'='.$value;
      }

      $feed = simplexml_load_file($url);

      foreach ($feed->{$func.'Result'} as $item) {
        $ns_dc = $item->children('http://schemas.datacontract.org/2004/07/emsdotnet.sonasystems');
      }
      return $ns_dc;
    }

}
