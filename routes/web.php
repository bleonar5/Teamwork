<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/get-group-task', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@getTask',
	'roles' => ['Participant', 'Group'] // Only a logged in user can view this page
]);

Route::get('/group-experiment-end', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@endExperiment',
	'roles' => ['Group'] // Only a logged in user can view this page
]);


Route::get('/get-individual-task', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@getTask',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/individual-task-results', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@showTaskResults',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/end-individual-task', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@endTask',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/end-group-task', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@endTask',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/group-task-results', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@showTaskResults',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/waiting', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@waiting',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);


Route::get('/clear-storage', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@clearStorage',
	'roles' => ['Participant','group'] // Only a logged in user can view this page
]);

Route::get('/in-room', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'WaitingRoomController@userInRoom',
	'roles' => ['Participant','group'] // Only a logged in user can view this page
]);

Route::post('/set-task-end', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@setTaskEnd',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::post('/mark-individual-response-complete', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'AjaxController@markIndividualResponseComplete',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::post('/mark-individual-ready', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'AjaxController@markIndividualReadyForGroup',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/check-group-ready', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'AjaxController@checkGroupReady',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/group-login-allowed', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'AjaxController@groupLoginAllowed',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/check-task-completion', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'AjaxController@checkTaskCompletion',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::post('/store-task-data', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'AjaxController@storeTaskData',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/participant-experiment-end', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@endExperiment',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/study-consent', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@studyConsent',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/no-study-consent', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@noStudyConsent',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/study-intro', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@studyIntro',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/choose-reporter', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@chooseReporter',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/reporter-chosen', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@reporterChosen',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/choose-reporter/{choice}', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@setReporter',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/teammates', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@getTeammates',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/survey', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@survey',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::post('/survey', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@saveSurvey',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::post('/teammates', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@saveTeammates',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/check-for-confirmation-code', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@checkForConfirmationCode',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/study-feedback', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@studyFeedback',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::post('/study-feedback', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@postStudyFeedback',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/study-conclusion', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@studyConclusion',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/team-role-intro', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@teamRoleIntro',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/team-role', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@teamRole',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::post('/team-role', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@saveTeamRole',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/team-role-end', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@teamRoleEnd',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/big-five-intro', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@bigFiveIntro',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/big-five', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@bigFive',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::post('/big-five', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@saveBigFive',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/big-five-end', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@bigFiveEnd',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/memory-individual-intro', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@memoryIntro',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/memory-individual', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@memory',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::post('/memory-individual', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@saveMemory',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/memory-individual-results', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@displayMemoryTaskResults',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/memory-group', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@memory',
	'roles' => ['Group', 'Participant'] // Only a logged in user can view this page
]);

Route::get('/memory-group-intro', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@memoryGroupIntro',
	'roles' => ['Group', 'Participant'] // Only a logged in user can view this page
]);

Route::post('/memory-group', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@saveMemory',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::post('/record-mem-review-selection', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'AjaxController@saveMemoryReviewSelection',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/rmet-individual-intro', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@eyesIntro',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/rmet-individual', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@eyes',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::post('/rmet-individual', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@saveEyes',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/unscramble-words-intro', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@unscrambleWordsIntro',
	'roles' => ['Group'] // Only a logged in user can view this page
]);

Route::get('/unscramble-words', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@unscrambleWords',
	'roles' => ['Group'] // Only a logged in user can view this page
]);

Route::post('/unscramble-words', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@scoreUnscrambleWords',
	'roles' => ['Group'] // Only a logged in user can view this page
]);

Route::get('/brainstorming-individual-intro', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@brainstormingIntro',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/brainstorming-individual', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@brainstorming',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::post('/brainstorming-individual', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@scoreBrainstorming',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/optimization-individual-intro', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@optimizationIntro',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/optimization-individual-alt-intro', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@optimizationAltIntro',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/optimization-individual', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@optimization',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::post('/optimization-individual', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@saveOptimizationGuess',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::post('/optimization-individual-final', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@saveOptimizationFinalGuess',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::post('/optimization-group-final', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@saveOptimizationFinalGuess',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/shapes-individual-intro', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@shapesIntro',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/shapes-individual', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@shapesIndividual',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::post('/shapes-individual', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@saveShapesIndividual',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/shapes-individual-result', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@shapesIndividualResult',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/shapes-group-intro', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@shapesGroupIntro',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/shapes-group', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@shapesGroup',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::post('/shapes-group', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@saveShapesGroup',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/end-shapes-task', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@endShapesGroup',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/optimization-group-intro', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@optimizationIntro',
	'roles' => ['Group', 'Participant'] // Only a logged in user can view this page
]);

Route::get('/optimization-group', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@optimization',
	'roles' => ['Group', 'Participant'] // Only a logged in user can view this page
]);

Route::post('/optimization-group', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@saveOptimization',
	'roles' => ['Group', 'Participant'] // Only a logged in user can view this page
]);

Route::get('/get-prob-val', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'AjaxController@getProbVal',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::post('/crypto-proceed', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@nextCryptoPage',
	'roles' => ['Participant','Group'] // Only a logged in user can view this page
]);

Route::post('/task-complete', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@taskComplete',
	'roles' => ['Participant','Group'] // Only a logged in user can view this page
]);

