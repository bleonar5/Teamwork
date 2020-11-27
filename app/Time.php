<?php

namespace Teamwork;

use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    protected $fillable = ['user_id', 'group_tasks_id', 'individual_tasks_id', 'type'];

    public function recordStartTime() {
      $this->start_time = date("Y-m-d H:i:s");
      $this->save();
    }

    public function recordEndTime() {
      $this->end_time = date("Y-m-d H:i:s");
      $this->save();
    }
}
