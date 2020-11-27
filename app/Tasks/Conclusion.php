<?php
namespace Teamwork\Tasks;
use Teamwork\ConfirmationCode;

class Conclusion {

  private $conclusions = [
    'mturk' => [[
                  'type' => 'sub-header',
                  'content' => 'You\'ve completed all the tasks!'
                ],
                [
                  'type' => 'sub-header',
                  'content' => 'Thank you for participating!'
                ],
      ],
    'hdsl_individual' => [[
                  'type' => 'sub-header',
                  'content' => ''
                ]
      ],

    'group_1' => [[
                  'type' => 'sub-header',
                  'content' => 'Your group has completed all of its tasks!'
                ],
                [
                  'type' => 'sub-header',
                  'content' => 'Please return to the waiting room.'
                ],
      ],

    'group_2' => [[
                  'type' => 'sub-header',
                  'content' => 'Your group has completed all of its tasks!'
                ],
                [
                  'type' => 'sub-header',
                  'content' => 'Please return to the waiting room.'
                ],
      ],

    'group_3' => [[
                    'type' => 'sub-header',
                    'content' => 'Your group has completed all of its tasks!'
                  ],
                  [
                    'type' => 'sub-header',
                    'content' => 'Please return to the waiting room.'
                  ],
        ],

    'group_4' => [[
                    'type' => 'sub-header',
                    'content' => 'Your group has completed all of its tasks!'
                  ],
                  [
                    'type' => 'sub-header',
                    'content' => 'Thank you for participating!'
                  ],
        ],

    'group_5' => [[
                  'type' => 'sub-header',
                  'content' => 'Your group has completed all of its tasks!'
                ],
                [
                  'type' => 'sub-header',
                  'content' => 'Please let a member of the Lab staff know that your group is finished.'
                ],
                [
                  'type' => 'sub-header',
                  'content' => 'They will let you know what to do next.'
                ]]
  ];

  private $feedbackLinks = [
    'pilot' => 'https://docs.google.com/forms/d/1ZZV8P_dyifFn4DRhalN82126POz3R-SgCWXvTo7DMaU/viewform?ts=5d35ccb7&edit_requested=true',
    'group5Pilot' => 'https://docs.google.com/forms/d/1KNfcSCGmdNy4vqKtx2R8paFvSeklTfQyvtWBPaINRwk/viewform?usp=sharing_eip&ts=5d405d8c&edit_requested=true',
    'groupTwo' => 'https://urldefense.proofpoint.com/v2/url?u=https-3A__docs.google.com_forms_d_e_1FAIpQLSfL8x7CTs9I6vPreeXAdw5-2DURJMj5G17SqKZuMyCNC-5F1EQw1w_viewform-3Fusp-3Dsf-5Flink&d=DwMFaQ&c=WO-RGvefibhHBZq3fL85hQ&r=tAX8kL7P9f6gcapc67VgDbxuAn6xB0Zl7vfMQBORGpQ&m=23Mvsql-PxIiPW6hhQRvCrbd4sGrl4FDLjm7r5XrFVM&s=Xa0buQFFydQM--IKsMwZf0uuKzfcX4YiwTD54YkoiHE&e='
  ];

  private static $avaialbleParams = ['hasIndividuals' => ['true', 'false'], 'hasGroup' => ['false'], 'type' => ['mturk', 'hdsl_individual'], 'hasCode' => ['true', 'false'], 'feedback' => ['true', 'false']];

  public function getConclusion($type) {
    return $this->conclusions[$type];
  }

  public function newConfirmationCode($type) {
    return ConfirmationCode::where('user_id', null)
                            ->where('type', $type)
                            ->first();
  }

  public function getConfirmationCode($user_id) {
    return ConfirmationCode::where('user_id', $user_id)
                            ->first();
  }

  public function getFeedbackLink($type) {
    return $this->feedbackLinks[$type];
  }

  public static function getAvailableParams()
  {
    return Self::$avaialbleParams;
  }

}
