<?php

#LEGACY CODE -- GABE MANSUR
#UPDATED BY JANANI SEKAR AND BRIAN LEONARD
#CONTAINS CONTENT FOR BIG FIVE TASK

namespace Teamwork\Tasks;

class BigFive {



  private static $avaialbleParams = ['hasIndividuals' => ['true'],
                                     'hasGroup' => ['false'],
                                     'statementOrder' => ['random', 'ordered']];

  private $statements = [
              ['number' => 24, 'statement' => 'Am the life of the party.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 50, 'statement' => 'Feel little concern for others.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 25, 'statement' => 'Am always prepared.', 'factor' =>	2, 'scoring' => 'negative'],
              ['number' => 15, 'statement' => 'Get stressed out easily.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 43, 'statement' => 'Have a rich vocabulary.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 19, 'statement' => 'Don\'t talk a lot.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 9, 'statement' => 'Am interested in people.', 'factor' =>	1, 'scoring' => 'negative'],
              ['number' => 30, 'statement' => 'Leave my belongings around.', 'factor' =>	2, 'scoring' => 'negative'],
              ['number' => 17, 'statement' => 'Am relaxed most of the time.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 8, 'statement' => 'Have difficulty understanding abstract ideas.', 'factor' =>	1, 'scoring' => 'negative'],
              ['number' => 48, 'statement' => 'Feel comfortable around people.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 23, 'statement' => 'Insult people.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 34, 'statement' => 'Pay attention to details', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 16, 'statement' => 'Worry about things.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 38, 'statement' => 'Have a vivid imaginiation.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 1, 'statement' => 'Keep in the background.', 'factor' =>	1, 'scoring' => 'normal'],
              ['number' => 20, 'statement' => 'Sympathize with other\'s feelings.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 42, 'statement' => 'Make a mess of things.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 2, 'statement' => 'Seldom feel blue.', 'factor' =>	1, 'scoring' => 'normal'],
              ['number' => 46, 'statement' => 'Am not intersted in abstract ideas.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 47, 'statement' => 'Start conversations.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 32, 'statement' => 'Am not interested in other people\'s problems.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 12, 'statement' => 'Get chores done right away.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 3, 'statement' => 'Am easily disturbed.', 'factor' =>	1, 'scoring' => 'normal'],
              ['number' => 41, 'statement' => 'Have excellent ideas.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 33, 'statement' => 'Have little to say.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 27, 'statement' => 'Have a soft heart.', 'factor' =>	2, 'scoring' => 'negative'],
              ['number' => 36, 'statement' => 'Often forget to put things in their proper place.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 7, 'statement' => 'Get upset easily.', 'factor' =>	1, 'scoring' => 'negative'],
              ['number' => 37, 'statement' => 'Do not have a good imagination.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 49, 'statement' => 'Talk to a lot of different people at parties.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 14, 'statement' => 'Am not really interested in others.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 31, 'statement' => 'Like order.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 4, 'statement' => 'Change my mood a lot.', 'factor' =>	1, 'scoring' => 'normal'],
              ['number' => 26, 'statement' => 'Am quick to understand things.', 'factor' =>	2, 'scoring' => 'negative'],
              ['number' => 18, 'statement' => 'Don\'t like to draw attention to myself.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 29, 'statement' => 'Take time out for others.', 'factor' =>	2, 'scoring' => 'negative'],
              ['number' => 10, 'statement' => 'Shirk my duties.', 'factor' =>	1, 'scoring' => 'negative'],
              ['number' => 21, 'statement' => 'Have frequent mood swings.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 40, 'statement' => 'Use difficult words.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 11, 'statement' => 'Don\'t mind being the center of attention.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 6, 'statement' => 'Feel other\'s emotions.', 'factor' =>	1, 'scoring' => 'negative'],
              ['number' => 45, 'statement' => 'Follow a schedule.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 44, 'statement' => 'Get irritated easily.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 28, 'statement' => 'Spend time reflecting on things.', 'factor' =>	2, 'scoring' => 'negative'],
              ['number' => 39, 'statement' => 'Am quiet around strangers.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 5, 'statement' => 'Make people feel at ease.', 'factor' =>	1, 'scoring' => 'normal'],
              ['number' => 13, 'statement' => 'Am exacting in my work.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 22, 'statement' => 'Often feel blue.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 35, 'statement' => 'Am full of ideas.', 'factor' =>	3, 'scoring' => 'normal']

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
