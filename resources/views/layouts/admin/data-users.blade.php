@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
      <div class="col-md-12">
        <table class="table table-striped">
            <tr>
              <th>User ID</th>
              <th>Unique ID</th>
              <th>Eligibility</th>
              <th>Score</th>
              <th>Created At</th>
              <th>Updated</th>
            </tr>
            @foreach($userData as $d)
              <tr>
                <td>{{ $d['user']->participant_id }}</td>
                <td>
                  {{ $d['user']->survey_code }}
                </td>
                <td>
                  @if($d['user']->score_group == '1')
                    Eligible
                  @elseif($d['user']->score_group == '2')
                    Ineligible
                  @else
                    Incomplete
                  @endif
                </td>
                <td>{{ $d['user']->score }}</td>
                <td>{{ $d['user']->created_at }}</td>
                <td>{{ $d['user']->updated_at }}</td>
              </tr>
            @endforeach
        </table>
      </div>
  </div>
</div>
@stop
