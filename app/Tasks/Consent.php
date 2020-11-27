<?php
namespace Teamwork\Tasks;

class Consent {

  private $subjectPool = ['mturk', 'hdsl'];

  private static $avaialbleParams = ['subjectPool' => ['mturk', 'hdsl', 'hdsl_individual_pilot'], 'hasIndividuals' => ['true'], 'hasGroup' => ['false']];

  public function getSubjectPool() {
    return $this->prompts;
  }

  public function setPrompts($prompts) {
    $this->prompts = $prompts;
  }

  public function getRandomPrompt() {
    return $this->prompts[array_rand($this->prompts)];
  }

  public function checkResponses($prompt, $responses) {
    $numCorrect = 0;

    return $numCorrect;
  }

  public static function getAvailableParams()
  {
    return Self::$avaialbleParams;
  }

}
