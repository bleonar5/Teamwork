<?php

#LEGACY CODE -- GABE MANSUR

namespace Teamwork\Tasks;

class Brainstorming {


  private static $avaialbleParams = ['hasIndividuals' => ['true', 'false'], 'hasGroup' => ['true', 'false']];

  public static function getAvailableParams()
  {
    return Self::$avaialbleParams;
  }

}
