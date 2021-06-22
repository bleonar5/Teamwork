<?php

#LEGACY CODE -- GABE MANSUR

namespace Teamwork\Tasks;

class Survey {


  private static $avaialbleParams = ['survey' => ['hdsl'], 'hasIndividuals' => ['true'], 'hasGroup' => ['false']];


  public static function getAvailableParams()
  {
    return Self::$avaialbleParams;
  }

}
