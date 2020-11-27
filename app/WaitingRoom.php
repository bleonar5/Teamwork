<?php

namespace Teamwork;

use Illuminate\Database\Eloquent\Model;
use Teamwork\User;

class WaitingRoom extends Model
{
    public function users() {
      return $this->hasMany('\Teamwork\User');
    }
}
