@extends('layouts.master')


@section('content')
<div class="container">
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
      <form action="/group-add-participants" method="post">
        {{ csrf_field() }}
        <fieldset class="bg-light p-4 rounded mb-lg-2">
          <div class="form-group">
            <label for="group_id">Group ID</label>
            <input type="text" class="form-control" name="group_id"
                   value="{{ old('group_id') }}">
          </div>
          <div class="form-group">
            <label for="group_id">
              Participant IDs<br>
              <small>Separated by a semicolon</small>
            </label>
            <textarea class="form-control" name="participant_ids" rows="6"></textarea>
          </div>
        </fieldset>
        <div class="text-center">
          <button class="btn btn-lg btn-primary" type="submit">Add</button>
        </div>
      </form>
    </div>
  </div>
</div>
@stop
