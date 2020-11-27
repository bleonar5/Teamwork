@extends('layouts.master')


@section('content')
<div class="container">
  <div class="row vertical-center">
    <div class="col-md-6 offset-3 text-left">

      <form action="/teammates" method="post">
        {{ csrf_field() }}
        <h4>
          Apart from having seen them at the Lab, had you previously met either of your teammates?
          <br>(e.g. friends, colleagues…)
        </h4>
        <div style="margin-left: 64px;">
          <div class="form-check">
            <input class="form-check-input form-check-large" type="radio" name="teammates" value="0" checked>
            <label class="form-check-label" for="teammates">
              No
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input form-check-large" type="radio" name="teammates" value="1">
            <label class="form-check-label" for="teammates">
              Yes
            </label>
          </div>
        </div>
        <div class="text-center">
          <button class="btn btn-lg btn-primary" type="submit">Next</button>
        </div>
      </form>
    </div>
  </div>
</div>
@stop
