<?php

namespace Teamwork;

use Illuminate\Database\Eloquent\Model;

class ConfirmationCode extends Model
{
  public function user()
  {
    return $this->hasOne('Teamwork\User');
  }
}
