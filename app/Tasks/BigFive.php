<?php
namespace Teamwork\Tasks;

class BigFive {



  private static $avaialbleParams = ['hasIndividuals' => ['true'],
                                     'hasGroup' => ['false'],
                                     'statementOrder' => ['random', 'ordered']];

  private $statements = [
              ['number' => 24, 'statement' => 'Love to help others.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 50, 'statement' => 'Leave a mess in my room.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 25, 'statement' => 'Insult people.', 'factor' =>	2, 'scoring' => 'negative'],
              ['number' => 15, 'statement' => 'Feel others\' emotions.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 43, 'statement' => 'Make a mess of things.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 19, 'statement' => 'Love children.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 9, 'statement' => 'Don\'t like to draw attention to myself.', 'factor' =>	1, 'scoring' => 'negative'],
              ['number' => 30, 'statement' => 'Am indifferent to the feelings of others.', 'factor' =>	2, 'scoring' => 'negative'],
              ['number' => 17, 'statement' => 'Inquire about others\' well-being.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 8, 'statement' => 'Have little to say.', 'factor' =>	1, 'scoring' => 'negative'],
              ['number' => 48, 'statement' => 'Do things in a half-way manner.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 23, 'statement' => 'Think of others first.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 34, 'statement' => 'Like order.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 16, 'statement' => 'Make people feel at ease.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 38, 'statement' => 'Continue until everything is perfect.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 1, 'statement' => 'Am the life of the party.', 'factor' =>	1, 'scoring' => 'normal'],
              ['number' => 20, 'statement' => 'Am on good terms with nearly everyone.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 42, 'statement' => 'Leave my belongings around.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 2, 'statement' => 'Feel comfortable around people.', 'factor' =>	1, 'scoring' => 'normal'],
              ['number' => 46, 'statement' => 'Neglect my duties.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 47, 'statement' => 'Waste my time.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 32, 'statement' => 'Pay attention to details.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 12, 'statement' => 'Sympathize with others\' feelings.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 3, 'statement' => 'Start conversations.', 'factor' =>	1, 'scoring' => 'normal'],
              ['number' => 41, 'statement' => 'Like to tidy up.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 33, 'statement' => 'Get chores done right away.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 27, 'statement' => 'Feel little concern for others.', 'factor' =>	2, 'scoring' => 'negative'],
              ['number' => 36, 'statement' => 'Am exacting in my work.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 7, 'statement' => 'Keep in the background.', 'factor' =>	1, 'scoring' => 'negative'],
              ['number' => 37, 'statement' => 'Do things according to a plan.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 49, 'statement' => 'Find it difficult to get down to work.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 14, 'statement' => 'Take time out for others.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 31, 'statement' => 'Am always prepared.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 4, 'statement' => 'Talk to a lot of different people at parties.', 'factor' =>	1, 'scoring' => 'normal'],
              ['number' => 26, 'statement' => 'Am not interested in other people\'s problems.', 'factor' =>	2, 'scoring' => 'negative'],
              ['number' => 18, 'statement' => 'Know how to comfort others.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 29, 'statement' => 'Am hard to get to know.', 'factor' =>	2, 'scoring' => 'negative'],
              ['number' => 10, 'statement' => 'Am quiet around strangers.', 'factor' =>	1, 'scoring' => 'negative'],
              ['number' => 21, 'statement' => 'Have a good word for everyone.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 40, 'statement' => 'Love order and regularity.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 11, 'statement' => 'Am interested in people.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 6, 'statement' => 'Don\'t talk a lot.', 'factor' =>	1, 'scoring' => 'negative'],
              ['number' => 45, 'statement' => 'Shirk my duties.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 44, 'statement' => 'Often forget to put things back in their proper place.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 28, 'statement' => 'Am not really interested in others.', 'factor' =>	2, 'scoring' => 'negative'],
              ['number' => 39, 'statement' => 'Make plans and stick to them.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 5, 'statement' => 'Don\'t mind being the center of attention.', 'factor' =>	1, 'scoring' => 'normal'],
              ['number' => 13, 'statement' => 'Have a soft heart.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 22, 'statement' => 'Show my gratitude.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 35, 'statement' => 'Follow a schedule.', 'factor' =>	3, 'scoring' => 'normal']

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
