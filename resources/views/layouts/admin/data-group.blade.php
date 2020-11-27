@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
      <div class="col-md-12">
        <table class="table table-striped">
            <tr>
              <th>User ID</th>
              <th>Reporter</th>
              <th>Unique ID</th>
              <th>Group ID</th>
              <th>Task</th>
              <th>Instruction Time</th>
              <th>Task Time</th>
              <th>Prompt</th>
              <th>Response</th>
              <th>Correct</th>
              <th>Points</th>
              <th>Recorded</th>
            </tr>
            @foreach($userData as $data)
              @foreach($data as $user)
                @foreach($user['tasks'] as $task)
                 @foreach($task['responses'] as $responses)
                  @foreach($responses as $response)
                    <tr>
                      <td>{{ $user['user'] }}</td>
                      <td>
                        @if($user['isReporter'])
                          Reporter
                        @else
                          Not Reporter
                        @endif
                      </td>
                      <td>
                        {{ $user['surveyCode'] }}
                      </td>
                      <td>{{ $user['group'] }}</td>
                      <td>{{ $task['name'] }}</td>
                      <td>{{ $task['introTime'] }}</td>
                      <td>{{ $task['taskTime'] }}</td>
                      <td>{{ $response['prompt'] }}</td>
                      <td>{{ $response['response'] }}</td>
                      <td>{{ $response['correct'] }}</td>
                      <td>{{ $response['points'] }}</td>
                      <td>{{ $response['time'] }}</td>
                    </tr>
                  @endforeach
                 @endforeach
                @endforeach
              @endforeach
            @endforeach
        </table>
      </div>
  </div>
</div>
@stop
