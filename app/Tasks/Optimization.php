<?php
namespace Teamwork\Tasks;

class Optimization {


  private $functions =      ['t1', 't2', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l'];
  private $functionLabels = ['a1', 'a2', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
  private static $avaialbleParams = ['hasIndividuals' => ['true', 'false'],
                                     'hasGroup' => ['true', 'false'],
                                     'function' => ['a1', 'a2', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
                                     'intro' => ['individual', 'individual_alt', 'group_1', 'group_2', 'group_3', 'group_alt_intro'],
                                     'maxResponses' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]];

  private static $functionStats = [
    '1' => ['ymax' =>  135.46, 'ymin' => -116.61, 'yrange' => 252.06, 'xmax' => 20.90],
    '2' => ['ymax' =>  246.25, 'ymin' => -75.97, 'yrange' => 322.22, 'xmax' => 126.90],
    '3' => ['ymax' =>  110.00, 'ymin' => -58.37, 'yrange' => 168.37, 'xmax' => 300.00],
    '4' => ['ymax' =>  191.06, 'ymin' => -83.54, 'yrange' => 274.60, 'xmax' => 300.00],
    '5' => ['ymax' =>  78.37, 'ymin' => -90, 'yrange' => 168.37, 'xmax' => 244.70],
    '6' => ['ymax' =>  250, 'ymin' => 100, 'yrange' => 150, 'xmax' => 275.00],
    '7' => ['ymax' =>  -50, 'ymin' => -95.28, 'yrange' => 45.28, 'xmax' => 0],
    '8' => ['ymax' =>  112.54, 'ymin' => 0, 'yrange' => 112.53, 'xmax' => 50.30],
    '9' => ['ymax' =>  250.35, 'ymin' => -149.01, 'yrange' => 399.36, 'xmax' =>250.00 ],
    '10' => ['ymax' =>  128.08, 'ymin' => -89.90, 'yrange' => 217.98, 'xmax' => 204.20],
    '11' => ['ymax' =>  104.2, 'ymin' => -49, 'yrange' => 153.20, 'xmax' => 0],
    '12' => ['ymax' =>  124.72, 'ymin' => -98.82, 'yrange' => 223.54, 'xmax' => 83.90]
  ];


  public function getFunctions() {
    return $this->functions;
  }

  public function setFunctions($functions) {
    $this->functions = $functions;
  }


  public function getRandomFunction() {
    return $this->functions[array_rand($this->functions)];
  }

  public function getFunction($functionType) {

    if($functionType == 'random') {
      return $this->functions[array_rand($this->functions)];
    }

    else return $this->functions[array_search($functionType, $this->functionLabels)];
  }

  public static function getAvailableParams()
  {
    return Self::$avaialbleParams;
  }

  public static function getFunctionStats()
  {
    return Self::$functionStats;
  }

}
