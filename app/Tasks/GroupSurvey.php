<?php

#CONTAINS CONTENT FOR GROUP SURVEY TASK

namespace Teamwork\Tasks;

class GroupSurvey {



  private static $avaialbleParams = ['hasIndividuals' => ['true'],
                                     'hasGroup' => ['false'],
                                     'statementOrder' => ['random', 'ordered']];

  private $statements = [
    'leader' =>[
      '1' => [
        ['question' => 'How warm did the leader seem throughout the group interaction? That is, how friendly, helpful and sincere did they seem?
  ', 'left_text' => 'Not at all warm', 'right_text' => 'Extremely warm'],
        ['question' => 'How competent did the leader seem throughout the group interaction? That is, how knowledgeable, skillful, and efficacious did they seem?
  ', 'left_text' => 'Not at all competent', 'right_text' => 'Extremely competent'],
        ['question' => 'How much did you like working with this leader?
  ', 'left_text' => 'Not at all', 'right_text' => 'Extremely'],
        ['question' => 'Would you want to work with this person again?', 'left_text' => 'I would rather not', 'right_text' => 'Yes!']
      ],
      '2' => [
        ['question' => 'How warm did the leader seem throughout the group interaction? That is, how friendly, helpful and sincere did they seem?
  ', 'left_text' => 'Not at all warm', 'right_text' => 'Extremely warm'],
        ['question' => 'How competent did the leader seem throughout the group interaction? That is, how knowledgeable, skillful, and efficacious did they seem?
  ', 'left_text' => 'Not at all competent', 'right_text' => 'Extremely competent'],
        ['question' => 'How much did you like working with this leader?
  ', 'left_text' => 'Not at all', 'right_text' => 'Extremely'],
        ['question' => 'Would you want to work with this person again?', 'left_text' => 'I would rather not', 'right_text' => 'Yes!']
      ]
    ],
    'member' => [
      '1' => [
        ['question' => 'How warm did the leader seem throughout the group interaction? That is, how friendly, helpful and sincere did they seem?
  ', 'left_text' => 'Not at all warm', 'right_text' => 'Extremely warm'],
        ['question' => 'How competent did the leader seem throughout the group interaction? That is, how knowledgeable, skillful, and efficacious did they seem?
  ', 'left_text' => 'Not at all competent', 'right_text' => 'Extremely competent'],
        ['question' => 'How much did you like working with this leader?
  ', 'left_text' => 'Not at all', 'right_text' => 'Extremely'],
        ['question' => 'Would you want to work with this person again?', 'left_text' => 'I would rather not', 'right_text' => 'Yes!']
      ],
      '2' => [
        ['question' => 'When someone made a mistake on this team, it was held against them', 'left_text' => 'Very untrue', 'right_text' => 'Very true'],
        ['question' => 'It was safe to take a risk on this team', 'left_text' => 'Very untrue', 'right_text' => 'Very true'],
        ['question' => 'It was difficult to ask other members of this team for help', 'left_text' => 'Very untrue', 'right_text' => 'Very true'],
        ['question' => 'Members of this team valued and respected each others\' contributions', 'left_text' => 'Very untrue', 'right_text' => 'Very true'],
        ['question' => 'Working with this team gave me a better understanding of how to perform this task', 'left_text' => 'Very untrue', 'right_text' => 'Very true'],
        ['question' => 'In this team, I felt comfortable suggesting or trying a new approach.', 'left_text' => 'Very untrue', 'right_text' => 'Very true']
      ]
    ]
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
