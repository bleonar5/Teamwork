<?php
namespace Teamwork\Tasks;

class UnscrambleWords {

  private $words = [
    'unusual', 'cylinder', 'newspaper', 'guitar', 'calendar', 'population',
    'mountain', 'environment', 'business', 'morning', 'neighborhood', 'sandwich',
    'athlete', 'steam', 'talent', 'electron', 'shoe', 'police',
    'joint', 'displace', 'arrange', 'telephone', 'ambulance', 'pencil'
  ];

  private static $avaialbleParams = ['hasIndividuals' => ['false'], 'hasGroup' => ['true'], 'wordList' => ['default']];


  public function getWords() {
    return $this->words;
  }

  public function setWords($words) {
    $this->words = $words;
  }

  public function getScrambledWords() {
    $words = $this->words;
    foreach ($words as $key => $value) {
      $words[$key] = str_shuffle($value);
    }
    return $words;
  }

  public function checkResponses($responses) {
    $numCorrect = 0;
    foreach ($responses as $key => $response) {
      if(in_array(strtolower($response), $this->words)) {
        $numCorrect++;
      }
    }
    return $numCorrect;
  }

  public function checkResponse($response) {
    return in_array(strtolower($response), $this->words);
  }

  public static function getAvailableParams()
  {
    return Self::$avaialbleParams;
  }

}
