<?php

namespace Teamwork;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    public function groupTask() {
      return $this->belongsTo('\Teamwork\GroupTask', 'group_tasks_id', 'id');
    }

}
