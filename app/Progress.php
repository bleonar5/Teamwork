<?php

#LEGACY CODE -- GABE MANSUR

namespace Teamwork;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
  protected $table = 'progress';

  public function groupTask() {
    return $this->belongsTo('\Teamwork\GroupTask', 'group_tasks_id', 'id');
  }
}
