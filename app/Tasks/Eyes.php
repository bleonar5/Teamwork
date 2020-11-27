<?php
namespace Teamwork\Tasks;

class Eyes {

  private $DIRECTORY = '/img/rmet/';
  private $test = [
        ['img' => 'eyes0.jpeg',
        'choices' => ['jealous', 'panicked', 'arrogant', 'hateful'],
        'correct' => ''],
        ['img' => 'eyes1.jpeg',
        'choices' => ['playful', 'comforting', 'irritated', 'bored'],
        'correct' => 'playful'],
        ['img' => 'eyes2.jpeg',
        'choices' => ['terrified', 'upset', 'arrogant', 'annoyed'],
        'correct' => 'upset'],
        ['img' => 'eyes4.jpeg',
        'choices' => ['joking', 'insisting', 'amused', 'relaxed'],
        'correct' => 'insisting'],
        ['img' => 'eyes5.jpeg',
        'choices' => ['irritated', 'sarcastic', 'worried', 'friendly'],
        'correct' => 'worried'],
        ['img' => 'eyes7.jpeg',
        'choices' => ['apologetic', 'friendly', 'uneasy', 'dispirited'],
        'correct' => 'uneasy'],
        ['img' => 'eyes9.jpeg',
        'choices' => ['annoyed', 'hostile', 'horrified', 'preoccupied'],
        'correct' => 'preoccupied'],
        ['img' => 'eyes10.jpeg',
        'choices' => ['cautious', 'insisting', 'bored', 'aghast'],
        'correct' => 'cautious'],
        ['img' => 'eyes11.jpeg',
        'choices' => ['terrified', 'amused', 'regretful', 'flirtatious'],
        'correct' => 'regretful'],
        ['img' => 'eyes13.jpeg',
        'choices' => ['decisive', 'anticipating', 'threatening', 'shy'],
        'correct' => 'anticipating'],
        ['img' => 'eyes14.jpeg',
        'choices' => ['irritated', 'disappointed', 'depressed', 'accusing'],
        'correct' => 'accusing'],
        ['img' => 'eyes15.jpeg',
        'choices' => ['contemplative', 'flustered', 'encouraging', 'amused'],
        'correct' => 'contemplative'],
        ['img' => 'eyes16.jpeg',
        'choices' => ['irritated', 'thoughtful', 'encouraging', 'sympathetic'],
        'correct' => 'thoughtful'],
        ['img' => 'eyes17.jpeg',
        'choices' => ['doubtful', 'affectionate', 'playful', 'aghast'],
        'correct' => 'doubtful'],
        ['img' => 'eyes18.jpeg',
        'choices' => ['decisive', 'amused', 'aghast', 'bored'],
        'correct' => 'decisive'],
        ['img' => 'eyes19.jpeg',
        'choices' => ['arrogant', 'grateful', 'sarcastic', 'tentative'],
        'correct' => 'tentative'],
        ['img' => 'eyes20.jpeg',
        'choices' => ['dominant', 'friendly', 'guilty', 'horrified'],
        'correct' => 'friendly'],
        ['img' => 'eyes22.jpeg',
        'choices' => ['preoccupied', 'grateful', 'insisting', 'imploring'],
        'correct' => 'preoccupied'],
        ['img' => 'eyes23.jpeg',
        'choices' => ['contented', 'apologetic', 'defiant', 'curious'],
        'correct' => 'defiant'],
        ['img' => 'eyes25.jpeg',
        'choices' => ['panicked', 'incredulous', 'despondent', 'interested'],
        'correct' => 'interested'],
        ['img' => 'eyes26.jpeg',
        'choices' => ['alarmed', 'shy', 'hostile', 'anxious'],
        'correct' => 'hostile'],
        ['img' => 'eyes27.jpeg',
        'choices' => ['joking', 'cautious', 'arrogant', 'reassuring'],
        'correct' => 'cautious'],
        ['img' => 'eyes29.jpeg',
        'choices' => ['impatient', 'aghast', 'irritated', 'reflective'],
        'correct' => 'reflective'],
        ['img' => 'eyes31.jpeg',
        'choices' => ['ashamed', 'confident', 'joking', 'dispirited'],
        'correct' => 'confident'],
        ['img' => 'eyes34.jpeg',
        'choices' => ['aghast', 'baffled', 'distrustful', 'terrified'],
        'correct' => 'distrustful'],
        ['img' => 'eyes35.jpeg',
        'choices' => ['puzzled', 'nervous', 'insisting', 'contemplative'],
        'correct' => 'nervous'],
        ['img' => 'eyes36.jpeg',
        'choices' => ['ashamed', 'nervous', 'suspicious', 'indecisive'],
        'correct' => 'suspicious'],

  ];

  private static $avaialbleParams = ['hasIndividuals' => ['true', 'false'], 'hasGroup' => ['false']];


  public function getTest() {
    return $this->test;
  }

  public static function getAvailableParams()
  {
    return Self::$avaialbleParams;
  }

  public function getDirectory() {
    return $this->DIRECTORY;
  }

  public function getImagesForPreloader()
  {
    $imgs = [];
    foreach ($this->test as $key => $value) {
      $imgs[] = $this->DIRECTORY.$value['img'];
    }
    return $imgs;
  }

}
