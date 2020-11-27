<?php

namespace Teamwork;

use Illuminate\Database\Eloquent\Model;

class IndividualTask extends Model
{
    protected $fillable = ['group_task_id'];

    public function groupTask() {
      return $this->belongsTo('\Teamwork\GroupTask');
    }
}
