<?php

#LEGACY CODE -- GABE MANSUR
#UPDATED BY JANANI SEKAR AND BRIAN LEONARD
#STIMULI FOR PSI/IRI TASK

namespace Teamwork\Tasks;

class PsiIri {



  private static $avaialbleParams = ['hasIndividuals' => ['true'],
                                     'hasGroup' => ['false'],
                                     'statementOrder' => ['random', 'ordered']];

  private $statements = [
              ['number' => 1, 'statement' => 'I am able to make most people feel comfortable and at ease around me.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 2, 'statement' => 'I am able to communicate easily and effectively with others.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 3, 'statement' => 'It is easy for me to develop good rapport with most people.', 'factor' =>	2, 'scoring' => 'negative'],
              ['number' => 4, 'statement' => 'I understand people very well.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 5, 'statement' => 'I am particularly good at sensing the motivations and hidden agendas of others.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 6, 'statement' => 'When communicating with others, I try to be genuine in what I say and do.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 7, 'statement' => 'I am good at getting people to like me.', 'factor' =>	1, 'scoring' => 'negative'],
              ['number' => 8, 'statement' => 'It is important that people believe I am sincere in what I say and do.', 'factor' =>	2, 'scoring' => 'negative'],
              ['number' => 9, 'statement' => 'I try to show a genuine interest in other people.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 10, 'statement' => 'I have good intuition or savvy about how to present myself to others.', 'factor' =>	1, 'scoring' => 'negative'],
              ['number' => 11, 'statement' => 'I always seem to instinctively know the right things to say or do to influence others.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 12, 'statement' => 'I pay close attention to people’s facial expressions.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 13, 'statement' => 'I often have tender, concerned feelings for people less fortunate than me.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 14, 'statement' => 'In emergency situations, I feel apprehensive and ill-at-ease.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 15, 'statement' => 'I try to look at everybody’s side of a disagreement before I make a decision.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 16, 'statement' => 'When I see someone being taken advantage of, I feel kind of protective toward them.', 'factor' =>	1, 'scoring' => 'normal'],
              ['number' => 17, 'statement' => 'I sometimes try to understand my friends better by imagining how things look from their perspective.', 'factor' =>	2, 'scoring' => 'normal'],
              ['number' => 18, 'statement' => 'Being in a tense emotional situation scares me.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 19, 'statement' => 'When I see someone being treated unfairly, I feel much pity for them.', 'factor' =>	1, 'scoring' => 'normal'],
              ['number' => 20, 'statement' => 'I would describe myself as a pretty soft-hearted person.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 21, 'statement' => 'I tend to lose control during emergencies.', 'factor' =>	3, 'scoring' => 'negative'],
              ['number' => 22, 'statement' => 'When I’m upset at someone, I usually try to “put myself in his shoes” for a while.', 'factor' =>	3, 'scoring' => 'normal'],
              ['number' => 23, 'statement' => 'When I see someone who badly needs help in an emergency, I go to pieces.', 'factor' => 3, 'scoring' => 'normal'],
              ['number' => 24, 'statement' => 'Before criticizing somebody, I try to imagine how I would feel if I were in their place.', 'factor' => 3, 'scoring' => 'normal']

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
