<?php
namespace Teamwork\Tasks;

class WaitingRoom {


  private static $avaialbleParams = ['hasIndividuals' => ['true', 'false'], 'hasGroup' => ['true', 'false'],'task'] => ['1','2'];

  public static function getAvailableParams()
  {
    return Self::$avaialbleParams;
  }

}
