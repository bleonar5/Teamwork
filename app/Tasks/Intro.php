<?php

#LEGACY CODE -- GABE MANSUR
#UPDATED BY JANANI SEKAR AND BRIAN LEONARD
#CONTAINS TEXT CONTENT AND SOME FORMATTING/FUNCTIONALITY FOR TASK INTROS
namespace Teamwork\Tasks;

class Intro {

  private $intro = [
    'mturk' => [[
                  'type' => 'header',
                  'content' => 'Individual Tasks'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'Over the next <span style = "background-color: yellow;"><strong>60-70 minutes</strong></span> you will complete a
                  range of different tasks. Our goal is to understand how well
                  you solve problems, your ability to perceive emotions in
                  others, and your short-term memory. This is a research study
                  and your answers are important.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'Most tasks begin with a practice. We’ve
                  included these practice questions to explain how our
                  tasks work. The practice questions do NOT count towards
                  your score. But, it is important to try to get these
                  simple questions correct, as we use them to make sure
                  that you’ve read and understood the instructions.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'There are 7 tasks to complete. Some tasks take
                  slightly longer than others, but not more than 15 minutes.
                  Feel free to take a break between tasks.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => '<div class="text-center">
                          <a href="/end-individual-task" role="button" class="btn btn-lg btn-primary">Next</a>
                        <div>'
                ]
      ],

    'chat_notification' => [[
                  'type' => 'header',
                  'content' => 'Having any trouble?'
                ],
                [
                  'type' => 'paragraph',
                  'content' => '<script type="text/javascript">
                                  setInterval(function(){
                                      Tawk_API.toggleVisibility();

                                    },500);
                                </script>
                                Talk to the research team by clicking the <b id="green_button_text" style="color:green">GREEN BUTTON</b>'
                ],
                [
                  'type' => 'paragraph',
                  'content' => '<div class="text-center">
                          <a href="/end-individual-task" role="button" class="btn btn-lg btn-primary">Next</a>
                        <div>'
                ]
      ],

    "part1_instructions" => [[
                  'type' => 'header',
                  'content' => 'Codebreakers Part 1'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'This session is divided into two parts.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'First, you will solve a cryptography puzzle by yourself. This will take around <b>20 minutes.</b>'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'Second, you will be send to a <b>waiting room.</b> From there, you will be matched into a small group, and you will solve similar problems <b>with other participants.</b> You will work in <b>TWO</b> separate groups before the end of today\'s session.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => '<div class="text-center">
                          <a href="/end-individual-task" role="button" class="btn btn-lg btn-primary">Next</a>
                        <div>'
                ]
      ],


    'hdsl_individual' => [[
                  'type' => 'header',
                  'content' => 'Welcome to the online component of the Superteams study!'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'Over the next <strong>50 minutes</strong> you will complete a
                  range of different tasks. Our goal is to understand how well
                  you solve problems, your ability to perceive emotions in
                  others, and your short-term memory. This is a research study
                  and your answers are important.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'There are 5 tasks to complete. Some tasks take
                  slightly longer than others, but not more than 15 minutes.
                  Feel free to take a break between tasks.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'If you have to stop, that\'s OK. Your answers will be saved.
                  To continue, come back to this website (<a href="http://teamwork.harvarddecisionlab.org/individual-login/hdsl">http://teamwork.harvarddecisionlab.org/individual-login/hdsl</a>) re-enter the
                  <strong>same</strong> email address, then pick up where you left off.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => '<span class="text-danger">Note: These tasks <strong>must</strong> be done on a laptop or desktop computer. They <strong>cannot</strong>
                  be done on a mobile phone or tablet. If you do not have access to a computer, please feel free to come to the lab and complete the tasks there.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => '<div class="text-center">
                          <a href="/end-individual-task" role="button" class="btn btn-lg btn-primary">Next</a>
                        <div>'
                ]
      ],

/*'eligibility' => [[
                'type' => 'header',
                'content' => 'Eligibility Notice'
              ],
              [
                'type' => 'paragraph',
                'content' => 'Our study requires participants who try their best
                in these Online Tasks. If you skip through questions, or don’t
                read the instructions, your responses cannot be used.'
              ],
              [
                'type' => 'paragraph',
                'content' => '<strong>Our tasks contain checks to see whether or not
                you’re reading the instructions and the questions.</strong>'
              ],
              [
                'type' => 'paragraph',
                'content' => 'Everybody who makes a genuine attempt to answer
                the questions will be eligible for the Superteams study,
                regardless of your score.'
              ],
              [
                'type' => 'paragraph',
                'content' => 'Click "I agree" if you would like to proceed.
                If not, thank you for your time!'
              ],
              [
                'type' => 'paragraph',
                'content' => '
                <a href="/no-study-consent" role="button" class="btn btn-lg btn-warning float-left">I Do Not Agree</a>
                <a href="/end-individual-task" role="button" class="btn btn-lg btn-success float-right">I Agree</a>
                '
              ],
    ],*/

    'eligibility' => [[
                'type' => 'header',
                'content' => 'Welcome to the Harvard Study Of Individual and Group Problem Solving.'
              ],
              [
                'type' => 'paragraph',
                'content' => 'The study has 2 parts.'
              ],
              [
                'type' => 'paragraph',
                'content' => '<strong>Part 1</strong> Individual Survey. You can do this immediately, and it takes 60-70 minutes. The individual survey involves answering questions about yourself and solving some puzzles.'
              ],
              [
                'type' => 'paragraph',
                'content' => '<strong>Part 2.</strong> Group problem solving sessions. These sessions will last about 60 minutes each. You can sign up for these online. We will try to schedule sessions across different days (Mon-Sat) and times.<strong>Please continue ONLY if you are willing to sign up for AT LEAST FOUR (4) GROUP SESSIONS.</strong>'
              ],
              [
                'type' => 'paragraph',
                'content' => '<strong>Payment can only be provided to participants who commit to completing both parts (total of 5 hours)</strong>'
              ],
              [
                'type' => 'paragraph',
                'content' => '
                <a href="/no-study-consent" role="button" class="btn btn-lg btn-warning float-left">I <strong>cannot</strong> commit to both parts of the experiment</a>
                <a href="/end-individual-task" role="button" class="btn btn-lg btn-success float-right">I <strong>can</strong> commit to both parts (individual session + <br />at least 4 group sessions over the period XX to XX)</a>'
              ],
    ],

    'adblock' => [[
                    'type' => 'header',
                    'content' => 'Important Note'
                  ],
                  [
                    'type' => 'paragraph',
                    'content' => 'This study is not compatible with the Adblock chrome extension, or other extensions like it. If you are using an ad blocker, make sure it is always disabled for this website. Otherwise, the study may not function correctly.'
                  ],
                  [
                  'type' => 'paragraph',
                  'content' => '<div class="text-center">
                          <a href="/end-group-task" role="button" class="btn btn-lg btn-primary">Next</a>
                        <div>'
                ]
    ],


    'crypto_pilot_guide' => [[
                  'type' => 'header',
                  'content' => 'Welcome to the Group Session!'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'Shortly, you will be matched with <b>2 other participants.</b> It is important that you can <b>see and hear</b> your teammates.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'Before you click next:'
                ],
                [
                  'type' => 'paragraph',
                  'content' => '<img src="/img/ins_1.png" />'
                ],
                [
                  'type' => 'paragraph',
                  'content' => '<div class="text-center">
                          <a href="/end-group-task" role="button" class="btn btn-lg btn-primary">Next</a>
                        <div>'
                ]
                
      ],
      'selection_page' => [[
                  'type' => 'header',
                  'content' => 'What Role would you choose?'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'You have now completed the <b>individual tasks.</b>'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'Next, you will be working in a group with two other people. There are two roles: <span style="color:blue">"Leader"</span> or <span style="color:green">"Team member"</span>.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => '<u>Your role will affect your bonus</u><br /> Team members get consistent bonuses.<br /> Leaders earn the most, or the least (depending on team performance)<br /> On average, both roles receive the same bonus.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'There is not a single right answer. Pick the role that you think suits you best.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'If it were up to you, WHICH ROLE WOULD YOU PREFER?'
                ],
                [
                  'type' => 'paragraph',
                  'content' => '<b style="color:red">Please note: we CANNOT guarantee that you will be allocated to your preferred option.</b>'
                ],
                [
                  'type' => 'paragraph',
                  'content' => '<div class="text-center">
                          <a href="/pick-leader" role="button" class="btn btn-lg btn-primary">Leader</a>
                          <a href="/pick-member" role="button" class="btn btn-lg btn-success">Team Member</a>
                        <div>'
                ]
                
      ],
      'individual_crypto_end' => [
                [
                  'type' => 'paragraph',
                  'content' => 'Thank you.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'When you click <b>Next</b>, you will move onto the <b>Group Session</b> (where you will solve puzzles in a group). This will involve video and audio.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'Please note that before we match you with a group, you will enter a "WAITING ROOM". You may be there for a few minutes.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => '<b>It is important that you don\'t leave the waiting room, as you will miss the second half of the session.</b>'
                ],
                [
                  'type' => 'paragraph',
                  'content' => '<div class="text-center">
                          <a role="button" href="/end-individual-task" class="btn btn-lg btn-primary">Next</a>
                        <div>'
                ]
                
      ],
      'feedback_page' => [
                [
                  'type' => 'sub-header',
                  'content' => 'If you have any feedback about the tasks, please let us know.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => '<input type="textarea" id="feedback" name="feedback" />'
                ],
                [
                  'type' => 'paragraph',
                  'content' => '<div class="text-center">
                          <button role="button" class="btn btn-lg btn-primary">Next</button>
                        <div>'
                ]
                
      ],

    'crypto_pilot_guide2' => [[
                  'type' => 'header',
                  'content' => 'What to expect next'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'When you click "Next" you will go to a Waiting Room. It is important you don\'t leave the waiting room, as you might miss the start of the session.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'If you are having any difficulties, click the <b style="color:green">GREEN BUTTON</b> to speak with us.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => '<div class="text-center">
                          <a href="/end-individual-task" role="button" class="btn btn-lg btn-primary">Next</a>
                        <div>'
                ]
      ],

    'group_survey_members_1' => [[
                  'type' => 'header',
                  'content' => 'Survey'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'How warm did the leader seem throughout the group interaction? That is, how friendly, helpful and sincere did they seem?<br />
                              <div style="display:grid;grid-auto-flow: column;width:100%;margin:auto">
                                  <label>1</label>
                                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 100%;" name="group_survey_member_1_1" value="1">
                                  <label>2</label>
                                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 100%;" name="group_survey_member_1_1" value="2">
                                  <label>3</label>
                                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 100%;" name="group_survey_member_1_1" value="3">
                                  <label>4</label>
                                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 100%;" name="group_survey_member_1_1" value="4">
                                  <label>5</label>
                                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 100%;" name="group_survey_member_1_1" value="5">
                                  <label>6</label>
                                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 100%;" name="group_survey_member_1_1" value="6">
                                  <label>7</label>
                                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 100%;" name="group_survey_member_1_1" value="7">
                              </div>
                              <div style="display:inline-block;width:100%;margin:auto">
                                  <p style="float:left;margin:auto">Not at all warm</p>
                                  <p style="float:right;margin:auto">Extremely Warm</p>
                              </div>
                              <hr />
                              How competent did the leader seem throughout the group interaction? That is, how knowledgeable, skillful, and efficacious did they seem?<br />
                              <div style="display:grid;grid-auto-flow: column;width:100%;margin:auto">
                                  <label>1</label>
                                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 100%;" name="group_survey_member_1_2" value="1">
                                  <label>2</label>
                                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 100%;" name="group_survey_member_1_2" value="2">
                                  <label>3</label>
                                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 100%;" name="group_survey_member_1_2" value="3">
                                  <label>4</label>
                                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 100%;" name="group_survey_member_1_2" value="4">
                                  <label>5</label>
                                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 100%;" name="group_survey_member_1_2" value="5">
                                  <label>6</label>
                                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 100%;" name="group_survey_member_1_2" value="6">
                                  <label>7</label>
                                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 100%;" name="group_survey_member_1_2" value="7">
                              </div>
                              <div style="display:inline-block;width:100%;margin:auto">
                                  <p style="float:left;margin:auto">Not at all competent</p>
                                  <p style="float:right;margin:auto">Extremely competent</p>
                              </div>'
                ],
                [
                  'type' => 'paragraph',
                  'content' => '<div class="text-center">
                          <a href="/end-group-task" role="button" class="btn btn-lg btn-primary">Next</a>
                        <div>'
                ]
      ],

    'group_1' => [[
                   'type' => 'header',
                   'content' => 'Welcome to your first group'
                 ],
                 [
                   'type' => 'paragraph',
                   'content' => 'You will be working together for around half an hour,
                   trying to solve 3 tasks.<br>You’ve seen similar
                   (or identical) tasks as individuals:'
                 ],
                 [
                   'type' => 'paragraph',
                   'content' => '<div class="row">
                   <div class="col-md-4 offset-md-4">
                   <ol><li>Optimization</li><li>Memory</li>
                   <li>Shapes</li></ol>
                   </div></div>'
                 ],
                 [
                   'type' => 'paragraph',
                   'content' => 'The main difference is that now you will be
                   answering as a group.'
                 ],
                 [
                   'type' => 'paragraph',
                   'content' => 'Take a moment to introduce yourselves!'
                 ],
                 [
                   'type' => 'paragraph',
                   'content' => '<div class="text-center">
                           <a href="/end-individual-task" role="button" class="btn btn-lg btn-primary">Next</a>
                         <div>'
                 ]

      ],
    'group_2' => [[
                   'type' => 'header',
                   'content' => 'Welcome to your new group'
                 ],
                 [
                   'type' => 'paragraph',
                   'content' => 'You will be working together for around half an hour,
                   trying to solve 3 tasks. The tasks will be similar
                   to those you worked on in your previous groups:'
                 ],
                 [
                   'type' => 'paragraph',
                   'content' => '<div class="row">
                   <div class="col-md-4 offset-md-4">
                   <ol><li>Optimization</li><li>Memory</li>
                   <li>Shapes</li></ol>
                   </div></div>'
                 ],
                 [
                   'type' => 'paragraph',
                   'content' => 'Take a moment to introduce yourselves!'
                 ],
                 [
                   'type' => 'paragraph',
                   'content' => 'The instructions will continue when all
                   three group members have hit "Next"'
                 ],
                 [
                   'type' => 'paragraph',
                   'content' => '<div class="text-center">
                           <a href="/end-individual-task" role="button" class="btn btn-lg btn-primary">Next</a>
                         <div>'
                 ]

      ],

      'group_5_break' => [
                   [
                     'type' => 'paragraph',
                     'content' => 'Feel free to take a break and stretch your legs.'
                   ],
                   [
                     'type' => 'paragraph',
                     'content' => '	There is one final puzzle to solve. You will be working <strong>in the same group</strong>, and staying in the room you’re in at the moment.'
                   ],
                   [
                     'type' => 'paragraph',
                     'content' => 'When you are ready, click "Next"'
                   ],
                   [
                     'type' => 'paragraph',
                     'content' => '<div class="text-center">
                             <a href="/end-individual-task" role="button" class="btn btn-lg btn-primary">Next</a>
                           <div>'
                   ]
        ],

    'group_5' => [[
                   'type' => 'header',
                   'content' => 'Welcome to your last task for today!'
                 ],
                  [
                   'type' => 'paragraph',
                   'content' => 'You will be working together for about 25 minutes trying to
                   solve a task you haven\'t seen before.'
                 ],
                 [
                   'type' => 'paragraph',
                   'content' => 'Click "Next" to continue.'
                 ],
                 [
                   'type' => 'paragraph',
                   'content' => '<div class="text-center">
                           <a href="/end-individual-task" role="button" class="btn btn-lg btn-primary">Next</a>
                         <div>'
                 ]
      ]
  ];

  private static $avaialbleParams = ['hasIndividuals' => ['true', 'false'], 'hasGroup' => ['false'], 'type' => ['mturk', 'hdsl_individual', 'group_1', 'group_2', 'group_5']];

  public function getIntro($type) {
    return $this->intro[$type];
  }

  public static function getAvailableParams()
  {
    return Self::$avaialbleParams;
  }

}
