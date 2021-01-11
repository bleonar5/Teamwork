<?php
namespace Teamwork\Tasks;

class Leadership {



  private static $avaialbleParams = ['hasIndividuals' => ['true'],
                                     'hasGroup' => ['false'],
                                     'statementOrder' => ['random', 'ordered']];

  private $statements = [
              ['number' => 1, 'statement' => 'I make others feel good to be around me.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 2, 'statement' => 'I express with a few simple words what we could and should do.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 3, 'statement' => 'I enable others to think about old problems in new ways.', 'factor' =>	2, 'scoring' => 'negative'],
              ['number' => 4, 'statement' => 'I help others develop themselves.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 5, 'statement' => 'I tell others what to do if they want to be rewarded for their work.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 6, 'statement' => 'I am satisfied when others meet agreed‐upon standards.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 7, 'statement' => 'I am content to let others continue working in the same ways always.', 'factor' =>	1, 'scoring' => 'negative'],
              ['number' => 8, 'statement' => 'Others have complete faith in me.', 'factor' =>	2, 'scoring' => 'negative'],
              ['number' => 9, 'statement' => 'I provide appealing images about what we can do.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 10, 'statement' => 'I provide others with new ways of looking at puzzling things.', 'factor' =>	1, 'scoring' => 'negative'],
              ['number' => 11, 'statement' => 'I let others know how I think they are doing.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 12, 'statement' => 'I provide recognition/rewards when others reach their goals.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 13, 'statement' => 'As long as things are working, I do not try to change anything.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 14, 'statement' => 'Whatever others want to do is OK with me.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 15, 'statement' => ' Others are proud to be associated with me.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 16, 'statement' => 'I help others find meaning in their work.', 'factor' =>	1, 'scoring' => 'normal'],
              ['number' => 17, 'statement' => 'I get others to rethink ideas that they had never questioned before.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 18, 'statement' => 'I give personal attention to others who seem rejected.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 19, 'statement' => 'I call attention to what others can get for what they accomplish.', 'factor' =>	1, 'scoring' => 'normal'],
              ['number' => 20, 'statement' => 'I tell others the standards they have to know to carry out their work.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 21, 'statement' => 'I ask no more of others than what is absolutely essential.', 'factor' =>	3, 'scoring' => 'negative']

  ];


  public function getStatements($order) {
    if($order == 'random') shuffle($this->statements);
    return $this->statements;
  }

  public static function getAvailableParams()
  {
    return Self::$avaialbleParams;
  }
}
