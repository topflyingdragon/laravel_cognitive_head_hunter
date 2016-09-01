<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class InsightController extends Controller
{
    /* GuzzleHttp client */
    protected $client;
    /* Concept Insight credentials */
    protected $ci_credentials;
    /*******************************************************************************************************************
     *                                                  Construct
     */
    public function __construct()
    {
        //get concept insight parameters
        $services = json_decode(getenv('VCAP_SERVICES'), true);
        if($services){
            $this->ci_credentials = $services['concept_insights'][0]['credentials'];
        }else{
            $this->ci_credentials = array(
                'url' => 'https://gateway.watsonplatform.net/concept-insights/api',
                'password' => 'OH5BVDVQBnEi',
                'username' => 'a44a8d0f-97b5-4158-9d27-16bf67234931'
            );
        }
        //init client
        $this->client = new Client(array(
            'timeout' => 60,
            'verify' => false
        ));
    }

    /******************************************************************************************************************
     *                                                  Get Auth
     */
    private function getAuth()
    {
        return [
            $this->ci_credentials['username'],
            $this->ci_credentials['password']
        ];
    }

    /******************************************************************************************************************
     *                                              function : request
     * @param $method  [GET], [PUT], [POST], [DELETE]
     * @param $uri
     * @param bool|false $json
     * @return bool|mixed
     */

    private function request($method, $uri, $json=false)
    {
        $url = $this->ci_credentials['url'].$uri;
        $params = ['auth' => $this->getAuth()];
        if($json !== false){
            $params = array_merge($params, ['json' => $json]);
        }
        try{
            $res = $this->client->request($method, $url, $params);
            if($res->getStatusCode()===200){
                $contents = $res->getBody()->getContents();
                return json_decode($contents);
            }else{
                return false;
            }
        }catch (ClientException $ex){
            return array(
                'error'=>$ex->getCode(),
                'message' => $ex->getMessage()
            );
        }
    }
    /*******************************************************************************************************************
     *                                              Get AccountId
     */
    private function getAccountId(Request $request)
    {
        if($request->session()->get('account_id')!=null){
            return $request->session()->get('account_id');
        }else{
            $data = $this->request('GET', '/v2/accounts');
            if(count($data)>0){
                $accountId = $data->accounts[0]->account_id;
                $request->session()->put('account_id', $accountId);
                $request->session()->save();
                return $accountId;
            }else{
                return false;
            }
        }
    }
    /*******************************************************************************************************************
     *                                             Create Corpus
     * @param $params
     */
    private function createCorpus($request, $params)
    {
        $accountId = $this->getAccountId($request);
        $req = array(
            'id' => $params['corpus'],
            'access' =>'private',
            'users' => [
                array(
                    'uid' => $accountId,
                    'permission' => 'ReadWriteAdmin'
                )
            ],
            "public_fields"=> [
                "string"
            ],
        );
        $this->request('PUT', '/v2'.$params['corpus'], $req);
    }
    /*******************************************************************************************************************
     *                                          Get Current Corpus
     */
    private function getCorpus(Request $request)
    {
        $corpus = $request->session()->get('corpus');
        if($corpus != null){
            return $corpus;
        }else{
            $accountId = $this->getAccountId($request);
            $corpus = array(
                'jobs'       => "/corpora/" . $accountId . "/jobs",
                'candidates' => "/corpora/" . $accountId . "/candidates"
            );
            $request->session()->put('corpus', $corpus);

            // create both corpus
            $params = array(
                'user' => $this->ci_credentials['username'],
                'corpus' => $corpus['jobs'],
                'access' => "private",
                'users' => array(
                    'uid' => $this->ci_credentials['username'],
                    'permission' => "ReadWriteAdmin"
                )
            );
            $this->createCorpus($request, $params);
            $params['corpus'] = $corpus['candidates'];
            $this->createCorpus($request, $params);
            return $corpus;
        }
    }
    /*******************************************************************************************************************
     *                                        List of Candidates (Api)
     * GET /ci/candidates
     *
     * @return bool|mixed
     */
    public function listCandidates(Request $request)
    {
        $corpus = $this->getCorpus($request);
        $data = $this->request('GET', '/v2'.$corpus['candidates'].'/documents');
        return $this->response_json($data);
    }
    /*******************************************************************************************************************
     *                                       Get Candidate by id
     * GET /ci/candidates/{id}
     *
     * @return bool|mixed
     */
    public function getCandidate(Request $request, $id)
    {
        $corpus = $this->getCorpus($request);
        $document_id = $corpus['candidates'].'/documents/'.$id;
        $data = $this->request('GET', '/v2'.$document_id);
        if($data){
            $annotations = $this->request('GET', '/v2'.$document_id.'/annotations');
            $data->annotations = $annotations->annotations;
        }
        return $this->response_json($data);
    }

    /*******************************************************************************************************************
     *                                        Get Candidate Annotations (Api)
     * GET /ci/candidates/{id}/annotations
     *
     * @param Request $request
     * @param $id
     * @return bool|mixed
     */
    public function getCandidateAnnotations(Request $request, $id)
    {
        $corpus = $this->getCorpus($request);
        $document_id = $corpus['candidates'].'/documents/'.$id;
        $annotations = $this->request('GET', '/v2'.$document_id.'/annotations');

        return $this->response_json($annotations);
    }
    /*******************************************************************************************************************
     *                                         Create Doducment (Api)
     * PUT /ci/candidates
     *
     * @param Request $request
     */
    public function createCandidate(Request $request)
    {
        $corpus = $this->getCorpus($request);
        $url = '/v2'.$corpus['candidates'].'/documents/'.$request->id;
        $document = array(
            'id' => $request->id,
			'label' => $request->fullName,
			'parts' =>[
				array(
                    'data' => $request->data,
					'name' => "Candidate",
					'content-type' => "text/plain"
                )
			],
            "user_fields" => array(
                'candidatePictureUrl'       => $request->pictureUrl,
                'candidatePublicProfileUrl' => $request->publicProfileUrl,
                'candidateEmailAddress'     => $request->emailAddress,
                'candidateHeadline'         => $request->headline
            )
        );
        $this->request('PUT', $url, $document);
    }
    /*******************************************************************************************************************
     *                                         Update Candidate (Api)
     * POST /ci/candidates
     *
     * @param Request $request
     */
    public function updateCandidate(Request $request)
    {
        $corpus = $this->getCorpus($request)['candidates'];
        $url = '/v2'.$corpus.'/documents/'.$request->id;
        $document = array(
            'id' => $request->id,
            'label' => $request->fullName,
            'parts' =>[
                array(
                    'data' => $request->data,
                    'name' => "Candidate",
                    'content-type' => "text/plain"
                )
            ],
            "user_fields" => array(
                'candidatePictureUrl'       => $request->pictureUrl,
                'candidatePublicProfileUrl' => $request->publicProfileUrl,
                'candidateEmailAddress'     => $request->emailAddress,
                'candidateHeadline'         => $request->headline
            )
        );
        $this->request('POST', $url, $document);
    }
    /*******************************************************************************************************************
     *                                       Delete Candidate by Id (Api)
     * DELETE /ci/candidate/{id}
     *
     * @return bool|mixed
     */
    public function deleteCandidate(Request $request, $id)
    {
        $corpus = $this->getCorpus($request);
        $job_id = $corpus['candidates'].'/documents/'.$id;
        $data = $this->request('delete', '/v2'.$job_id);
        return $this->response_json($data);
    }

    /*******************************************************************************************************************
     *                                         Graph Search By ID (Api)
     * GET /ci/graph_search/{id}
     *
     * @param Request $request
     * @param $id
     * @return json
     */
    public function graphSearchById(Request $request, $id)
    {
        $url = "/graphs/wikipedia/en-20120601/concepts/".$id;
        $data = $this->request('GET', '/v2'.$url);
        return $this->response_json($data);
    }

    /*******************************************************************************************************************
     *                                          Semantic Search By candidate (Api)
     * GET /ci/semantic_search/candidate/{candidate}/{limit}
     *
     * @param Request $request
     * @param $candidate
     * @param $limit
     * @return json
     */
    public function semanticSearchByCandidate(Request $request, $candidate, $limit)
    {
        $corpus = $this->getCorpus($request);
        $candidateId = [$corpus['candidates'] . "/documents/" . $candidate];
        $candidateId = json_encode($candidateId);
        $url = $corpus['jobs'] . "/conceptual_search?ids=$candidateId&cursor=0&limit=$limit";
        $data = $this->request('GET', '/v2'.$url);

        return $this->response_json($data);
    }

    /*******************************************************************************************************************
     *                                              Create Job (Api)
     * PUT/POST /ci/jobs
     *
     * @param Request $request
     */
    public function createJob(Request $request)
    {
        $corpus = $this->getCorpus($request);
        $url = '/v2'.$corpus['jobs'].'/documents/'.$request->id;
        $document = array(
            'id' => $request->id,
            'label' => $request->title,
            'parts' =>[
                array(
                    'data' => $request->description,
                    'name' => "Job description",
                    'content-type' => "text/plain"
                )
            ]
        );
        $this->request('PUT', $url, $document);
    }
    /*******************************************************************************************************************
     *                                         List Jobs (Api)
     * GET /ci/jobs
     * @return bool|mixed
     */
    public function listJobs(Request $request)
    {
        $corpus = $this->getCorpus($request);
        $data = $this->request('GET', '/v2'.$corpus['jobs'].'/documents');

        return $this->response_json($data);
    }
    /*******************************************************************************************************************
     *                                         Get Job by Id (Api)
     * GET /ci/jobs/{id}
     *
     * @return bool|mixed
     */
    public function getJob(Request $request, $id)
    {
        $corpus = $this->getCorpus($request);
        $job_id = $corpus['jobs'].'/documents/'.$id;
        $data = $this->request('GET', '/v2'.$job_id);
        if($data){
            $annotations = $this->request('GET', '/v2'.$job_id.'/annotations');
            $data->annotations = $annotations->annotations;
        }
        return $this->response_json($data);
    }
    /*******************************************************************************************************************
     *                                           getJobAnnotations (Api)
     * GET /ci/jobs/{id}/annotations
     *
     * @param Request $request
     * @param $id
     * @return bool|mixed
     */
    public function getJobAnnotations(Request $request, $id)
    {
        $corpus = $this->getCorpus($request);
        $job_id = $corpus['jobs'].'/documents/'.$id;
        $annotations = $this->request('GET', '/v2'.$job_id.'/annotations');

        return $this->response_json($annotations);
    }
    /*******************************************************************************************************************
     *                                         Delete Job by Id (Api)
     * DELETE /ci/jobs/{id}
     *
     * @return bool|mixed
     */
    public function deleteJob(Request $request, $id)
    {
        $corpus = $this->getCorpus($request);
        $job_id = $corpus['jobs'].'/documents/'.$id;
        $data = $this->request('delete', '/v2'.$job_id);

        return $this->response_json($data);
    }

    /*******************************************************************************************************************
     *                                           User Dashboard (View)
     * GET: /user/{id}
     *
     * @param Request $request
     * @param $id
     * @return view
     */
    public function userDashboard(Request $request, $id)
    {
        $corpus = $this->getCorpus($request);
        $document_id = $corpus['candidates'].'/documents/'.$id;
        $data = $this->request('GET', '/v2'.$document_id);
        if(count($data)>0)
        {
            $newUser = array();
            $newUser['id']               = $data->id;
            $newUser['fullName']         = isset($data->label)?$data->label:'';
            $newUser['data']             = $data->parts[0]->data;
            $newUser['pictureUrl']       = '/images/user.png';
            $newUser['headline']         = '';
            $newUser['publicProfileUrl'] = '';

            if(isset($data->user_fields)){
                $tmp = $data->user_fields;
                $newUser['pictureUrl']       = isset($tmp->candidatePictureUrl)?$tmp->candidatePictureUrl:'/images/user.png';
                $newUser['headline']         = isset($tmp->candidateHeadline)?$tmp->candidateHeadline:'';
                $newUser['publicProfileUrl'] = isset($tmp->candidatePublicProfileUrl)?$tmp->candidatePublicProfileUrl:'';
            }
            return view('user-dashboard',['user'=>$newUser]);
        }
        else
        {
            return "User not found";
        }
    }
    /*******************************************************************************************************************
     *                                         Semantic Search By job (Api)
     * GET /ci/semantic_search/job/{job}/{limit}
     *
     * @param Request $request
     * @param $job
     * @param $limit
     * @return json
     */
    public function semanticSearchByJob(Request $request, $job, $limit)
    {
        $corpus = $this->getCorpus($request);
        $jobId = [$corpus['jobs'] . "/documents/" . $job];
        $jobId = json_encode($jobId);
        $url = $corpus['candidates'] . "/conceptual_search?ids=$jobId&cursor=0&limit=$limit";
        $data = $this->request('GET', '/v2'.$url);
        return $this->response_json($data);
    }

    /*******************************************************************************************************************
     *                                              Analyze Job (View)
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function analyzeJob(Request $request, $id)
    {
        $corpus = $this->getCorpus($request);
        $job_id = $corpus['jobs'].'/documents/'.$id;
        $data = $this->request('GET', '/v2'.$job_id);
        if($data){
            $annotations = $this->request('GET', '/v2'.$job_id.'/annotations');
            $data->annotations = $annotations->annotations;
        }
        return view('analyze-jobs', ['job'=>$data]);
    }



    /*******************************************************************************************************************
     *                                      for testing
     */
    function publicCorpusList(){
        $data = $this->request('GET', '/v2/corpora/public');
        var_dump($data);
    }
    function getCorpusList(Request $request){
        $data = $this->request('GET', '/v2/corpora/'.$this->getAccountId($request));
        var_dump($data);
    }
    function deleteCorpus(Request $request,$id){
        $data = $this->request('DELETE', '/v2/corpora/'.$this->getAccountId($request)."/".$id);
        var_dump($data);
    }
}

