<?php
namespace Teamwork\Tasks;
use Teamwork\ConfirmationCode;

class Feedback {

  private $messages = [
    'mturk' => [[
                  'type' => 'sub-header',
                  'content' => 'You\'ve completed all the tasks!'
                ],
                [
                  'type' => 'sub-header',
                  'content' => 'Thank you for participating!'
                ],
      ],
    'hdsl_individual' => [[
                  'type' => 'sub-header',
                  'content' => 'You\'ve completed all the online tasks!'
                ],
                [
                  'type' => 'sub-header',
                  'content' => 'Thank you for participating!'
                ],
      ],
  ];

  private static $avaialbleParams = ['hasIndividuals' => ['true', 'false'], 'hasGroup' => ['false'], 'type' => ['mturk', 'hdsl_individual'], 'hasCode' => ['true', 'false']];

  public function getMessage($type) {
    return $this->messages[$type];
  }

  public static function getAvailableParams()
  {
    return Self::$avaialbleParams;
  }

}
