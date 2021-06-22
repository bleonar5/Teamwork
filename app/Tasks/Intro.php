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
                  'content' => 'What to expect in this session (1/2)'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'Welcome! You are about to join a video study with <strong>2 other team members.</strong>'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'Once your group session begins, you should be able to <strong>see</strong> and <strong>hear</strong> 2 other people.'
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
      'selection_page' => [
                [
                  'type' => 'paragraph',
                  'content' => 'Thank you for participating in this pilot study!'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'We intend to run a follow-up experiment.<br />This would pay a total of around <b>100USD</b>, working in groups of 3 people.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'You would have a choice about your role.<br />You can be a <b>leader</b> or a <b>team member</b>.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'Highly successful Leaders earn the most of anyone.<br />Unsuccessful Leaders earn the least.<br />Team members get the higher guaranteed payment.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'On average, both roles receive the same payment.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'There is not a single right answer for everyone, so <b>pick the role that you think suits you best</b>'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'IF you participated, what role would you choose?'
                ],
                [
                  'type' => 'paragraph',
                  'content' => '<div class="text-center">
                          <a href="/pick-leader" role="button" class="btn btn-lg btn-primary">Leader</a>
                          <a href="/pick-member" role="button" class="btn btn-lg btn-success">Team Member</a>
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
                  'content' => 'What to expect in this session (2/2)'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'You may briefly go to a "waiting room". <strong>Please wait up to 5 minutes</strong> for your team members to join.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => '<strong>Technical Difficulties?</strong>'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'Click the green chat bubble at the top right of your screen to speak with us.'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'This session will last approximately 25 minutes total (including any time spent in the waiting room).'
                ],
                [
                  'type' => 'paragraph',
                  'content' => 'Please click “I agree” to continue.'
                ],
                
                [
                  'type' => 'paragraph',
                  'content' => '<div class="text-center">
                          <a href="/end-group-task" role="button" class="btn btn-lg btn-primary">I agree</a>
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
