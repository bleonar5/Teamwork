<?php

namespace Teamwork;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'group_number'
    ];

    public function users() {
      return $this->hasMany('\Teamwork\User');
    }

    public function groupTasks() {
      return $this->hasMany('\Teamwork\GroupTask');
    }

    public function responses() {
      return $this->hasManyThrough('Teamwork\Response', 'Teamwork\GroupTask');
    }
}