Route::post('/rule-broken', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@ruleBroken',
	'roles' => ['Participant','Group'] // Only a logged in user can view this page
]);

Route::get('/cryptography-intro', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@cryptographyIntro',
	'roles' => ['Group', 'Participant'] // Only a logged in user can view this page
]);

Route::get('/cryptography-individual-intro', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@cryptographyIntro',
	'roles' => ['Participant'] // Only a logged in user can view this page
]);

Route::get('/cryptography', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@cryptography',
	'roles' => ['Group', 'Participant'] // Only a logged in user can view this page
]);

Route::get('/cryptography-individual', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@cryptographyIndividual',
	'roles' => ['Group', 'Participant'] // Only a logged in user can view this page
]);

Route::post('/cryptography', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@saveCryptographyResponse',
	'roles' => ['Group', 'Participant'] // Only a logged in user can view this page
]);

Route::get('/cryptography-test', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@saveCryptographyResponse',
	'roles' => ['Group', 'Participant'] // Only a logged in user can view this page
]);

Route::post('/cryptography-end', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'GroupTaskController@endCryptographyTask',
	'roles' => ['Group', 'Participant'] // Only a logged in user can view this page
]);

Route::post('/cryptography-individual-end', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'IndividualTaskController@endCryptographyTask',
	'roles' => ['Group', 'Participant'] // Only a logged in user can view this page
]);

Route::get('/waiting-room', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'WaitingRoomController@getWaitingRoom',
	'roles' => ['Group', 'Participant'] // Only a logged in user can view this page
]);

Route::get('/test-assignment', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'WaitingRoomController@makeGroups',
	'roles' => ['Group', 'Participant'] // Only a logged in user can view this page
]);

Route::post('/leave-room', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'WaitingRoomController@leaveWaitingRoom',
	'roles' => ['Group', 'Participant'] // Only a logged in user can view this page
]);

Route::post('/still-here', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'WaitingRoomController@stillHere',
	'roles' => ['Group', 'Participant'] // Only a logged in user can view this page
]);

Route::get('/task-room/{task}', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'TaskRoomController@taskRoom',
	'roles' => ['Group', 'Participant'] // Only a logged in user can view this page
]);

Route::get('/participant-login', [
	'uses' => 'LoginController@participantLogin',
]);

Route::get('/participant-login/{package}', [
	'uses' => 'LoginController@participantPackageLogin',
]);

Route::post('/participant-login', [
	'uses' => 'LoginController@postParticipantLogin',
]);

Route::get('/individual-login', [
	'uses' => 'LoginController@individualLogin',
]);

Route::get('/individual-login/{package}', [
	'uses' => 'LoginController@individualPackageLogin',
]);

Route::get('/retry-individual-tasks', [
	'uses' => 'LoginController@retryIndividual',
]);

Route::post('/individual-login', [
	'uses' => 'LoginController@postIndividualLogin',
]);

Route::get('/group-login', [
	'uses' => 'LoginController@groupLogin',
]);

Route::post('/group-login', [
	'uses' => 'LoginController@postGroupLogin',
]);

Route::get('/group-create', [
	'uses' => 'LoginController@groupCreateLogin',
]);

Route::post('/group-create', [
	'uses' => 'LoginController@postGroupCreateLogin',
]);

Route::get('/group-add-participants', [
	'uses' => 'LoginController@groupAddParticipants',
]);

Route::post('/group-add-participants', [
	'uses' => 'LoginController@postGroupAddParticipants',
]);

Route::get('/admin', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'AdminController@getAdminHome',
	'roles' => ['Researcher'] // Only a logged in user can view this page
]);

Route::get('/admin/users', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'AdminController@getUsers',
	'roles' => ['Researcher'] // Only a logged in user can view this page
]);

Route::get('/admin/individual-responses', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'AdminController@getIndividualTaskResponses',
	'roles' => ['Researcher'] // Only a logged in user can view this page
]);

Route::get('/admin/group-responses', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'AdminController@getGroupTaskResponses',
	'roles' => ['Researcher'] // Only a logged in user can view this page
]);

Route::get('/download-individual-csv', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'AdminController@getIndividualCSV',
	'roles' => ['Researcher'] // Only a logged in user can view this page
]);

Route::get('/download-group-csv', [
	'middleware' => ['auth', 'roles'], // A 'roles' middleware must be specified
	'uses' => 'AdminController@getGroupCSV',
	'roles' => ['Researcher'] // Only a logged in user can view this page
]);

// Testing Routes
Route::get('/responses-test', [
  'uses' => 'IndividualTaskController@responsesTest',
]);

Route::get('/add-scores-to-users', [
  'uses' => 'IndividualTaskController@addScoresToUsers',
]);

Route::get('/optimization-test', [
  'uses' => 'AjaxController@testOptimization',
]);

Route::get('/memory-test', [
  'uses' => 'IndividualTaskController@testMemory',
]);

Route::get('/test-mem-results/{id}', [
  'uses' => 'IndividualTaskController@testMemResults',
]);

Route::get('/test-eligibility/{id}', [
  'uses' => 'IndividualTaskController@testEligibility',
]);

Route::get('/score-test/{groupId}', [
  'uses' => 'IndividualTaskController@calculateScore',
]);

Route::get('/cryptography-save-test', [
  'uses' => 'GroupTaskController@testCryptograhySave',
]);
