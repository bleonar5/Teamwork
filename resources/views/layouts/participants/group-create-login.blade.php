@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/group-create.js') }}"></script>
@stop

@section('content')
<div class="container">
    <div class="row">
      <div class="col-md-8 offset-md-2">
        <div id="task-list"></div>
      </div>
    </div>
    <div class="row justify-content-center">
    <div class="col-md-6 p-4">
      @if($errors)
        @foreach ($errors->all() as $error)
        <h5 class="text-white bg-danger p-2">
          {{ $error }}
        </h5>
        @endforeach
      @endif
      @if ($message = Session::get('message'))
      <div class="alert alert-success text-center" role="alert">
        {{ $message }}
      </div>
      @endif
      <form action="/group-create" method="post">
        {{ csrf_field() }}
        <fieldset class="bg-light p-4 rounded mb-lg-2">
          <div class="form-group">
            <label for="group_id">Group ID</label>
            <input type="text" class="form-control" name="group_id"
                   value="{{ old('group_id') }}">
          </div>
          <h5 class="text-center">Add tasks for this group:</h5>

            <div class="form-group">
              <label for="task">Task:</label>
              <select class="form-control" id="task" name="task">
                  <option>---------------------</option>
                @foreach($tasks as $key => $task)
                  <option value="{{ $task['name'] }}">{{ $task['name'] }}</option>
                @endforeach
              </select>
            </div>

            @foreach($tasks as $key => $task)
              <div id="{{ $task['name'] }}" class="task-params row">
                <div class="col-md-12">
                  <h6>Task Parameters</h6>
                </div>
                @foreach($task['params'] as $key => $param)
                  <div class="params col-md-4">
                    <label class="task-params-select" for="{{ $task['name'].'_'.$key }}">{{ $key }}</label>
                    <select class="form-control form-control-sm task-params-select" data-name="{{ $key }}"
                            id="{{ $task['name'].'_'.$key }}"
                            @if(array_key_exists('allows_multiples', $param))
                              multiple
                            @endif
                    >
                      @if(array_key_exists('allows_multiples', $param))
                        @foreach($param['allows_multiples'] as $option)
                          <option>{{ $option }}</option>
                        @endforeach
                      @else
                        @foreach($param as $option)
                          <option>{{ $option }}</option>
                        @endforeach
                      @endif
                      </select>
                  </div>
                @endforeach
              </div>
            @endforeach

          <input type="hidden" id="taskArray" name="taskArray" value="">
          <div class="text-center">
            <button class="btn btn-lg btn-primary mt-lg-2" id="addTask" type="button">Add</button>
          </div>
        </fieldset>
        <div class="text-center">
          <button class="btn btn-lg btn-primary" type="submit">Finished</button>
        </div>
      </form>
    </div>
  </div>
</div>
@stop
