<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

use App\Task;

use Illuminate\Http\Request;

////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                              Web Route
////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::group(['middleware' => ['web']], function () {
    //------------------------------------------------------------------------------------------------
    //                                          Page Views
    //------------------------------------------------------------------------------------------------
    Route::get ('/',                         function () {return view('home');});
    Route::get ('/about',                    function () {return view('about');});
    Route::get ('/tos',                      function () {return view('tos');});
    Route::get ('/job/import',               function () {return view('import-job');});
    Route::get ('/analyze',                  function () {return view('analyze');});
    Route::get ('/concepts/required/{id}',   function ($id) {return view('req-concept', ['id'=>$id]);});
    Route::get ('/manage',                   function() {return view('manage');});
    Route::get ('/candidatesearch/{jobid}',  function ($jobid) {return view('job-dashboard', ['jobid'=>$jobid]);});

    Route::get ('/jobsearch',                       'MainController@jobSearch');
    Route::post('/parse',                           'MainController@parse');
    Route::get ('/user/{id}',                       'InsightController@userDashboard');
    Route::get ('/analyze-jobs/{id}',               'InsightController@analyzeJob');
    Route::get ('/auth',                            'MainController@auth');
    Route::get ('/profile',                         'MainController@profile');

    //------------------------------------------------------------------------------------------------
    //                                       Concept Insights
    //------------------------------------------------------------------------------------------------
    Route::put ('/ci/candidates',                   'InsightController@createCandidate');
    Route::post('/ci/candidates',                   'InsightController@updateCandidate');
    Route::get ('/ci/candidates',                   'InsightController@listCandidates');
    Route::get ('/ci/candidates/{id}',              'InsightController@getCandidate');
    Route::get ('/ci/candidates/{id}/annotations',  'InsightController@getCandidateAnnotations');
    Route::delete('/ci/candidates/{id}',            'InsightController@deleteCandidate');
    Route::get ('/ci/semantic_search/candidate/{candidate}/{limit}', 'InsightController@semanticSearchByCandidate');

    Route::put ('/ci/jobs',                         'InsightController@createJob');
    Route::post('/ci/jobs',                         'InsightController@createJob');
    Route::get ('/ci/jobs',                         'InsightController@listJobs');
    Route::get ('/ci/jobs/{id}',                    'InsightController@getJob');
    Route::get ('/ci/jobs/{id}/annotations',        'InsightController@getJobAnnotations');
    Route::delete('/ci/jobs/{id}',                  'InsightController@deleteJob');
    Route::get ('/ci/semantic_search/job/{job}/{limit}', 'InsightController@semanticSearchByJob');
    Route::get ('/ci/graph_search/{id}',            'InsightController@graphSearchById');

    //------------------------------------------------------------------------------------------------
    //                                       Personality Insights
    //------------------------------------------------------------------------------------------------
    Route::post('/pi',                              'PersonalityInsightController@pi');
    Route::get ('/pi',                              'PersonalityInsightController@piTest');

    //------------------------------------------------------------------------------------------------
    //                                         for testing
    //------------------------------------------------------------------------------------------------
    Route::get('/corpus',                           'InsightController@getCorpusList');
    Route::get('/public_corpus',                    'InsightController@publicCorpusList');
    Route::get('/corpus/{id}/delete',               'InsightController@deleteCorpus');
    Route::get('/ci/candidates/{id}/delete',        'InsightController@deleteCandidate');
    Route::get('/test_parse',                       'MainController@testParse');
    Route::get('/env',                              'MainController@testEnv');
});

////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                              API Route
////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::group(['middleware' => ['api']], function ()
{
    //------------------------------------------------------------------------------------------------
    //                                              DB
    //------------------------------------------------------------------------------------------------
    Route::match(['get' ],       '/db/candidates',                  'DbController@candidatesList');
    Route::match(['post'],       '/db/candidates',                  'DbController@candidatesInsert');
    Route::match(['get','post'], '/db/candidates/by-name/{name}',   'DbController@candidatesByName');
    Route::match(['delete'],     '/db/candidates/{id}',             'DbController@deleteCandidatesById');

    Route::match(['get' ],       '/db/jobs',                        'DbController@jobsList');
    Route::match(['post'],       '/db/jobs',                        'DbController@jobsInsert');
    Route::match(['get'],        '/db/jobs/by-code/{code}',         'DbController@jobsByCode');
    Route::match(['get','post'], '/db/jobs/{id}',                   'DbController@jobsById');
    Route::match(['delete'],     '/db/jobs/{id}',                   'DbController@deleteJobsById');
});
