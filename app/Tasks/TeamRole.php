<?php
namespace Teamwork\Tasks;

class TeamRole {


  private static $avaialbleParams = ['hasIndividuals' => ['true'],
                                     'hasGroup' => ['false'],
                                     'scenarios' => ['allows_multiples' => ['all', '1', '2', '3', '4']]];


  private $scenarios = [
    ['desc' => ['You are the most experienced member of a newly formed '
                .'production team with several members who are new to this '
                .'type of manufacturing.',
                'The manufacturing process is complex, requiring compliance '
                .'with precise standards, to avoid large amounts of product '
                .'waste and possible equipment damage.',
                'Your supervisor has just informed your team that the sales '
                .'department had made a "rush order", committing to ship a '
                .'large batch of product five days before the anticipated ship date.'],
      'prompt' => 'Please rate the effectiveness of each of the following responses:',
      'key' => ['Very Effective', 'Somewhat Effective', 'Neutral', 'Somewhat Ineffective', 'Very Ineffective'],
      'responses' => [
                      ['response' => 'Immediately touch base with the other team members to '
                      .'find out who is the fastest at each of the manufacturing '
                      .'stations, and allocate tasks among you accordingly',
                      'scoring' => 'normal'],
                      ['response' => 'Avoid being overly assertive in the new '
                        .'team and let others determine the team’s direction, '
                        .'because it is important that the younger members take '
                        .'the lead.',
                      'scoring' => 'reverse'],
                      ['response' => 'Quickly decide to continue with your '
                        .'planned production schedule because it probably won\'t '
                        .'be possible to meet the rush order deadline.',
                      'scoring' => 'reverse'],
                      ['response' => 'Quickly meet with your team members to '
                        .'decide the priority that should be given to the '
                        .'"rush order".',
                      'scoring' => 'normal'],
                      ['response' => 'Meet with each of the team members, '
                        .'encouraging them and clarifying what each will '
                        .'have to do in order to reach the deadline.',
                      'scoring' => 'normal'],
                      ['response' => 'Gather the team together and map out a '
                        .'realistic timeline of what must be accomplished in '
                        .'order for the rush shipment to be completed.',
                      'scoring' => 'normal'],
                      ['response' => 'Help the team stay calm by letting them '
                        .'know that they shouldn’t be too stressed about meeting '
                        .'the deadline because Sales knew that it was an '
                        .'unrealistic deadline when they made the commitment.',
                      'scoring' => 'reverse'],
                      ['response' => 'Try not to react too strongly to the news '
                        .'to help the new team members understand that this kind '
                        .'of rush order occurs far too often.',
                      'scoring' => 'reverse'],
                      ['response' => 'Let the team know that although you have '
                        .'not produced an order so quickly in the past, you are '
                        .'confident that by staying focused your team can ship '
                        .'the rush order on time.',
                      'scoring' => 'normal'],
                      ['response' => 'Suggest that the deadline is unreasonable, '
                        .'and you will simply have to do your best without '
                        .'worrying about meeting the unrealistic shipment date '
                        .'to which the Sales department committed themselves.',
                      'scoring' => 'reverse'],
                    ]
        ], // end scenario 1

        ['desc' => ['You are a member of a long-standing manufacturing team.',
                    'Your team has been together for several years and has become pretty good at handling the work at hand.',
                    'Each team member is comfortable with the work process and the other team members.',
                    'Your work team will be moving to a new building that has been added onto the plant.',
                    'The area manager has asked your team for some new ideas on how the equipment should be laid out in the new building.'],
          'prompt' => 'Please rate the effectiveness of each of the following responses:',
          'key' => ['Very Effective', 'Somewhat Effective', 'Neutral', 'Somewhat Ineffective', 'Very Ineffective'],
          'responses' => [
                          ['response' => 'Highlight the fact that you have been doing the job for 15 years and the current layout is the best one.',
                          'scoring' => 'reverse'],
                          ['response' => 'Propose that a good strategy may be to think outside of the box and get new layout ideas from high performance manufacturers in other industries.',
                          'scoring' => 'normal'],
                          ['response' => 'Keep the team on track by pointing out that it is very inefficient to waste time discussing solutions that are probably not practically feasible.',
                          'scoring' => 'reverse'],
                          ['response' => 'Suggest that the team keep the ideas similar to the current layout to simplify the transition to the new building.',
                          'scoring' => 'reverse'],
                          ['response' => 'Suggest that to find the optimal layout, the team should first generate innovative ideas and save the evaluation of the ideas for later in the process.',
                          'scoring' => 'normal'],
                          ['response' => 'Make sure the team doesn’t waste time discussing drastic changes to the current layout because management is not likely to endorse such a proposal anyway.',
                          'scoring' => 'reverse'],
                          ['response' => 'Make sure production levels don’t drop due to letting your team’s most creative member make all suggestions.',
                          'scoring' => 'reverse'],
                          ['response' => 'Remind the team that they have been working in the current environment for many years, and should consider all layout options even if they seem unfamiliar.',
                          'scoring' => 'normal'],
                          ['response' => 'Recommend that each team member take time this week to create three layout designs and bring them to the next team meeting.',
                          'scoring' => 'normal'],
                          ['response' => 'Suggest that a good way to generate innovative layouts would be to hold the next meeting in the new building.',
                          'scoring' => 'normal'],
                        ]
            ], // end scenario 2

            ['desc' => ['You are a member of an equipment maintenance team responsible for 12 high tech manufacturing machines.',
                        'Your five-person team has experts in many areas, including mechanical, electrical, & hydraulic systems.',
                        'You are the only member of your team with experience in the structural mechanics of the machines.',
                        'One of the machines was struck by a forklift while being prepared for an important production run.',
                        'The company owner quickly pulls your team together and asks if it is ok to run the machine. You are the last one to arrive at the meeting.',
                        'Apparently, the team feels that there was not any visible damage to the machine and it can be used safely.  They tell you that they have looked it over and didn’t see any damage.'],
              'prompt' => 'Please rate the effectiveness of each of the following responses:',
              'key' => ['Very Effective', 'Somewhat Effective', 'Neutral', 'Somewhat Ineffective', 'Very Ineffective'],
              'responses' => [
                              ['response' => 'Make sure the team knows of potential risks and that it is critical that you are able to check for damage to the machine before any recommendation is made.',
                              'scoring' => 'normal'],
                              ['response' => 'Support your team members in their decision, you’ve worked together for a long time, and trust their judgment.',
                              'scoring' => 'reverse'],
                              ['response' => 'Make sure the machine gets the job done on schedule by accepting the judgment of your experienced teammates.',
                              'scoring' => 'reverse'],
                              ['response' => 'Acknowledge that it’s likely that the electrical systems have not been damaged, but insist that you be given a chance to inspect the machine’s structural soundness yourself.',
                              'scoring' => 'normal'],
                              ['response' => 'Tell the team about your experiences with similar accidents in the past and ask that you be able to inspect the machine personally before the recommendation is made.',
                              'scoring' => 'normal'],
                              ['response' => 'Recommend that they go ahead and run the machine to support your team and then check its structural soundness after the important production run is finished.',
                              'scoring' => 'reverse'],
                              ['response' => 'Relate to the team some additional areas of potential damage to the machine after inquiring into the inspections they have made.',
                              'scoring' => 'normal'],
                              ['response' => 'Although you are more experienced in the structural mechanics, go with the judgment made by your teammates because two of them have been with the company much longer than you have.',
                              'scoring' => 'reverse'],
                              ['response' => 'Thank the team for checking the machine over, but relate to the team your experience with "unseen damage" occurring even when it is not evident from an outside glance.',
                              'scoring' => 'normal'],
                              ['response' => 'Don\'t waste time unnecessarily by asking the team to repeat everything they have checked, just let them know you will go with their judgment as long as they accept responsibility for the decision.',
                              'scoring' => 'reverse'],
                            ]
                ], // end scenario 3

                ['desc' => ['You are a member of a manufacturing production team.',
                            'Your team is responsible for the production of three product lines.',
                            'Last month management asked for a recommendation from your team as to whether or not your team could assume responsibility for another product line (making a total of four).',
                            'Your personal view is that taking on the new product line would increase the visibility and importance of the team within the organization, which would help raise salaries and promotional opportunities.',
                            'The other four members of your team feel that, in addition to the increased time and personnel demands placed on the team, current technology may not be capable of producing the new product efficiently.',
                            'For the past several weeks your team has been carefully investigating the possibility of taking on another product. Your team has thoroughly discussed all the issues and would like to propose to management that the product line be manufactured at another facility.'],
                  'prompt' => 'Please rate the effectiveness of each of the following responses:',
                  'key' => ['Very Effective', 'Somewhat Effective', 'Neutral', 'Somewhat Ineffective', 'Very Ineffective'],
                  'responses' => [
                                  ['response' => 'Acknowledge that your arguments have been heard and discussed by the team, and support the team’s decision.',
                                  'scoring' => 'normal'],
                                  ['response' => 'Ask the team to reconsider one more time your perspective on the long-term political impact of refusing the new product line because you feel it is an important issue.',
                                  'scoring' => 'reverse'],
                                  ['response' => 'Let the team know that although you believe in team unity, you can’t bring yourself to refuse the new product line.',
                                  'scoring' => 'reverse'],
                                  ['response' => 'Show your willingness to be a team player by voicing your support of the team’s decision. ',
                                  'scoring' => 'normal'],
                                  ['response' => 'Recognize that the team has reviewed the issues, and begin discussing ways to make the recommendation to management.',
                                  'scoring' => 'normal'],
                                  ['response' => 'Suggest that since the team can’t reach consensus they should allow upper management to make the final decision.',
                                  'scoring' => 'reverse'],
                                  ['response' => 'Even though you would still prefer to take on the new product line, go along with the team’s decision, because they have let you have your say in the matter.',
                                  'scoring' => 'normal'],
                                  ['response' => 'Recognize that the team has some good arguments, but remain committed to making the team see that the benefits clearly outweigh the costs.',
                                  'scoring' => 'reverse'],
                                  ['response' => 'Sustain the team’s decision on the recommendation, as long as they acknowledge to upper management that the team would revisit its decision if more resources are provided.',
                                  'scoring' => 'normal'],
                                  ['response' => 'Be assertive and let the team know you still feel strongly that the team should take on the new product line.',
                                  'scoring' => 'reverse'],
                                ]
                    ], // end scenario 4
  ];


  public function getScenarios($chosenScenarios) {
    $scenarios = [];
    if ($chosenScenarios == 'all')
      return $this->scenarios;
    foreach ($chosenScenarios as $s) {
      $scenarios[] = $this->scenarios[$s - 1];
    }
    return $scenarios;
  }

  public function setScenario($key) {
    return $this->scenarios[$key];
  }


  public static function getAvailableParams()
  {
    return Self::$avaialbleParams;
  }
}
