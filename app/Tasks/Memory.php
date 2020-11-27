<?php
namespace Teamwork\Tasks;

class Memory {

  private $memoryTests = [
    'intro' => ['test_name' => 'intro','task_type' => 'intro'],
    'group_1_intro' => ['test_name' => 'group_1_intro','type' => 'intro', 'task_type' => 'intro', 'blocks' => []],
    'results' => ['test_name' => 'results', 'task_type' => 'results'],
    'images_instructions' => [
      'test_name' => 'images_instructions',
      'task_type' => 'images',
      'type' => 'intro',
      'directory' => '/img/memory-task/faces/',
      'blocks' => [
        ['type' => 'text',
         'header' => 'Image Memory',
         'text' => 'In this task, you\'ll be presented with a set of images (for
                    example, 6 faces). There will be 6 images to memorize. We\'ll
                    call these "target images". We\'ll start with a practice.',],
          ['type' => 'practice_review',
          'text' => 'Look at these 6 faces for a few seconds. We\'ll call these
                    "target faces".',
          'targets' => ['p0_targets_1.png'],
          'review_time' => null],
        [ 'type' => 'practice_test',
          'selection_type' => 'select_one',
          'prompt' => 'Which of the following faces is a "target" face?',
          'img' => 'p0_test_1.png',
          'correct' => [1]],
        [ 'type' => 'practice_test',
          'selection_type' => 'select_one',
          'prompt' => 'Which of the following faces is a "target" face?',
          'img' => 'p0_test_2.png',
          'correct' => [3]],
        ['type' => 'text',
          'text' => 'Now for the actual task. When you click "Continue" a screen will appear with 6 target
                     images.
                     You can view the images front-on or in profile.
                     Click the "change perspective" button to see them from a different angle.
                     You will have 20 seconds to memorize
                     these target images.'],
      ]// end blocks
    ], // end images_instructions

    'images_short_intro' => [
      'test_name' => 'images_short_intro',
      'task_type' => 'images',
      'type' => 'intro',
      'directory' => '/img/memory-task/faces/',
      'blocks' => [
          ['type' => 'text',
            'header' => 'Image Memory',
            'text' => 'Now you\'ll perform the task again with a different set
              of target images. When you click "Continue" a screen will appear with 6 target
              images. You will have 20 seconds to memorize
              these target images.'],
        ]
    ],


    'words_instructions' => [
      'test_name' => 'words_instructions',
      'task_type' => 'words',
      'type' => 'intro',
      'directory' => '/img/memory-task/faces/',
      'blocks' => [
        ['type' => 'text',
         'header' => 'Word Memory',
         'text' => 'In this task,
                  you’ll be presented with a set of "target words".  Each word
                  will show up separately for 2 seconds. We’ll start
                  with a practice. In the practice round, you only have
                  to remember 3 target words.  This
                  will not count toward your score, but will indicate whether
                  you have read and understood the instructions, so please
                  answer all questions carefully.',],
        ['type' => 'review',
        'text' => '',
        'targets' => ['blue', 'yellow', 'red'],
        'review_time_each' => 2,
        'review_time' => null],
        [ 'type' => 'practice_test',
          'selection_type' => 'select_all',
          'prompt' => 'Which of the following are target words?',
          'choices' => ['red', 'rust', 'blue'],
          'correct' => [1, 3]],
        [ 'type' => 'practice_test',
          'selection_type' => 'select_all',
          'prompt' => 'Which of the following are target words?',
          'choices' => ['green', 'baseball', 'egg'],
          'correct' => [],
          'popup_text' => 'Sometimes, none of the words will be a target word.<br>In that case, just click "next"!',
          'popup_display_time' => 5],
        ['type' => 'text',
        'text' => 'Now for the actual task. You will be presented with 12 target
                    words. Try to remember all of them. Each word will
                    show up separately for 2 seconds.
                    You’re not allowed to write anything down. The words will
                    begin to appear when you click "Continue".',],
      ]// end blocks
    ], // end words_instructions

    'words_short_intro' => [
      'test_name' => 'words_short_intro',
      'task_type' => 'words',
      'type' => 'intro',
      'directory' => '/img/memory-task/',
      'blocks' => [
          ['type' => 'text',
            'header' => 'Word Memory',
            'text' => 'Now you\'ll perform the task again with a different set
              of target words. When you click "Continue" You will be presented
              with 12 new target words. Each word will
              show up separately for 2 seconds.'],
        ]
    ],

    'story_instructions' => [
      'test_name' => 'story_instructions',
      'task_type' => 'story',
      'type' => 'intro',
      'directory' => '/img/memory-task/',
      'blocks' => [
        ['type' => 'text',
         'header' => 'Story Memory',
         'text' => 'This memory task asks you to remember
                         two very short stories. Once again, we’ll start with a
                         practice. In the practice round each "story" will only have one sentence.'],
        ['type' => 'review',
        'text' => 'Practice:',
        'subtext' => 'Read these two \'stories\' quickly but carefully. When you\'re finished click continue.',
        'targets' => ['Peter was hungry, so he went to the store on the
                        corner of his street and bought a hamburger.',
                      'Yesterday, a local woman found a 10-foot crocodile
                      in her kitchen, an event the fire department
                      described as "unusual".'],
        'review_time' => null],
        [ 'type' => 'practice_test',
          'selection_type' => 'select_one',
          'prompt' => 'Why did Peter go to the store?',
          'choices' => ['Because he was hungry',
                        'To get coffee',
                        'To buy food for his dog'],
          'correct' => [1]],
        [ 'type' => 'practice_test',
          'selection_type' => 'select_one',
          'prompt' => 'Where was a crocodile found yesterday?',
          'choices' => ['In a kitchen',
                        'On a roof in Manhattan',
                        'On the moon'],
          'correct' => [1]],
        ['type' => 'text',
         'text' => 'Now for the actual task. You will be presented with two longer stories.
                    You will have 30 seconds to read them. Try to take in as much information as possible.
                    There is a timer in the top right of the screen.
                    After the 30 seconds are up, we’ll ask you some questions about the stories.
                    Your 30 seconds will start when you hit continue.'],
      ]// end blocks
    ], // end story_instructions

    'story_short_intro' => [
      'test_name' => 'story_short_intro',
      'task_type' => 'story',
      'type' => 'intro',
      'directory' => '/img/memory-task/',
      'blocks' => [
          ['type' => 'text',
            'header' => 'Story Memory',
            'text' => 'Now you\'ll perform the task again with two different
                       stories. When you press "Continue" you will be presented
                       with two new stories. You will have 30 seconds to read them.'],
        ]
    ],

    'faces_1' => [
        'test_name' => 'faces_1',
        'task_type' => 'images',
        'type' => 'task',
        'directory' => '/img/memory-task/faces/',
        'blocks' => [
            ['type' => 'review',
              'text' => '',
              'targets' => ['1_targets_1.png', '1_targets_2.png', '1_targets_3.jpg'],
              'review_time' => 20,],
              [ 'type' => 'test',
                'selection_type' => 'select_one',
                'show_numbers' => 'true',
                'prompt' => 'Which of the following images is a "target" face?',
                'img' => '1_test_1.jpg',
                'correct' => [3]],
              ['type' => 'test',
                'selection_type' => 'select_one',
                'show_numbers' => 'true',
                'prompt' => 'Which of the following images is a "target" face?',
                'img' => '1_test_2.jpg',
                'correct' => [3]],
              ['type' => 'test',
                'selection_type' => 'select_one',
                'show_numbers' => 'true',
                'prompt' => 'Which of the following images is a "target" face?',
                'img' => '1_test_3.jpg',
                'correct' => [1]],
              ['type' => 'test',
                'selection_type' => 'select_one',
                'show_numbers' => 'true',
                'prompt' => 'Which of the following images is a "target" face?',
                'img' => '1_test_4.jpg',
                'correct' => [1]],
              ['type' => 'test',
                'selection_type' => 'select_one',
                'show_numbers' => 'true',
                'prompt' => 'Which of the following images is a "target" face?',
                'img' => '1_test_5.jpg',
                'correct' => [3]],
              ['type' => 'test',
                'selection_type' => 'select_one',
                'show_numbers' => 'true',
                'prompt' => 'Which of the following images is a "target" face?',
                'img' => '1_test_6.jpg',
                'correct' => [2]],
              ['type' => 'test',
                'selection_type' => 'select_one',
                'show_numbers' => 'true',
                'prompt' => 'Which of the following images is a "target" face?',
                'img' => '1_test_7.jpg',
                'correct' => [3]],
              ['type' => 'test',
                'selection_type' => 'select_one',
                'show_numbers' => 'true',
                'prompt' => 'Which of the following images is a "target" face?',
                'img' => '1_test_8.jpg',
                'correct' => [3]],
              ['type' => 'test',
                'selection_type' => 'select_one',
                'show_numbers' => 'true',
                'prompt' => 'Which of the following images is a "target" face?',
                'img' => '1_test_9.jpg',
                'correct' => [1]],

              ['type' => 'review',
                'text' => 'Here’s a reminder of the faces.
                You have 10 seconds to refresh your memory.',
                'targets' => ['1_targets_1.png', '1_targets_2.png', '1_targets_3.jpg'],
                'review_time' => 10,
              ],

              ['type' => 'text',
                'text' => 'For the last six questions, some
                of the images are deliberately blurred to make
                things more challenging.',
              ],

              ['type' => 'test',
                'selection_type' => 'select_one',
                'show_numbers' => 'true',
                'prompt' => 'Which of the following images is a "target" face?',
                'img' => '1_test_10.jpg',
                'correct' => [1]],
              ['type' => 'test',
                'selection_type' => 'select_one',
                'show_numbers' => 'true',
                'prompt' => 'Which of the following images is a "target" face?',
                'img' => '1_test_11.jpg',
                'correct' => [3]],
              ['type' => 'test',
                'selection_type' => 'select_one',
                'show_numbers' => 'true',
                'prompt' => 'Which of the following faces is a "target" face?',
                'img' => '1_test_12.jpg',
                'correct' => [3]],
              ['type' => 'test',
                'selection_type' => 'select_one',
                'show_numbers' => 'true',
                'prompt' => 'Which of the following images is a "target" face?',
                'img' => '1_test_13.jpg',
                'correct' => [2]],
              ['type' => 'test',
                'selection_type' => 'select_one',
                'show_numbers' => 'true',
                'prompt' => 'Which of the following images is a "target" face?',
                'img' => '1_test_14.jpg',
                'correct' => [1]],
              ['type' => 'test',
                'selection_type' => 'select_one',
                'show_numbers' => 'true',
                'prompt' => 'Which of the following images is a "target" face?',
                'img' => '1_test_15.jpg',
                'correct' => [2]],

          ], // End blocks

    ], // End faces_1

    'faces_2' => [
        'test_name' => 'faces_2',
        'task_type' => 'images',
        'type' => 'task',
        'directory' => '/img/memory-task/faces/',
        'blocks' => [

          ['type' => 'review',
            'text' => '',
            'targets' => ['2_targets_1.jpg', '2_targets_2.jpg', '2_targets_3.jpg'],
            'review_time' => 20,],
            [ 'type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'true',
              'prompt' => 'Which of the following images is a "target" face?',
              'img' => '2_test_1.jpg',
              'correct' => [3]],
            ['type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'true',
              'prompt' => 'Which of the following images is a "target" face?',
              'img' => '2_test_2.jpg',
              'correct' => [1]],
            ['type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'true',
              'prompt' => 'Which of the following images is a "target" face?',
              'img' => '2_test_3.jpg',
              'correct' => [2]],
            ['type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'true',
              'prompt' => 'Which of the following images is a "target" face?',
              'img' => '2_test_4.jpg',
              'correct' => [3]],
            ['type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'true',
              'prompt' => 'Which of the following images is a "target" face?',
              'img' => '2_test_5.jpg',
              'correct' => [1]],
            ['type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'true',
              'prompt' => 'Which of the following images is a "target" face?',
              'img' => '2_test_6.jpg',
              'correct' => [3]],
            ['type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'true',
              'prompt' => 'Which of the following images is a "target" face?',
              'img' => '2_test_7.jpg',
              'correct' => [2]],
            ['type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'true',
              'prompt' => 'Which of the following images is a "target" face?',
              'img' => '2_test_8.jpg',
              'correct' => [2]],
            ], // End blocks
    ], // End faces_2

    'cars_1' => [
        'test_name' => 'cars_1',
        'task_type' => 'images',
        'type' => 'task',
        'directory' => '/img/memory-task/cars/',
        'blocks' => [
          ['type' => 'review',
            'text' => '',
            'targets' => ['1_targets_1.jpg', '1_targets_2.jpg', '1_targets_3.jpg'],
            'review_time' => 20,],
            [ 'type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'false',
              'prompt' => 'Which of the following images is a "target" car?',
              'img' => '1_test_1.jpg',
              'correct' => [3]],
            ['type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'false',
              'prompt' => 'Which of the following images is a "target" car?',
              'img' => '1_test_2.jpg',
              'correct' => [1]],
            ['type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'false',
              'prompt' => 'Which of the following images is a "target" car?',
              'img' => '1_test_3.jpg',
              'correct' => [1]],
            ['type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'false',
              'prompt' => 'Which of the following images is a "target" car?',
              'img' => '1_test_4.jpg',
              'correct' => [2]],
            ['type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'false',
              'prompt' => 'Which of the following images is a "target" car?',
              'img' => '1_test_5.jpg',
              'correct' => [3]],
            ['type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'false',
              'prompt' => 'Which of the following images is a "target" car?',
              'img' => '1_test_6.jpg',
              'correct' => [2]],
            ['type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'false',
              'prompt' => 'Which of the following images is a "target" car?',
              'img' => '1_test_7.jpg',
              'correct' => [2]],
            ['type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'false',
              'prompt' => 'Which of the following images is a "target" car?',
              'img' => '1_test_8.jpg',
              'correct' => [3]],
            ], // End blocks
    ], // End cars_1

    'bikes_1' => [
        'test_name' => 'bikes_1',
        'task_type' => 'images',
        'type' => 'task',
        'directory' => '/img/memory-task/bikes/',
        'blocks' => [
          ['type' => 'review',
            'text' => '',
            'targets' => ['1_target_1.jpeg', '1_target_2.jpeg', '1_target_3.jpeg'],
            'review_time' => 20,],
            [ 'type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'true',
              'prompt' => 'Which of the following images is a "target" bicycle?',
              'img' => '1_test_1.jpeg',
              'correct' => [1]],
            ['type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'true',
              'prompt' => 'Which of the following images is a "target" bicycle?',
              'img' => '1_test_2.jpeg',
              'correct' => [2]],
            ['type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'true',
              'prompt' => 'Which of the following images is a "target" bicycle?',
              'img' => '1_test_3.jpeg',
              'correct' => [1]],
            ['type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'true',
              'prompt' => 'Which of the following images is a "target" bicycle?',
              'img' => '1_test_4.jpeg',
              'correct' => [2]],
            ['type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'true',
              'prompt' => 'Which of the following images is a "target" bicycle?',
              'img' => '1_test_5.jpeg',
              'correct' => [3]],
            ['type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'true',
              'prompt' => 'Which of the following images is a "target" bicycle?',
              'img' => '1_test_6.jpeg',
              'correct' => [3]],
            ['type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'true',
              'prompt' => 'Which of the following images is a "target" bicycle?',
              'img' => '1_test_7.jpeg',
              'correct' => [2]],
            ['type' => 'test',
              'selection_type' => 'select_one',
              'show_numbers' => 'true',
              'prompt' => 'Which of the following images is a "target" bicycle?',
              'img' => '1_test_8.jpeg',
              'correct' => [3]],
            ], // End blocks
    ], // End bikes_1

    'words_1' => [
          'test_name' => 'words_1',
          'task_type' => 'words',
          'type' => 'task',
          'blocks' => [
              ['type' => 'review',
              'text' => '',
              'targets' => ['horse', 'cave', 'lion', 'opal', 'tiger',
                          'pearl', 'hut', 'emerald', 'sapphire',
                          'tent', 'hotel', 'cow'],
              'review_time_each' => 2,
              'review_time' => null],
              ['type' => 'test',
               'selection_type' => 'select_all',
               'prompt' => 'Which of the following are target words',
               'choices' => ['house', 'tiger', 'hut'],
               'correct' => [2, 3]],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['hotel', 'coffee', 'mountain'],
                'correct' => [1]],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['cow', 'dog', 'emerald'],
                'correct' => [1, 3]],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['ruby', 'pig', 'balloon'],
                'correct' => []],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['apartment', 'tiger', 'diamond'],
                'correct' => [2]],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['pearl', 'scarf', 'hotel'],
                'correct' => [1, 3]],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['boat', 'penny', 'cat'],
                'correct' => []],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['lion', 'sapphire', 'cave'],
                'correct' => [1, 2, 3]],


          ] // End blocks

    ], // End words_1

    'words_2' => [
          'test_name' => 'words_2',
          'task_type' => 'words',
          'type' => 'task',
          'blocks' => [
              ['type' => 'review',
              'text' => '',
              'targets' => ['pistol', 'fork', 'pot', 'sword', 'vodka',
                          'rum', 'bomb', 'pan', 'wine',
                          'spatula', 'bourbon', 'rifle'],
              'review_time_each' => 2,
              'review_time' => null],
              ['type' => 'test',
               'selection_type' => 'select_all',
               'prompt' => 'Which of the following are target words',
               'choices' => ['fork', 'pan', 'knife'],
               'correct' => [1, 2]],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['spatula', 'beer', 'bomb'],
                'correct' => [1, 3]],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['whiskey', 'lemon', 'gun'],
                'correct' => []],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['gun', 'pistol', 'trout'],
                'correct' => [2]],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['wine', 'fork', 'sword'],
                'correct' => [1, 2, 3]],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['rum', 'pencil', 'pot'],
                'correct' => [1, 3]],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['can', 'vodka', 'spoon'],
                'correct' => [2]],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['rifle', 'gold', 'strainer'],
                'correct' => [1]],


          ] // End blocks

    ], // End words_2

    'words_3' => [
          'test_name' => 'words_3',
          'task_type' => 'words',
          'type' => 'task',
          'blocks' => [
              ['type' => 'review',
              'text' => '',
              'targets' => ['garlic', 'wood', 'sugar', 'coal', 'clarinet',
                            'trumpet', 'cinnamon', 'flute', 'kerosine',
                            'vanilla', 'gasoline', 'violin'],
              'review_time_each' => 2,
              'review_time' => null],
              ['type' => 'test',
               'selection_type' => 'select_all',
               'prompt' => 'Which of the following are target words',
               'choices' => ['oil', 'kerosine', 'priest'],
               'correct' => [2]],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['salt', 'basement', 'piano'],
                'correct' => []],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['wood', 'harmonica', 'trumpet'],
                'correct' => [1, 3]],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['flute', 'clarinet', 'piano'],
                'correct' => [2]],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['garlic', 'violin', 'vanilla'],
                'correct' => [1, 2, 3]],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['chair', 'pepper', 'horn'],
                'correct' => []],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['sand', 'sugar', 'drum'],
                'correct' => [2]],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['coal', 'electricity', 'lemon'],
                'correct' => [1]],


          ] // End blocks

    ], // End words_3

    'words_4' => [
          'test_name' => 'words_4',
          'task_type' => 'words',
          'type' => 'task',
          'blocks' => [
              ['type' => 'review',
              'text' => '',
              'targets' => ['bluebird', 'chisel', 'eagle', 'screwdriver', 'crow',
                            'wrench', 'pants', 'nails', 'shoes',
                            'canary', 'skirt', 'blouse'],
              'review_time_each' => 2,
              'review_time' => null],
              ['type' => 'test',
               'selection_type' => 'select_all',
               'prompt' => 'Which of the following are target words',
               'choices' => ['child', 'bluebird', 'chapel'],
               'correct' => [2]],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['canary', 'socks', 'robin'],
                'correct' => [1]],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['hammer', 'skirt', 'apple'],
                'correct' => [2]],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['shirt', 'saw', 'wren'],
                'correct' => []],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['chisel', 'screwdriver', 'sparrow'],
                'correct' => [1, 2]],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['silver', 'nails', 'eagle'],
                'correct' => [2, 3]],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['crow', 'rock', 'rook'],
                'correct' => [1]],
              ['type' => 'test',
                'selection_type' => 'select_all',
                'prompt' => 'Which of the following are target words',
                'choices' => ['blouse', 'pants', 'wrench'],
                'correct' => [1, 2, 3]],


          ] // End blocks

    ], // End words_4

    'story_1' => [
        'test_name' => 'story_1',
        'task_type' => 'story',
        'type' => 'task',
        'directory' => '/img/memory-task/faces/',
        'blocks' => [
            ['type' => 'review',
            'text' => '',
            'targets' => ['Anna Thompson of South Boston, a cook in a school cafeteria, reported to the police
                          that she had been robbed of $56 on State Street the night before last. She had four
                          small children, the rent was due, and they had not eaten for two days. The police,
                          touched by the woman’s story, collected money for her.',
                          'A 67 year old woman in Greensville, Florida used a black umbrella to stop an
                          attack by two men. The woman was walking down Main Street when the attackers
                          got out of a yellow pickup and approached her. She hit one with her umbrella and
                          the other man fled. Both men were arrested by police.'],
            'review_time' => 30],

            [ 'type' => 'test',
              'selection_type' => 'select_one',
              'prompt' => 'On what street was Anna Thompson robbed?',
              'choices' => ['State Street',
                            'Main Street',
                            'Sixth Street'],
              'correct' => [1]],
            [ 'type' => 'test',
              'selection_type' => 'select_one',
              'prompt' => 'In which town was an attack stopped by an umbrella?',
              'choices' => ['Greensville',
                            'Gainessville',
                            'Greensboro'],
              'correct' => [1]],
            [ 'type' => 'test',
              'selection_type' => 'select_one',
              'prompt' => 'How old was the woman who defended herself with an umbrella?',
              'choices' => ['56',
                            '57',
                            '67'],
                'correct' => [2]],
              [ 'type' => 'test',
                'selection_type' => 'select_one',
                'prompt' => 'What was Anna Thompson\'s job?',
                'choices' => ['Cook',
                              'She didn\'t have a job',
                              'Janitor'],
                'correct' => [1]],
              [ 'type' => 'test',
                'selection_type' => 'select_one',
                'prompt' => 'When did Anna report being robbed?',
                'choices' => ['One night after the robbery',
                              'Two nights after the robbery',
                              'A week after the robbery'],
                'correct' => [2]],
              [ 'type' => 'test',
                'selection_type' => 'select_one',
                'prompt' => 'How long had it been since Anna\'s family had eaten?',
                'choices' => ['2 days',
                              '1 day',
                              'They missed 2 meals'],
                'correct' => [1]],
              [ 'type' => 'test',
                'selection_type' => 'select_one',
                'prompt' => 'What color was the pickup truck used by the attackers in Florida?',
                'choices' => ['Yellow',
                              'Black',
                              'It wasn\'t mentioned'],
                'correct' => [1]],
              [ 'type' => 'test',
                'selection_type' => 'select_one',
                'prompt' => 'What did the police do to help Anna Thompson?',
                'choices' => ['Make arrests',
                              'Collect money for her',
                              'Give her food'],
                'correct' => [2]],

        ] // End blocks
    ], // End story_1

    'story_2' => [
        'test_name' => 'story_2',
        'task_type' => 'story',
        'type' => 'task',
        'blocks' => [
            ['type' => 'review',
            'text' => '',
            'targets' => ['A recent survey of supermarket shoppers in Baytown revealed that eight
                          out of ten shopping carts have faulty wheels or are difficult to steer.
                          More than half of people reported having had accidents with their carts.
                          These included collisions with other shoppers and bumping into groceries.
                          Retailers claim that the problem is not with the carts, but that shoppers
                          are not using them carefully.',
                          'There were violent scenes at Grangers, a London department store, when
                          animal rights protesters invaded the furs section.  Two expensive mink
                          jackets were ruined and one leather skirt was ripped.  A protester was
                          taken to hospital after a confrontation with police.  The manager said
                          that tomorrow, business would be back to normal.'],
            'review_time' => 30],

            [ 'type' => 'test',
              'selection_type' => 'select_one',
              'prompt' => 'More than half the respondents to the supermarket survey reported what?',
              'choices' => ['Collisions with other shoppers',
                            'Running into stacks of groceries',
                            'Having accidents with their carts'],
              'correct' => [3]],
            [ 'type' => 'test',
              'selection_type' => 'select_one',
              'prompt' => 'When was the supermarket survey conducted?',
              'choices' => ['Recently',
                            'Last month',
                            'A week ago'],
              'correct' => [1]],
            [ 'type' => 'test',
              'selection_type' => 'select_one',
              'prompt' => 'How many people were arrested after the Department store protest in London?',
              'choices' => ['None',
                            'One',
                            'It\'s unclear based on the story'],
                'correct' => [3]],
              [ 'type' => 'test',
                'selection_type' => 'select_one',
                'prompt' => 'What problems did customers identify with the shopping carts?',
                'choices' => ['They make an annoying noise',
                              'They have faulty wheels',
                              'They can be difficult to move'],
                'correct' => [2]],
              [ 'type' => 'test',
                'selection_type' => 'select_one',
                'prompt' => 'What was the name of the Department Store in London?',
                'choices' => ['Grangers',
                              'Graysons',
                              'Greysons'],
                'correct' => [1]],
              [ 'type' => 'test',
                'selection_type' => 'select_one',
                'prompt' => 'Retailers responded to the supermarket survey by:',
                'choices' => ['Saying they will fix their shopping carts',
                              'Claiming the problem is with the customers',
                              'Promising they’ll look into the issue'],
                'correct' => [2]],
              [ 'type' => 'test',
                'selection_type' => 'select_one',
                'prompt' => 'What proportion of shopping carts had issues with faulty wheels or steering?',
                'choices' => ['More than half',
                              'Almost all',
                              '8 out of 10'],
                'correct' => [3]],
              [ 'type' => 'test',
                'selection_type' => 'select_one',
                'prompt' => 'How were the mink jackets described?',
                'choices' => ['Black',
                              'Expensive',
                              'New'],
                'correct' => [2]]

        ] // End blocks
    ], // End story_2

    'story_3' => [
        'test_name' => 'story_3',
        'task_type' => 'story',
        'type' => 'task',
        'blocks' => [
            ['type' => 'review',
            'text' => '',
            'targets' => ['Clothing makers in Europe and China have a problem.  The shape of the
                          American male has changed.  American men now have slimmer waists and larger chests
                          than they did in 1933 when the last measurements were taken.  Manufacturers will
                          alter their designs and have promised to update their statistics more frequently.',
                          'Michael Simpson earned a reputation for being stubborn after refusing to
                          accept pay cheques.  Instead of cheques, he wanted his wages to be paid in
                          cash.  He eventually collected ten thousand dollars in back pay.  His wife
                          was pleased because she had been forced to cook on a camping stove, after
                          services to their home were cut off eighteen months ago.'],
            'review_time' => 30],

            [ 'type' => 'test',
              'selection_type' => 'select_one',
              'prompt' => 'Clothing makers from which areas are mentioned in the story?',
              'choices' => ['Europe and Mexico',
                            'Europe and China',
                            'China and Mexico'],
              'correct' => [2]],
            [ 'type' => 'test',
              'selection_type' => 'select_one',
              'prompt' => 'Michael had a reputation for being:',
              'choices' => ['Stubborn',
                            'Cheap',
                            'Rude'],
              'correct' => [1]],
            [ 'type' => 'test',
              'selection_type' => 'select_one',
              'prompt' => 'How long ago did the services in Michael’s house get turned off?',
              'choices' => ['18 months ago',
                            '6 months ago',
                            '12 months ago'],
                'correct' => [1]],
              [ 'type' => 'test',
                'selection_type' => 'select_one',
                'prompt' => 'What was Michael’s last name?',
                'choices' => ['Simpson',
                              'Sanderson',
                              'Sandford'],
                'correct' => [1]],
              [ 'type' => 'test',
                'selection_type' => 'select_one',
                'prompt' => 'When did clothing makers last "measure the American male"?',
                'choices' => ['1933',
                              '1932',
                              '1923'],
                'correct' => [1]],
              [ 'type' => 'test',
                'selection_type' => 'select_one',
                'prompt' => 'How much back pay did Michael receive?',
                'choices' => ['Ten thousand dollars',
                              'Eleven thousand dollars',
                              'Twelve thousand dollars'],
                'correct' => [1]],
              [ 'type' => 'test',
                'selection_type' => 'select_one',
                'prompt' => 'How has the shape of the American male changed?',
                'choices' => ['Slimmer waists and larger arms',
                              'Larger waists and slimmer legs',
                              'Slimmer waists and larger chests'],
                'correct' => [3]],
              [ 'type' => 'test',
                'selection_type' => 'select_one',
                'prompt' => 'How did Michael’s wife feel when he finally got paid?',
                'choices' => ['Relieved',
                              'Pleased',
                              'Grateful'],
                'correct' => [2]],

        ] // End blocks
    ], // End story_3

    'story_4' => [
        'test_name' => 'story_4',
        'task_type' => 'story',
        'type' => 'task',
        'blocks' => [
            ['type' => 'review',
            'text' => '',
            'targets' => ['At 7:35pm on Monday, Joe Garcia of San Francisco was watching television as he
                          dressed to go out. A weather bulletin interrupted the program to warn of a
                          thunderstorm. The announcer said the storm could bring hail and up to four
                          inches of rain. Joe decided to stay home. He took off his coat and sat down
                          to watch old movies.',
                          'A Tokyo barmaid is suing a customer for two hundred and twenty thousand
                          dollars. The complaint arose after the man attempted a goodnight kiss,
                          causing the couple to fall down a flight of stairs.  The forty six year
                          old woman suffered facial injuries in the fall but avoided the attempted
                          kiss.'],
            'review_time' => 30],

            [ 'type' => 'test',
              'selection_type' => 'select_one',
              'prompt' => 'In which city was there bad weather predicted?',
              'choices' => ['San Jose',
                            'San Diego',
                            'San Francisco'],
              'correct' => [3]],
            [ 'type' => 'test',
              'selection_type' => 'select_one',
              'prompt' => 'On which day was bad weather predicted?',
              'choices' => ['Monday',
                            'Sunday',
                            'Tuesday'],
              'correct' => [1]],
            [ 'type' => 'test',
              'selection_type' => 'select_one',
              'prompt' => 'How much is the Tokyo barmaid suing for?',
              'choices' => ['$220,000',
                            '$200,000',
                            '$20,000'],
                'correct' => [1]],
              [ 'type' => 'test',
                'selection_type' => 'select_one',
                'prompt' => 'How much rain was the storm predicted to bring?',
                'choices' => ['At least 4 inches',
                              'Up to 4 inches',
                              'More than 4 inches'],
                'correct' => [2]],
              [ 'type' => 'test',
                'selection_type' => 'select_one',
                'prompt' => 'How old was the Tokyo barmaid?',
                'choices' => ['36',
                              '46',
                              '26'],
                'correct' => [2]],
              [ 'type' => 'test',
                'selection_type' => 'select_one',
                'prompt' => 'Who is the Tokyo barmaid suing?',
                'choices' => ['Her manager',
                              'Her employer',
                              'Her customer'],
                'correct' => [3]],
              [ 'type' => 'test',
                'selection_type' => 'select_one',
                'prompt' => 'What did Joe do when he heard the news about bad weather?',
                'choices' => ['Call in sick',
                              'Watch old movies',
                              'Go back to watching TV programs'],
                'correct' => [2]],
              [ 'type' => 'test',
                'selection_type' => 'select_one',
                'prompt' => 'How did Joe find about the weather?',
                'choices' => ['He was watching a news program',
                              'A weather report interrupted the program he was watching',
                              'He saw a weather bulletin between shows'],
                'correct' => [2]],

        ] // End blocks
    ], // End story_4

    // Group Memory Tasks
    'group_1_instructions' => [
      'test_name' => 'group_1_instructions',
      'task_type' => 'mixed',
      'type' => 'intro',
      'directory' => '/img/memory-task/bodies/',
      'blocks' => [
        ['type' => 'text_intro',
         'header' => 'Memory Task',
         'wait_for_all' => 'true',
         'text' => ['Next is a test of your group’s collective memory. This
                     will be similar to the individual memory tasks you
                     completed earlier. We will ask you to remember images,
                     words and stories.',
                     'The group task has a twist. <strong>We’ll ask you to memorize all
                       three types of stimuli at the same time</strong>.',
                     'We’ll start with a practice round.',
                     'The practice will begin when all three members of your group have clicked "Next".'
                   ]],
        ['type' => 'text',
         'header' => 'Memory: practice round',
         'wait_for_all' => 'true',
         'text' => ['This practice round will not count toward your group’s score.',
                    'Your group has <strong>20 seconds</strong> to memorize
                    <strong>3</strong> images, <strong>6</strong> words, and
                    <strong>2</strong> very short stories.',
                    'During the memorization period, each person will look at
                    their own laptop and try to memorize as much as they can.
                    It is possible, but difficult, for one person to remember
                    all three types of stimuli.',
                    '<span class="text-danger">You are NOT allowed to write anything down.</span>'
                   ]],
          ['type' => 'review_choice',
           'header' => 'Practice Round',
          'text' => ['This is the page where you can choose what to memorize.
                      We will ask your group about all three types of stimuli:
                      Images, Words and Stories.',
                     'If you want, you can try to memorize several types of stimuli.
                      For example, you might start with "Stories" (by clicking
                      on the <span class="text-danger">Stories</span> button).
                      Then, if you have time, you can click on a different button and
                      try to memorize the <span class="text-warning">Words</span>
                      and/or the <span class="text-success">Images</span>.',
                      '<strong>Take a minute to discuss with your group how you\'ll
                      approach this task.</strong>',
                     '<strong>Reminder</strong>: in this practice round there
                     are 6 words; 3 images; and 2 very short stories.',
                     'Your 20 seconds will begin when everyone in the group has
                     clicked on a button below.'],
          'choices' => [['color' => 'success', 'type' => 'images'],
                        ['color' => 'warning', 'type' => 'words'],
                        ['color' => 'danger', 'type' => 'stories']],
          'review_time' => 20],

          ['type' => 'mixed_review',
          'text' => [],
          'types' => [
                       ['type' => 'images', 'directory' => '/img/memory-task/bodies/',
                        'prompt' => 'Remember these target images',
                        'targets' => ['1_targets_1.jpeg', '1_targets_2.jpeg', '1_targets_3.jpeg']
                        ],
                       ['type' => 'words',
                        'prompt' => 'Remember these target words',
                        'targets' => ['mountain', 'bread', 'cat', 'ice', 'rabbit', 'bun']
                       ],
                       ['type' => 'stories',
                        'prompt' => 'Remember these target stories',
                        'targets' => ['Monica was running late for a job interview
                                       because her train broke down outside of
                                       Chicago. The interviewer was very
                                       understanding as the same thing had once
                                       happened to her.',
                                      'A student at Stone Mountain High School
                                      named Jeff Jones, set a world record by
                                      eating 125 hotdogs in one sitting. Jeff
                                      said that his inspiration was his pet
                                      golden retriever.'],
                       ]
          ],
          'choices' => [['color' => 'success', 'type' => 'images'],
                        ['color' => 'warning', 'type' => 'words'],
                        ['color' => 'danger', 'type' => 'stories']],
          'review_time' => 20],

          ['type' => 'text',
           'header' => 'Memory: practice round',
           'end_individual_section' => 'true',
           'text' => ['Now we will ask you some questions about the stimuli.
                       Remember, this is a practice!<br><br>
                       You will answer as a group. When you’re answering the
                       questions <strong>everyone should be able to see the Reporter\'s
                       laptop</strong>.<br><br>
                       If you are not the Reporter, leave your laptop open. You\'ll
                       come back to it shortly.<br><br>
                       The practice questions will begin when The Reporter clicks "Next"'
                     ]],

         ['type' => 'text',
          'header' => 'Memory: practice round',
          'text' => ['Make sure all your group members
                      can see this screen. We are about to ask questions about
                      the words, stories and images you memorized.<br>
                      Click "Next" to continue.'
                    ]],

          ['type' => 'practice_test_stories',
           'selection_type' => 'select_one',
           'prompt' => 'What is the record number of hotdogs eaten in one sitting?',
           'choices' => ['100',
                         '150',
                         '125'],
           'correct' => [3]],

          [ 'type' => 'practice_test_words',
           'selection_type' => 'select_all',
           'prompt' => 'Which of the following are target words?',
           'choices' => ['bread', 'fish', 'pie'],
           'correct' => [1]],

          [ 'type' => 'practice_test_images',
           'selection_type' => 'select_one',
           'show_numbers' => 'false',
           'prompt' => 'Which of the following images is a "target" image?',
           'img' => 'p0_test_1.jpeg',
           'correct' => [1]],

      ]// end blocks
    ], // end group_1_instructions

    'group_2_instructions' => [
      'test_name' => 'group_2_instructions',
      'task_type' => 'mixed',
      'type' => 'intro',
      'directory' => '/img/memory-task/faces/',
      'blocks' => [
        ['type' => 'text_intro',
         'header' => 'Memory Task',
         'wait_for_all' => 'true',
         'text' => ['Next is a test of your group’s collective memory. This
                      will be the same as the group memory task you completed previously.'
                   ]],
        /* WE ARE SKIPPING THE PRACTICE FOR THIS GROUP FOR NOW
        ['type' => 'text',
         'header' => 'Memory: practice round',
         'wait_for_all' => 'true',
         'text' => ['This practice round will not count toward your group’s score.',
                    'Your group has <strong>20 seconds</strong> to memorize
                    <strong>3</strong> images, <strong>6</strong> words, and
                    <strong>2</strong> very short stories.',
                    'During the memorization period, each person will look at
                    their own laptop and try to memorize as much as they can.
                    It is possible, but difficult, for one person to remember
                    all three types of stimuli.',
                    'There will be final instructions when all three group
                    members have clicked "Next".'
                   ]],
          ['type' => 'review_choice',
           'header' => 'Practice Round',
          'text' => ['This is the page where you can choose what to memorize.
                      We will ask your group about all three types of stimuli:
                      Images, Words and Stories.',
                     'If you want, you can try to memorize several types of stimuli.
                      For example, you might start with "Stories" (by clicking
                      on the <span class="text-danger">Stories</span> button).
                      Then, if you have time, you can click on a different button and
                      try to memorize the <span class="text-warning">Words</span>
                      and/or the <span class="text-success">Images</span>.
                      Alternatively, each group member can focus on memorizing
                      one type of stimulus.',
                      '<strong>Take a minute to discuss with your group how you\'ll
                      approach this task.</strong>',
                     '<strong>Reminder</strong>: in this practice round there
                     are 6 words; 3 images; and 2 very short stories.',
                     'Your 20 seconds will begin when everyone in the group has
                     clicked on a button below. There is a timer in the top
                     right of the screen'],
          'choices' => [['color' => 'success', 'type' => 'images'],
                        ['color' => 'warning', 'type' => 'words'],
                        ['color' => 'danger', 'type' => 'stories']],
          'review_time' => 20],

          ['type' => 'mixed_review',
          'text' => [],
          'types' => [
                       ['type' => 'images', 'directory' => '/img/memory-task/faces/',
                        'prompt' => 'Remember these target images',
                        'targets' => ['1_targets_1.png', '1_targets_2.png', '1_targets_3.jpg']
                        ],
                       ['type' => 'words',
                        'prompt' => 'Remember these target words',
                        'targets' => ['tree', 'pine', 'root', 'glove', 'baseball', 'hotdog']
                       ],
                       ['type' => 'stories',
                        'prompt' => 'Remember these target stories',
                        'targets' => ['The oldest surviving human, who was born in
                                      1902, recently identified her secret to long life,
                                      citing the benefits of swimming every day in very
                                      cold water.',
                                      'A poll, conducted a week ago by Pew Research,
                                      found that 60% of people prefer vanilla icecream
                                      to chocolate. Critics suggest that the poll
                                      was biased.'],
                       ]
          ],
          'choices' => [['color' => 'success', 'type' => 'images'],
                        ['color' => 'warning', 'type' => 'words'],
                        ['color' => 'danger', 'type' => 'stories']],
          'review_time' => 20],

          ['type' => 'text',
           'header' => 'Memory: practice round',
           'end_individual_section' => 'true',
           'text' => ['Now we will ask you some questions about the stimuli.
                       Remember, this is a practice!<br><br>
                       You will answer as a group. When you’re answering the
                       questions <strong>everyone should be able to see the Reporter\'s
                       laptop</strong>.<br><br>
                       If you are not the Reporter, leave your laptop open. You\'ll
                       come back to it shortly.<br><br>
                       The practice questions will begin when The Reporter clicks "Next"'
                     ]],

          [ 'type' => 'practice_test_words',
           'selection_type' => 'select_all',
           'prompt' => 'Which of the following are target words?',
           'choices' => ['tree', 'pine', 'grow'],
           'correct' => [1, 2]],

           ['type' => 'practice_test_stories',
           'selection_type' => 'select_one',
           'prompt' => 'What year was the oldest known human born?',
           'choices' => ['1901',
                         '1902',
                         '1903'],
           'correct' => [2]],

          [ 'type' => 'practice_test_images',
           'selection_type' => 'select_one',
           'show_numbers' => 'false',
           'prompt' => 'Which of the following images is a "target" image?',
           'img' => '1_test_1.jpg',
           'correct' => [3]],
          */
      ]// end blocks
    ], // end group_2_instructions


    'group_3_instructions' => [
      'test_name' => 'group_3_instructions',
      'task_type' => 'mixed',
      'type' => 'intro',
      'directory' => '/img/memory-task/faces/',
      'blocks' => [
        ['type' => 'text_intro',
         'header' => 'Memory Task',
         'wait_for_all' => 'true',
         'text' => ['Next is a test of your group’s collective memory.',
                     'We’ll start with a practice round.',
                     'The practice will begin when all three members of your group have clicked "Next".'
                   ]],

       ['type' => 'text',
        'header' => 'Memory: practice round',
        'wait_for_all' => 'true',
        'text' => ['This practice round will not count toward your group’s score.',
                   'Your group has <strong>20 seconds</strong> to memorize
                   <strong>3</strong> images, <strong>6</strong> words, and
                   <strong>2</strong> very short stories.',
                   'During the memorization period, each person will look at
                   their own laptop and try to memorize as much as they can.
                   It is possible, but difficult, for one person to remember
                   all three types of stimuli.',
                   'There will be final instructions when all three group
                   members have clicked "Next".'
                  ]],
         ['type' => 'review_choice',
          'header' => 'Practice Round',
         'text' => ['This is the page where you can choose what to memorize.
                     We will ask your group about all three types of stimuli:
                     Images, Words and Stories.',
                    'If you want, you can try to memorize several types of stimuli.
                     For example, you might start with "Stories" (by clicking
                     on the <span class="text-danger">Stories</span> button).
                     Then, if you have time, you can click on a different button and
                     try to memorize the <span class="text-warning">Words</span>
                     and/or the <span class="text-success">Images</span>.
                     Alternatively, each group member can focus on memorizing
                     one type of stimulus.',
                     '<strong>Take a minute to discuss with your group how you\'ll
                     approach this task.</strong>',
                    '<strong>Reminder</strong>: in this practice round there
                    are 6 words; 3 images; and 2 very short stories.',
                    'Your 20 seconds will begin when everyone in the group has
                    clicked on a button below. There is a timer in the top
                    right of the screen'],
         'choices' => [['color' => 'success', 'type' => 'images'],
                       ['color' => 'warning', 'type' => 'words'],
                       ['color' => 'danger', 'type' => 'stories']],
         'review_time' => 20],

         ['type' => 'mixed_review',
         'text' => [],
         'types' => [
                      ['type' => 'images', 'directory' => '/img/memory-task/faces/',
                       'prompt' => 'Remember these target images',
                       'targets' => ['1_targets_1.png', '1_targets_2.png', '1_targets_3.jpg']
                       ],
                      ['type' => 'words',
                       'prompt' => 'Remember these target words',
                       'targets' => ['tree', 'pine', 'root', 'glove', 'baseball', 'hotdog']
                      ],
                      ['type' => 'stories',
                       'prompt' => 'Remember these target stories',
                       'targets' => ['The oldest surviving human, who was born in
                                     1902, recently identified her secret to long life,
                                     citing the benefits of swimming every day in very
                                     cold water.',
                                     'A poll, conducted a week ago by Pew Research,
                                     found that 60% of people prefer vanilla icecream
                                     to chocolate. Critics suggest that the poll
                                     was biased.'],
                      ]
         ],
         'choices' => [['color' => 'success', 'type' => 'images'],
                       ['color' => 'warning', 'type' => 'words'],
                       ['color' => 'danger', 'type' => 'stories']],
         'review_time' => 20],

         ['type' => 'text',
          'header' => 'Memory: practice round',
          'end_individual_section' => 'true',
          'text' => ['Now we will ask you some questions about the stimuli.
                      Remember, this is a practice!<br><br>
                      You will answer as a group. When you’re answering the
                      questions <strong>everyone should be able to see the Reporter\'s
                      laptop</strong>.<br><br>
                      If you are not the Reporter, leave your laptop open. You\'ll
                      come back to it shortly.<br><br>
                      The practice questions will begin when The Reporter clicks "Next"'
                    ]],

         [ 'type' => 'practice_test_words',
          'selection_type' => 'select_all',
          'prompt' => 'Which of the following are target words?',
          'choices' => ['tree', 'pine', 'grow'],
          'correct' => [1, 2]],

          ['type' => 'practice_test_stories',
          'selection_type' => 'select_one',
          'prompt' => 'What year was the oldest known human born?',
          'choices' => ['1901',
                        '1902',
                        '1903'],
          'correct' => [2]],

         [ 'type' => 'practice_test_images',
          'selection_type' => 'select_one',
          'show_numbers' => 'false',
          'prompt' => 'Which of the following images is a "target" image?',
          'img' => '1_test_1.jpg',
          'correct' => [3]],
      ]// end blocks
    ], // end group_3_instructions

    'group_4_instructions' => [
      'test_name' => 'group_4_instructions',
      'task_type' => 'mixed',
      'type' => 'intro',
      'directory' => '/img/memory-task/faces/',
      'blocks' => [
        ['type' => 'text_intro',
         'header' => 'Memory Task',
         'wait_for_all' => 'true',
         'text' => ['Next is a test of your group’s collective memory. This is
                    the same as the group memory exercise you have completed twice already.',
                     '<strong>As you have already seen this task twice, we will
                     NOT do a practice this time.</strong>',
                     'Remember, each group member will memorize the stimuli on
                     their own computer. But, you will answer as a group.
                     Questions will only appear on The Reporters laptop.',
                     'The task will continue when everyone has clicked "Next".'
                   ]],
      ]// end blocks
    ], // end group_3_instructions

    'group_1' => [
      'test_name' => 'group_1',
      'task_type' => 'mixed',
      'type' => 'intro',
      'directory' => '/img/memory-task/cars/',
      'blocks' => [
        ['type' => 'text',
         'header' => '',
         'wait_for_all' => 'true',
         'text' => ['Now for the actual task. <strong>Everyone should go back to their own laptop</strong>.',
                    'Your group will have <strong>40</strong> seconds to memorize 6 images, 12 words and 2 short
                    stories. This will be the same as the practice, but you have twice as long to
                    remember twice as many things.',
                    'As in the practice, you will use your own laptop to memorize the stimuli. Again, you
                    have the option of looking at multiple types of stimuli
                    (e.g. <span class="text-danger">stories</span> AND
                    <span class="text-success">images</span>). Or,
                    you can divide the responsibilities of memorizing different things.',
                    '<strong>Take some time to discuss how you will approach this task.</strong>',
                    'You will receive some final instructions when each group member has clicked "Next".'
                   ]],
          ['type' => 'review_choice',
          'text' => ['You will have <strong>40</strong> seconds to memorize everything as a group. There
                      is a timer in the top right of the screen.',
                     'Remember, during the 40 seconds, you can always change the stimuli
                     you are memorizing by clicking on a different button.',
                     '<strong>You’re NOT allowed to write anything down.</strong>',
                     'The time starts when everyone has clicked on one of the buttons.'],
          'choices' => [['color' => 'success', 'type' => 'images'],
                        ['color' => 'warning', 'type' => 'words'],
                        ['color' => 'danger', 'type' => 'stories']],
          'review_time' => 40],

          ['type' => 'mixed_review',
          'text' => [],
          'types' => [
                       ['type' => 'images', 'directory' => '/img/memory-task/cars/',
                        'prompt' => 'Remember these target images',
                        'targets' => ['1_targets_1.jpg', '1_targets_2.jpg', '1_targets_3.jpg']
                        ],
                       ['type' => 'words',
                        'prompt' => 'Remember these target words',
                        'targets' => ['pistol', 'fork', 'pot', 'sword', 'vodka',
                                    'rum', 'bomb', 'pan', 'wine',
                                    'spatula', 'bourbon', 'rifle']
                       ],
                       ['type' => 'stories',
                        'prompt' => 'Remember these target stories',
                        'targets' => ['A recent survey of supermarket shoppers in Baytown revealed that eight
                                      out of ten shopping carts have faulty wheels or are difficult to steer.
                                      More than half of people reported having had accidents with their carts.
                                      These included collisions with other shoppers and bumping into groceries.
                                      Retailers claim that the problem is not with the carts, but that shoppers
                                      are not using them carefully.',
                                      'There were violent scenes at Grangers, a London department store, when
                                      animal rights protesters invaded the furs section.  Two expensive mink
                                      jackets were ruined and one leather skirt was ripped.  A protester was
                                      taken to hospital after a confrontation with police.  The manager said
                                      that tomorrow, business would be back to normal.'],
                       ]
          ],
          'choices' => [['color' => 'success', 'type' => 'images'],
                        ['color' => 'warning', 'type' => 'words'],
                        ['color' => 'danger', 'type' => 'stories']],
          'review_time' => 40],

          ['type' => 'text',
           'header' => '',
           'end_individual_section' => 'true',
           'text' => ['We will now ask you questions.',
                      '<strong>Everyone should be able to see the screen of the Reporter\'s laptop</strong>. You will
                       answer as a group, on The Reporter\'s laptop.',
                       'The questions will begin when The Reporter clicks "Next"'
                     ]],
         ['type' => 'text',
          'text' => ['Make sure all your group members
                      can see this screen. We are about to ask questions about
                      the words, stories and images you memorized.<br>
                      Click "Next" to continue.'
                    ]],
          [ 'type' => 'test_images',
           'selection_type' => 'select_one',
           'show_numbers' => 'false',
           'prompt' => 'Which of the following images is a "target" image?',
           'img' => '1_test_1.jpg',
           'correct' => [3]],

          ['type' => 'test_stories',
          'selection_type' => 'select_one',
          'prompt' => 'More than half the respondents to the
                       supermarket survey reported what?',
          'choices' => ['Collisions with other shoppers',
                        'Running into stacks of groceries',
                        'Having accidents with their carts'],
          'correct' => [3]],

          [ 'type' => 'test_words',
           'selection_type' => 'select_all',
           'prompt' => 'Which of the following are target words?',
           'choices' => ['fork', 'pan', 'knife'],
           'correct' => [1, 2]],

          [ 'type' => 'test_images',
            'selection_type' => 'select_one',
            'show_numbers' => 'false',
            'prompt' => 'Which of the following images is a "target" image?',
            'img' => '1_test_2.jpg',
            'correct' => [1]],

          [ 'type' => 'test_words',
           'selection_type' => 'select_all',
           'prompt' => 'Which of the following are target words?',
           'choices' => ['spatula', 'beer', 'bomb'],
           'correct' => [1, 3]],

          ['type' => 'test_stories',
            'selection_type' => 'select_one',
            'prompt' => 'When was the supermarket survey conducted?',
            'choices' => ['Recently',
                          'Last month',
                          'A week ago'],
            'correct' => [1]],

          [ 'type' => 'test_images',
            'selection_type' => 'select_one',
            'show_numbers' => 'false',
            'prompt' => 'Which of the following images is a "target" image?',
            'img' => '1_test_3.jpg',
            'correct' => [1]],

          [ 'type' => 'test_words',
           'selection_type' => 'select_all',
           'prompt' => 'Which of the following are target words?',
           'choices' => ['whiskey', 'lemon', 'gun'],
           'correct' => []],

          ['type' => 'test_stories',
           'selection_type' => 'select_one',
           'prompt' => 'How many people were arrested after the Department store protest in London?',
           'choices' => ['None',
                         'One',
                         'It\'s unclear based on the story'],
           'correct' => [3]],

          [ 'type' => 'test_words',
          'selection_type' => 'select_all',
          'prompt' => 'Which of the following are target words?',
          'choices' => ['gun', 'pistol', 'trout'],
          'correct' => [2]],

          [ 'type' => 'test_images',
            'selection_type' => 'select_one',
            'show_numbers' => 'false',
            'prompt' => 'Which of the following images is a "target" image?',
            'img' => '1_test_4.jpg',
            'correct' => [2]],

          ['type' => 'test_stories',
           'selection_type' => 'select_one',
           'prompt' => 'What problems did customers identify with the shopping carts?',
           'choices' => ['They make an annoying noise',
                         'They have faulty wheels',
                         'They can be difficult to move'],
           'correct' => [2]],

          ['type' => 'test_stories',
            'selection_type' => 'select_one',
            'prompt' => 'What was the name of the Department Store in London?',
            'choices' => ['Grangers',
                        'Graysons',
                        'Greysons'],
            'correct' => [1]],

          [ 'type' => 'test_images',
            'selection_type' => 'select_one',
            'show_numbers' => 'false',
            'prompt' => 'Which of the following images is a "target" image?',
            'img' => '1_test_5.jpg',
            'correct' => [3]],

          [ 'type' => 'test_words',
            'selection_type' => 'select_all',
            'prompt' => 'Which of the following are target words?',
            'choices' => ['wine', 'fork', 'sword'],
            'correct' => [1, 2, 3]],

          [ 'type' => 'test_images',
            'selection_type' => 'select_one',
            'show_numbers' => 'false',
            'prompt' => 'Which of the following images is a "target" image?',
            'img' => '1_test_6.jpg',
            'correct' => [2]],

          [ 'type' => 'test_words',
            'selection_type' => 'select_all',
            'prompt' => 'Which of the following are target words?',
            'choices' => ['rum', 'pencil', 'pot'],
            'correct' => [1, 3]],

          ['type' => 'test_stories',
            'selection_type' => 'select_one',
            'prompt' => 'Retailers responded to the supermarket survey by:',
            'choices' => ['Saying they will fix their shopping carts',
                        'Claiming the problem is with the customers',
                        'Promising they’ll look into the issue'],
            'correct' => [2]],

          [ 'type' => 'test_images',
            'selection_type' => 'select_one',
            'show_numbers' => 'false',
            'prompt' => 'Which of the following images is a "target" image?',
            'img' => '1_test_7.jpg',
            'correct' => [2]],

          ['type' => 'test_stories',
            'selection_type' => 'select_one',
            'prompt' => 'What proportion of shopping carts had issues with faulty wheels or steering?',
            'choices' => ['More than half',
                        'Almost all',
                        '8 out of 10'],
            'correct' => [3]],

          [ 'type' => 'test_images',
            'selection_type' => 'select_one',
            'show_numbers' => 'false',
            'prompt' => 'Which of the following images is a "target" image?',
            'img' => '1_test_8.jpg',
            'correct' => [3]],

          [ 'type' => 'test_words',
            'selection_type' => 'select_all',
            'prompt' => 'Which of the following are target words?',
            'choices' => ['can', 'vodka', 'spoon'],
            'correct' => [1, 3]],

          ['type' => 'test_stories',
            'selection_type' => 'select_one',
            'prompt' => 'How were the mink jackets described?',
            'choices' => ['Black',
                        'Expensive',
                        'New'],
            'correct' => [2]],

          [ 'type' => 'test_words',
            'selection_type' => 'select_all',
            'prompt' => 'Which of the following are target words?',
            'choices' => ['rifle', 'gold', 'strainer'],
            'correct' => [1]],

      ]// end blocks
    ], // end group_1

    'group_2' => [
      'test_name' => 'group_2',
      'task_type' => 'mixed',
      'type' => 'intro',
      'directory' => '/img/memory-task/faces/',
      'blocks' => [
        ['type' => 'text',
         'header' => 'Memory Task',
         'wait_for_all' => 'true',
         'text' => ['You have seen this group memory task before, so we will NOT do a
                    practice this time.',
                    'Remember, each person will memorize the stimuli on their own computer.
                    But, you will answer as a group. Questions will only appear on
                    the Reporter\'s laptop.',
                    'The task will continue when everyone has clicked "Next".'
                   ]],
          ['type' => 'review_choice',
          'text' => ['You will have <strong>40</strong> seconds to memorize everything as a group. There
                      is a timer in the top right of the screen.',
                     'Remember, during the 40 seconds, you can always change the stimuli
                     you are memorizing by clicking on a different button.',
                     '<strong>You’re NOT allowed to write anything down.</strong>',
                     'The time starts when everyone has clicked on one of the buttons.'],
          'choices' => [['color' => 'success', 'type' => 'images'],
                        ['color' => 'warning', 'type' => 'words'],
                        ['color' => 'danger', 'type' => 'stories']],
          'review_time' => 40],

          ['type' => 'mixed_review',
          'text' => [],
          'types' => [
                       ['type' => 'images', 'directory' => '/img/memory-task/faces/',
                        'prompt' => 'Remember these target images',
                        'targets' => ['2_targets_1.jpg', '2_targets_2.jpg', '2_targets_3.jpg']
                        ],
                       ['type' => 'words',
                        'prompt' => 'Remember these target words',
                        'targets' => ['garlic', 'wood', 'sugar', 'coal', 'clarinet',
                                      'trumpet', 'cinnamon', 'flute', 'kerosine',
                                      'vanilla', 'gasoline', 'violin']
                       ],
                       ['type' => 'stories',
                        'prompt' => 'Remember these target stories',
                        'targets' => ['Clothing makers in Europe and China have a problem.  The shape of the
                                      American male has changed.  American men now have slimmer waists and larger chests
                                      than they did in 1933 when the last measurements were taken.  Manufacturers will
                                      alter their designs and have promised to update their statistics more frequently.',
                                      'Michael Simpson earned a reputation for being stubborn after refusing to
                                      accept pay cheques.  Instead of cheques, he wanted his wages to be paid in
                                      cash.  He eventually collected ten thousand dollars in back pay.  His wife
                                      was pleased because she had been forced to cook on a camping stove, after
                                      services to their home were cut off eighteen months ago.'],
                       ]
          ],
          'choices' => [['color' => 'success', 'type' => 'images'],
                        ['color' => 'warning', 'type' => 'words'],
                        ['color' => 'danger', 'type' => 'stories']],
          'review_time' => 40],

          ['type' => 'text',
           'header' => '',
           'end_individual_section' => 'true',
           'text' => ['We will now ask you questions.',
                      '<strong>Everyone should be able to see the screen of the Reporter\'s laptop</strong>. You will
                       answer as a group, on The Reporter\'s laptop.',
                       'The questions will begin when The Reporter clicks "Next"'
                     ]],
         ['type' => 'text',
          'text' => ['Make sure all your group members
                      can see this screen. We are about to ask questions about
                      the words, stories and images you memorized.<br>
                      Click "Next" to continue.'
                    ]],
        [ 'type' => 'test_images',
          'selection_type' => 'select_one',
          'show_numbers' => 'true',
          'prompt' => 'Which of the following images is a "target" face?',
          'img' => '2_test_1.jpg',
          'correct' => [3]],

          [ 'type' => 'test_stories',
            'selection_type' => 'select_one',
            'prompt' => 'Clothing makers from which areas are mentioned in the story?',
            'choices' => ['Europe and Mexico',
                          'Europe and China',
                          'China and Mexico'],
            'correct' => [2]],

          ['type' => 'test_words',
           'selection_type' => 'select_all',
           'prompt' => 'Which of the following are target words',
           'choices' => ['oil', 'kerosine', 'priest'],
           'correct' => [2]],

         ['type' => 'test_words',
           'selection_type' => 'select_all',
           'prompt' => 'Which of the following are target words',
           'choices' => ['salt', 'basement', 'piano'],
           'correct' => []],

        ['type' => 'test_images',
          'selection_type' => 'select_one',
          'show_numbers' => 'true',
          'prompt' => 'Which of the following images is a "target" image?',
          'img' => '2_test_2.jpg',
          'correct' => [1]],

          [ 'type' => 'test_stories',
            'selection_type' => 'select_one',
            'prompt' => 'Michael had a reputation for being:',
            'choices' => ['Stubborn',
                          'Cheap',
                          'Rude'],
            'correct' => [1]],

        ['type' => 'test_images',
          'selection_type' => 'select_one',
          'show_numbers' => 'true',
          'prompt' => 'Which of the following images is a "target" image?',
          'img' => '2_test_3.jpg',
          'correct' => [2]],

          ['type' => 'test_words',
            'selection_type' => 'select_all',
            'prompt' => 'Which of the following are target words',
            'choices' => ['wood', 'harmonica', 'trumpet'],
            'correct' => [1, 3]],

          [ 'type' => 'test_stories',
            'selection_type' => 'select_one',
            'prompt' => 'How long ago did the services in Michael’s house get turned off?',
            'choices' => ['18 months ago',
                          '6 months ago',
                          '12 months ago'],
              'correct' => [1]],

        ['type' => 'test_images',
          'selection_type' => 'select_one',
          'show_numbers' => 'true',
          'prompt' => 'Which of the following images is a "target" image?',
          'img' => '2_test_4.jpg',
          'correct' => [3]],


        ['type' => 'test_images',
          'selection_type' => 'select_one',
          'show_numbers' => 'true',
          'prompt' => 'Which of the following images is a "target" image?',
          'img' => '2_test_5.jpg',
          'correct' => [1]],

          [ 'type' => 'test_stories',
            'selection_type' => 'select_one',
            'prompt' => 'What was Michael’s last name?',
            'choices' => ['Simpson',
                          'Sanderson',
                          'Sandford'],
            'correct' => [1]],

          [ 'type' => 'test_stories',
            'selection_type' => 'select_one',
            'prompt' => 'When did clothing makers last "measure the American male"?',
            'choices' => ['1933',
                          '1932',
                          '1923'],
            'correct' => [1]],

          ['type' => 'test_words',
            'selection_type' => 'select_all',
            'prompt' => 'Which of the following are target words',
            'choices' => ['garlic', 'violin', 'vanilla'],
            'correct' => [1, 2, 3]],

        ['type' => 'test_images',
          'selection_type' => 'select_one',
          'show_numbers' => 'true',
          'prompt' => 'Which of the following images is a "target" image?',
          'img' => '2_test_6.jpg',
          'correct' => [3]],

        ['type' => 'test_words',
          'selection_type' => 'select_all',
          'prompt' => 'Which of the following are target words',
          'choices' => ['chair', 'pepper', 'horn'],
          'correct' => []],

          [ 'type' => 'test_stories',
            'selection_type' => 'select_one',
            'prompt' => 'How much back pay did Michael receive?',
            'choices' => ['Ten thousand dollars',
                          'Eleven thousand dollars',
                          'Twelve thousand dollars'],
            'correct' => [1]],

          ['type' => 'test_words',
            'selection_type' => 'select_all',
            'prompt' => 'Which of the following are target words',
            'choices' => ['sand', 'sugar', 'drum'],
            'correct' => [2]],

        ['type' => 'test_images',
          'selection_type' => 'select_one',
          'show_numbers' => 'true',
          'prompt' => 'Which of the following images is a "target" image?',
          'img' => '2_test_7.jpg',
          'correct' => [2]],

          [ 'type' => 'test_stories',
            'selection_type' => 'select_one',
            'prompt' => 'How has the shape of the American male changed?',
            'choices' => ['Slimmer waists and larger arms',
                          'Larger waists and slimmer legs',
                          'Slimmer waists and larger chests'],
            'correct' => [3]],

        ['type' => 'test_images',
          'selection_type' => 'select_one',
          'show_numbers' => 'true',
          'prompt' => 'Which of the following images is a "target" image?',
          'img' => '2_test_8.jpg',
          'correct' => [2]],

          [ 'type' => 'test_stories',
            'selection_type' => 'select_one',
            'prompt' => 'How did Michael’s wife feel when he finally got paid?',
            'choices' => ['Relieved',
                          'Pleased',
                          'Grateful'],
            'correct' => [2]],

          ['type' => 'test_words',
            'selection_type' => 'select_all',
            'prompt' => 'Which of the following are target words',
            'choices' => ['flute', 'clarinet', 'piano'],
            'correct' => [2]],

            ['type' => 'test_words',
              'selection_type' => 'select_all',
              'prompt' => 'Which of the following are target words',
              'choices' => ['coal', 'electricity', 'lemon'],
              'correct' => [1]],
      ]// end blocks
    ], // end group_2


    'group_3' => [
      'test_name' => 'group_3',
      'task_type' => 'mixed',
      'type' => 'intro',
      'directory' => '/img/memory-task/bikes/',
      'blocks' => [
        ['type' => 'text',
         'header' => 'Memory Task',
         'wait_for_all' => 'true',
         'text' => ['Your group will have <strong>40</strong> seconds to memorize 6 images, 12 words and 2 short
                    stories.',
                    'Everyone should be in front of their own laptop. During the memorization
                     period, you have the option of looking at multiple types of stimuli
                    (e.g. <span class="text-danger">stories</span> AND
                    <span class="text-success">images</span>). Or,
                    you can divide the responsibilities of memorizing different things.',
                    '<strong>Take some time to discuss how you will approach this task.</strong>',
                    'You will receive some final instructions when each group member has clicked "Next".'
                   ]],
          ['type' => 'review_choice',
          'text' => ['Remember: you have 40 seconds, and there is a timer in
                      the top right of the screen.',
                     '<strong>You’re NOT allowed to write anything down.</strong>',
                     'If you want, you can always change the stimuli
                     you are memorizing by clicking on a different button..',
                     'The timer starts when everyone has clicked on one of the buttons.'],
          'choices' => [['color' => 'success', 'type' => 'images'],
                        ['color' => 'warning', 'type' => 'words'],
                        ['color' => 'danger', 'type' => 'stories']],
          'review_time' => 40],

          ['type' => 'mixed_review',
          'text' => [],
          'types' => [
                       ['type' => 'images', 'directory' => '/img/memory-task/bikes/',
                        'prompt' => 'Remember these target images',
                        'targets' => ['1_target_1.jpeg', '1_target_2.jpeg', '1_target_3.jpeg']
                        ],
                       ['type' => 'words',
                        'prompt' => 'Remember these target words',
                        'targets' => ['bluebird', 'chisel', 'eagle', 'screwdriver', 'crow',
                                      'wrench', 'pants', 'nails', 'shoes',
                                      'canary', 'skirt', 'blouse'],
                       ],
                       ['type' => 'stories',
                        'prompt' => 'Remember these target stories',
                        'targets' => ['At 7:35pm on Monday, Joe Garcia of San Francisco was watching television as he
                                      dressed to go out. A weather bulletin interrupted the program to warn of a
                                      thunderstorm. The announcer said the storm could bring hail and up to four
                                      inches of rain. Joe decided to stay home. He took off his coat and sat down
                                      to watch old movies.',
                                      'A Tokyo barmaid is suing a customer for two hundred and twenty thousand
                                      dollars. The complaint arose after the man attempted a goodnight kiss,
                                      causing the couple to fall down a flight of stairs.  The forty six year
                                      old woman suffered facial injuries in the fall but avoided the attempted
                                      kiss.'],
                       ]
          ],
          'choices' => [['color' => 'success', 'type' => 'images'],
                        ['color' => 'warning', 'type' => 'words'],
                        ['color' => 'danger', 'type' => 'stories']],
          'review_time' => 40],

          ['type' => 'text',
           'header' => '',
           'end_individual_section' => 'true',
           'text' => ['We will now ask you questions.',
                      '<strong>Everyone should be able to see the screen of the Reporter\'s laptop</strong>. You will
                       answer as a group, on The Reporter\'s laptop.',
                       'The questions will begin when The Reporter clicks "Next"'
                     ]],

         [ 'type' => 'test_images',
           'selection_type' => 'select_one',
           'show_numbers' => 'true',
           'prompt' => 'Which of the following images is a "target" image?',
           'img' => '1_test_1.jpeg',
           'correct' => [1]],

           [ 'type' => 'test_stories',
             'selection_type' => 'select_one',
             'prompt' => 'In which city was there bad weather predicted?',
             'choices' => ['San Jose',
                           'San Diego',
                           'San Francisco'],
             'correct' => [3]],

         ['type' => 'test_images',
           'selection_type' => 'select_one',
           'show_numbers' => 'true',
           'prompt' => 'Which of the following images is a "target" image?',
           'img' => '1_test_2.jpeg',
           'correct' => [2]],

           ['type' => 'test_words',
            'selection_type' => 'select_all',
            'prompt' => 'Which of the following are target words',
            'choices' => ['child', 'bluebird', 'chapel'],
            'correct' => [2]],

            [ 'type' => 'test_stories',
              'selection_type' => 'select_one',
              'prompt' => 'On which day was bad weather predicted?',
              'choices' => ['Monday',
                            'Sunday',
                            'Tuesday'],
              'correct' => [1]],

         ['type' => 'test_images',
           'selection_type' => 'select_one',
           'show_numbers' => 'true',
           'prompt' => 'Which of the following images is a "target" image?',
           'img' => '1_test_3.jpeg',
           'correct' => [1]],

           ['type' => 'test_words',
             'selection_type' => 'select_all',
             'prompt' => 'Which of the following are target words',
             'choices' => ['canary', 'socks', 'robin'],
             'correct' => [1]],
           ['type' => 'test_words',
             'selection_type' => 'select_all',
             'prompt' => 'Which of the following are target words',
             'choices' => ['hammer', 'skirt', 'apple'],
             'correct' => [2]],

         ['type' => 'test_images',
           'selection_type' => 'select_one',
           'show_numbers' => 'true',
           'prompt' => 'Which of the following images is a "target" image?',
           'img' => '1_test_4.jpeg',
           'correct' => [2]],

           [ 'type' => 'test_stories',
             'selection_type' => 'select_one',
             'prompt' => 'How much is the Tokyo barmaid suing for?',
             'choices' => ['$220,000',
                           '$200,000',
                           '$20,000'],
               'correct' => [1]],

           ['type' => 'test_words',
             'selection_type' => 'select_all',
             'prompt' => 'Which of the following are target words',
             'choices' => ['shirt', 'saw', 'wren'],
             'correct' => []],

           [ 'type' => 'test_stories',
             'selection_type' => 'select_one',
             'prompt' => 'How much rain was the storm predicted to bring?',
             'choices' => ['At least 4 inches',
                           'Up to 4 inches',
                           'More than 4 inches'],
             'correct' => [2]],

           ['type' => 'test_words',
             'selection_type' => 'select_all',
             'prompt' => 'Which of the following are target words',
             'choices' => ['chisel', 'screwdriver', 'sparrow'],
             'correct' => [1, 2]],

         ['type' => 'test_images',
           'selection_type' => 'select_one',
           'show_numbers' => 'true',
           'prompt' => 'Which of the following images is a "target" image?',
           'img' => '1_test_5.jpeg',
           'correct' => [3]],

           [ 'type' => 'test_stories',
             'selection_type' => 'select_one',
             'prompt' => 'How old was the Tokyo barmaid?',
             'choices' => ['36',
                           '46',
                           '26'],
             'correct' => [2]],

         ['type' => 'test_images',
           'selection_type' => 'select_one',
           'show_numbers' => 'true',
           'prompt' => 'Which of the following images is a "target" image?',
           'img' => '1_test_6.jpeg',
           'correct' => [3]],

           ['type' => 'test_words',
             'selection_type' => 'select_all',
             'prompt' => 'Which of the following are target words',
             'choices' => ['silver', 'nails', 'eagle'],
             'correct' => [2, 3]],

             [ 'type' => 'test_stories',
               'selection_type' => 'select_one',
               'prompt' => 'Who is the Tokyo barmaid suing?',
               'choices' => ['Her manager',
                             'Her employer',
                             'Her customer'],
               'correct' => [3]],

         ['type' => 'test_images',
           'selection_type' => 'select_one',
           'show_numbers' => 'true',
           'prompt' => 'Which of the following images is a "target" image?',
           'img' => '1_test_7.jpeg',
           'correct' => [2]],

           ['type' => 'test_words',
             'selection_type' => 'select_all',
             'prompt' => 'Which of the following are target words',
             'choices' => ['crow', 'rock', 'rook'],
             'correct' => [1]],

             [ 'type' => 'test_stories',
               'selection_type' => 'select_one',
               'prompt' => 'What did Joe do when he heard the news about bad weather?',
               'choices' => ['Call in sick',
                             'Watch old movies',
                             'Go back to watching TV programs'],
               'correct' => [2]],

         ['type' => 'test_images',
           'selection_type' => 'select_one',
           'show_numbers' => 'true',
           'prompt' => 'Which of the following images is a "target" image?',
           'img' => '1_test_8.jpeg',
           'correct' => [3]],

           [ 'type' => 'test_stories',
             'selection_type' => 'select_one',
             'prompt' => 'How did Joe find about the weather?',
             'choices' => ['He was watching a news program',
                           'A weather report interrupted the program he was watching',
                           'He saw a weather bulletin between shows'],
             'correct' => [2]],

           ['type' => 'test_words',
             'selection_type' => 'select_all',
             'prompt' => 'Which of the following are target words',
             'choices' => ['blouse', 'pants', 'wrench'],
             'correct' => [1, 2, 3]],
      ]// end blocks
    ], // end group_3

    'group_4' => [
      'test_name' => 'group_4',
      'task_type' => 'mixed',
      'type' => 'intro',
      'directory' => '/img/memory-task/bodies/',
      'blocks' => [
        ['type' => 'text',
         'header' => 'Memory Task',
         'wait_for_all' => 'true',
         'text' => ['Your group will have <strong>40</strong> seconds to memorize 6 images, 12 words and 2 short
                    stories.',
                    'Everyone should be in front of their own laptop. During the memorization
                     period, you have the option of looking at multiple types of stimuli
                    (e.g. <span class="text-danger">stories</span> AND
                    <span class="text-success">images</span>). Or,
                    you can divide the responsibilities of memorizing different things.',
                    '<strong>Take some time to discuss how you will approach this task.</strong>',
                    'You will receive some final instructions when each group member has clicked "Next".'
                   ]],
          ['type' => 'review_choice',
          'text' => ['Remember: you have 40 seconds, and there is a timer in
                      the top right of the screen.',
                     '<strong>You’re NOT allowed to write anything down.</strong>',
                     'If you want, you can always change the stimuli
                     you are memorizing by clicking on a different button..',
                     'The timer starts when everyone has clicked on one of the buttons.'],
          'choices' => [['color' => 'success', 'type' => 'images'],
                        ['color' => 'warning', 'type' => 'words'],
                        ['color' => 'danger', 'type' => 'stories']],
          'review_time' => 40],

          ['type' => 'mixed_review',
          'text' => [],
          'types' => [
                       ['type' => 'images', 'directory' => '/img/memory-task/bodies/',
                        'prompt' => 'Remember these target images',
                        'targets' => ['2_target_1.jpeg', '2_target_2.jpeg', '2_target_3.jpeg']
                        ],
                       ['type' => 'words',
                        'prompt' => 'Remember these target words',
                        'targets' => ['tennis', 'professor', 'golf', 'dentist', 'lettuce',
                                      'bean', 'basketball', 'corn', 'teacher',
                                      'engineer', 'soccer', 'potato'],
                       ],
                       ['type' => 'stories',
                        'prompt' => 'Remember these target stories',
                        'targets' => ['The watermelon eating world record was set in July 2015 at the Plainview
                                       Community Picnic in California. The record holder is Jim "Bison" Bingham,
                                       a native of Georgia who grew up on a watermelon farm with his eight brothers
                                       and sisters. He said that growing up in a big family sharpened his eating
                                       skills as "if you didn’t eat quickly, you missed out".',
                                      'Adrian Li recently bought a new cat. He wanted a small dog, like a Scottish
                                      Terrier, but his landlord would not allow it. He found his new cat, Luna, on
                                      the Blue Cross website. Her previous owner was a 17 year old girl who was going
                                      off to Jefferson College to study veterinary technology.'],
                       ]
          ],
          'choices' => [['color' => 'success', 'type' => 'images'],
                        ['color' => 'warning', 'type' => 'words'],
                        ['color' => 'danger', 'type' => 'stories']],
          'review_time' => 40],

          ['type' => 'text',
           'header' => '',
           'end_individual_section' => 'true',
           'text' => ['We will now ask you questions.',
                      '<strong>Everyone should be able to see the screen of the Reporter\'s laptop</strong>. You will
                       answer as a group, on The Reporter\'s laptop.',
                       'The questions will begin when The Reporter clicks "Next"'
                     ]],

         [ 'type' => 'test_images',
           'selection_type' => 'select_one',
           'show_numbers' => 'false',
           'prompt' => 'Which of the following images is a "target" image?',
           'img' => '2_test_1.jpeg',
           'correct' => [3]],

           [ 'type' => 'test_stories',
             'selection_type' => 'select_one',
             'prompt' => 'In which state was the watermelon eating world record set?',
             'choices' => ['Georgia',
                           'California',
                           'Florida'],
             'correct' => [2]],

         ['type' => 'test_images',
           'selection_type' => 'select_one',
           'show_numbers' => 'false',
           'prompt' => 'Which of the following images is a "target" image?',
           'img' => '2_test_2.jpeg',
           'correct' => [1]],

           ['type' => 'test_words',
            'selection_type' => 'select_all',
            'prompt' => 'Which of the following are target words',
            'choices' => ['soccer', 'potato', 'football'],
            'correct' => [1, 2]],

            [ 'type' => 'test_stories',
              'selection_type' => 'select_one',
              'prompt' => 'What is the nickname of the watermelon eating  record holder?',
              'choices' => ['Bison',
                            'Buffalo',
                            'Bull'],
              'correct' => [1]],

         ['type' => 'test_images',
           'selection_type' => 'select_one',
           'show_numbers' => 'false',
           'prompt' => 'Which of the following images is a "target" image?',
           'img' => '2_test_3.jpeg',
           'correct' => [1]],

           ['type' => 'test_words',
             'selection_type' => 'select_all',
             'prompt' => 'Which of the following are target words',
             'choices' => ['glove', 'lawyer', 'snake'],
             'correct' => []],

           ['type' => 'test_words',
             'selection_type' => 'select_all',
             'prompt' => 'Which of the following are target words',
             'choices' => ['spinach', 'professor', 'lettuce'],
             'correct' => [2, 3]],

         ['type' => 'test_images',
           'selection_type' => 'select_one',
           'show_numbers' => 'false',
           'prompt' => 'Which of the following images is a "target" image?',
           'img' => '2_test_4.jpeg',
           'correct' => [2]],

         [ 'type' => 'test_stories',
           'selection_type' => 'select_one',
           'prompt' => 'What is the name of Adrian’s new cat?',
           'choices' => ['Lily',
                         'Lucy',
                         'Luna'],
           'correct' => [3]],

           ['type' => 'test_words',
             'selection_type' => 'select_all',
             'prompt' => 'Which of the following are target words',
             'choices' => ['engineer', 'corn', 'tulip'],
             'correct' => [1, 2]],

           [ 'type' => 'test_stories',
             'selection_type' => 'select_one',
             'prompt' => 'What is Adrian’s last name?',
             'choices' => ['Lim',
                           'Li',
                           'Xi'],
             'correct' => [2]],


           ['type' => 'test_words',
             'selection_type' => 'select_all',
             'prompt' => 'Which of the following are target words',
             'choices' => ['spider', 'doctor', 'tennis'],
             'correct' => [3]],

         ['type' => 'test_images',
           'selection_type' => 'select_one',
           'show_numbers' => 'false',
           'prompt' => 'Which of the following images is a "target" image?',
           'img' => '2_test_5.jpeg',
           'correct' => [2]],

           [ 'type' => 'test_stories',
             'selection_type' => 'select_one',
             'prompt' => 'Jim said that, in his household, if you didn’t eat quickly, you:',
             'choices' => ['"went hungry"',
                           '"missed out"',
                           '"got nothing"'],
             'correct' => [2]],

         ['type' => 'test_images',
           'selection_type' => 'select_one',
           'show_numbers' => 'false',
           'prompt' => 'Which of the following images is a "target" image?',
           'img' => '2_test_6.jpeg',
           'correct' => [1]],

         ['type' => 'test_words',
           'selection_type' => 'select_all',
           'prompt' => 'Which of the following are target words',
           'choices' => ['golf', 'teacher', 'water'],
           'correct' => [1, 2]],

         [ 'type' => 'test_stories',
           'selection_type' => 'select_one',
           'prompt' => 'How many brothers and sisters does Jim Bingham have?',
           'choices' => ['6',
                         '7',
                         '8'],
             'correct' => [3]],

         ['type' => 'test_images',
           'selection_type' => 'select_one',
           'show_numbers' => 'false',
           'prompt' => 'Which of the following images is a "target" image?',
           'img' => '2_test_7.jpeg',
           'correct' => [3]],

           ['type' => 'test_words',
             'selection_type' => 'select_all',
             'prompt' => 'Which of the following are target words',
             'choices' => ['basketball', 'dentist', 'submarine'],
             'correct' => [1, 2]],

             [ 'type' => 'test_stories',
               'selection_type' => 'select_one',
               'prompt' => 'What school is the cat’s previous owner attending?',
               'choices' => ['Jackson College',
                             'Jefferson College',
                             'Jefferson State University'],
               'correct' => [2]],

         ['type' => 'test_images',
           'selection_type' => 'select_one',
           'show_numbers' => 'false',
           'prompt' => 'Which of the following images is a "target" image?',
           'img' => '2_test_8.jpeg',
           'correct' => [2]],

         [ 'type' => 'test_stories',
           'selection_type' => 'select_one',
           'prompt' => 'When was the watermelon eating world record set?',
           'choices' => ['July 2015',
                         'June 2015',
                         'June 2005'],
           'correct' => [1]],

           ['type' => 'test_words',
             'selection_type' => 'select_all',
             'prompt' => 'Which of the following are target words',
             'choices' => ['carrot', 'baseball', 'bean'],
             'correct' => [3]],
      ]// end blocks
    ], // end group_4

  ]; // End memoryTests


  private static $avaialbleParams = ['hasIndividuals' => ['true', 'false'], 'hasGroup' => ['false', 'true'],
                                     'test' => ['images_instructions', 'images_short_intro',
                                                'faces_1', 'faces_2', 'cars_1', 'bikes_1',
                                                'words_instructions', 'words_short_intro',
                                                'words_1', 'words_2', 'words_3', 'words_4',
                                                'story_instructions', 'story_short_intro',
                                                'story_1', 'story_2', 'story_3', 'story_4',
                                                'group_1_intro', 'group_1_instructions', 'group_1',
                                                'group_2_intro', 'group_2_instructions', 'group_2',
                                                'group_3_intro', 'group_3_instructions', 'group_3',
                                                'group_4_intro', 'group_4_instructions', 'group_4',
                                                'results']];

  public function getTests() {
    return $this->prompts;
  }

  public function setTests($tests) {
    $this->memoryTests = $tests;
  }

  public function getRandomTest() {
    return $this->memoryTests[array_rand($this->memoryTests)];
  }

  public function getTest($test) {
    return $this->memoryTests[$test];
  }

  public function getImagesForPreloader($testName)
  {
    // If this isn't an images task, just return an empty array
    if($this->memoryTests[$testName]['task_type'] != 'images') return [];

    $imgs = [];
    foreach ($this->memoryTests[$testName]['blocks'] as $key => $block) {
      if($block['type'] == 'review') {
        foreach ($block['targets'] as $target) {
          $imgs[] = $this->memoryTests[$testName]['directory'].$target;
        }
      }
      else if($block['type'] == 'test'){
        $imgs[] = $this->memoryTests[$testName]['directory'].$block['img'];
      }

    }
    return $imgs;
  }

  public static function getAvailableParams()
  {
    return Self::$avaialbleParams;
  }

  public function getMemoryBlock($testName, $block){
    return $this->memoryTests[$testName]['blocks'][$block];
  }

}
