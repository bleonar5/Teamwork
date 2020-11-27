<?php

namespace Teamwork;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  public function users() {
    return $this->hasMany('\User', 'role_id', 'id');
  }

}
